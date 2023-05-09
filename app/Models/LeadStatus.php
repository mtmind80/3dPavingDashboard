<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model
{
    protected $table = 'lead_status';

    public $timestamps = false;

    protected $fillable = [
        'status',
        'color',
    ];

    /** relationships */

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }


    /** scopes */

    /** Accessor(get) and Mutators(set) */

    /** Methods */

    static public function statusCB($default = [])
    {
        $items = self::orderBy('status')->pluck('status', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }

}

