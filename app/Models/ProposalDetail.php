<?php

namespace App\Models;

use App\Helpers\Currency;
use App\Traits\SearchTrait;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;

class ProposalDetail extends Model
{
    use SortableTrait, SearchTrait;

    protected $guarded = ['id'];

    public $fillable = [
        'proposal_id',
        'change_order_id',
        'services_id',
        'contractor_id',
        'contractor_bid',
        'status_id',
        'location_id',
        'fieldmanager_id',
        'second_fieldmanager_id',
        'cost',
        'cost_per_linear_feet',
        'material_cost',
        'service_name',
        'service_desc',
        'bill_after',
        'dsort',
        'linear_feet',
        'square_feet',
        'square_yards',
        'cubic_yards',
        'tons',
        'loads',
        'locations',
        'depth',
        'profit',
        'days',
        'cost_per_day',
        'break_even',
        'primer',
        'yield',
        'fast_set',
        'additive',
        'sealer',
        'sand',
        'phases',
        'overhead',
        'catchbasins',
        'proposal_text',
        'alt_desc',
        'proposal_note',
        'proposal_field_note',
        'created_by',
        'scheduled_by',
        'completed_by',
        'completed_date',
        'start_date',
        'end_date',
        'created_at',
    ];

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

    public static function boot()
    {
        static::creating(function ($model) {
            $model->dsort = $model->getMaxDSort() + 1;
        });

        parent::boot();
    }

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

    public function status()
    {
        return $this->belongsTo(ProposalStatus::class, 'status_id');
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

    public function acceptedSubcontractor()
    {
        return $this->hasOne(ProposalDetailSubcontractor::class, 'proposal_detail_id')->where('accepted', 1);
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

    public function getFormattedCostAttribute()
    {
        return Currency::format($this->cost ?? 0);
    }

    public function getHtmlDsortAttribute()
    {
        return !empty($this->dsort) ? $this->dsort : null;
    }

    public function getTotalCostVehiclesAttribute()
    {
        $otalCost = 0;

        foreach ($this->vehicles as $item) {
            $otalCost += $item->cost;
        }

        return $otalCost;
    }

    public function getTotalCostEquipmentAttribute()
    {
        $otalCost = 0;

        foreach ($this->equipment as $item) {
            $otalCost += $item->cost;
        }

        return $otalCost;
    }

    public function getTotalCostLaborAttribute()
    {
        $otalCost = 0;

        foreach ($this->labor as $item) {
            $otalCost += $item->cost;
        }

        return $otalCost;
    }

    public function getTotalCostSubcontractorAttribute()
    {
        return $this->acceptedSubcontractor->total_cost ?? 0;
    }

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

    public function getMaxDSort()
    {
        return $this->max('d_sort');
    }

}
