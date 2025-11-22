<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckExpiredResavedTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:check-expired-resaved';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for expired resaved tickets and marks them as failed.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredBookings = Booking::where('payment_status', 'resaved')
            ->where('resaved_until', '<', Carbon::now())
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->info('No expired resaved tickets found.');
            Log::info('No expired resaved tickets found.');
            return;
        }

        foreach ($expiredBookings as $booking) {
            $booking->update(['payment_status' => 'Fail']);
            $this->info("Booking {$booking->booking_code} (ID: {$booking->id}) marked as failed due to expiration.");
            Log::info("Booking {$booking->booking_code} (ID: {$booking->id}) marked as failed due to expiration.");
        }

        $this->info('Expired resaved tickets checked and updated successfully.');
        Log::info('Expired resaved tickets checked and updated successfully.');
    }
}
