<?php

namespace App\Livewire\Mantenance;

use App\Enum\MillageItems;
use App\Models\MaintenanceItem;
use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class MantenaceTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    /**
     * The record to display in the maintenance table.
     *
     * @var mixed
     **/
    public $record;

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return $this->record->maintenances();
            })
            ->striped()
            ->paginated([5, 10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(5)
            ->searchable()
            ->columns([
                Tables\Columns\TextColumn::make('maintenanceItem.name')
                    ->label('Mantenimiento')
                    ->sortable()
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
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
                    // ->slideOver(true)
                    ->form([
                        Forms\Components\Section::make('Archivos')
                            ->columns(2)
                            ->schema([
                                Forms\Components\FileUpload::make('photo')
                                    ->label('Foto del Mantenimiento')
                                    ->disk('public')
                                    ->directory('maintenance/photos')
                                    ->visibility('public')
                                    ->default(null),
                                Forms\Components\FileUpload::make('file')
                                    ->label('Archivo del Mantenimiento')
                                    ->disk('public')
                                    ->directory('maintenance/files')
                                    //->acceptedFileTypes(['application/pdf'])
                                    ->maxSize(2048),
                            ]),
                        Forms\Components\Grid::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\Select::make('vehicle_id')
                                    ->label('Vehiculo')
                                    ->options(Vehicle::all()->pluck('placa', 'id'))
                                    ->default(fn() => $this->record->id)
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                                Forms\Components\Select::make('maintenance_item_id')
                                    ->label('Mantenimiento')
                                    ->options(MaintenanceItem::all()->pluck('name', 'id'))
                                    ->label('Mantenimiento Item')
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('mileage')
                                    ->label('Kilometro')
                                    ->options(MillageItems::class)
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('status')
                                    ->label('Estado')
                                    ->options([
                                        '1' => 'Si',
                                        '0' => 'No',
                                    ])
                                    ->disabled()
                                    ->dehydrated()
                                    ->default('1'),
                                Forms\Components\Section::make('Costos')
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
                                            ->label('Mano de obra')
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
                                            ->label('Costo total')
                                            ->prefix('S/.')
                                            ->inputMode('decimal')
                                            ->mask(RawJs::make('$money($input, ",")'))
                                            ->numeric(),
                                    ]),

                            ]),

                    ]),

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
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->button()
                    ->color('primary')
                    ->form([
                        Forms\Components\Section::make('Archivos')
                            ->columns(2)
                            ->schema([
                                Forms\Components\FileUpload::make('photo')
                                    ->label('Foto del Mantenimiento')
                                    ->disk('public')
                                    ->directory('maintenance/photos')
                                    ->visibility('public')
                                    ->default(null),
                                Forms\Components\FileUpload::make('file')
                                    ->label('Archivo del Mantenimiento')
                                    ->disk('public')
                                    ->directory('maintenance/files')
                                    //->acceptedFileTypes(['application/pdf'])
                                    ->maxSize(2048),
                            ]),
                        Forms\Components\Grid::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\Select::make('vehicle_id')
                                    ->label('Vehiculo')
                                    ->options(Vehicle::all()->pluck('placa', 'id'))
                                    ->default(fn() => $this->record->id)
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                                Forms\Components\Select::make('maintenance_item_id')
                                    ->label('Mantenimiento')
                                    ->options(MaintenanceItem::all()->pluck('name', 'id'))
                                    ->label('Mantenimiento Item')
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('mileage')
                                    ->label('Kilometro')
                                    ->options(MillageItems::class)
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('status')
                                    ->label('Estado')
                                    ->options([
                                        '1' => 'Si',
                                        '0' => 'No',
                                    ])
                                    ->disabled()
                                    ->dehydrated()
                                    ->default('1'),
                                Forms\Components\Section::make('Costos')
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
                                            ->label('Mano de obra')
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
                                            ->label('Costo total')
                                            ->prefix('S/.')
                                            ->inputMode('decimal')
                                            ->mask(RawJs::make('$money($input, ",")'))
                                            ->numeric(),
                                    ]),

                            ]),

                    ]),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('delete')
                        ->label('Eliminar')
                        ->icon('heroicon-o-trash')
                        ->action(function (array $records) {
                            foreach ($records as $record) {
                                $record->delete();
                            }
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('Valorizaado')
                        ->label('Valorizado')
                        ->icon('bi-file-pdf-fill')
                        ->color('danger')
                        ->openUrlInNewTab()
                        ->deselectRecordsAfterCompletion()
                        ->action(function (Collection $records) {
                            $vehicle = $this->record; // Asegúrate de tener el vehículo actual
                            dd($vehicle);
                            return response()->streamDownload(function () use ($records) {
                                echo Pdf::loadHtml(
                                    Blade::render('pdf.value-maintenance', [
                                        'records' => $records,
                                        'vehicle' => $this->record,
                                    ])
                                )->stream();
                            }, $vehicle->placa . '-' . now()->format('Y-m-d') . '.pdf');
                        }),
                ]),
            ]);
    }

    public function render()
    {
        return view('livewire.mantenance.mantenace-table');
    }
}
