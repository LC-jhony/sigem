<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\MaintenanceItem;
use Illuminate\Database\Seeder;
use Database\Seeders\VehicleSeeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(2)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
        // $this->call([
        //     VehicleSeeder::class,
        //     // Add other seeders here as needed
        // ]);
        $items = [
            ['name' => 'FILTRO DE ACEITE DE MOTOR'],
            ['name' => 'FILTRO DE COMBUSTIBLE'],
            ['name' => 'FILTRO DE AIRE'],
            ['name' => 'FILTRO P/POLVO A/C'],
            ['name' => 'FILTRO TAMIZ'],
            ['name' => 'ANILLO TAPON DE CARTER'],
            ['name' => 'ACEITE SINTETICO - MOTOR'],
            ['name' => 'ACEITE DE CAJA DE CAMBIOS'],
            ['name' => 'ACEITE DIFERENCIAL'],
            ['name' => 'ACEITE DE DIRECCION ATF'],
            ['name' => 'LIQUIDO REFRIG. PARA MOTOR'],
            ['name' => 'LIQUIDO PARA FRENOS/EMBRIAGUE'],
            ['name' => 'CONCENTRADO LAVACRISTALES'],
        ];

        foreach ($items as $item) {
            MaintenanceItem::create($item);
        }
    }
}
