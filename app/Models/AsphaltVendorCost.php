<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AsphaltVendorCost extends Model
{
    public $timestamps = false;
    protected $table = 'asphalt_vendors_costs';
    protected $guarded=['id'];
}
