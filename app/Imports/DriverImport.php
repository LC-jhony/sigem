<?php

namespace App\Imports;

use App\Models\Cargo;
use App\Models\Driver;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DriverImport implements ToCollection, WithHeadingRow
{
    protected $additionalData = [];

    protected $customImportData = [];

    protected $importStats = [
        'total' => 0,
        'imported' => 0,
        'skipped' => 0,
        'errors' => 0,
    ];

    public function collection(Collection $rows)
    {
        $successCount = 0;
        $errorCount = 0;
        $duplicatesInExcel = 0;
        $duplicatesNotImported = 0;
        $errors = [];
        $processedDnis = [];
        $duplicateDnis = [];

        foreach ($rows as $rowIndex => $row) {
            try {
                // Skip empty rows
                if (empty($row['dni']) || empty($row['nombres'])) {
                    continue;
                }

                $dni = trim($row['dni']);

                // Check if this DNI already appeared in the Excel file
                if (in_array($dni, $processedDnis)) {
                    $duplicatesInExcel++;
                    $duplicateDnis[] = $dni;

                    // Log the duplicate
                    Log::warning('Duplicate DNI found in Excel', [
                        'dni' => $dni,
                        'row' => $rowIndex + 2, // +2 because of 0-index and header row
                        'data' => $row,
                    ]);

                    // Skip processing this duplicate row
                    $duplicatesNotImported++;

                    continue;
                }

                // Add DNI to processed list
                $processedDnis[] = $dni;

                // Find or create cargo if provided
                $cargo = null;
                if (! empty($row['cargo'])) {
                    $cargo = Cargo::firstOrCreate(
                        ['name' => trim($row['cargo'])]
                    );
                }

                // Create or update driver
                $driver = Driver::updateOrCreate(
                    ['dni' => $dni], // Find by DNI
                    [
                        'name' => trim($row['nombres']),
                        'last_paternal_name' => ! empty($row['apellido_paterno']) ? trim($row['apellido_paterno']) : null,
                        'last_maternal_name' => ! empty($row['apellido_materno']) ? trim($row['apellido_materno']) : null,
                        'cargo_id' => $cargo?->id,
                    ]
                );

                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = 'Fila '.($rowIndex + 2)." con DNI {$row['dni']}: ".$e->getMessage();
                Log::error('Error importing driver', [
                    'row' => $rowIndex + 2,
                    'data' => $row,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Send detailed notification with results
        $this->sendImportNotification($successCount, $errorCount, $duplicatesInExcel, $duplicatesNotImported, $duplicateDnis);
    }

    private function sendImportNotification(int $successCount, int $errorCount, int $duplicatesInExcel, int $duplicatesNotImported, array $duplicateDnis)
    {
        $title = 'Resultado de la importación';
        $bodyParts = [];

        // Success message
        if ($successCount > 0) {
            $bodyParts[] = "✅ {$successCount} conductores importados exitosamente";
        }

        // Duplicates in Excel message
        if ($duplicatesInExcel > 0) {
            $bodyParts[] = "⚠️ {$duplicatesInExcel} datos duplicados encontrados en el Excel";
            $bodyParts[] = "🚫 {$duplicatesNotImported} datos duplicados no fueron importados";

            // Show some example DNIs if there are duplicates
            if (count($duplicateDnis) > 0) {
                $exampleDnis = array_slice(array_unique($duplicateDnis), 0, 3);
                $bodyParts[] = '📋 Ejemplos de DNIs duplicados: '.implode(', ', $exampleDnis);
                if (count($duplicateDnis) > 3) {
                    $bodyParts[] = '... y '.(count(array_unique($duplicateDnis)) - 3).' más';
                }
            }
        }

        // Error message
        if ($errorCount > 0) {
            $bodyParts[] = "❌ {$errorCount} filas tuvieron errores";
        }

        $body = implode("\n", $bodyParts);

        // Determine notification type and color
        if ($successCount > 0 && $errorCount === 0 && $duplicatesInExcel === 0) {
            // Perfect import
            Notification::make()
                ->title('✅ Importación exitosa')
                ->body($body)
                ->success()
                ->duration(5000)
                ->send();
        } elseif ($successCount > 0) {
            // Partial success
            Notification::make()
                ->title('⚠️ Importación completada con observaciones')
                ->body($body)
                ->warning()
                ->duration(8000)
                ->send();
        } else {
            // Failed import
            Notification::make()
                ->title('❌ Error en la importación')
                ->body($body ?: 'No se pudo importar ningún conductor. Revisa el formato del archivo.')
                ->danger()
                ->duration(10000)
                ->send();
        }

        // Additional detailed notification if there were many duplicates
        if ($duplicatesInExcel > 5) {
            Notification::make()
                ->title('📊 Reporte detallado de duplicados')
                ->body("Se encontraron {$duplicatesInExcel} registros duplicados en el archivo Excel. ".
                    'Estos duplicados fueron omitidos para evitar inconsistencias en la base de datos. '.
                    'Revisa tu archivo Excel para eliminar las filas duplicadas antes de importar.')
                ->info()
                ->duration(12000)
                ->send();
        }
    }

    public function headingRow(): int
    {
        return 1; // First row contains headers
    }

    /**
     * Obtener DNI de la fila con múltiples variaciones
     */
    private function getDniFromRow($row): string
    {
        $dniFields = ['dni', 'documento', 'cedula', 'ci', 'numero_documento'];

        foreach ($dniFields as $field) {
            if (! empty($row[$field])) {
                return trim((string) $row[$field]);
            }
        }

        return '';
    }

    /**
     * Obtener apellido paterno de la fila
     */
    private function getLastPaternalName($row): string
    {
        $fields = [
            'apellido_paterno',
            'apellido paterno',
            'last_paternal_name',
            'primer_apellido',
            'primer apellido',
        ];

        foreach ($fields as $field) {
            if (! empty($row[$field])) {
                return trim((string) $row[$field]);
            }
        }

        return '';
    }

    /**
     * Obtener apellido materno de la fila
     */
    private function getLastMaternalName($row): string
    {
        $fields = [
            'apellido_materno',
            'apellido materno',
            'last_maternal_name',
            'segundo_apellido',
            'segundo apellido',
        ];

        foreach ($fields as $field) {
            if (! empty($row[$field])) {
                return trim((string) $row[$field]);
            }
        }

        return '';
    }

    /**
     * Obtener nombre de la fila
     */
    private function getName($row): string
    {
        $fields = [
            'name',
            'nombres',
            'nombre',
            'first_name',
            'nombre_completo',
        ];

        foreach ($fields as $field) {
            if (! empty($row[$field])) {
                return trim((string) $row[$field]);
            }
        }

        return '';
    }

    /**
     * Obtener o crear cargo
     */
    private function getOrCreateCargo($row): Cargo
    {
        $cargoFields = ['CARGO', 'cargo', 'position', 'puesto', 'job_title'];
        $cargoName = 'Default Cargo';

        foreach ($cargoFields as $field) {
            if (! empty($row[$field])) {
                $cargoName = trim((string) $row[$field]);
                break;
            }
        }

        return Cargo::firstOrCreate(
            ['name' => $cargoName],
            ['name' => $cargoName]
        );
    }

    /**
     * Obtener estadísticas de importación
     */
    public function getImportStats(): array
    {
        return $this->importStats;
    }

    public function setAdditionalData($data)
    {
        $this->additionalData = $data;
    }

    public function setCustomImportData($data)
    {
        $this->customImportData = $data;
    }
}
