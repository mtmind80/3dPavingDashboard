<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $timestamps = false;
    protected $table = 'services';
    protected $guarded = ['id'];


    // Relationships:

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function scopeServices($query) //services
    {
        return $query->orderBy('service_category_id');
    }

    static public function servicesCB($default = [])
    {
        $items = self::services()->get()->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }

}
