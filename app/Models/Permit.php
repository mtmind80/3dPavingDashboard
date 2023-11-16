<?php

namespace App\Models;

use App\Traits\SearchTrait;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
    use SortableTrait, SearchTrait;

    protected $casts = [
        'expires_on' => 'date',
    ];

    public $fillable = [
        'proposal_id',
        'proposal_detail_id',
        'status',
        'type',
        'number',
        'city',
        'expires_on',
        'county',
        'created_by',
        'last_updated_by',
    ];

    public $sortable = [
        'status',
        'type',
        'number',
        'county',
        'permits.proposal_id|proposals.name',
        'permits.proposal_detail_id|proposal_details.service',
        'permits.created_by|users.fname',
    ];

    public $searchable = [
        'status' => 'LIKE',
        'number' => 'LIKE',
        'childModels' => [
            'proposal'     => [
                'fields' => [
                    'name' => 'LIKE',
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

    // Relationships:

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function proposalDetail()
    {
        return $this->belongsTo(ProposalDetail::class);
    }

    public function notes()
    {
        return $this->hasMany(PermitNote::class, 'permit_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** scopes */

    public function scopeIncomplete($query)
    {
        return $query->where('status', '<>','Completed');
    }

    /** Accessor(get) and Mutators(set) */

    public function getHtmlExpiredOnAttribute()
    {
        return !empty($this->expires_on) ? $this->expires_on->format('j F, Y') : null;
    }

    public function getStatusOptionsAttributes() : array
    {
        return ['Approved','Not Submitted','Submitted','Under Review','Comments'];
    }


    /** Methods */

}
