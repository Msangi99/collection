<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\bus; // Using the correct model name (lowercase)
use Carbon\Carbon;

class RouteController extends Controller
{
    public function bus_name(Request $request)
    {
        $currentTime = Carbon::now()->format('H:i:s');
        $currentDate = Carbon::now()->format('Y-m-d');

        // Validate request parameter
        if (!$request->has('bus_name') || empty($request->bus_name)) {
            return response()->json(['error' => 'bus_name parameter is required'], 400);
        }

        // Simplified query - remove redundant whereHas conditions
        $busList = bus::with([
            'schedules' => function ($query) use ($currentDate, $currentTime) {
                $query->where('schedule_date', '>=', $currentDate)
                    ->where(function ($timeQuery) use ($currentDate, $currentTime) {
                        // If it's today, only show schedules that haven't started yet
                        if ($currentDate === Carbon::now()->toDateString()) {
                            $timeQuery->where('start', '>', $currentTime);
                        }
                    });
            },
            'busname' => function ($query) {
                $query->where('status', 1);
            }
        ])
        ->where('campany_id', $request->bus_name)
        ->whereHas('busname', function ($query) {
            $query->where('status', 1);
        })
        ->whereHas('schedules', function ($query) use ($currentDate, $currentTime) {
            $query->where('schedule_date', '>=', $currentDate)
                ->where(function ($timeQuery) use ($currentDate, $currentTime) {
                    // If it's today, only show schedules that haven't started yet
                    if ($currentDate === Carbon::now()->toDateString()) {
                        $timeQuery->where('start', '>', $currentTime);
                    }
                });
        })
        ->get();

        // Debug version - uncomment to see what's happening
        /*
        $debugInfo = [
            'company_id' => $request->bus_name,
            'current_date' => $currentDate,
            'current_time' => $currentTime,
            'results_count' => $busList->count(),
            'results' => $busList->toArray()
        ];
        return response()->json($debugInfo);
        */

        return view('bus_name', compact('busList'));
    }

    // Alternative method with less restrictive time filtering
    public function bus_name_alternative(Request $request)
    {
        $currentDate = Carbon::now()->format('Y-m-d');

        // Validate request parameter
        if (!$request->has('bus_name') || empty($request->bus_name)) {
            return response()->json(['error' => 'bus_name parameter is required'], 400);
        }

        // More permissive query - show all future schedules
        $busList = bus::with([
            'schedules' => function ($query) use ($currentDate) {
                $query->where('schedule_date', '>=', $currentDate);
            },
            'busname' => function ($query) {
                $query->where('status', 1);
            }
        ])
        ->where('campany_id', $request->bus_name)
        ->whereHas('busname', function ($query) {
            $query->where('status', 1);
        })
        ->whereHas('schedules', function ($query) use ($currentDate) {
            $query->where('schedule_date', '>=', $currentDate);
        })
        ->get();

        return view('bus_name', compact('busList'));
    }

    // Debug method to check data step by step
    public function debug_bus_name(Request $request)
    {
        $currentTime = Carbon::now()->format('H:i:s');
        $currentDate = Carbon::now()->format('Y-m-d');

        if (!$request->has('bus_name') || empty($request->bus_name)) {
            return response()->json(['error' => 'bus_name parameter is required'], 400);
        }

        // Check company
        $company = \App\Models\Campany::where('id', $request->bus_name)->first();
        
        // Check buses
        $buses = bus::where('campany_id', $request->bus_name)->get();
        
        // Check schedules
        $schedules = \App\Models\Schedule::whereHas('bus', function($query) use ($request) {
            $query->where('campany_id', $request->bus_name);
        })->where('schedule_date', '>=', $currentDate)->get();

        return response()->json([
            'company_id' => $request->bus_name,
            'company' => $company,
            'buses' => $buses,
            'schedules' => $schedules,
            'current_date' => $currentDate,
            'current_time' => $currentTime
        ]);
    }
}
