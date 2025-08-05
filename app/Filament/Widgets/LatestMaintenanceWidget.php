<?php

namespace App\Filament\Widgets;

use App\Models\Maintenance;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestMaintenanceWidget extends BaseWidget
{
    protected static ?string $heading = 'Últimos Mantenimientos';

    protected static ?int $sort = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Maintenance::query()
                    ->with(['vehicle', 'maintenanceItem'])
                    ->latest()
            )
            ->striped()
            ->paginated([5, 10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(3)
            ->searchable()
            ->columns([
                Tables\Columns\TextColumn::make('vehicle.placa')
                    ->label('Vehículo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('maintenanceItem.name')
                    ->label('Tipo de Mantenimiento')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mileage')
                    ->label('Kilometraje')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Estado')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning'),
                Tables\Columns\TextColumn::make('maintenance_cost')
                    ->label('Costo Total')
                    ->prefix('S/. ')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->paginated(false)
            ->defaultPaginationPageOption(5);
    }
}
