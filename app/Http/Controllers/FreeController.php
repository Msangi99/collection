<?php

namespace App\Http\Controllers;

use App\Models\AdminWallet;
use App\Models\Bima;
use App\Models\Booking;
use App\Models\bus;
use App\Models\PaymentFees;
use App\Models\SystemBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class FreeController extends Controller
{
    public function cash($booking, $xcode)
    {
        //return ['booking' => $booking, 'code' => $xcode];
        //return $booking;
        return $this->processSuccessfulPayment($booking, $xcode);
    }

    private function processSuccessfulPayment($booking, $transToken)
    {
        // Retrieve booking using CompanyRef (which should be booking_code)
        $code = $booking->booking_code;
        $booking = Booking::where('booking_code', $code)->first();

        if (!$booking) {
            Log::error('Booking not found', ['transaction_ref_id' => $transToken]);
            return [
                'errorMessage' => 'Booking not found',
                'transactionToken' => $transToken
            ];
        }

        // Check for duplicate processing
        if ($booking->payment_status !== 'Unpaid') {
            Log::warning('Booking already processed', ['transaction_ref_id' => $transToken]);
            return view('dpo.success', [
                'message' => 'Payment already processed',
                'booking' => $booking
            ]);
        }

        // Begin transaction
        DB::beginTransaction();

        try {
            // Initialize admin wallet
            $adminWallet = AdminWallet::find(1);

            if (!$adminWallet) {
                throw new \Exception('Admin wallet not found');
            }

            // Define VAT function
            $vat = function ($amount, $state) use ($booking, $adminWallet) {
                $vatRate = 18; // VAT percentage
                $vatFactor = 1 + ($vatRate / 100);
                $vatAmount = $amount - ($amount / $vatFactor);

                if ($state == 'fee') {
                    $booking->fee_vat = $vatAmount;
                } elseif ($state == 'service') {
                    $booking->service_vat = $vatAmount;
                } else {
                    return $amount; // Fallback in case state is invalid
                }

                $adminWallet->increment('vat', $vatAmount);
                return $amount - $vatAmount;
            };

            // Define vendor function
            $vender = function ($amount, $state) use ($booking) {
                if ($booking->vender_id > 0 && $booking->vender && $booking->vender->VenderAccount) {
                    $vendorPercentage = $booking->vender->VenderAccount->percentage;
                    $vendorShare = $amount * ($vendorPercentage / 100);

                    $booking->vender->VenderBalances->increment('amount', $vendorShare);

                    if ($state === 'fee') {
                        $booking->vender_fee = $vendorShare;
                    } elseif ($state === 'service') {
                        $booking->vender_service = $vendorShare;
                    }

                    return $amount - $vendorShare;
                }

                return $amount;
            };

            // Calculate shares
            $bimaAmount = $booking->bima_amount ?? 0;
            $fees = $booking->amount - $booking->busFee - $bimaAmount;

            //////temp_wallet//////
            $busOwnerAmount = $booking->busFee + Session::get('cancel');
            /////////for cancel procelss./////////

            //auth()->user()->temp_wallets->amount = 0;
            //auth()->user()->temp_wallets->save();

            /////////////////////////

            // Calculate VAT on bus owner amount
            //$vatAmount = $busOwnerAmount * (18 / 118);
            //$booking->vat = $vatAmount;
            //$busOwnerAmount -= $vatAmount;

            // Calculate system shares
            $bus = bus::with(['busname', 'route', 'campany.balance'])->find($booking->bus_id);
            $companyPercentage = $bus->campany->percentage;
            $systemShares = $busOwnerAmount * ($companyPercentage / 100);
            $busOwnerAmount -= $systemShares;

            // Apply vendor share calculations
            $systemBalanceAmount = $systemShares;
            $paymentFeesAmount = $fees;

            if ($booking->vender_id > 0) {
                $systemBalanceAmount = $vender($systemShares, 'fee');
                $paymentFeesAmount = $vender($fees, 'service');
            }

            $bookingFee = $systemBalanceAmount;
            $bookingService = $paymentFeesAmount;

            // Update Bima if applicable
            if ($bimaAmount > 0) {
                Bima::create([
                    'booking_id' => $booking->id,
                    'start_date' => $booking->travel_date,
                    'end_date' => $booking->insuranceDate,
                    'amount' => $bimaAmount,
                    'bima_vat' => $bimaAmount * (18 / 118),
                ]);
                $adminWallet->increment('balance', $bimaAmount);
            }

            // Update booking
            $booking->update([
                'payment_status' => 'Paid',
                'trans_status' => 'success',
                'trans_token' => $transToken,
                'fee' => $bookingFee,
                'service' => $bookingService,
                'amount' => $busOwnerAmount, // Store bus owner share separately
                'payment_method' => 'cash',
            ]);

            // Update SystemBalance
            SystemBalance::create([
                'campany_id' => $bus->campany->id,
                'balance' => $systemBalanceAmount,
            ]);

            // Increment admin wallet for system balance
            $adminWallet->increment('balance', $systemBalanceAmount);

            // Update PaymentFees
            PaymentFees::create([
                'campany_id' => $bus->campany->id,
                'amount' => $paymentFeesAmount,
                'booking_id' => $booking->id,
            ]);

            // Increment admin wallet for payment fees
            $adminWallet->increment('balance', $paymentFeesAmount);

            // Update company balance
            $bus->campany->balance->increment('amount', $busOwnerAmount);

            DB::commit();

            Log::info('DPO Payment processed successfully', [
                'booking_id' => $booking->id,
                'company_id' => $bus->campany->id,
                'company_balance_increment' => $busOwnerAmount,
                'system_balance' => $systemBalanceAmount,
                'payment_fees' => $paymentFeesAmount,
                'vendor_fee_share' => $booking->vender_fee ?? 0,
                'vendor_service_share' => $booking->vender_service ?? 0,
                'bima_amount' => $bimaAmount,
            ]);

            /*return view('dpo.success', [
                'message' => 'Payment processed successfully',
                'booking' => $booking->fresh() // Get updated booking
            ]);*/

            Session::forget('booking');
            Session::forget('cancel');
            $key = new FunctionsController();
            $key->delete_key($booking);

            /* if (auth()->check()) {
                if (auth()->user()->role == 'customer') {
                    return redirect()->route('customer.mybooking')->with('success', 'Payment processed successfully');
                } elseif(auth()->user()->role == 'vender') {
                    return redirect()->route('vender.index')->with('success', 'Payment processed successfully');
                }else{
                    return redirect()->route('home')->with('success', 'Payment processed successfully');
                }
            }else{
                return redirect()->route('home')->with('success', 'Payment processed successfully');
            } */

            $url = new RedirectController();
            return $url->_redirect($booking->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update records in DPO payment', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id,
                'transaction_token' => $transToken
            ]);

            $url = new RedirectController();
            return $url->_redirect($booking->id);

            /*return [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id,
                'transaction_token' => $transToken
            ];*/
        }
    }
}
