<?php

namespace App\Models;

use App\Helpers\Currency;
use Illuminate\Database\Eloquent\Model;

class ProposalDetailVehicle extends Model
{
    protected $table = 'proposal_detail_vehicles';

    protected $guarded =['id'];

    protected $fillable = [
        'proposal_detail_id',
        'vehicle_id',
        'vehicle_name',
        'number_of_vehicles',
        'days',
        'hours',
        'rate_per_hour',
        'created_by',
    ];

    /** relationships */

    /** scopes */

    /** Accessor(get) and Mutators(set) */

    public function getHtmlRatePerHourAttribute()
    {
        return Currency::format($this->rate_per_hour);
    }

    public function getCostAttribute()
    {
        return (integer)$this->number_of_vehicles * (float)$this->days * (float)$this->hours * (float)$this->rate_per_hour;
    }

    public function getHtmlCostAttribute()
    {
        return Currency::format($this->cost);
    }

    /** Methods */

}
