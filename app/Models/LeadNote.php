<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadNote extends Model
{
    protected $fillable = [
        'lead_id',
        'created_by',
        'note',
    ];

    /** relationships */

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /** Accessor(get) and Mutators(set) */

    public function getDateCreatorAttribute()
    {
        return !empty($this->created_at) ? $this->created_at->format('m/d/Y') . ' - ' . $this->creator->full_name : null;
    }

    public function getDateCreatedAttribute()
    {
        return $this->created_at->format('M-d-Y');

    }

}
