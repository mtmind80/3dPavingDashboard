<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    public $timestamps = false;
    protected $table = 'terms';
    protected $guarded = ['id'];

}
