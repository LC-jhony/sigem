<?php

namespace App\Filament\Resources;

use App\Enum\MillageItems;
use App\Filament\Resources\MaintenanceResource\Pages;
use App\Models\Maintenance;
use App\Models\MaintenanceItem;
use App\Models\Vehicle;
use App\Tables\Columns\BrakePadProgress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;

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
                            ->disk('public')
                            ->directory('maintenance/photos')
                            ->visibility('public')
                            ->default(null)
                            ->helperText(str('La Foto  **del Mantenimiento** debe de subirlo para el mantenimiento.')->inlineMarkdown()->toHtmlString()),
                        Forms\Components\FileUpload::make('file')
                            ->label('Archivo del Mantenimiento')
                            ->disk('public')
                            ->directory('maintenance/files')
                            // ->acceptedFileTypes(['application/pdf'])
                            ->helperText(str('El archivo  **Boleta, Factura** debe de subirlo para el mantenimiento.')->inlineMarkdown()->toHtmlString()),
                    ]),
                Forms\Components\Grid::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('vehicle_id')
                            ->label('Vehiculo')
                            ->options(Vehicle::all()->pluck('placa', 'id'))
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('maintenance_item_id')
                            ->label('Mantenimiento')
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
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                '1' => 'Si',
                                '0' => 'No',
                            ])
                            ->disabled()
                            ->dehydrated()
                            ->default('1'),
                        Forms\Components\Section::make('Pastilla de Freno')
                            ->icon('iconpark-brakepads-o')
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('front_left_brake_pad')
                                    ->label('Pastilla delantera izquierda')
                                    ->prefix('%')
                                    ->numeric(),
                                Forms\Components\TextInput::make('front_right_brake_pad')
                                    ->label('Pastilla delantera derecha')
                                    ->prefix('%')
                                    ->numeric(),
                                Forms\Components\TextInput::make('rear_left_brake_pad')
                                    ->label('Pastilla trasera izquierda')
                                    ->prefix('%')
                                    ->numeric(),
                                Forms\Components\TextInput::make('rear_right_brake_pad')
                                    ->label('Pastilla trasera derecha')
                                    ->prefix('%')
                                    ->numeric(),
                                Forms\Components\DatePicker::make('brake_pads_checked_at')
                                    ->label('Fecha de Verificación')
                                    ->default(now())
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->native(false),
                            ]),
                        Forms\Components\Section::make('Costos')
                            ->description('Valorizado del Mantenimiento Vehicular')
                            ->icon('heroicon-o-currency-dollar')
                            ->columns(3)
                            ->schema([
                                Forms\Components\TextInput::make('Price_material')
                                    ->label('Precio Material')
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
                                    ->label('Mano de Obra')
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
                                    ->label('Costo Total')
                                    ->prefix('S/.')
                                    ->inputMode('decimal')
                                    ->mask(RawJs::make('$money($input, ",")'))
                                    ->numeric(),
                            ]),

                    ]),
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
                // Tables\Columns\IconColumn::make('status')
                //     ->label('Estado')
                //     ->searchable()
                //     ->boolean(),
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
                BrakePadProgress::make('brake_pad_progress'),
                // Tables\Columns\TextColumn::make('front_left_brake_pad')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('front_right_brake_pad')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('rear_left_brake_pad')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('rear_right_brake_pad')
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
