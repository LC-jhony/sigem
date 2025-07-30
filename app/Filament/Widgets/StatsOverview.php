<?php

namespace App\Filament\Widgets;

use App\Models\Driver;
use App\Models\Mine;
use App\Models\Vehicle;
use App\Models\Maintenance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getHeading(): string
    {
        return 'Estadísticas Generales';
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Minas', Mine::count())
                ->description('Minas registradas en el sistema')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('success'),

            Stat::make('Vehículos Activos', Vehicle::where('status', true)->count())
                ->description('Vehículos operativos')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info'),

            Stat::make('Conductores Activos', Driver::where('status', true)->count())
                ->description('Conductores disponibles')
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),

            Stat::make('Mantenimientos Pendientes', Maintenance::where('status', false)->count())
                ->description('Mantenimientos por realizar')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('danger'),
        ];
    }
}
