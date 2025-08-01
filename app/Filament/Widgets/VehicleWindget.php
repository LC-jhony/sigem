<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use Filament\Widgets\ChartWidget;

class VehicleWindget extends ChartWidget
{
    protected static ?string $heading = 'Distribución de Estados de Vehículos';
    // protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $statuses = [
            'Operativo' => Vehicle::where('status', 'Operativo')->count(),
            'En Mantenimiento' => Vehicle::where('status', 'En Mantenimiento')->count(),
            'Fuera de Servicio' => Vehicle::where('status', 'Fuera de Servicio')->count(),
            'En Reparación' => Vehicle::where('status', 'En Reparación')->count(),
            'Disponible' => Vehicle::where('status', 'Disponible')->count(),
            'En Uso' => Vehicle::where('status', 'En Uso')->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Vehículos',
                    'data' => array_values($statuses),
                    'backgroundColor' => [
                        '#10B981', // success - Operativo
                        '#F59E0B', // warning - En Mantenimiento
                        '#EF4444', // danger - Fuera de Servicio
                        '#8B5CF6', // purple - En Reparación
                        '#3B82F6', // info - Disponible
                        '#6B7280', // gray - En Uso
                    ],
                ],
            ],
            'labels' => array_keys($statuses),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
