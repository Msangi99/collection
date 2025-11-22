<?php

namespace App\Console\Commands;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateRecurringSchedules extends Command
{
    protected $signature = 'schedules:generate-recurring';
    protected $description = 'Generate schedules for the next day for recurring trips';

    public function handle()
    {
        $today = Carbon::today();
        $tomorrow = $today->copy()->addDay();

        // Find recurring schedules for today
        $recurringSchedules = Schedule::where('is_recurring', true)
            ->where('departure_date', $today)
            ->get();

        foreach ($recurringSchedules as $schedule) {
            // Check if a schedule already exists for tomorrow
            $exists = Schedule::where('bus_id', $schedule->bus_id)
                ->where('route_id', $schedule->route_id)
                ->where('trip_type', $schedule->trip_type)
                ->where('departure_date', $tomorrow)
                ->exists();

            if (!$exists) {
                $newSchedule = $schedule->replicate()->fill([
                    'departure_date' => $tomorrow,
                    'parent_schedule_id' => null, // Reset for new outbound trips
                ]);
                $newSchedule->save();

                // If it’s an outbound trip with a return trip, replicate the return trip
                if ($schedule->trip_type === 'outbound' && $schedule->childSchedule) {
                    $childSchedule = $schedule->childSchedule;
                    $newReturnDate = $childSchedule->departure_date->copy()->addDay();
                    $newChildSchedule = $childSchedule->replicate()->fill([
                        'departure_date' => $newReturnDate,
                        'parent_schedule_id' => $newSchedule->id,
                    ]);
                    $newChildSchedule->save();
                }
            }
        }

        $this->info('Recurring schedules generated for ' . $tomorrow->toDateString());
    }
}