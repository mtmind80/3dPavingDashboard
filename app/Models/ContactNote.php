<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactNote extends Model
{
    protected $fillable = [
        'contact_id',
        'created_by',
        'contact_lead_id',
        'note',
    ];

    /** relationships */

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lead()
    {
        return $this->belongsTo(ContactLead::class, 'contact_lead_id');
    }

    /** Accessor(get) and Mutators(set) */

    public function getDateCreatorAttribute()
    {
        return $this->created_at->format('m/d/Y') . ' - ' . $this->creator->full_name;
    }

}
