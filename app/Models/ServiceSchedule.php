<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceSchedule extends Model
{
    use HasFactory;

    protected $table = 'service_schedule';
    protected $guarded =['id'];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(ProposalDetail::class, 'id', 'proposal_detail_id');

    }

}
