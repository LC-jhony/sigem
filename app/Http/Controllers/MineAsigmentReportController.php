<?php

namespace App\Http\Controllers;

use App\Models\DriverMineAssigment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MineAsigmentReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $id)
    {
        // Validar los parámetros requeridos
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        // Obtener los datos de asignaciones
        $assignments = DriverMineAssigment::with(['driver.cargo', 'mine'])
            ->where('mine_id', $id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->where('status', 'Activo')
            ->get();

        // Obtener información de la mina
        $mine = \App\Models\Mine::findOrFail($id);

        // Obtener el nombre del mes
        $months = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];
        $monthName = $months[$request->month];

        // Generar el PDF
        $pdf = Pdf::loadView('pdf.assigment-mine-driver-report', [
            'assignments' => $assignments,
            'mine' => $mine,
            'month' => $request->month,
            'monthName' => $monthName,
            'year' => $request->year,
        ])
            ->setPaper('A4', 'portrait')
            ->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->stream("reporte_asignaciones_{$mine->name}_{$monthName}_{$request->year}.pdf");
    }
}
