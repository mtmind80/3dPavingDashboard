<?php

namespace App\Models;

use App\Traits\SearchTrait;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;

class PermitNote extends Model
{
    use SortableTrait, SearchTrait;

    public $fillable = [
        'permit_id',
        'created_by',
        'note',
        'fee',
    ];

    public $sortable = [
        'status',
        'type',
        'number',
        'permits.permit_id|permits.number',
    ];

    public $searchable = [
        'note',
        'fee',
        'childModels' => [
            'permit'     => [
                'fields' => [
                    'number' => 'LIKE',
                ],
            ],
            'createdBy' => [
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

    // Relationships:

    public function permit()
    {
        return $this->belongsTo(Permit::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Accessor(get) and Mutators(set) */

    public function getDateCreatorAttribute()
    {
        return !empty($this->created_at) ? $this->created_at->format('m/d/Y') . ' - ' . $this->createdBy->full_name : null;
    }

    public function getCreatorAttribute()
    {
        return  $this->createdBy->full_name;
    }
}
