<?php

namespace App\Livewire\Mantenance;

use Filament\Forms;
use Filament\Tables;
use App\Models\Vehicle;
use Livewire\Component;
use App\Enum\MillageItems;
use Filament\Tables\Table;
use App\Models\MaintenanceItem;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Support\RawJs;
use Filament\Tables\Concerns\InteractsWithTable;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class MantenaceTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    /**
     * The record to display in the maintenance table.
     * @var mixed 
     **/
    public $record;
    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return $this->record->maintenances();
            })
            ->columns([
                Tables\Columns\TextColumn::make('maintenanceItem.name')
                    ->label('Mantenimiento')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mileage')
                    ->label('KM')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Estado')
                    ->sortable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('Price_material')
                    ->label('Precio Material')
                    ->sortable()
                    ->searchable()
                    ->money('S/.', 2),
                Tables\Columns\TextColumn::make('workforce')
                    ->label('Mano de Obra')
                    ->sortable()
                    ->searchable()
                    ->money('S/.', 2),
                Tables\Columns\TextColumn::make('maintenance_cost')
                    ->label('Costo Total')
                    ->sortable()
                    ->searchable()
                    ->money('S/.', 2),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable()
            ])
            ->headerActions([
                Tables\Actions\Action::make('valued_pdf')
                    ->label('Valorizado')
                    ->icon('bi-file-pdf-fill')
                    ->url(route('valuemantenacevehicle', $this->record->id))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('maintenance_report')
                    ->label('Historial')
                    ->icon('heroicon-o-table-cells')
                    ->color('danger')
                    ->url(route('maintenacehistory', $this->record->id))
                    ->openUrlInNewTab(),
                CreateAction::make()
                    ->label('Nuevo Mantenimiento')
                    ->color('warning')
                    ->icon('heroicon-o-plus-circle')
                    ->modalWidth(MaxWidth::SevenExtraLarge)
                    //   ->slideOver(true)
                    ->form([
                        Forms\Components\Section::make('Archivos')
                            ->columns(2)
                            ->schema([
                                Forms\Components\FileUpload::make('photo')
                                    ->label('Foto del Mantenimiento')
                                    ->label('Documento')
                                    // ->multiple()

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
                                    ->default(fn() => $this->record->id)
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                                Forms\Components\Select::make('maintenance_item_id')
                                    ->options(MaintenanceItem::all()->pluck('name', 'id'))
                                    ->label('Mantenimiento Item')
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('mileage')
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

                    ])

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('mileage')
                    ->label('Kilometraje')
                    ->options(MillageItems::class)
                    ->native(false),
                Tables\Filters\SelectFilter::make('maintenance_item_id')
                    ->label('Tipo de Mantenimiento')
                    ->options(MaintenanceItem::all()->pluck('name', 'id'))
                    ->native(false),
            ])
            ->actions([]);
    }
    public function render()
    {
        return view('livewire.mantenance.mantenace-table');
    }
}
