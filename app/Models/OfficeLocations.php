<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfficeLocations extends Model
{
    //
    public $timestamps = false;
    protected $table = 'office_locations';

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'zip',
        'phone',
        'manager',
    ];

}
