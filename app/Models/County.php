<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class County extends Model
{
    public $timestamps = false;

    protected $table = 'florida_counties';

    /** Methods */

    static public function countiesCB($default = null)
    {
        $counties = self::distinct('county')
            ->orderBy('county')
            ->where('state','=','FL')
            ->pluck('county', 'county')
            ->toArray();

        return $default !== null ? $default + $counties : $counties;
    }

    static public function citiesCB($countyId, $default = null)
    {
        $cities = self::where('county', $countyId)
            ->distinct()
            ->orderBy('city')
            ->where('state','=','FL')
            ->pluck('city', 'city')
            ->toArray();

        return $default !== null ? $default + $cities : $cities;
    }

}
