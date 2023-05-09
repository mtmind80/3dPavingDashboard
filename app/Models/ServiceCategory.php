<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    public $timestamps = false;

    protected $table = 'service_categories';

    // Relationships:

    public function services()
    {
        return $this->hasMany(Service::class, 'service_category_id')->orderBy('services.name');
    }


    public function getMaterialsNameAttribute()
    {
        return $this->name;
    }
    
}
