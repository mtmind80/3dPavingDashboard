<?php

namespace App\Models;


use App\Helpers\Currency;
use Illuminate\Database\Eloquent\Model;

class ProposalDetailLabor extends Model
{
    protected $table = 'proposal_detail_labor';

    protected $fillable = [
        'proposal_detail_id',
        'labor_name',
        'number',
        'days',
        'hours',
        'rate_per_hour',
        'created_by',
    ];

    /** relationships */

    /** scopes */

    /** Accessor(get) and Mutators(set) */

    public function getLaborIdAttribute()
    {
        return ($row = LaborRate::where('name', $this->labor_name)->where('rate', $this->rate_per_hour)->first()) ? $row->id : null;
    }

    public function getHtmlRatePerHourAttribute()
    {
        return Currency::format($this->rate_per_hour);
    }

    public function getCostAttribute()
    {
        return (integer)$this->number * (float)$this->days * (float)$this->hours * (float)$this->rate_per_hour;
    }

    public function getHtmlCostAttribute()
    {
        return Currency::format($this->cost);
    }

    public function getHtmlNameAndRateAttribute()
    {
        return $this->labor_name . ' - ' . Currency::format($this->rate_per_hour);
    }

    /** Methods */

}
