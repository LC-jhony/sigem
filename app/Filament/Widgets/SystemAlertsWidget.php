<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use App\Models\Maintenance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class SystemAlertsWidget extends BaseWidget
{
    protected function getHeading(): string
    {
        return 'Alertas del Sistema';
    }

    protected function getStats(): array
    {
        $today = Carbon::today();
        $nextWeek = Carbon::today()->addWeek();

        // Vehículos sin mantenimiento reciente
        $vehiclesWithoutMaintenance = Vehicle::whereDoesntHave('maintenances', function ($query) {
            $query->where('created_at', '>=', Carbon::now()->subMonths(3));
        })->where('status', true)->count();

        // Mantenimientos pendientes (no realizados)
        $pendingMaintenance = Maintenance::where('status', false)->count();

        // Vehículos con documentos próximos a vencer
        $documentsExpiringSoon = Vehicle::whereHas('documents', function ($query) use ($nextWeek, $today) {
            $query->where('date', '<=', $nextWeek)
                ->where('date', '>=', $today);
        })->count();

        return [
            Stat::make('Vehículos sin Mantenimiento', $vehiclesWithoutMaintenance)
                ->description('Sin mantenimiento en 3 meses')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($vehiclesWithoutMaintenance > 0 ? 'danger' : 'success'),

            Stat::make('Mantenimientos Pendientes', $pendingMaintenance)
                ->description('Mantenimientos no realizados')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingMaintenance > 0 ? 'warning' : 'success'),

            Stat::make('Documentos por Vencer', $documentsExpiringSoon)
                ->description('Documentos que vencen en 7 días')
                ->descriptionIcon('heroicon-m-document-text')
                ->color($documentsExpiringSoon > 0 ? 'warning' : 'success'),
        ];
    }
}
