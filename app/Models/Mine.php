<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mine extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'location',
        'status'
    ];
    protected $casts = [
        'status' => 'boolean',
    ];
    public function assignments(): HasMany
    {
        return $this->hasMany(
            DriverMineAssigment::class,
        );
    }
    public function activeAssignments(): HasMany
    {
        return $this->hasMany(
            DriverMineAssigment::class,
        )->where('status', 'Activo');
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(
            DriverMineAssigment::class,
        )->where('status', 'Activo');
    }
    public function users(): HasMany
    {
        return $this->hasMany(
            User::class,
        );
    }
}
