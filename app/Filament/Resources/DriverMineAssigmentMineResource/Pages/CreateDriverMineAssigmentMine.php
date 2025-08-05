<?php

namespace App\Filament\Resources\DriverMineAssigmentMineResource\Pages;

use App\Filament\Resources\DriverMineAssigmentMineResource;
use App\Models\DriverMineAssigment;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateDriverMineAssigmentMine extends CreateRecord
{
    protected static string $resource = DriverMineAssigmentMineResource::class;

    /**
     * Define la URL de redirección después de crear un registro
     *
     * Redirige al usuario a la página de índice del recurso después
     * de crear exitosamente una asignación.
     *
     * @return string URL de redirección
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Hook que se ejecuta antes de crear el registro
     *
     * Valida que no exista una asignación duplicada para el mismo conductor
     * en el mismo período (año y mes). Si encuentra una asignación existente,
     * muestra una notificación de error y detiene el proceso de creación.
     *
     * @throws \Exception Si existe una asignación duplicada
     */
    protected function beforeCreate(): void
    {
        $data = $this->form->getState();

        // Validar asignación duplicada (unique constraint)
        $this->validateUniqueAssignment($data);

        // Validar conflictos de horarios
        $this->validateScheduleConflicts($data);

        // Validar capacidad del vehículo
        // $this->validateVehicleCapacity($data);

        // Validar licencias del conductor
        $this->validateDriverLicenses($data);
    }

    private function validateUniqueAssignment(array $data): void
    {
        // Extraer año y mes de la fecha de inicio
        $startDate = \Carbon\Carbon::parse($data['start_date']);
        $year = $startDate->year;
        $month = $startDate->month;

        // Verificar si ya existe una asignación para este conductor en este período
        $existingAssignment = DriverMineAssigment::where('driver_id', $data['driver_id'])
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        if ($existingAssignment) {
            $driver = \App\Models\Driver::find($data['driver_id']);
            $mine = \App\Models\Mine::find($existingAssignment->mine_id);

            Notification::make()
                ->title('Asignación Duplicada')
                ->body("El conductor {$driver->full_name} ya tiene una asignación en {$existingAssignment->month_name} {$existingAssignment->year} en la mina {$mine->name}. No se pueden crear asignaciones duplicadas para el mismo período.")
                ->danger()
                ->send();
            $this->halt();
        }
    }

    private function validateDriverLicenses(array $data): void
    {
        $driver = \App\Models\Driver::find($data['driver_id']);

        if (! $driver) {
            Notification::make()
                ->title('Conductor no encontrado')
                ->body('No se encontró el conductor seleccionado.')
                ->danger()
                ->send();
            $this->halt();
        }

        // Suponiendo que la relación es $driver->licenses y el campo es expiration_date
        $hasValidLicense = $driver->driverLicenses()
            ->whereDate('expiration_date', '>=', $data['start_date'])
            ->whereNull('deleted_at') // Por si usas SoftDeletes
            ->exists();

        if (! $hasValidLicense) {
            Notification::make()
                ->title('Licencia no válida')
                ->body('El conductor no tiene una licencia activa o la licencia está vencida para la fecha de inicio de la asignación.')
                ->danger()
                ->send();
            $this->halt();
        }
    }

    private function validateScheduleConflicts(array $data): void
    {
        $conflicts = DriverMineAssigment::where('driver_id', $data['driver_id'])
            ->where('status', 'active')
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                    ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']])
                    ->orWhere(function ($q) use ($data) {
                        $q->where('start_date', '<=', $data['start_date'])
                            ->where('end_date', '>=', $data['end_date']);
                    });
            })
            ->exists();

        if ($conflicts) {
            Notification::make()
                ->title('Conflicto de Horarios')
                ->body('El conductor ya tiene asignaciones en el período seleccionado.')
                ->warning()
                ->send();
            $this->halt();
        }
    }

    /**
     * Hook que se ejecuta después de crear el registro exitosamente
     *
     * Muestra una notificación de éxito confirmando que la asignación
     * ha sido creada correctamente.
     */
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Asignación Creada')
            ->body('La asignación ha sido creada exitosamente.')
            ->success();
    }
}
