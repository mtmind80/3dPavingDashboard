<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeOrders extends Model
{
    public $timestamps = false;

    protected $table = 'change_orders';

    /** Relationships */

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function newProposal()
    {
        return $this->hasOne(Proposal::class, 'id', 'new_proposal_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
