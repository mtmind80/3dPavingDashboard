<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $guarded =['id'];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class, 'id', 'proposal_id');

    }

}
