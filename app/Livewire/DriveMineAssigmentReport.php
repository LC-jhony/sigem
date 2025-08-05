<?php

namespace App\Livewire;

use Filament\Forms;
use App\Models\Mine;
use Livewire\Component;
use Filament\Forms\Form;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DriverMineAssigment;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class DriveMineAssigmentReport extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información de Reporte')
                    ->description('Ingrese los datos del Reporte')
                    ->icon('bi-file-pdf-fill')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('mine_id')
                            ->label('Mina')
                            ->options(Mine::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->native(false),
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
                            ->required(),
                        Forms\Components\TextInput::make('year')
                            ->label('Año')
                            ->required()
                            ->numeric(),
                    ])
            ])
            ->statePath('data')
            ->model(DriverMineAssigment::class);
    }

    public function create()
    {
        $data = $this->data;
        $pdf = Pdf::loadView('pdf.assigment-mine-driver-report', [
            'mine' => Mine::find($data['mine_id']),
            'month' => $data['month'],
            'year' => $data['year'],
        ]);
        return response()->streamDownload(
            fn() => print($pdf->output()),
            "reporte_mina_{$data['year']}_{$data['month']}.pdf"
        );
    }
    public function render(): View
    {
        return view('livewire.drive-mine-assigment-report');
    }
}
