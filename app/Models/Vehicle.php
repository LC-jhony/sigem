<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'placa',
        'marca',
        'unidad',
        'property_card',
        'status',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(
            related: Document::class,
            foreignKey: 'vehicle_id',
        );
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(
            related: Maintenance::class,
            foreignKey: 'vehicle_id',
        );
    }
}
