<?php

namespace App\Filament\Pages;

use App\Models\Vehicle;
use Filament\Actions;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use Livewire\WithPagination;

class Control extends Page
{
    use WithPagination;
    protected static ?string $navigationIcon = 'bi-file-pdf-fill';

    protected static string $view = 'filament.pages.control';

    protected static ?string $navigationGroup = 'Reportes';

    protected static ?string $navigationLabel = 'Control de Vehículos';

    protected static ?string $title = 'Programacion de unidades semaforo';

    protected static ?int $navigationSort = 1;

    public $vehicles;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download')
        ];
    }
    public function mount(): void
    {
        $this->vehicles = Vehicle::with(['documents' => function ($query) {
            $query->orderBy('date', 'asc');
        }])->get();
    }
}
