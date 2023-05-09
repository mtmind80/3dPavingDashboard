<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SealcoatTypes extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'cost',
    ];
    protected $table = 'sealcoat_types';
    static public function sealcoatCB($default = [])
    {
        $items = self::orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }

}
