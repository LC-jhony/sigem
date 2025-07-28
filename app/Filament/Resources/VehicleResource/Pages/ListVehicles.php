<?php

namespace App\Filament\Resources\VehicleResource\Pages;

use App\Exports\VehicleDocumentExport;
use App\Filament\Resources\VehicleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListVehicles extends ListRecords
{
    protected static string $resource = VehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf')
                ->label('Exportar PDF')
                ->color('primary')
                ->icon('bi-file-pdf-fill')
                ->url(route('vehicledocument.pdf'))
                ->openUrlInNewTab(),
            Actions\Action::make('Excel')
                ->label('Exportar Excel')
                ->color('success')
                ->icon('uiw-file-excel')
                ->action(function () {
                    return Excel::download(new VehicleDocumentExport, 'vehicle_documents-' . now()->format('Y-m-d') . '.xlsx');
                }),
            Actions\CreateAction::make()
                ->icon('heroicon-o-squares-plus'),
        ];
    }
}
