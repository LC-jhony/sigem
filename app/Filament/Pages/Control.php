<?php

namespace App\Filament\Pages;

use Filament\Actions;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;

class Control extends Page
{
    protected static ?string $navigationIcon = 'bi-file-pdf-fill';

    protected static string $view = 'filament.pages.control';

    protected static ?string $navigationGroup = 'Reportes';

    protected static ?string $navigationLabel = 'Control de Vehículos';

    protected static ?string $title = 'Programacion de unidades semaforo';

    protected static ?int $navigationSort = 1;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download')
        ];
    }
}
