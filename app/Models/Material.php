<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'cost',
        'service_category_id',
    ];

    protected $table ='materials';

    static public function materialsCB($default = [])
    {
        $items = self::orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }


    public function scopeByServiceCategory($query, $service_category_id)
    {
        return $query->where('service_category_id', '=',$service_category_id);
    }

}
