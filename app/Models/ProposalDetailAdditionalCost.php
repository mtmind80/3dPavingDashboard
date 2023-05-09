<?php

namespace App\Models;

use App\Helpers\Currency;
use Illuminate\Database\Eloquent\Model;

class ProposalDetailAdditionalCost extends Model
{
    public $fillable = [
        'proposal_detail_id',
        'created_by',
        'amount',
        'type',
        'description',
    ];

    /** relationships */

    public function proposalDetails()
    {
        return $this->belongsTo(ProposalDetail::class, 'proposal_detail_id');
    }

    /** scopes */

    /** Accessor(get) and Mutators(set) */

    public function getCostAttribute()
    {
        return (float)$this->amount;
    }

    public function getHtmlCostAttribute()
    {
        return Currency::format($this->cost);
    }

    /** Methods */

}
