<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ValueMaintenanceVehicleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id)
    {
        $record = Vehicle::with(['maintenances.maintenanceItem'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.value-mantenace-vehicle', [
            'record' => $record,
        ]);

        return $pdf->stream();
    }
}
