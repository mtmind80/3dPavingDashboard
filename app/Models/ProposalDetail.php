<?php

namespace App\Models;

use App\Traits\SearchTrait;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;

class ProposalDetail extends Model
{
    use SortableTrait, SearchTrait;

    protected $guarded = ['id'];

    public $sortable = [
        'proposal_details.proposal_id|proposals.name',
        'proposal_details.services_id|services.name',
        'proposal_details.status_id|proposal_detail_statuses.status',
        'proposal_details.fieldmanager_id|users.fname',
        'proposal_details.second_fieldmanager_id|users.fname',
        'proposal_details.service_name',
        'proposal_details.service_desc',
    ];

    public $searchable = [
        'proposal_details.service_name',
        'proposal_details.service_desc',
        'name'        => "LIKE",
        'childModels' => [
            'status'      => [
                'fields' => [
                    'proposal_detail_statuses.status' => '=',
                ],
            ],
            'fieldmanager' => [
                'fields' => [
                    'users.fname' => 'LIKE',
                    'users.lname' => 'LIKE',
                ],
            'secondfieldmanager' => [
                'fields' => [
                    'users.fname' => 'LIKE',
                    'users.lname' => 'LIKE',
                ],
            ],
            ],
        ]

    ];

    public function sortableColumns()
    {
        return $this->sortable;
    }

    public function searchableColumns()
    {
        return $this->searchable;
    }

    // Relationships:

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function vehicles()
    {
        return $this->HasMany(ProposalDetailVehicle::class, 'proposal_detail_id');
    }

    public function labor()
    {
        return $this->HasMany(ProposalDetailLabor::class, 'proposal_detail_id');
    }

    public function strippingServices()
    {
        return $this->HasMany(ProposalDetailStripingService::class, 'proposal_detail_id');
    }

    public function equipment()
    {
        return $this->HasMany(ProposalDetailEquipment::class, 'proposal_detail_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function additionalCosts()
    {
        return $this->hasMany(ProposalDetailAdditionalCost::class, 'proposal_detail_id');
    }

    public function subcontractors()
    {
        return $this->hasMany(ProposalDetailSubcontractor::class, 'proposal_detail_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'services_id');
    }

    public function fieldmanager()
    {
        return $this->belongsTo(User::class, 'fieldmanager_id');
    }
    public function secondfieldmanager()
    {
        return $this->belongsTo(User::class, 'second_fieldmanager_id');
    }

    public function permits()
    {
        return $this->hasMany(Permit::class);
    }

    // Mutators and Accessors

    public function getTotalAdditionalCostsAttribute()
    {
        return $this->getTotalAdditionalCosts();
    }

    public function getIsScheduledAttribute()
    {
        return $this->getIsScheduled();
    }
    // Methods:

    public function getIsScheduled()
    {
        return ($this->status_id > 1);
    }

    public function getTotalAdditionalCosts()
    {
        return $this->additionalCosts()->sum('amount');
    }


}