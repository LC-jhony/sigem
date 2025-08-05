<?php

namespace App\Filament\Resources;

use App\Enum\DocumentName;
use App\Enum\VeicleStatus;
use App\Filament\Resources\VehicleResource\Pages;
use App\Models\Vehicle;
use Asmit\FilamentUpload\Forms\Components\AdvancedFileUpload;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'mdi-excavator';

    protected static ?string $navigationGroup = 'Gestión de Personal';

    protected static ?string $modelLabel = 'Vehículos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Registro de Vehículo')
                    ->description('Ingrese los datos del vehículo')
                    ->icon('heroicon-o-rectangle-stack')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Grid::make()
                            ->columns(3)
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->label('PROG.')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('placa')
                                    ->label('Placa')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('marca')
                                    ->label('Marca')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Grid::make()
                            ->columns(3)
                            ->schema([
                                Forms\Components\TextInput::make('unidad')
                                    ->label('Unidad')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('property_card')
                                    ->label('Tarjeta de Propiedad')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('status')
                                    ->label('Estado del Vehículo')
                                    ->options(VeicleStatus::class)
                                    ->required()
                                    ->native(false),
                            ]),
                    ]),
                Forms\Components\Section::make('Documentos del Vehículo')
                    ->description('Ingrese los documentos del vehículo')
                    ->icon('bi-file-pdf-fill')
                    ->schema([
                        Forms\Components\Repeater::make('documents')
                            ->label('')
                            ->relationship()
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\DatePicker::make('date')
                                            ->label('Fecha de Vencimiento')
                                            ->timezone('America/Lima')
                                            ->displayFormat('d/m/Y')
                                            ->locale('es')
                                            ->native(false),
                                        Forms\Components\Select::make('name')
                                            ->label('Tipo de Documento')
                                            ->options(DocumentName::class)
                                            ->native(false)
                                            ->required(),
                                    ]),
                                AdvancedFileUpload::make('file')
                                    ->label('Documento')
                                    ->label('Documento')
                                    ->default(null)
                                    ->visibility('public')
                                    ->directory('DocumentsVehicle')
                                    ->acceptedFileTypes(['application/pdf']),
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
                Tables\Columns\TextColumn::make('code')
                    ->label('PROG.')
                    ->searchable(),
                Tables\Columns\TextColumn::make('placa')
                    ->label('Placa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('marca')
                    ->label('Marca')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unidad')
                    ->label('Unidad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('property_card')
                    ->label('Tar. Propiedad')
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('soat')
                    ->label('SOAT')
                    ->getStateUsing(function ($record) {
                        try {
                            if (! $documentSoat = $record->documents->firstWhere('name', 'SOAT')) {
                                return 'no-document';
                            }

                            return $documentSoat->date ? Carbon::parse($documentSoat->date) : null;
                        } catch (\Exception $e) {
                            Log::error("Error en SOAT [Vehículo {$record->id}]: ".$e->getMessage());

                            return 'invalid-date';
                        }
                    })
                    ->formatStateUsing(fn ($state) => match (true) {
                        $state === 'no-document' => 'Sin SOAT',
                        $state === 'invalid-date' => 'Fecha inválida',
                        default => $state?->format('d/m/Y') ?? 'Sin fecha'
                    })
                    ->badge()
                    ->color(function ($state) {
                        if (! is_object($state)) {
                            return 'gray';
                        }

                        $dias = now()->diffInDays($state, false);

                        return match (true) {
                            $dias < 0 || $dias <= 7 => 'danger',
                            $dias <= 30 => 'warning',
                            default => 'success'
                        };
                    }),
                Tables\Columns\TextColumn::make('tarjeta')
                    ->label('Tarjeta')
                    ->getStateUsing(function ($record) {
                        try {
                            if (! $documentSoat = $record->documents->firstWhere('name', 'TARJETA DE CIRCULACION')) {
                                return 'no-document';
                            }

                            return $documentSoat->date ? Carbon::parse($documentSoat->date) : null;
                        } catch (\Exception $e) {
                            Log::error("Error en Tarjeta [Vehículo {$record->id}]: ".$e->getMessage());

                            return 'invalid-date';
                        }
                    })
                    ->formatStateUsing(fn ($state) => match (true) {
                        $state === 'no-document' => 'Sin TARJETA',
                        $state === 'invalid-date' => 'Fecha inválida',
                        default => $state?->format('d/m/Y') ?? 'Sin fecha'
                    })
                    ->badge()
                    ->color(function ($state) {
                        if (! is_object($state)) {
                            return 'gray';
                        }

                        $dias = now()->diffInDays($state, false);

                        return match (true) {
                            $dias < 0 || $dias <= 7 => 'danger',
                            $dias <= 30 => 'warning',
                            default => 'success'
                        };
                    }),
                Tables\Columns\TextColumn::make('revision')
                    ->label('Revision')
                    ->getStateUsing(function ($record) {
                        try {
                            if (! $documentSoat = $record->documents->firstWhere('name', 'REVICION TECNICA')) {
                                return 'no-document';
                            }

                            return $documentSoat->date ? Carbon::parse($documentSoat->date) : null;
                        } catch (\Exception $e) {
                            Log::error("Error en Revision [Vehículo {$record->id}]: ".$e->getMessage());

                            return 'invalid-date';
                        }
                    })
                    ->formatStateUsing(fn ($state) => match (true) {
                        $state === 'no-document' => 'Sin REVISIÓN',
                        $state === 'invalid-date' => 'Fecha inválida',
                        default => $state?->format('d/m/Y') ?? 'Sin fecha'
                    })
                    ->badge()
                    ->color(function ($state) {
                        if (! is_object($state)) {
                            return 'gray';
                        }

                        $dias = now()->diffInDays($state, false);

                        return match (true) {
                            $dias < 0 || $dias <= 7 => 'danger',
                            $dias <= 30 => 'warning',
                            default => 'success'
                        };
                    }),
                Tables\Columns\TextColumn::make('poliza')
                    ->label('Poliza')
                    ->getStateUsing(function ($record) {
                        try {
                            if (! $documentSoat = $record->documents->firstWhere('name', 'POLIZA DE SEGURO VEHICULAR')) {
                                return 'no-document';
                            }

                            return $documentSoat->date ? Carbon::parse($documentSoat->date) : null;
                        } catch (\Exception $e) {
                            Log::error("Error en POLIZA [Vehículo {$record->id}]: ".$e->getMessage());

                            return 'invalid-date';
                        }
                    })
                    ->formatStateUsing(fn ($state) => match (true) {
                        $state === 'no-document' => 'Sin POLIZA',
                        $state === 'invalid-date' => 'Fecha inválida',
                        default => $state?->format('d/m/Y') ?? 'Sin fecha'
                    })
                    ->badge()
                    ->color(function ($state) {
                        if (! is_object($state)) {
                            return 'gray';
                        }

                        $dias = now()->diffInDays($state, false);

                        return match (true) {
                            $dias < 0 || $dias <= 7 => 'danger',
                            $dias <= 30 => 'warning',
                            default => 'success'
                        };
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Operativo' => 'success',
                        'En Mantenimiento' => 'warning',
                        'Fuera de Servicio' => 'danger',
                        'En Reparación' => 'gray',
                        'Disponible' => 'info',
                        'En Uso' => 'success',
                        default => 'primary',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_maintenances')
                    ->label('Mantenimientos')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->color('warning')
                    ->modalContent(function ($record) {
                        return view('livewire.mantenance_modal', ['record' => $record]);
                    })
                    ->modalHeading(fn ($record) => 'Mantenimientos - Vehículo: '.$record->placa)
                    ->slideOver(true)
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->modalWidth(MaxWidth::SevenExtraLarge)
                    ->visible(fn ($record) => ! empty($record) && auth()->user()->hasAnyRole(['super_admin', 'Super Admin', 'Usuario'])),
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->color('primary'),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'view' => Pages\ViewVehicle::route('/{record}'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
