<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeOrders extends Model
{

    public $timestamps = false;
    protected $table = 'change_orders';
    protected $guarded=['id'];
    
}
