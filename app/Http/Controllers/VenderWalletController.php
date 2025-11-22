<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PDOController;
use App\Http\Controllers\TigosecureController;
use App\Models\Transaction;
use App\Models\VenderBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class VenderWalletController extends Controller
{
    public function showDepositForm()
    {
        return view('vender.deposit');
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:tigosecure,pdo',
            // 'phone_number' => 'required_if:payment_method,tigosecure|string',
            // 'first_name' => 'required_if:payment_method,tigosecure|string',
            // 'last_name' => 'required_if:payment_method,tigosecure|string',
            // 'email' => 'required_if:payment_method,tigosecure|email',
        ]);

        $user = auth()->user();
        if($request->payment_method == 'pdo'){
            $phone = $user->contact;
            $email = $user->email;
            $name = $user->name;
            $amount = $request->amount;

            Session::put('amount', $amount);

            $pdo = new PDOController();
            return $pdo->VenderinitiatePayment($amount, $name, 'vender', $phone, $email);
        }

    }

    public function returned()
    {
        $amount = Session::get('amount');
        $user = auth()->user();
        $user->VenderBalances()->increment('amount', $amount);
        Session::forget('amount');
        return redirect()->route('vender.transaction')->with('success', 'Payment processed successfully');
    }
}
