<?php

namespace App\Models;


use App\Helpers\Currency;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    public $timestamps = false;

    protected $table = 'equipment';

    protected $fillable = [
        'name',
        'rate_type',
        'rate',
        'min_cost',
        'do_not_use'
    ];

    /** relationships */

    /** scopes */

    public function scopeAvailable($query)
    {
        return $query->where('do_not_use', 0);
    }

    /** Accessor(get) and Mutators(set) */

    public function getHtmlNameAndRateAttribute()
    {
        return $this->name.' - '.Currency::format($this->rate);
    }

    public function getHtmlNameAndRateTypeAttribute()
    {
        return $this->name.' - ('.$this->rate_type.')';
    }

    public function getHtmlMinCostAttribute()
    {
        return !empty($this->min_cost) ? Currency::format($this->min_cost) : null;
    }

    /** Methods */

    static public function equipmentCB($default = [])
    {
        $items = self::available()->orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }

    static public function allEquipmentCB($default = [])
    {
        $items = self::orderBy('name')->pluck('name', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }

}
