<?php

namespace App\Filament\Widgets;

use App\Models\Maintenance;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MaintenanceTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Tendencia de Mantenimientos';

    protected function getData(): array
    {
        $months = collect();
        $maintenanceCounts = collect();

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push($date->format('M Y'));

            $count = Maintenance::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $maintenanceCounts->push($count);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Mantenimientos',
                    'data' => $maintenanceCounts->toArray(),
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $months->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
