<?php

namespace App\Models;

use App\Models\Vehicle;
use App\Enum\DocumentName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'vehicle_id',
        'date',
        'name',
        'file'
    ];
    // protected $casts = [
    //     'name' => DocumentName::class,
    // ];
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(
            related: Vehicle::class,
            foreignKey: 'vehicle_id',
        );
    }
}
