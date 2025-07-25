<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceItem;
use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MaintenanceHistoryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id)
    {
        $record = Vehicle::with('maintenances')->findOrFail($id);
        $maintenanceitems = MaintenanceItem::all();
        $pdf = Pdf::loadView('pdf.maintenance-history', compact(
            'record',
            'maintenanceitems'
        ))
            ->setPaper('A4', 'landscape')
            ->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->stream();
    }
}
