<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripingCost extends Model
{
    //
    public $timestamps = false;
    protected $table = 'striping_costs';

    protected $fillable = [
        'striping_service_id',
        'description',
        'cost',
    ];

    public function service()
    {
        return $this->belongsTo(StripingService::class,'striping_service_id');
    }

    static public function strippingCB($default = [])
    {
        $items = self::orderBy('description')->pluck('description', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }

}
