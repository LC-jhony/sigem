<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverLicense extends Model
{
    /** @use HasFactory<\Database\Factories\DriverLicenseFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'driver_id',
        'license_number',
        'expiration_date',
        'license_type',
        'file',
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(
            related: Driver::class,
            foreignKey: 'driver_id',
        );
    }
}
