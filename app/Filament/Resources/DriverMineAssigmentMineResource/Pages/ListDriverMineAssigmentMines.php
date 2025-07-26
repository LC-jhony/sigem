<?php

namespace App\Filament\Resources\DriverMineAssigmentMineResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\DriverMineAssigmentMineResource;
use Illuminate\Database\Eloquent\Builder;

class ListDriverMineAssigmentMines extends ListRecords
{
    protected static string $resource = DriverMineAssigmentMineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-squares-plus'),
        ];
    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Todas')
                ->badge(fn() => $this->getModel()::count()),

            'active' => Tab::make('Activas')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'active'))
                ->badge(fn() => $this->getModel()::where('status', 'active')->count()),

            'current_month' => Tab::make('Mes Actual')
                ->modifyQueryUsing(fn(Builder $query) => $query->currentMonth())
                ->badge(fn() => $this->getModel()::currentMonth()->count()),

            'completed' => Tab::make('Completadas')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'completed'))
                ->badge(fn() => $this->getModel()::where('status', 'completed')->count()),
        ];
    }
}
