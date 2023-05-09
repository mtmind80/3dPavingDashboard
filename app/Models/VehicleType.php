<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    public $timestamps = false;
    protected $table = 'vehicle_types';
    protected $fillable = [
        'name',
    ];

}
