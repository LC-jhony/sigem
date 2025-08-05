<?php

namespace App\Exports;

use App\Models\Vehicle;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VehicleDocumentExport implements FromCollection, WithColumnWidths, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Vehicle::with('documents')->get();
    }

    public function headings(): array
    {
        return [
            ['Supervisión y Control de Mantenimiento', '', '', '', '', 'TARJETA DE PROPIEDAD', 'SOAT', '', 'TARJETA DE CIRCULACION', '', 'REVICION TECNICA', '', 'POLIZA DE SEGURO VEHICULAR', ''],
            ['FECHA', '', '', '', '', '', 'VENCIMIENTO', '', 'VENCIMIENTO', '', 'VENCIMIENTO', '', 'VENCIMIENTO', ''],
            ['COD', 'PROG.', 'PLACA', 'MARCA', 'UNIDAD', 'AÑO', 'Fecha Vencimiento', 'Vence en Días', 'Fecha Vencimiento', 'Vence en Días', 'Fecha Vencimiento', 'Vence en Días', 'Fecha Vencimiento', 'Vence en Días'],
        ];
    }

    public function map($vehicle): array
    {
        $soat = $vehicle->documents->firstWhere('name', 'SOAT');
        $soatDate = $soat ? Carbon::parse($soat->date)->setTimezone('America/Lima') : null;
        $soatDias = '';
        if ($soatDate) {
            $dias = now()->setTimezone('America/Lima')->diffInDays($soatDate, false);
            $soatDias = (int) $dias;
        }

        $circulacion = $vehicle->documents->firstWhere('name', 'TARJETA DE CIRCULACION');
        $circulacionDate = $circulacion ? Carbon::parse($circulacion->date)->setTimezone('America/Lima') : null;
        $circulacionDias = '';
        if ($circulacionDate) {
            $dias = now()->setTimezone('America/Lima')->diffInDays($circulacionDate, false);
            $circulacionDias = (int) $dias;
        }

        $revision = $vehicle->documents->firstWhere('name', 'REVICION TECNICA');
        $revisionDate = $revision ? Carbon::parse($revision->date)->setTimezone('America/Lima') : null;
        $revisionDias = '';
        if ($revisionDate) {
            $dias = now()->setTimezone('America/Lima')->diffInDays($revisionDate, false);
            $revisionDias = (int) $dias;
        }

        $poliza = $vehicle->documents->firstWhere('name', 'POLIZA DE SEGURO VEHICULAR');
        $polizaDate = $poliza ? Carbon::parse($poliza->date)->setTimezone('America/Lima') : null;
        $polizaDias = '';
        if ($polizaDate) {
            $dias = now()->setTimezone('America/Lima')->diffInDays($polizaDate, false);
            $polizaDias = (int) $dias;
        }

        return [
            '', // COD (se llenará automáticamente)
            $vehicle->code,
            $vehicle->placa,
            $vehicle->marca,
            $vehicle->unidad,
            $vehicle->property_card,
            $soatDate ? $soatDate->format('d/m/Y') : 'Sin SOAT',
            $soatDias !== '' ? $soatDias : '-',
            $circulacionDate ? $circulacionDate->format('d/m/Y') : 'Sin Tarjeta',
            $circulacionDias !== '' ? $circulacionDias : '-',
            $revisionDate ? $revisionDate->format('d/m/Y') : 'Sin Revisión',
            $revisionDias !== '' ? $revisionDias : '-',
            $polizaDate ? $polizaDate->format('d/m/Y') : 'Sin Póliza',
            $polizaDias !== '' ? $polizaDias : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilos para los encabezados
        $sheet->getStyle('A1:N3')->getFont()->setBold(true);
        $sheet->getStyle('A1:N3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:N3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Fondo gris para los encabezados
        $sheet->getStyle('A1:N3')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('A1:N3')->getFill()->getStartColor()->setRGB('F2F2F2');

        // Bordes para toda la tabla
        $sheet->getStyle('A1:N'.($sheet->getHighestRow()))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Combinar celdas para el título principal
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('G1:H1');
        $sheet->mergeCells('I1:J1');
        $sheet->mergeCells('K1:L1');
        $sheet->mergeCells('M1:N1');

        // Combinar celdas para "FECHA"
        $sheet->mergeCells('A2:F2');

        // Combinar celdas para "VENCIMIENTO"
        $sheet->mergeCells('G2:H2');
        $sheet->mergeCells('I2:J2');
        $sheet->mergeCells('K2:L2');
        $sheet->mergeCells('M2:N2');

        // Combinar celdas para "AÑO"
        $sheet->mergeCells('F3:F3');

        // Combinar celdas para "TARJETA DE PROPIEDAD"
        $sheet->mergeCells('G3:G3');

        // Combinar celdas para "SOAT"
        $sheet->mergeCells('H3:H3');

        // Combinar celdas para "TARJETA DE CIRCULACION"
        $sheet->mergeCells('I3:I3');

        // Combinar celdas para "REVICION TECNICA"
        $sheet->mergeCells('J3:J3');

        // Combinar celdas para "POLIZA DE SEGURO VEHICULAR"
        $sheet->mergeCells('K3:L3');

        // Combinar celdas para "M3:N3"
        $sheet->mergeCells('M3:N3');

        // Ajustar altura de filas
        $sheet->getRowDimension('1')->setRowHeight(30);
        $sheet->getRowDimension('2')->setRowHeight(25);
        $sheet->getRowDimension('3')->setRowHeight(25);

        // Estilos para los datos
        $sheet->getStyle('A4:N'.($sheet->getHighestRow()))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:N'.($sheet->getHighestRow()))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Aplicar colores según el estado de los documentos
        $highestRow = $sheet->getHighestRow();
        for ($row = 4; $row <= $highestRow; $row++) {
            // SOAT
            $soatDias = $sheet->getCell('H'.$row)->getValue();
            if (is_numeric($soatDias) && $soatDias < 0) {
                $sheet->getStyle('G'.$row.':H'.$row)->getFont()->getColor()->setRGB('FF0000');
            } elseif (is_numeric($soatDias) && $soatDias <= 30) {
                $sheet->getStyle('G'.$row.':H'.$row)->getFont()->getColor()->setRGB('FFA500');
            } else {
                $sheet->getStyle('G'.$row.':H'.$row)->getFont()->getColor()->setRGB('008000');
            }

            // TARJETA DE CIRCULACION
            $circulacionDias = $sheet->getCell('J'.$row)->getValue();
            if (is_numeric($circulacionDias) && $circulacionDias < 0) {
                $sheet->getStyle('I'.$row.':J'.$row)->getFont()->getColor()->setRGB('FF0000');
            } elseif (is_numeric($circulacionDias) && $circulacionDias <= 30) {
                $sheet->getStyle('I'.$row.':J'.$row)->getFont()->getColor()->setRGB('FFA500');
            } else {
                $sheet->getStyle('I'.$row.':J'.$row)->getFont()->getColor()->setRGB('008000');
            }

            // REVICION TECNICA
            $revisionDias = $sheet->getCell('L'.$row)->getValue();
            if (is_numeric($revisionDias) && $revisionDias < 0) {
                $sheet->getStyle('K'.$row.':L'.$row)->getFont()->getColor()->setRGB('FF0000');
            } elseif (is_numeric($revisionDias) && $revisionDias <= 30) {
                $sheet->getStyle('K'.$row.':L'.$row)->getFont()->getColor()->setRGB('FFA500');
            } else {
                $sheet->getStyle('K'.$row.':L'.$row)->getFont()->getColor()->setRGB('008000');
            }

            // POLIZA DE SEGURO VEHICULAR
            $polizaDias = $sheet->getCell('N'.$row)->getValue();
            if (is_numeric($polizaDias) && $polizaDias < 0) {
                $sheet->getStyle('M'.$row.':N'.$row)->getFont()->getColor()->setRGB('FF0000');
            } elseif (is_numeric($polizaDias) && $polizaDias <= 30) {
                $sheet->getStyle('M'.$row.':N'.$row)->getFont()->getColor()->setRGB('FFA500');
            } else {
                $sheet->getStyle('M'.$row.':N'.$row)->getFont()->getColor()->setRGB('008000');
            }
        }

        // Agregar números de fila en la columna COD
        for ($row = 4; $row <= $highestRow; $row++) {
            $sheet->setCellValue('A'.$row, $row - 3);
        }
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // COD
            'B' => 12,  // PROG.
            'C' => 15,  // PLACA
            'D' => 15,  // MARCA
            'E' => 15,  // UNIDAD
            'F' => 12,  // AÑO
            'G' => 18,  // SOAT Fecha
            'H' => 15,  // SOAT Días
            'I' => 18,  // CIRCULACION Fecha
            'J' => 15,  // CIRCULACION Días
            'K' => 18,  // REVISION Fecha
            'L' => 15,  // REVISION Días
            'M' => 18,  // POLIZA Fecha
            'N' => 15,  // POLIZA Días
        ];
    }
}
