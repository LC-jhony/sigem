<?php

namespace App\Services;

use App\Models\Driver;
use App\Models\Mine;
use App\Models\DriverMineAssigment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DriverRotationService
{
    /**
     * Rota automáticamente los conductores entre minas para el mes especificado
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    public function rotateDrivers(int $year, int $month): array
    {
        $drivers = Driver::all();
        $mines = Mine::all();

        if ($drivers->isEmpty() || $mines->isEmpty()) {
            return [
                'success' => false,
                'message' => 'No hay conductores o minas disponibles para la rotación.'
            ];
        }

        // Obtener asignaciones del mes anterior para evitar repeticiones
        $prevMonth = $month == 1 ? 12 : $month - 1;
        $prevYear = $month == 1 ? $year - 1 : $year;

        $prevAssignments = DriverMineAssigment::where('year', $prevYear)
            ->where('month', $prevMonth)
            ->get()
            ->keyBy('driver_id');

        $mineIds = $mines->pluck('id')->toArray();
        $assignments = [];

        DB::beginTransaction();

        try {
            foreach ($drivers as $driver) {
                // Evitar que repita la misma mina del mes anterior
                $prevMineId = $prevAssignments->get($driver->id)?->mine_id;
                $availableMines = $prevMineId ? array_diff($mineIds, [$prevMineId]) : $mineIds;
                
                // Si no hay minas disponibles, usar todas
                if (empty($availableMines)) {
                    $availableMines = $mineIds;
                }

                $mineId = $availableMines[array_rand($availableMines)];

                $startDate = Carbon::create($year, $month, 1);
                $endDate = $startDate->copy()->endOfMonth();

                $assignment = DriverMineAssigment::updateOrCreate(
                    [
                        'driver_id' => $driver->id,
                        'year' => $year,
                        'month' => $month,
                    ],
                    [
                        'mine_id' => $mineId,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'status' => 'Activo',
                        'notes' => 'Asignación automática por rotación mensual'
                    ]
                );

                $assignments[] = $assignment;
            }

            DB::commit();

            return [
                'success' => true,
                'message' => "Rotación completada exitosamente. {$drivers->count()} conductores reasignados.",
                'assignments' => $assignments
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => 'Error durante la rotación: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verifica si un conductor puede ser asignado a una mina específica
     *
     * @param int $driverId
     * @param int $mineId
     * @param string $startDate
     * @param string $endDate
     * @return bool
     */
    public function canAssignDriver(int $driverId, int $mineId, string $startDate, string $endDate): bool
    {
        // Verificar conflictos de horarios
        $conflicts = DriverMineAssigment::where('driver_id', $driverId)
            ->where('status', 'Activo')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->exists();

        return !$conflicts;
    }

    /**
     * Obtiene estadísticas de rotación
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    public function getRotationStats(int $year, int $month): array
    {
        $assignments = DriverMineAssigment::where('year', $year)
            ->where('month', $month)
            ->with(['driver', 'mine'])
            ->get();

        $totalDrivers = Driver::count();
        $assignedDrivers = $assignments->count();
        $activeAssignments = $assignments->where('status', 'Activo')->count();

        return [
            'total_drivers' => $totalDrivers,
            'assigned_drivers' => $assignedDrivers,
            'active_assignments' => $activeAssignments,
            'unassigned_drivers' => $totalDrivers - $assignedDrivers,
            'assignments' => $assignments
        ];
    }
} 