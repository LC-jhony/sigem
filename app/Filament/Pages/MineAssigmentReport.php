<?php

namespace App\Filament\Pages;

use App\Models\DriverMineAssigment;
use App\Models\Mine;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class MineAssigmentReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'bi-file-pdf-fill';

    protected static string $view = 'filament.pages.mine-assigment-report';

    protected static ?string $navigationGroup = 'Gestión de Minas';

    protected static ?string $navigationLabel = 'Reporte de Asignaciones';

    protected static ?string $title = 'Reporte Asignaciones';

    public ?array $data = [];

    public $selectedMine = null;

    public $selectedMonth = null;

    public $selectedYear = null;

    public $previewData = [];

    public function mount(): void
    {
        $this->form->fill([
            'year' => date('Y'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información de Reporte')
                    ->description('Seleccione los parámetros para generar el reporte')
                    ->icon('bi-file-pdf-fill')
                    ->schema([
                        Select::make('mine_id')
                            ->label('Mina')
                            ->options(Mine::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(fn () => $this->updatePreview()),
                        Select::make('month')
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

                            ->live()
                            ->afterStateUpdated(fn () => $this->updatePreview()),
                        TextInput::make('year')
                            ->label('Año')
                            ->required()
                            ->numeric()
                            ->default(date('Y'))
                            ->live()
                            ->afterStateUpdated(fn () => $this->updatePreview()),
                        Actions::make([
                            // Action::make('preview')
                            //     ->label('Vista Previa')
                            //     ->icon('heroicon-o-eye')
                            //     ->color('info')
                            //     ->action('generatePreview'),
                            Action::make('export')
                                ->label('Exportar PDF')
                                ->icon('bi-file-pdf-fill')
                                ->color('success')
                                ->action(function () {
                                    $data = $this->form->getState();

                                    // Validar que todos los campos estén completos
                                    if (empty($data['mine_id']) || empty($data['month']) || empty($data['year'])) {
                                        Notification::make()
                                            ->title('Error')
                                            ->body('Por favor complete todos los campos antes de generar el PDF.')
                                            ->danger()
                                            ->send();

                                        return;
                                    }

                                    // Generar la URL del PDF y abrir en nueva pestaña
                                    $url = url("mineassigmentreport/{$data['mine_id']}")."?month={$data['month']}&year={$data['year']}";

                                    // Usar JavaScript para abrir en nueva pestaña
                                    $this->js("window.open('$url', '_blank');");
                                }),

                            // ->disabled(fn() => empty($this->previewData)),
                        ])->fullWidth(),
                    ]),
            ])
            ->statePath('data');
    }

    public function updatePreview()
    {
        $data = $this->form->getState();

        if (! empty($data['mine_id']) && ! empty($data['month']) && ! empty($data['year'])) {
            $this->selectedMine = Mine::find($data['mine_id']);
            $this->selectedMonth = $data['month'];
            $this->selectedYear = $data['year'];

            // Obtener datos de asignaciones para el preview
            $this->previewData = DriverMineAssigment::with(['driver.cargo', 'mine'])
                ->where('mine_id', $data['mine_id'])
                ->whereMonth('created_at', $data['month'])
                ->whereYear('created_at', $data['year'])
                ->where('status', 'Activo')
                ->get()
                ->toArray();
        } else {
            $this->previewData = [];
            $this->selectedMine = null;
            $this->selectedMonth = null;
            $this->selectedYear = null;
        }
    }

    public function generatePreview()
    {
        $this->updatePreview();

        if (empty($this->previewData)) {
            Notification::make()
                ->title('Sin datos')
                ->body('No se encontraron asignaciones para los parámetros seleccionados.')
                ->warning()
                ->send();
        } else {
            Notification::make()
                ->title('Vista previa actualizada')
                ->body('Se encontraron '.count($this->previewData).' asignaciones.')
                ->success()
                ->send();
        }
    }

    public function exportPdf()
    {
        if (empty($this->previewData)) {
            Notification::make()
                ->title('Error')
                ->body('No hay datos para exportar. Genere primero la vista previa.')
                ->danger()
                ->send();

            return;
        }

        $pdf = Pdf::loadView('pdf.assigment-mine-driver-report', [
            'mine' => $this->selectedMine,
            'month' => $this->selectedMonth,
            'year' => $this->selectedYear,
            'assignments' => $this->previewData,
        ]);

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            "reporte_mina_{$this->selectedYear}_{$this->selectedMonth}.pdf"
        );
    }

    public function getMonthName($month)
    {
        $months = [
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
        ];

        return $months[$month] ?? '';
    }
}
