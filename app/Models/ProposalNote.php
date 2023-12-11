<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalNote extends Model
{
    protected $table = 'proposal_notes';

    protected $guarded=['id'];

    protected $casts = [
        'reminder_date' => 'date',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

}
