<?php

namespace App\Http\Controllers\Pdf;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class Report extends Controller
{
    public function generatePDF($data)
    {
        //$invoice = Invoice::findOrFail($invoiceId); // Example model
        $pdf = Pdf::loadView('print.report', ['bookings' => $data]);

        return $pdf->stream('report.pdf'); 
    }
}
