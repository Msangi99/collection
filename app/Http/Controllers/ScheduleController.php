<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateUsersJob;
use App\Mail\UpdateUsersMail;
use App\Models\Booking;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Mail;

class ScheduleController extends Controller
{
    public function edit($id)
    {
        $schedule = Schedule::with(['bus.busname', 'route'])->findOrFail($id);
        $buses = Bus::with('busname', 'campany')
            ->whereHas('campany', function ($query) {
                $query->where('campany_id', auth()->user()->campany->id);
            })
            ->get();
        $routes = Route::all();

        return view('controller.schedule_edit', compact('schedule', 'buses', 'routes'));
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        // Validate request data
        $validated = $request->validate([
            'bus_id' => ['required', 'exists:buses,id'],  
            'start' => ['required'],
            'end' => ['required'],
            'schedule_date' => ['required', 'date'], // Add schedule_date validation
        ]);

        // Ensure the bus belongs to the user's company
        $bus = Bus::findOrFail($validated['bus_id']);
        if ($bus->campany_id !== auth()->user()->campany->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($request->bus_id != $schedule->bus_id) {
            $schedule->update($validated);

            // Get bookings related to the schedule
            $bookings = Booking::with('bus','campany','route.schedule') 
                ->where('payment_status', 'Paid')
                ->where('travel_date', $schedule->schedule_date)
                ->get();
            /////////////////notify user////////////////////

            if(count($bookings) > 0) {
                foreach($bookings as $booking) {
                    //UpdateUsersJob::dispatch($booking);
                    Mail::to($booking->customer_email)->send(new UpdateUsersMail($booking)); // pass $schedule
                }
            }
            
            //////////////////////////////////////////////////

            return back()->with('success', 'Schedule updated successfully.');
        }

        return back()->with('success', 'Schedule updated successfully.');
    }
}