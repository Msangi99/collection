<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bima;
use Illuminate\Http\Request;

class BimaController extends Controller
{
    const price = 100;
    const foreign = 200;

    public function index()
    {
        $bimas = Bima::with('booking')
            ->orderBy('start_date', 'desc')
            ->get()
            ->map(function ($item) {
                // Calculate valid days
                $start = Carbon::parse(now());
                $end = Carbon::parse($item->end_date);

                // Check if current date is greater than end date
                if ($start->greaterThan($end)) {
                    $item->valid_days = 'expired';
                } else {
                    $item->valid_days = $start->diffInDays($end);
                }

                return $item;
            });

        return view('system.bima', compact('bimas'));
    }

    public function getData()
    {
        $bimas = Bima::with('booking')->get();

        return response()->json([
            'data' => $bimas
        ]);
    }
}
