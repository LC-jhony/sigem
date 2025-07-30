<?php

namespace App\Filament\Widgets;

use App\Models\Driver;
use App\Models\DriverMineAssigment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopDriversWidget extends BaseWidget
{
    protected static ?string $heading = 'Conductores Más Activos';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Driver::withCount(['driverMineAssigments' => function ($query) {
                    $query->where('status', 'Activo');
                }])
                    ->where('status', true)
                    ->orderBy('driver_mine_assigments_count', 'desc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('driver_mine_assigments_count')
                    ->label('Asignaciones Activas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('license_number')
                    ->label('Número de Licencia')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ]),
            ])
            ->paginated(false);
    }
}
