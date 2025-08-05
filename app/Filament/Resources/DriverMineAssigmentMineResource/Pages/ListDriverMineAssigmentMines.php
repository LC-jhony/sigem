<?php

namespace App\Filament\Resources\DriverMineAssigmentMineResource\Pages;

use Filament\Actions;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Livewire\DriveMineAssigmentReport;
use App\Filament\Resources\DriverMineAssigmentMineResource;

class ListDriverMineAssigmentMines extends ListRecords
{
    protected static string $resource = DriverMineAssigmentMineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('report')
                ->label('Reporte')
                ->modalHeading('Reporte asignacion de empleados a Minas')
                ->icon('heroicon-o-document-text')
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                //  ->modalWidth(MaxWidth::SevenExtraLarge)
                ->icon('bi-file-pdf-fill')
                ->color('danger')
                ->modalContent(fn() => view('ReportManteneaceMineAssigment')),
            Actions\CreateAction::make()
                ->icon('heroicon-o-squares-plus'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Todas')
                ->badge(fn() => $this->getModel()::count()),
            'current_month' => Tab::make('Mes Actual')
                ->modifyQueryUsing(fn(Builder $query) => $query->currentMonth())
                ->badge(fn() => $this->getModel()::currentMonth()->count()),

            'completed' => Tab::make('Completadas')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'completed'))
                ->badge(fn() => $this->getModel()::where('status', 'completed')->count()),
        ];
    }
}
