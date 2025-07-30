<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use Filament\Widgets\ChartWidget;

class VehicleStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Estado de Vehículos';

    protected function getData(): array
    {
        $activeVehicles = Vehicle::where('status', true)->count();
        $inactiveVehicles = Vehicle::where('status', false)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Vehículos',
                    'data' => [$activeVehicles, $inactiveVehicles],
                    'backgroundColor' => ['#10B981', '#EF4444'],
                ],
            ],
            'labels' => ['Activos', 'Inactivos'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
