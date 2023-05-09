<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    public $timestamps = false;
    protected $table = 'letters';

    protected $guarded = ['id'];

}
