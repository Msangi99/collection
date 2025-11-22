<?php

namespace App\Http\Controllers;

use App\Models\bus; // Using the correct model name (lowercase)
use App\Models\Campany;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function bus_name(Request $request)
    {
        // Validate the request
        $busList = '';
        //return $request->all();
        if (!$request->query('query')) {
            $currentTime = Carbon::now()->format('H:i:s');
            $currentDate = Carbon::now()->format('Y-m-d');

            $busList = Bus::with(['schedules' => function ($query) use ($currentDate) {
                $query->where('schedule_date', '>', $currentDate)
                    ->orwhere('schedule_date', '=', $currentDate);
                //->where('start', '>=', Carbon::now()->format('H:i:s')); // Optional: future schedules
            }])
                ->where('campany_id', $request->bus_id)
                ->whereHas('schedules', function ($query) use ($currentDate) {
                    $query->where('schedule_date', '>=', $currentDate);
                    //->where('start', '>=', Carbon::now()->format('H:i:s')); // Optional
                })
                ->get();
        }
        //return $busList;
        return view('bus_name', compact('busList'));
    }
}
