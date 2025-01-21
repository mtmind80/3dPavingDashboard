<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ContactType extends Model
{
    public $timestamps = false;

    protected $appends = [
        'entity_type',
    ];
    
    /** relationships */


    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /** scopes */

    /** Accessor(get) and Mutators(set) */

    public function getEntityTypeAttribute()
    {
        if (in_array($this->id, [1, 7, 10, 14])) {
            return 'company';
        }

        if (in_array($this->id, [16, 18, 22])) {
            return 'person';
        }

        return null;
    }

    /** Methods */

    static public function typesCBActive($default = [])
    {
        $types = self::whereIn('id', [1, 7, 10, 14, 16, 18, 22])->orderBy('type')->pluck('type', 'id')->toArray();

        if (!empty($default)) {
            return $default + $types;
        }

        return $types;
    }

    static public function typesActive()
    {
        return self::whereIn('id', [1, 7, 10, 14, 16, 18, 22])->orderBy('type')->get();
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
