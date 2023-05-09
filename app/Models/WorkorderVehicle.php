<?php

namespace App\Models;

use App\Traits\SearchTrait;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;

class WorkorderVehicle extends Model
{
    use SortableTrait, SearchTrait;

    protected $table = 'workorder_vehicles';

    protected $guarded = ['id'];

    protected $dates = ['report_date'];

    public $fillable = [
        'proposal_id',
        'proposal_detail_id',
        'vehicle_id',
        'vehicle_name',
        'report_date',
        'created_by',
        'note',
        'number_of_vehicles',
    ];

    public $sortable = [
        'report_date',
        'vehicle_name',
        'number_of_vehicles',
        'workorder_vehicles.proposal_id|proposals.name',
        'workorder_vehicles.created_by|users.fname',
    ];

    public $searchable = [
        'report_date'              => 'LIKE',
        'vehicle_name' => 'LIKE',
        'childModels'              => [
            'proposal' => [
                'fields' => [
                    'proposals.name' => 'LIKE',
                ],
            ],
            'creator'  => [
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

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    /** Accessor(get) and Mutators(set) */

}
