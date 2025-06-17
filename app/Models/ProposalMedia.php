<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalMedia extends Model
{
    protected $table = 'proposal_media';

    /** Relations */

    public function type()
    {
        return $this->belongsTo(MediaType::class, 'media_type_id');
    }


}
