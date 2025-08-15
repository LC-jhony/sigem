<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceItem;
use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceHistoryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id)
    {
        // Definir los kilometrajes una sola vez
        $mileages = [7500, 15000, 22500, 30000, 37500, 45000, 52500, 60000, 67500, 75000, 82500, 90000, 97500, 105000, 112500, 120000, 127500, 135000, 142500, 150000, 157500, 165000];

        // Obtener datos del vehículo (solo campos necesarios)
        $record = Vehicle::select('id', 'placa', 'marca', 'unidad', 'property_card')->findOrFail($id);

        // Obtener items de mantenimiento (solo campos necesarios)
        $maintenanceitems = MaintenanceItem::select('id', 'name')->get();

        // Consulta SQL optimizada para obtener solo los mantenimientos existentes
        $maintenances = DB::table('maintenances')
            ->select('maintenance_item_id', 'mileage')
            ->where('vehicle_id', $id)
            ->whereIn('mileage', $mileages)
            ->whereNull('deleted_at') // Considerar soft deletes
            ->get();

        // Crear matriz optimizada para la tabla
        $maintenanceMatrix = [];
        foreach ($maintenanceitems as $item) {
            $maintenanceMatrix[$item->id] = [];
            foreach ($mileages as $mileage) {
                $maintenanceMatrix[$item->id][$mileage] = false;
            }
        }

        // Llenar la matriz con los mantenimientos existentes
        foreach ($maintenances as $maintenance) {
            if (isset($maintenanceMatrix[$maintenance->maintenance_item_id])) {
                $maintenanceMatrix[$maintenance->maintenance_item_id][$maintenance->mileage] = true;
            }
        }

        // Configurar opciones optimizadas para DomPDF
        $pdf = Pdf::loadView('pdf.history_maintenance', compact(
            'record',
            'maintenanceitems',
            'maintenanceMatrix',
            'mileages'
        ))
            ->setPaper('A4', 'landscape')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => false,
                'isFontSubsettingEnabled' => false,
                'debugKeepTemp' => false,
                'debugCss' => false,
                'debugLayout' => false,
                'debugLayoutLines' => false,
                'debugLayoutBlocks' => false,
                'debugLayoutInline' => false,
                'debugLayoutPaddingBox' => false,
            ]);

        return $pdf->stream();
    }
}
