<?php

namespace App\Models;

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
        'file',
        'front_left_brake_pad',
        'front_right_brake_pad',
        // Pastillas de freno traseras
        'rear_left_brake_pad',
        'rear_right_brake_pad',
        // Fecha de último registro
        'brake_pads_checked_at',

    ];
    protected $casts = [
        'status' => 'boolean',
        'brake_pads_checked_at' => 'date',
    ];
    public function getAverageBrakePadAttribute()
    {
        $values = [
            $this->front_left_brake_pad ?? 0,
            $this->front_right_brake_pad ?? 0,
            $this->rear_left_brake_pad ?? 0,
            $this->rear_right_brake_pad ?? 0
        ];

        $total = array_sum($values);
        return round($total / 4, 2);
    }
    public function getBrakePadStatusAttribute()
    {
        $average = $this->average_brake_pad;
        if ($average >= 70) {
            return 'Bueno';
        } elseif ($average >= 30) {
            return 'Regular';
        } else {
            return 'Malo';
        }
    }
    public function getBrakePadStatusColorAttribute()
    {
        $average = $this->average_brake_pad;
        if ($average >= 70) {
            return 'success';
        } elseif ($average >= 30) {
            return 'warning';
        } else {
            return 'danger';
        }
    }
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
