<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestVehiclesTable extends BaseWidget
{
    protected static ?string $heading = 'Vehículos Recientes';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Vehicle::query()
                    ->latest()
                    ->limit(10)
            )
            ->striped()
            ->paginated([5, 10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(3)
            ->searchable()
            ->columns([
                TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('placa')
                    ->label('Placa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('marca')
                    ->label('Marca')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('unidad')
                    ->label('Unidad')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('property_card')
                    ->label('Tarjeta de Propiedad')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Operativo' => 'success',
                        'En Mantenimiento' => 'warning',
                        'Fuera de Servicio' => 'danger',
                        'En Reparación' => 'purple',
                        'Disponible' => 'info',
                        'En Uso' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Fecha de Registro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
