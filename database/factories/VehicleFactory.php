<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'placa' => fake()->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'modelo' => fake()->randomElement([
                'Camión Minero',
                'Excavadora',
                'Cargador Frontal',
                'Bulldozer',
                'Camión Volquete',
                'Grúa Móvil',
                'Perforadora',
                'Motoniveladora',
                'Compactadora',
                'Camión Cisterna',
                'Retroexcavadora',
                'Tractor Oruga',
                'Camión de Servicio',
                'Ambulancia Minera',
                'Camioneta 4x4'
            ]),
            'marca' => fake()->randomElement([
                'Caterpillar',
                'Komatsu',
                'Volvo',
                'Liebherr',
                'Hitachi',
                'John Deere',
                'Case',
                'JCB',
                'Scania',
                'Mercedes-Benz',
                'Iveco',
                'Ford',
                'Toyota',
                'Nissan',
                'Chevrolet'
            ]),
            'year' => fake()->numberBetween(2010, 2024),
            'status' => fake()->randomElement([
                'Operativo',
                'En Mantenimiento',
                'Fuera de Servicio',
                'En Reparación',
                'Disponible',
                'En Uso'
            ]),
        ];
    }
}
