<?php

namespace App\Models;

use App\Helpers\Currency;
use Illuminate\Database\Eloquent\Model;

class WorkorderSubcontractor extends Model
{
    public $fillable = [
        'proposal_id',
        'proposal_detail_id',
        'workorder_field_report_id',
        'contractor_id',
        'created_by',
        'cost',
        'description',
    ];

    public $sortable = [
        'cost',
        'workorder_subcontractors.proposal_id|proposals.name',
        'workorder_subcontractors.contractor_id|contractors.name',
        'workorder_subcontractors.created_by|users.fname',
        'workorder_subcontractors.workorder_field_report_id|workorder_field_reports.report_date',
    ];

    public $searchable = [
        'workorder_subcontractors.name' => 'LIKE',
        'workorder_subcontractors.description' => 'LIKE',
        'childModels' => [
            'proposal' => [
                'fields' => [
                    'proposals.name' => 'LIKE',
                ],
            ],
            'subcontractor' => [
                'fields' => [
                    'contractors.name' => 'LIKE',
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

    public function subcontractor()
    {
        return $this->belongsTo(Contractor::class, 'contractor_id');
    }

    /** Accessor(get) and Mutators(set) */

    public function getHtmlCostAttribute()
    {
        return Currency::format($this->cost);
    }

}
