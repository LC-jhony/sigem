<?php

namespace App\Console\Commands;

use App\Services\DriverRotationService;
use Illuminate\Console\Command;

class RotateDriversCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drivers:rotate {--month= : Mes específico (1-12)} {--year= : Año específico}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rota automáticamente los conductores entre minas para el mes especificado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $month = $this->option('month') ?: date('n');
        $year = $this->option('year') ?: date('Y');

        $this->info("Iniciando rotación de conductores para {$month}/{$year}...");

        $service = new DriverRotationService;
        $result = $service->rotateDrivers($year, $month);

        if ($result['success']) {
            $this->info('✅ '.$result['message']);

            // Mostrar estadísticas
            $stats = $service->getRotationStats($year, $month);
            $this->table(
                ['Métrica', 'Valor'],
                [
                    ['Total Conductores', $stats['total_drivers']],
                    ['Conductores Asignados', $stats['assigned_drivers']],
                    ['Asignaciones Activas', $stats['active_assignments']],
                    ['Conductores Sin Asignar', $stats['unassigned_drivers']],
                ]
            );
        } else {
            $this->error('❌ '.$result['message']);

            return 1;
        }

        return 0;
    }
}
