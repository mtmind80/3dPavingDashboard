<?php

namespace App\Models;

use App\Helpers\Currency;
use App\Traits\SearchTrait;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;

class WorkorderAdditionalCost extends Model
{
    use SortableTrait, SearchTrait;

    public $fillable = [
        'proposal_id',
        'proposal_detail_id',
        'workorder_field_report_id',
        'created_by',
        'cost',
        'description',
    ];

    public $sortable = [
        'cost',
        'workorder_additional_costs.proposal_id|proposals.name',
        'workorder_additional_costs.created_by|users.fname',
        'workorder_additional_costs.workorder_field_report_id|workorder_field_reports.report_date',
    ];

    public $searchable = [
        'cost' => 'LIKE',
        'description' => 'LIKE',
        'childModels' => [
            'proposal' => [
                'fields' => [
                    'proposals.name' => 'LIKE',
                ],
            ],
            'creator' => [
                'fields' => [
                    'fname' => 'LIKE',
                    'lname' => 'LIKE',
                ],
            ],
            'fieldReport' => [
                'fields' => [
                    'report_date' => 'LIKE',
                ],
            ],
        ],
    ];

    public function sortableColumns()
    {
        return $this->sortable;
    }

    public function searchableColumns()
    {
        return $this->searchable;
    }

    /** Relationships */

    public function proposalDetails()
    {
        return $this->belongsTo(ProposalDetail::class, 'proposal_detail_id');
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }

    public function fieldReport()
    {
        return $this->belongsTo(WorkorderFieldReport::class, 'workorder_field_report_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Accessor(get) and Mutators(set) */

    public function getHtmlCostAttribute()
    {
        return Currency::format($this->cost);
    }

}
