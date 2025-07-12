<?php

namespace App\Models;

use App\Models\Cargo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'last_paternal_name',
        'last_maternal_name',
        'dni',
        'cargo_id',
        'file',
        'status',
    ];
    protected $casts = [
        'status' => 'boolean',
        'cargo_id' => 'integer',
    ];
    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }
}
