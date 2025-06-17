<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceSchedule extends Model
{
    use HasFactory;

    protected $table = 'service_schedule';

    public $fillable = [
        'proposal_detail_id',
        'title',
        'start_date',
        'end_date',
        'note',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function proposalDetail(): BelongsTo
    {
        return $this->belongsTo(ProposalDetail::class);
    }

    //
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(ProposalDetail::class, 'id', 'proposal_detail_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
