<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalDetailSubcontractor extends Model
{
    //
    protected $table = 'proposal_detail_subcontractors';

    /** relationships */

    public function proposalDetails()
    {
        return $this->belongsTo(ProposalDetail::class, 'proposal_detail_id');
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class, 'contractor_id');
    }

}
