<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use Filament\Widgets\ChartWidget;

class MaintenanceChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Mantenimientos  Vehículos';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $vehicles = Vehicle::withCount('maintenances')
            ->orderBy('maintenances_count', 'desc')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Cantidad de Mantenimientos',
                    'data' => $vehicles->pluck('maintenances_count')->toArray(),
                    'backgroundColor' => [
                        '#3B82F6',
                        '#EF4444',
                        '#10B981',
                        '#F59E0B',
                        '#8B5CF6',
                        '#06B6D4',
                        '#84CC16',
                        '#F97316',
                        '#EC4899',
                        '#6366F1',
                    ],
                ],
            ],

        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
