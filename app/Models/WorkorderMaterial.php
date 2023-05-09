<?php

namespace App\Models;

use App\Traits\SearchTrait;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;

class WorkorderMaterial extends Model
{
    use SortableTrait, SearchTrait;

    protected $table = 'workorder_materials';

    protected $guarded = ['id'];

    protected $dates = ['report_date'];

    public $fillable = [
        'proposal_id',
        'proposal_detail_id',
        'material_id',
        'name',
        'report_date',
        'created_by',
        'cost',
        'note',
        'quantity',
    ];

    public $sortable = [
        'report_date',
        'workorder_materials.name',
        'cost',
        'quantity',
        'workorder_materials.proposal_id|proposals.name',
        'workorder_materials.created_by|users.fname',
    ];

    public $searchable = [
        'report_date'              => 'LIKE',
        'workorder_materials.name' => 'LIKE',
        'workorder_materials.note' => 'LIKE',
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

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    /** Accessor(get) and Mutators(set) */

}
