<?php

namespace App\Models;

use App\Helpers\Currency;
use Illuminate\Database\Eloquent\Model;

class ProposalDetailEquipment extends Model
{
    protected $table = 'proposal_detail_equipment';

    protected $fillable = [
        'proposal_detail_id',
        'equipment_id',
        'created_by',
        'hours',
        'days',
        'number_of_units',
        'rate_type ',
        'rate',
    ];

    /** relationships */

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    /** scopes */

    /** Accessor(get) and Mutators(set) */

    public function getHtmlRateAttribute()
    {
        return Currency::format($this->rate);
    }

    public function getCostAttribute()
    {
        if ($this->rate_type === 'per day') {
            $cost = (integer)$this->number_of_units * (float)$this->days * (float)$this->rate;
        } else {
            $cost = (integer)$this->number_of_units * (float)$this->hours * (float)$this->rate;
            if (!empty($this->days)) {
                $cost *= (float)$this->days;
            }
        }

        return $cost;
    }

    public function getHtmlCostAttribute()
    {
        return Currency::format($this->cost);
    }

    /** Methods */

}
