<?php

namespace App\Imports;

use App\Models\Cargo;
use App\Models\Driver;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Clase para importar conductores desde archivos Excel
 * 
 * Esta clase maneja la importación de datos de conductores desde archivos Excel,
 * validando duplicados por DNI y creando automáticamente los cargos necesarios.
 * 
 * Implementa:
 * - ToModel: Para convertir cada fila del Excel en un modelo de Driver
 * - WithHeadingRow: Para usar la primera fila como encabezados de columna
 */
class DriverImport implements ToModel, WithHeadingRow
{
    /**
     * Convierte una fila del archivo Excel en un modelo Driver
     * 
     * Este método procesa cada fila del archivo Excel y crea un nuevo conductor
     * si no existe un DNI duplicado. También maneja la creación automática de cargos.
     * 
     * @param array $row Array asociativo con los datos de la fila, donde las claves
     *                   son los nombres de las columnas del Excel
     * 
     * @return \Illuminate\Database\Eloquent\Model|null Retorna un modelo Driver si
     *                                                   los datos son válidos, o null
     *                                                   si la fila debe ser omitida
     * 
     * Columnas esperadas en el Excel:
     * - 'dni' o 'documento': Documento Nacional de Identidad (requerido)
     * - 'apellido paterno' o 'apellido_materno': Apellido materno del conductor
     * - 'apellido materno' o 'apellido_paterno': Apellido paterno del conductor
     * - 'name' o 'nombres': Nombres del conductor
     * - 'cargo' o 'CARGO': Cargo del conductor (se crea automáticamente si no existe)
     * 
     * Validaciones:
     * - Omite filas con DNI vacío
     * - Omite filas con DNI duplicado (ya existente en la base de datos)
     * 
     * Comportamiento especial:
     * - Crea automáticamente el cargo si no existe usando firstOrCreate()
     * - Usa 'Default Cargo' como valor por defecto si no se especifica cargo
     * - Maneja múltiples variaciones de nombres de columnas para flexibilidad
     */
    public function model(array $row)
    {
        // Obtener DNI con múltiples variaciones de nombres de columna
        $dni = $row['dni'] ?? $row['documento'] ?? '';

        // Verificar si el DNI ya existe en la base de datos
        // Retorna null para omitir la fila si:
        // 1. El DNI está vacío
        // 2. Ya existe un conductor con ese DNI
        if (empty($dni) || Driver::where('dni', $dni)->exists()) {
            return null; // Omitir esta fila
        }

        // Crear o encontrar el cargo asociado al conductor
        // Usa firstOrCreate para evitar duplicados de cargo
        $cargo = Cargo::firstOrCreate(
            [
                'name' => $row['cargo'] ?? $row['CARGO'] ?? 'Default Cargo',
            ]
        );

        // Crear y retornar nuevo modelo Driver con los datos de la fila
        // Nota: Hay un posible error en el mapeo de apellidos (ver comentario abajo)
        return new Driver([
            'last_maternal_name' => $row['apellido paterno'] ?? $row['apellido_materno'] ?? '',
            'last_paternal_name' => $row['apellido materno'] ?? $row['apellido_paterno'] ?? '',
            'name' => $row['name'] ?? $row['nombres'] ?? '',
            'dni' => $dni,
            'cargo_id' => $cargo->id,
        ]);
    }
}
