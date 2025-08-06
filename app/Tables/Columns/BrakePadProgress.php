<?php

namespace App\Tables\Columns;

use Filament\Tables\Columns\ViewColumn;

class BrakePadProgress extends ViewColumn
{
    protected string $view = 'filament.tables.columns.brake-pad-progress';

    protected function setUp(): void
    {
        parent::setUp();

        // Configurar el estado para devolver el promedio de pastillas de freno
        $this->state(function ($record) {
            // Validar que el record existe y tiene el método
            if (!$record || !method_exists($record, 'getAverageBrakePadAttribute')) {
                return 0.0;
            }

            // Obtener el promedio y asegurar que esté en rango válido
            $average = $record->average_brake_pad ?? 0;
            return (float) max(0, min(100, $average));
        });

        // Configurar label por defecto
        $this->label('Estado Pastillas');

        // Hacer la columna ordenable por el promedio
        $this->sortable(query: function ($query, string $direction) {
            return $query->orderByRaw("
                (COALESCE(front_left_brake_pad, 0) +
                 COALESCE(front_right_brake_pad, 0) +
                 COALESCE(rear_left_brake_pad, 0) +
                 COALESCE(rear_right_brake_pad, 0)) / 4 {$direction}
            ");
        });

        // Hacer la columna buscable por estado
        $this->searchable(query: function ($query, string $search) {
            return $query->whereRaw("
                CASE
                    WHEN (COALESCE(front_left_brake_pad, 0) +
                          COALESCE(front_right_brake_pad, 0) +
                          COALESCE(rear_left_brake_pad, 0) +
                          COALESCE(rear_right_brake_pad, 0)) / 4 >= 70 THEN 'Bueno'
                    WHEN (COALESCE(front_left_brake_pad, 0) +
                          COALESCE(front_right_brake_pad, 0) +
                          COALESCE(rear_left_brake_pad, 0) +
                          COALESCE(rear_right_brake_pad, 0)) / 4 >= 30 THEN 'Regular'
                    ELSE 'Malo'
                END LIKE ?
            ", ["%{$search}%"]);
        });
    }
}
