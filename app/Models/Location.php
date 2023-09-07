<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{

    protected $appends = [
        'full_location_one_line',
        'full_location_two_lines',
        'short_location_two_lines',
    ];

    // Relationships:
    public function locationType()
    {
        return $this->belongsTo(LocationType::class);
    }

    // Scopes:

    // Mutators and Accessors

    public function getFullLocationOneLineAttribute()
    {
        return self::buildFullAddress($this->address_line1, $this->city, $this->county, $this->state, $this->postal_code, ', ', $this->parcel_number);
    }

    public function getFullLocationTwoLinesAttribute()
    {
        return self::buildFullAddress($this->address_line1, $this->city, $this->county, $this->state, $this->postal_code, '</br>', $this->parcel_number );
    }

    public function getShortLocationTwoLinesAttribute()
    {
        return self::buildFullAddress($this->address_line1, $this->city, null, null, $this->postal_code, '<br>');
    }

    // Methods:

    static public function getIdsPerCountyWithZipCode($county)
    {
        return self::where('county', $county)->where('postal_code', '>=', 1)->pluck('id')->toArray();
    }

    static public function getIdsPerCountyWithCity($county)
    {
        return self::where('county', $county)->whereNotNull('city')->pluck('id')->toArray();
    }


    static public function countiesCB($default = [])
    {
        $types = self::distinct('county')->orderBy('county')->pluck('county', 'county')->toArray();

        if (!empty($default)) {
            return $default + $types;
        }

        return $types;
    }

    public static function buildFullAddress($address, $city = '', $county = '', $state = '', $zip = '', $separator = '<br>', $parcelNumber = '')
    {
        $result = [];
        $line1 = !empty($address) ? [$address] : [];
        if (!empty($parcelNumber)) {
            $line1[] = $parcelNumber;
        }
        if (count($line1)) {
            $result[] = count($line1) > 1 ? implode(', ', $line1) : $line1[0];
        }

        $line2 = [];
        if (!empty($city)) {
            $line2[] = $city;
        }
        if (!empty($county)) {
            $line2[] = $county;
        }
        $stateZip = [];
        if (!empty($state)) {
            $stateZip[] = $state;
        }
        if (!empty($zip)) {
            $stateZip[] = $zip;
        }
        if (!empty($stateZip)) {
            $line2[] = implode(' ', $stateZip);
        }
        if (!empty($line2)) {
            $result[] = implode(', ', $line2);
        }

        return trim(preg_replace('/\s+/', ' ', implode($separator, $result)));
    }


    static public function locationTypesCB($default = [])
    {
        $items = LocationType::orderBy('type')->get()->pluck('type', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }
}
