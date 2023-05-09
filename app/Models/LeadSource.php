<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class LeadSource extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    /** relationships */

    /** scopes */

    /** Accessor(get) and Mutators(set) */

    /** Methods */

    static public function sourcesCB($default = [])
    {
        $items = self::orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }

    static public function sources($default = [])
    {
        $items = self::orderBy('name')->pluck('name', 'name')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }

    static public function sourcesInUse()
    {
        return self::whereHas('contacts', function($q){
            $q->active();
        })->withCount(['contacts' => function($q){
            $q->active();
        }])->orderBy('name')->get();
    }


}
