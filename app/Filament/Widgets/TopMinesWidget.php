<?php

namespace App\Filament\Widgets;

use App\Models\Mine;
use App\Models\DriverMineAssigment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TopMinesWidget extends BaseWidget
{
    protected function getHeading(): string
    {
        return 'Minas Más Activas';
    }

    protected function getStats(): array
    {
        $topMines = Mine::withCount(['assignments' => function ($query) {
            $query->where('status', 'Activo');
        }])
            ->orderBy('assignments_count', 'desc')
            ->limit(3)
            ->get();

        $stats = [];

        foreach ($topMines as $mine) {
            $stats[] = Stat::make($mine->name, $mine->assignments_count)
                ->description('Conductores asignados')
                ->descriptionIcon('heroicon-m-users')
                ->color('success');
        }

        return $stats;
    }
}
