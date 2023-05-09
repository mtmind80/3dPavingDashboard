<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Traits\SortableTrait;
use App\Traits\SearchTrait;


class Vendor extends Model
{

    use SortableTrait, SearchTrait;
    
    protected $table = 'vendors';

    
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
        'vendor_type_id',
    ];

    public $sortable = [
        'name',
        'city',
        'vendor_type_id',
    ];

    public $searchable = [
        'contractors.name' => 'LIKE',
        'contractors.city' => 'LIKE',
        'contractors.vendor_type_id' => '=',
    ];

    public function sortableColumns()
    {
        return $this->sortable;
    }

    public function searchableColumns()
    {
        return $this->searchable;
    }




    /** Accessor(get) and Mutators(set) */

    
    public function getFullAddressTwoLineAttribute()
    {
        return self::buildFullAddress(address_line1, $this->city, $this->state, $this->postal_code, '<br>', address_line2);
    }

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

}
