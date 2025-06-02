<?php

namespace App\Models;

use App\Traits\SearchTrait;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;

class WorkorderFieldReport extends Model
{
    use SortableTrait, SearchTrait;

    protected $casts = [
        'report_date' => 'date',
    ];

    public $fillable = [
        'proposal_id',
        'proposal_detail_id',
        'created_by',
        'report_date',
    ];

    public $sortable = [
        'report_date',
        'workorder_field_reports.proposal_id|proposals.name',
        'workorder_field_reports.created_by|users.fname',
    ];

    public $searchable = [
        'report_date' => 'LIKE',
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
        return $this->belongsTo(Proposal::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function additionalCosts()
    {
        return $this->hasMany(WorkorderAdditionalCost::class, 'workorder_field_report_id');
    }

    public function equipments()
    {
        return $this->hasMany(WorkorderEquipment::class, 'workorder_field_report_id');
    }

    public function materials()
    {
        return $this->hasMany(WorkorderMaterial::class, 'workorder_field_report_id');
    }

    public function subcontractors()
    {
        return $this->hasMany(WorkorderSubcontractor::class, 'workorder_field_report_id');
    }

    public function timesheets()
    {
        return $this->hasMany(WorkorderTimesheets::class, 'workorder_field_report_id');
    }

    public function vehicles()
    {
        return $this->hasMany(WorkorderVehicle::class, 'workorder_field_report_id');
    }

    /** Scopes */

    public function scopeForProposalDetail($query, $proposalDetailId)
    {
        return $query->where('proposal_detail_id', $proposalDetailId);
    }

    /** Accessor(get) and Mutators(set) */

}
