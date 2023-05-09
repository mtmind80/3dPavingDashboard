<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MediaType extends Model
{
    public $timestamps = false;
    protected $table = 'media_types';
    
    protected $fillable = [
        'section',
        'type'
    ];
}
