<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicles';
    public $timestamps = false;

    protected $fillable = [
        'vehicle_types_id',
        'name',
        'description',
        'active',
        'office_location_id'
    ];

    // Relationships:

    public function type()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_types_id');
    }

    static public function vehiclesCB($default = [])
    {
        $items = self::orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }

}
