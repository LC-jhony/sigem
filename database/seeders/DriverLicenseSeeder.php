<?php

namespace Database\Seeders;

use App\Models\DriverLicense;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DriverLicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DriverLicense::factory(151)->create();
    }
}
