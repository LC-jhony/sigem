<?php

namespace Database\Factories;

use App\Models\Vehicle;
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
        $statusOptions = [
            'Operativo',
            'En Mantenimiento',
            'Fuera de Servicio',
            'En Reparación',
            'Disponible',
            'En Uso',
        ];

        return [
            'code' => strtoupper($this->faker->bothify('???##')), // Ej: ABC12
            'placa' => $this->generateUniquePlaca(),
            'marca' => $this->faker->randomElement(['Toyota', 'Nissan', 'Hyundai', 'Ford', 'Chevrolet', 'Kia']),
            'unidad' => $this->faker->word.' '.$this->faker->randomElement(['Model X', '2023', 'Turbo', 'Premium']),
            'property_card' => $this->faker->unique()->numerify('PC-#####'),
            'status' => $this->faker->randomElement($statusOptions),
        ];
    }

    private function generateUniquePlaca()
    {
        do {
            $placa = strtoupper($this->faker->regexify('[A-Z]{3}-[0-9]{3}')); // Formato: ABC-123
        } while (Vehicle::where('placa', $placa)->exists());

        return $placa;
    }
}
