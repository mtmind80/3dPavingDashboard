<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RockVendorsCosts extends Model
{

    public $timestamps = false;
    
    protected $table = 'rock_vendors_costs';
    protected $guarded=['id'];

    
}
