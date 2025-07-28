<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class VehicleDocumentPdf extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $vehicles = Vehicle::with('documents')->get();
        $pdf = Pdf::loadView('pdf.vehehicledocuments', compact('vehicles'))

            ->setPaper('A4', 'landscape');
        return $pdf->stream();
        //return $pdf->download('vehicle_documents-' . now()->format('Y-m-d') . '.pdf');
    }
}
