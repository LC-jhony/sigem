<?php

namespace App\Filament\Widgets;

use App\Models\Maintenance;
use App\Models\DriverMineAssigment;
use App\Models\Vehicle;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Carbon\Carbon;

class RecentActivityWidget extends BaseWidget
{
    protected static ?string $heading = 'Actividad Reciente';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Maintenance::query()
                    ->with(['vehicle'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('vehicle.placa')
                    ->label('Vehículo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Actividad')
                    ->limit(40),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Estado')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning'),
            ])
            ->paginated(false);
    }
}
