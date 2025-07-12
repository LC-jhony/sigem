<?php

namespace Database\Factories;

use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DriverLicense>
 */
class DriverLicenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'driver_id' => Driver::inRandomOrder()->first()->id,
            'license_number' => $this->faker->unique()->numerify('########'),
            'expiration_date' => $this->faker->dateTimeBetween('+1 year', '+10 years'),
            'license_type' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'CDL']),
        ];
    }

    /**
     * Create a license that's about to expire
     */
    public function expiringSoon(): static
    {
        return $this->state(fn(array $attributes) => [
            'expiration_date' => $this->faker->dateTimeBetween('now', '+3 months'),
        ]);
    }

    /**
     * Create an expired license
     */
    public function expired(): static
    {
        return $this->state(fn(array $attributes) => [
            'expiration_date' => $this->faker->dateTimeBetween('-2 years', '-1 day'),
        ]);
    }

    /**
     * Create a commercial driver's license
     */
    public function commercial(): static
    {
        return $this->state(fn(array $attributes) => [
            'license_type' => 'CDL',
        ]);
    }
}
