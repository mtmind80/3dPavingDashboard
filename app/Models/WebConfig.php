<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebConfig extends Model
{
    //
    protected $table = 'web_configs';
    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
    ];
}
