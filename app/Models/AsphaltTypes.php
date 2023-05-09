<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AsphaltTypes extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'cost',
    ];
    protected $table = 'asphalt_types';
    static public function asphaltCB($default = [])
    {
        $items = self::orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }

}
