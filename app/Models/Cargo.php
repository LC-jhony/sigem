<?php

namespace App\Models;

use App\Models\Driver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cargo extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'status',
    ];
    protected $casts = [
        'status' => 'boolean',
    ];
    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }
}
