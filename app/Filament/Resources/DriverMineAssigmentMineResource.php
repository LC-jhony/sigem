<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DriverMineAssigmentMineResource\Pages;
use App\Models\DriverMineAssigment;
use App\Models\Mine;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DriverMineAssigmentMineResource extends Resource
{
    protected static ?string $model = DriverMineAssigment::class;

    protected static ?string $navigationIcon = 'healthicons-o-miner-worker';

    protected static ?string $navigationLabel = 'Asignaciones';

    protected static ?string $modelLabel = 'Asignación';

    protected static ?string $pluralModelLabel = 'Asignaciones de Conductores';

    protected static ?string $navigationGroup = 'Gestión de Minas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Section::make('Información de Asignación')
                            ->schema([
                                Forms\Components\Select::make('driver_id')
                                    ->label('Conductor')
                                    ->relationship('driver')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name.' - '.$record->dni)
                                    ->searchable(['name', 'last_paternal_name', 'last_maternal_name', 'dni'])
                                    ->required()
                                    ->preload()
                                    ->native(false),
                                Forms\Components\Select::make('mine_id')
                                    ->label('Mina')
                                    ->relationship('mine', 'name')
                                    ->searchable()
                                    ->required()
                                    ->preload()
                                    ->native(false),
                            ])
                            ->columns(2),
                        Forms\Components\Section::make('Período de Asignación')
                            ->schema([
                                Forms\Components\Select::make('month')
                                    ->label('Mes')
                                    ->options([
                                        1 => 'Enero',
                                        2 => 'Febrero',
                                        3 => 'Marzo',
                                        4 => 'Abril',
                                        5 => 'Mayo',
                                        6 => 'Junio',
                                        7 => 'Julio',
                                        8 => 'Agosto',
                                        9 => 'Septiembre',
                                        10 => 'Octubre',
                                        11 => 'Noviembre',
                                        12 => 'Diciembre',
                                    ])
                                    ->default(date('n'))
                                    ->required()
                                    ->native(false)
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                        self::updateDates($set, $get);
                                    }),
                                Forms\Components\TextInput::make('year')
                                    ->label('Año')
                                    // ->options(function () {
                                    //     $currentYear = date('Y');
                                    //     $years = [];
                                    //     for ($i = $currentYear - 1; $i <= $currentYear + 2; $i++) {
                                    //         $years[$i] = $i;
                                    //     }

                                    //     return $years;
                                    // })
                                    ->default(date('Y'))
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                        self::updateDates($set, $get);
                                    }),
                            ])
                            ->columns(2),
                        Forms\Components\Section::make('Fechas')
                            ->schema([
                                Forms\Components\DatePicker::make('start_date')
                                    ->label('Fecha de Inicio')
                                    ->required()
                                    ->default(now()
                                        ->startOfMonth())
                                    ->native(false),
                                Forms\Components\DatePicker::make('end_date')
                                    ->label('Fecha de Fin')
                                    ->required()
                                    ->default(now()
                                        ->endOfMonth())
                                    ->native(false),
                            ])
                            ->columns(2),
                    ])
                    ->columns(3),
                Forms\Components\Section::make('Estado y Notas')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                'Activo' => 'Activo',
                                'Completedo' => 'Completado',
                                'Cancelado' => 'Cancelado',
                            ])
                            ->default('Activo')
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                // Independientemente del status, siempre usar mes y año actual
                                $set('month', (int) date('n'));
                                $set('year', (int) date('Y'));
                                self::updateDates($set, $get);
                            }),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

            ]);
    }

    protected static function updateDates(Set $set, Get $get): void
    {
        $year = $get('year');
        $month = $get('month');

        if ($year && $month) {
            $startDate = Carbon::create($year, $month, 1);
            $endDate = $startDate->copy()->endOfMonth();

            $set('start_date', $startDate->format('Y-m-d'));
            $set('end_date', $endDate->format('Y-m-d'));
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->paginated([5, 10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(5)
            ->searchable()
            ->columns([
                Tables\Columns\TextColumn::make('driver.full_name')
                    ->label('Conductor')
                    ->searchable(['drivers.name', 'drivers.last_paternal_name', 'drivers.last_maternal_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('mine.name')
                    ->label('Mina')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('period')
                    ->label('Período')
                    ->sortable(['year', 'month']),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Fecha Inicio')
                    ->date()
                    ->dateTimeTooltip()
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Fecha Fin')
                    ->since()
                    ->dateTimeTooltip()
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'activo' => 'success',
                        'completado', 'completedo' => 'warning',
                        'cancelado' => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match (strtolower($state)) {
                        'activo' => 'Activo',
                        'completado', 'completedo' => 'Completado',
                        'cancelado' => 'Cancelado',
                        default => ucfirst($state),
                    }),
                // Tables\Columns\IconColumn::make('is_active')
                //     ->label('Vigente')
                //     ->boolean()
                //     ->trueIcon('heroicon-o-check-circle')
                //     ->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('mine_id')
                    ->label('Mina')
                    ->options(Mine::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->native(false),
                Tables\Filters\SelectFilter::make('month')
                    ->label('Mes')
                    ->options([
                        1 => 'Enero',
                        2 => 'Febrero',
                        3 => 'Marzo',
                        4 => 'Abril',
                        5 => 'Mayo',
                        6 => 'Junio',
                        7 => 'Julio',
                        8 => 'Agosto',
                        9 => 'Septiembre',
                        10 => 'Octubre',
                        11 => 'Noviembre',
                        12 => 'Diciembre',
                    ]),
                Tables\Filters\SelectFilter::make('year')
                    ->label('Año')
                    ->options(function () {
                        $years = range(date('Y') - 1, date('Y') + 2);

                        return array_combine($years, $years);
                    }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('complete')
                    ->label('Completar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->button()
                    ->visible(fn (DriverMineAssigment $record) => $record->status === 'Activo')
                    ->requiresConfirmation()
                    ->action(fn (DriverMineAssigment $record) => $record->update(['status' => 'Completedo'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\BulkAction::make('complete_selected')
                        ->label('Completar Periodo')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                if ($record->status === 'Activo') {
                                    $record->update(['status' => 'Completedo']);
                                }
                            });
                        }),
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
            'index' => Pages\ListDriverMineAssigmentMines::route('/'),
            'create' => Pages\CreateDriverMineAssigmentMine::route('/create'),
            'edit' => Pages\EditDriverMineAssigmentMine::route('/{record}/edit'),
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
