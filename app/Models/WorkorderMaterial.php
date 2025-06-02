<?php

namespace App\Models;

use App\Traits\SearchTrait;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;

class WorkorderMaterial extends Model
{
    use SortableTrait, SearchTrait;

    public $fillable = [
        'proposal_id',
        'proposal_detail_id',
        'workorder_field_report_id',
        'material_id',
        'name',
        'created_by',
        'cost',
        'note',
        'quantity',
    ];

    public $sortable = [
        'workorder_materials.name',
        'cost',
        'quantity',
        'workorder_materials.proposal_id|proposals.name',
        'workorder_materials.created_by|users.fname',
        'workorder_materials.workorder_field_report_id|workorder_field_reports.report_date',
    ];

    public $searchable = [
        'workorder_materials.name' => 'LIKE',
        'workorder_materials.note' => 'LIKE',
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

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    /** Accessor(get) and Mutators(set) */

}
