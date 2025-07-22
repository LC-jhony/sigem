<?php

namespace App\Models;

use App\Models\Vehicle;
use App\Models\MaintenanceItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    protected $fillable = [
        'vehicle_id',
        'maintenance_item_id',
        'mileage',
        'status',
        'Price_material',
        'workforce',
        'maintenance_cost',
        'photo',
        'file'
    ];
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(
            related: Vehicle::class,
            foreignKey: 'vehicle_id',
        );
    }

    public function maintenanceItem(): BelongsTo
    {
        return $this->belongsTo(
            related: MaintenanceItem::class,
            foreignKey: 'maintenance_item_id',
        );
    }
}
