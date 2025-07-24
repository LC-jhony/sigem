<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Vehicle;
use Filament\Forms\Form;
use App\Enum\MillageItems;
use Filament\Tables\Table;
use App\Models\Maintenance;
use Filament\Support\RawJs;
use App\Models\MaintenanceItem;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Wallo\FilamentSelectify\Components\ButtonGroup;
use App\Filament\Resources\MaintenanceResource\Pages;
use App\Filament\Resources\MaintenanceResource\RelationManagers;

class MaintenanceResource extends Resource
{
    protected static ?string $model = Maintenance::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Mantenimiento';
    protected static ?string $modelLabel = 'Mantenimiento';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Archivos')
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('photo')
                            ->label('Foto del Mantenimiento')
                            ->visibility('public')
                            ->directory('Mantenimiento')
                            ->default(null),
                        Forms\Components\FileUpload::make('file')
                            ->label('Archivo del Mantenimiento')
                            ->disk('public')
                            ->directory('maintenance/files')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(2048)
                    ]),
                Forms\Components\Grid::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('vehicle_id')
                            ->label('Vehicle')
                            ->options(Vehicle::all()->pluck('placa', 'id'))
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('maintenance_item_id')
                            ->options(MaintenanceItem::all()->pluck('name', 'id'))
                            ->label('Mantenimiento')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('mileage')
                            ->label('kilometro')
                            ->options(MillageItems::class)
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),
                        // Forms\Components\Toggle::make('status')
                        //     ->required()
                        //     ->default(true),
                        ButtonGroup::make('status')
                            ->options([
                                '1' => 'Si',
                                '0' => 'No',
                            ])
                            ->onColor('primary')
                            ->offColor('gray')
                            ->gridDirection('row')
                            ->default('1')
                            ->icons([
                                '1' => 'heroicon-m-check-badge',
                                '0' => 'heroicon-m-x-circle',
                            ])
                            ->iconPosition(\Filament\Support\Enums\IconPosition::Before)
                            ->iconSize(IconSize::Medium),
                        Forms\Components\Section::make('Costos')
                            ->columns(3)
                            ->schema([
                                Forms\Components\TextInput::make('Price_material')
                                    ->prefix('S/.')
                                    // ->inputMode('decimal')
                                    // ->mask(RawJs::make('$money($input, \',\')'))
                                    ->numeric()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $workforce = floatval($get('workforce') ?? 0);
                                        $set('maintenance_cost', floatval($state) + $workforce);
                                    }),
                                Forms\Components\TextInput::make('workforce')
                                    ->prefix('S/.')
                                    // ->inputMode('decimal')
                                    // ->mask(RawJs::make('$money($input, ",")'))
                                    ->numeric()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, $get) {
                                        $workforce = floatval($get('Price_material') ?? 0);
                                        $set('maintenance_cost', floatval($state) + $workforce);
                                    }),
                                Forms\Components\TextInput::make('maintenance_cost')
                                    ->prefix('S/.')
                                    ->inputMode('decimal')
                                    ->mask(RawJs::make('$money($input, ",")'))
                                    ->numeric(),
                            ])

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->paginated([5, 10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(5)
            ->searchable()
            ->columns([
                Tables\Columns\TextColumn::make('vehicle.placa')
                    ->label('Vehiculo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('maintenanceItem.name')
                    ->label('Mantenimiento')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mileage')
                    ->label('KM')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Estado')
                    ->searchable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('Price_material')
                    ->label('Precio Material')
                    ->prefix('S/.')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('workforce')
                    ->label('Mano de Obra')
                    ->prefix('S/.')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('maintenance_cost')
                    ->label('Costo Total')
                    ->prefix('S/.')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('photo')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('file')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Eliminado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('vehicle_id')
                    ->label('Vehículo')
                    ->options(Vehicle::all()->pluck('placa', 'id'))
                    ->searchable()
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaintenances::route('/'),
            'create' => Pages\CreateMaintenance::route('/create'),
            'edit' => Pages\EditMaintenance::route('/{record}/edit'),
        ];
    }
}
