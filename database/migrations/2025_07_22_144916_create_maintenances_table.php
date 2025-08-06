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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id') // ID del vehículo
                ->constrained('vehicles')
                ->onDelete('cascade');
            $table->foreignId('maintenance_item_id') // ID del ítem de mantenimiento
                ->constrained('maintenance_items')
                ->onDelete('cascade');

            $table->integer('mileage') // Kilometraje (7500 - 165000)
                ->default(7500);
            $table->boolean('status') // Estado (realizado/no realizado)
                ->default(false);
            $table->decimal('Price_material', 10, 2);
            $table->decimal('workforce', 10, 2);
            $table->decimal('maintenance_cost', 10, 2);
            $table->string('photo')->nullable();
            $table->string('file')->nullable();
            // Pastillas de freno delanteras
            $table->integer('front_left_brake_pad');
            $table->integer('front_right_brake_pad');
            // Pastillas de freno traseras
            $table->integer('rear_left_brake_pad');
            $table->integer('rear_right_brake_pad');
            // Fecha de último registro
            $table->timestamp('brake_pads_checked_at')->nullable()->comment('Fecha de último registro de pastillas');
            $table->timestamps();
            $table->softDeletes(); // Para manejar eliminaciones suaves
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
