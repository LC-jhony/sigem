<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TopMinesWidget;
use App\Filament\Widgets\TopDriversWidget;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Widgets\SystemAlertsWidget;
use App\Filament\Widgets\VehicleStatusChart;
use App\Filament\Widgets\RecentActivityWidget;
use App\Filament\Widgets\MaintenanceTrendChart;
use Illuminate\Session\Middleware\StartSession;
use App\Filament\Widgets\LatestMaintenanceTable;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Widgets\InformationSistemWidget;
use App\Filament\Widgets\VehicleWindget;
use App\Filament\Widgets\LatestVehiclesTable;
use App\Filament\Widgets\MaintenanceWidget;
use App\Filament\Widgets\MaintenanceChartWidget;
use App\Filament\Widgets\LatestMaintenanceWidget;
use Filament\Http\Middleware\AuthenticateSession;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('/')
            ->login()
            //->registration()
            //->passwordReset()
            //->emailVerification()
            ->profile()
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                InformationSistemWidget::class,
                VehicleWindget::class,

                MaintenanceChartWidget::class,
                LatestMaintenanceWidget::class,
                Widgets\AccountWidget::class,
                //  Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 4,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
                AuthUIEnhancerPlugin::make(),
            ]);
    }
}
