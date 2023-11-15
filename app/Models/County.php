<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class County extends Model
{
    public $timestamps = false;

    protected $table = 'counties';

    /** Methods */

    static public function countiesCB($default = [])
    {
        $items = self::orderBy('county')->pluck('county', 'county')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }



}
