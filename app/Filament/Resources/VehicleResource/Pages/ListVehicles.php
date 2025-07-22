<?php

namespace App\Filament\Resources\VehicleResource\Pages;

use App\Filament\Resources\VehicleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVehicles extends ListRecords
{
    protected static string $resource = VehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf')
                ->label('Exportar PDF')
                ->color('primary'),
            Actions\Action::make('Excel')
                ->label('Exportar Excel')
                ->color('primary'),
            Actions\CreateAction::make()
                ->icon('heroicon-o-squares-plus'),
        ];
    }
}
