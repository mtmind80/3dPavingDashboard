<?php

namespace App\Models;

use App\Traits\SearchTrait;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;

class WorkorderTimesheets extends Model
{
    use SortableTrait, SearchTrait;

    protected $table = 'workorder_timesheets';

    protected $dates = ['start_time', 'end_time'];

    public $fillable = [
        'proposal_id',
        'proposal_detail_id',
        'workorder_field_report_id',
        'employee_id',
        'created_by',
        'start_time',
        'end_time',
        'actual_hours',
        'rate',
    ];

    public $sortable = [
        'start_time',
        'end_time',
        'actual_hours',
        'rate',
        'created_at',
        'workorder_timesheets.proposal_id|proposals.name',
        'workorder_timesheets.employee_id|users.fname',
        'workorder_timesheets.workorder_field_report_id|workorder_field_reports.report_date',
    ];

    public $searchable = [
        'created_at' => 'LIKE',
        'childModels' => [
            'proposal' => [
                'fields' => [
                    'name' => 'LIKE',
                ],
            ],
            'employee' => [
                'fields' => [
                    'fname' => 'LIKE',
                    'lname' => 'LIKE',
                ],
            ],
            'fieldReport' => [
                'fields' => [
                    'report_date' => 'LIKE',
                ],
            ],
        ],
    ];

    public function sortableColumns()
    {
        return $this->sortable;
    }

    public function searchableColumns()
    {
        return $this->searchable;
    }

    // Relationships:

    public function proposalDetails()
    {
        return $this->belongsTo(ProposalDetail::class, 'proposal_detail_id');
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }

    public function fieldReport()
    {
        return $this->belongsTo(WorkorderFieldReport::class, 'workorder_field_report_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // total_hours

    /** Accessor(get) and Mutators(set) */

    public function getTotalHoursAttribute()
    {
        if (empty($this->start_time) || empty($this->end_time)) {
            return null;
        }


        return '';
    }

    // html_start html_finish

    public function getHtmlStartAttribute()
    {
        return !empty($this->start_time) ? $this->start_time->format('g:i A') : null;
    }

    public function getHtmlFinishAttribute()
    {
        return !empty($this->end_time) ? $this->end_time->format('g:i A') : null;
    }

    public function getHtmlTotalHoursAttribute()
    {
        return !empty($this->end_time) ? $this->end_time->format('g:i A') : null;
    }

}
