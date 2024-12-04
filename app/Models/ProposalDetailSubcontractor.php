<?php

namespace App\Models;

use App\Helpers\Currency;
use Illuminate\Database\Eloquent\Model;

class ProposalDetailSubcontractor extends Model
{
    protected $fillable = [
        'proposal_detail_id',
        'contractor_id',
        'cost',
        'overhead',
        'accepted',
        'attached_bid',
        'description',
        'created_by',
    ];

    protected $appends = ['total_cost'];


    /** relationships */

    public function proposalDetails()
    {
        return $this->belongsTo(ProposalDetail::class, 'proposal_detail_id');
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class, 'contractor_id');
    }

    /** scopes */

    /** Accessor(get) and Mutators(set) */

    public function getOverheadPercentAttribute()
    {
        return round($this->overhead, 1).'%';
    }

    public function getNameAndOverheadPercentAttribute()
    {
        return $this->name.' - '.$this->overhead_percent;
    }

    public function getHtmlCostAttribute()
    {
        return Currency::format($this->cost);
    }

    public function getTotalCostAttribute()
    {
        return $this->cost * (1 + $this->overhead / 100);
    }

    public function getHtmlTotalCostAttribute()
    {
        return Currency::format($this->total_cost);
    }

    public function getLinkAttachedBidAttribute()
    {
        return !empty($this->attached_bid) ? '<a href="'.asset('media/bids').'/'.$this->attached_bid.'" target="_blank">'.$this->attached_bid.'</a>' : null;
    }

    /** Methods */

    public static function clearAccepted($proposalDetailId)
    {
        return self::where('proposal_detail_id', $proposalDetailId)->where('accepted', 1)->update(['accepted' => false]);
    }


}
