<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProposalTerm extends Model
{


    protected $table = 'proposal_terms';
    
    public function proposal()
    {
        return $this->belongsTo('App\Models\Proposal');
    }
}
