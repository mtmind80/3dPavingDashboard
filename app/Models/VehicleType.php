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

    public function getNameRateAttribute()
    {
        return $this->nameRate();

    }

    public function nameRate() {
        return $this->name. ' - $' . $this->rate;

    }
    static public function vehicleTypesCB($default = [])
    {
        $items = self::orderBy('name')->get()->pluck('name_rate', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }



}
