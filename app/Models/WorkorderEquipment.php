<?php

namespace App\Models;

use App\Helpers\Currency;
use App\Traits\SearchTrait;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;

class WorkorderEquipment extends Model
{
    use SortableTrait, SearchTrait;

    protected $table = 'workorder_equipment';

    public $fillable = [
        'proposal_id',
        'proposal_detail_id',
        'workorder_field_report_id',
        'equipment_id',
        'name',
        'created_by',
        'hours',
        'number_of_units',
        'rate_type',
        'rate',
    ];

    public $sortable = [
        'hours',
        'rate_type',
        'rate',
        'created_at',
        'workorder_equipment.proposal_id|proposals.name',
        'workorder_equipment.created_by|users.fname',
        'workorder_equipment.workorder_field_report_id|workorder_field_reports.report_date',
    ];

    public $searchable = [
        'rate_type' => 'LIKE',
        'rate' => 'LIKE',
        'created_at' => 'LIKE',
        'childModels' => [
            'proposal' => [
                'fields' => [
                    'name' => 'LIKE',
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

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    /** Accessor(get) and Mutators(set) */

    public function getHtmlRateAttribute()
    {
        return Currency::format($this->rate);
    }

}
