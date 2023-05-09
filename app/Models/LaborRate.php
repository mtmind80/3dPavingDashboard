<?php

namespace App\Models;

use App\Helpers\Currency;
use Illuminate\Database\Eloquent\Model;

class LaborRate extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'rate',
    ];

    /** relationships */

    /** scopes */

    /** Accessor(get) and Mutators(set) */

    public function getHtmlNameAndRateAttribute()
    {
        return $this->name . ' - ' . Currency::format($this->rate);
    }

    static public function uniqueLaborCB($default = [])
    {
        $items = self::distinct('name')->orderBy('name')->pluck('name', 'name')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }

    static public function LaborWithRatesCB($default = [])
    {
        $items = self::orderBy('name')->orderBy('rate')->get()->pluck('html_name_and_rate', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }

    /** Methods */

}
