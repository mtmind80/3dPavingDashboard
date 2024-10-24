<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripingService extends Model
{
    //
    public $timestamps = false;
    protected $table = 'striping_services';


    protected $fillable = [
        'name',
        'dsort',
    ];


    public function striping_costs()
    {
        return $this->hasMany(StripingCost::class);
    }
    
}
