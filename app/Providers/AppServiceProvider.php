<?php

namespace App\Providers;

use App\Filament\Components\MaintenanceTable;
use Filament\Forms\Components\Component;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Component::macro('maintenanceTable', function (): MaintenanceTable {
            return MaintenanceTable::make();
        });
    }
}
