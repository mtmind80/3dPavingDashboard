<?php

namespace App\Models;

use App\Helpers\Currency;
use Illuminate\Database\Eloquent\Model;

class StripingService extends Model
{
    public $timestamps = false;

    protected $table = 'striping_services';

    protected $fillable = [
        'name',
        'dsort',
    ];

    public function striping_costs()
    {
        return $this->hasMany(StripingCost::class);
    }

    public function services()
    {
        return $this->hasMany(ProposalDetailStripingService::class, 'striping_service_id');
    }

    public function getTotalCostAttribute()
    {
        return $this->services->sum(fn($q) => $q->cost * $q->quantity);
    }

    public function getHtmlTotalCostAttribute()
    {
        return Currency::format($this->total_cost);
    }
    
}
