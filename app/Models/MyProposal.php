<?php

namespace App\Models;

use App\Helpers\Currency;
use App\Scopes\MyOrderScope;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MyProposal extends Proposal
{
    protected $table = 'proposals';

    public function __construct()
    {
        parent::__construct();

        $this->appends[] = 'sales_year';
    }

    protected static function boot()
    {
        static::saved(function ($model){
            Cache::forget('existing_sales_years');
        });
        static::deleted(function ($model){
            Cache::forget('existing_sales_years');
        });

        parent::boot();

        static::addGlobalScope(new MyOrderScope);
    }

    // Relationships


    public function permits()
    {
        $this->hasMany(Permit::class, 'proposal_id', 'id');

    }
    public function services()
    {
        $this->hasMany(ProposalDetail::class, 'proposal_id', 'id');

    }
    public function payment()
    {
        $this->hasMany(Payment::class, 'proposal_id', 'id');

    }

    // Scopes:

    public function scopeActive($query)
    {
        return $query->where('proposal_statuses_id', 5);
    }

    public function scopeCompleted($query)
    {
        return $query->where('proposal_statuses_id', 6);
    }

    public function scopeCancelled($query)
    {
        return $query->where('proposal_statuses_id', 7);
    }

    public function scopeBilled($query)
    {
        return $query->where('proposal_statuses_id', 8);
    }

    public function scopePaid($query)
    {
        return $query->where('proposal_statuses_id', 9);
    }

    // Accessors and mutators

    public function getSalesYearAttribute()
    {
        return !empty($this->sale_date) ? $this->sale_date->format('Y') : null;
    }

    // Methods

    public function getTotalCosts()
    {
        return (float)$this->details()->sum('cost');
    }

    public static function existingSalesYearsCB()
    {
        $unique = Cache::remember('existing_sales_years', 60 * 24, function(){
            return ($rows = self::select('sale_date')
                ->whereNotNull('sale_date')
                ->orderBy('sale_date', "DESC")
                ->get())
            ? $unique = $rows->unique('sales_year')
            : null;
        });

        return !empty($unique) ? $unique->pluck('sales_year', 'sales_year')->toArray() : null;
    }

    protected function getSales($fromDate, $toDate, $salesPersonId)
    {
        $rows = self::sale()
            ->whereBetween('sale_date', [$fromDate, $toDate])
            ->when($salesPersonId, function($q) use ($salesPersonId) {
                return $q->where('salesperson_id', $salesPersonId);
            })
            ->with(['contact', 'salesPerson', 'creator', 'location', 'status'])
            ->orderBy('sale_date', 'DESC')
            ->get();

        $globalCost = 0;

        foreach ($rows as & $row) {
            $totalCost = $row->getTotalCosts();

            $row->total_cost = $totalCost;
            $row->html_total_cost = Currency::format($row->getTotalCosts());
            $row->sales_person_name = $row->salesPerson->full_name ?? '';
            $row->client_name = $row->contact->full_name ?? '';
            $row->creator_name = $row->creator->full_name ?? '';
            $row->county = $row->location->county ?? '';
            $row->status_name = $row->status->status ?? '';
            $row->html_sale_date = $row->sale_date->format('m/d/Y');

            $globalCost += $totalCost;
        }
        unset($row);

        return [
            'rows'        => $rows,
            'global_cost' => Currency::format($globalCost),
        ];
    }


    public function getHasPaymentsAttribute()
    {

        $paymentsOK = false;
        //has at least one payment record
        $payments = Payment::where('proposal_id','=', $this->id)->get();
        if(count($payments))
        {
            $paymentsOK = true;
        }
        return $paymentsOK;
    }

    public function getHasPermitsAttribute()
    {

        $permitOK = true; // by default permit ok is true
        if(!$this->permit_required)
        {
            return true;
        }
        if($this->permit_required) // if permits are required then assume they are not completed
        {
            $permitOK = false;
        }

        //get all permits for this record
        $permits = Permit::where('proposal_id', '=', $this->id)->get();
        if(count($permits)) { // if there are permits entered assume they are all good before we check them
            $permitOK = true;
        }
        foreach($permits as $permit) // check any permits to make sure they are all approved
        {

            if($permit->status != 'Completed') // if ANY permit is not approved then return false.
            {
                $permitOK = false;
            }
        }
        //where proposal permit_required = true, then check has
        // any permit record
        //where status <> 'Approved' (see enums for this field)
        //If all permits have a status 'Approved' then return true else return false
        // if proposal permit_required is true but there are No permit records the result is still false.
        return $permitOK;

    }
}
