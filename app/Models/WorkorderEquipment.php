<?php

namespace App\Models;

use App\Traits\SearchTrait;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;

class WorkorderEquipment extends Model
{
    use SortableTrait, SearchTrait;

    protected $table = 'workorder_equipment';

    protected $guarded = ['id'];

    protected $dates = ['report_date'];

    public $fillable = [
        'proposal_id',
        'proposal_detail_id',
        'equipment_id',
        'name',
        'report_date',
        'created_by',
        'hours',
        'number_of_units',
        'rate_per_hour',
    ];

    public $sortable = [
        'report_date',
        'hours',
        'rate_per_hour',
        'created_at',
        'workorder_equipment.proposal_id|proposals.name',
        'workorder_equipment.created_by|users.fname',
    ];

    public $searchable = [
        'report_date'  => 'LIKE',
        'created_at'   => 'LIKE',
        'childModels' => [
            'proposal'     => [
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    /** Accessor(get) and Mutators(set) */

}
