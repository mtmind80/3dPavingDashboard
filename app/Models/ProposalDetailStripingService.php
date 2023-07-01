<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalDetailStripingService extends Model
{
    //
    public $timestamps = false;
    protected $table = 'proposal_detail_striping_services';
    protected $guarded = ['id'];


    public function service()
    {
        return $this->belongsTo(StripingService::class,'striping_service_id');
    }


    public function getServiceSortAttribute($value)
    {
        return $this->service()->dsort;
    }
    public function getCostTotalAttribute($value)
    {
        return (float) ($this->cost * $this->quantity);
    }
}
