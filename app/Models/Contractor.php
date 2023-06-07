<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Traits\SortableTrait;
use App\Traits\SearchTrait;


class Contractor extends Model
{

    use SortableTrait, SearchTrait;

    protected $table = 'contractors';


    protected $fillable = [
        'name',
        'contact',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'email',
        'note',
        'contractor_type_id',
    ];

    public $sortable = [
        'name',
        'city',
        'contractor_type_id',
    ];

    public $searchable = [
        'contractors.name' => 'LIKE',
        'contractors.city' => 'LIKE',
        'contractors.contractor_type_id' => '=',
    ];

    public function sortableColumns()
    {
        return $this->sortable;
    }

    public function searchableColumns()
    {
        return $this->searchable;
    }

    /** relationships */

    /** scopes */

    /** Accessor(get) and Mutators(set) */

    public function getFullAddressTwoLineAttribute()
    {
        return self::buildFullAddress($this->address_line1, $this->city, $this->state, $this->postal_code, '<br>', $this->address_line2);
    }

    public function getOverheadPercentAttribute()
    {
        return round($this->overhead, 1).'%';
    }

    public function getNameAndOverheadPercentAttribute()
    {
        return $this->name.' - '.$this->overhead_percent;
    }

    /** Methods */

    public static function buildFullAddress($address, $city = '', $state = '', $zip = '', $separator = '<br>', $address2 = '')
    {
        $result = [];
        $line1 = !empty($address) ? [$address] : [];
        if (!empty($address2)) {
            $line1[] = $address2;
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

    static public function contractorsCB($default = [])
    {
        $items = self::orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }

    static public function contractorsWithOverheadCB($default = [])
    {
        $items = self::orderBy('name')->get()->pluck('name_and_overhead_percent', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }

}
