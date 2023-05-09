<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ContactType extends Model
{
    public $timestamps = false;

    protected $table = 'contact_types';
    
    /** relationships */


    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /** scopes */

    /** Accessor(get) and Mutators(set) */

    /** Methods */

    static public function typesCBActive($default = [])
    {
        $types = self::whereIn('id', array(1, 7,10,14,16,17,22))->orderBy('type')->pluck('type', 'id')->toArray();

        if (!empty($default)) {
            return $default + $types;
        }

        return $types;
    }
    
    
    static public function typesCB($default = [])
    {
        $types = self::orderBy('type')->pluck('type', 'id')->toArray();

        if (!empty($default)) {
            return $default + $types;
        }

        return $types;
    }

    static public function typesInUse()
    {
        return self::whereHas('contacts', function($q){
            $q->active();
        })->withCount(['contacts' => function($q){
            $q->active();
        }])->orderBy('type')->get();
    }
}
