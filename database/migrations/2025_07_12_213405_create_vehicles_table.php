<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('placa')->unique();
            $table->string('marca');
            $table->string('unidad');
            $table->string('property_card');
            $table->enum('status', [
                'Operativo',
                'En Mantenimiento',
                'Fuera de Servicio',
                'En Reparación',
                'Disponible',
                'En Uso'
            ]);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
