<?php

namespace App\Models;

use App\Helpers\Currency;
use App\Traits\SearchTrait;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Lead extends Model
{
    use SortableTrait, SearchTrait;

    protected $appends = ['created_year'];

    public $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'contact_type_id',
        'address1',
        'address2',
        'city',
        'postal_code',
        'state',
        'county',
        'created_by',
        'status_id',
        'assigned_to',
        'worked_before',
        'worked_before_description',
        'previous_assigned_to',
        'type_of_work_needed',
        'lead_source',
        'how_related',
        'community_name',
        'onsite',
        'best_days',
    ];

    public $sortable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address1',
        'city',
        'postal_code',
        'state',
        'county',
        'onsite',
        'leads.status_id|lead_status.status',
        'leads.created_by|users.fname',
        'leads.assigned_to|users.fname',
        'leads.previous_assigned_to|users.fname',
    ];

    public $searchable = [
        'first_name'  => 'LIKE',
        'last_name'   => 'LIKE',
        'email'       => 'LIKE',
        'phone'       => 'LIKE',
        'address1'    => 'LIKE',
        'city'        => 'LIKE',
        'postal_code' => 'LIKE',
        'county'      => 'LIKE',
        'childModels' => [
            'status'     => [
                'fields' => [
                    'status' => 'LIKE',
                ],
            ],
            'assignedTo' => [
                'fields' => [
                    'fname' => 'LIKE',
                    'lname' => 'LIKE',
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

    /** relationships */

    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }

    public function previousAssignedTo()
    {
        return $this->belongsTo(User::class, 'previous_assigned_to');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function status()
    {
        return $this->belongsTo(LeadStatus::class, 'status_id');
    }

    public function notes()
    {
        return $this->hasMany(LeadNote::class);
    }

    public function lastNote()
    {
        return $this->hasOne(LeadNote::class)->orderBy('created_at', 'DESC');
    }

    /** scopes */

    public function scopeOwn($query, $userId = null)
    {
        if (!empty($userId)) {
            $query->where('assigned_to', $userId);
        } else if (!auth()->user()->isAdmin()) {
            $query->where('assigned_to', auth()->user()->id);
        }

        return $query;
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }

    /** Accessor(get) and Mutators(set) */

    public function getCreatedYearAttribute()
    {
        return !empty($this->created_at) ? $this->created_at->format('Y') : null;
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getFullAddressOneLineAttribute()
    {
        return self::buildFullAddress($this->address1, $this->city, $this->county, $this->state, $this->postal_code, ', ', $this->address2);
    }

    public function getFullAddressTwoLineAttribute()
    {
        return self::buildFullAddress($this->address1, $this->city, $this->county, $this->state, $this->postal_code, '<br>', $this->address2);
    }

    /** Methods */

    static public function assigneesCB($default = [])
    {
        $items = User::active()->whereIn('role_id', [1, 2, 3])->orderBy('fname')->get()->pluck('full_name', 'id')->toArray();

        if (!empty($default)) {
            return $default + $items;
        }

        return $items;
    }

    public static function buildFullAddress($address, $city = '', $county = '', $state = '', $zip = '', $separator = '<br>', $address2 = '')
    {
        $result = [];
        $line1 = !empty($address) ? [$address] : [];
        if (!empty($address2)) {
            $line1[] = $address2;
        }
        if (count($line1)) {
            $result[] = count($line1) > 1 ? implode(', ', $line1) : $line1[0];
        }

        $line2 = [];
        if (!empty($city)) {
            $line2[] = $city;
        }
        if (!empty($county)) {
            $line2[] = $county;
        }
        $stateZip = [];
        if (!empty($state)) {
            $stateZip[] = $state;
        }
        if (!empty($zip)) {
            $stateZip[] = $zip;
        }
        if (!empty($stateZip)) {
            $line2[] = implode(' ', $stateZip);
        }
        if (!empty($line2)) {
            $result[] = implode(', ', $line2);
        }

        return trim(preg_replace('/\s+/', ' ', implode($separator, $result)));
    }

    // report

    public static function existingLeadsYearsCB()
    {
        $unique = Cache::remember('existing_leads_years', 60 * 24, function(){
            return ($rows = self::select('created_at')
                ->whereNotNull('created_at')
                ->orderBy('created_at', "DESC")
                ->get())
                ? $rows->unique('created_year')
                : null;
        });

        return !empty($unique) ? $unique->pluck('created_year', 'created_year')->toArray() : null;
    }

    public static function existingLeadSourcesByYearCB($year)
    {
        return Cache::remember('existing_leads_sources_by_year_'.$year, 60 * 24, function() use ($year){
            return self::distinct('lead_source')
                ->where(DB::raw('YEAR(created_at)'), '=', $year)
                ->orderBy('lead_source', "ASC")
                ->pluck('lead_source');
        });
    }

    protected function getLeads($year)
    {
        $sources = self::existingLeadSourcesByYearCB($year);

        $leadsArray = [];

        foreach ($sources as $source) {

            $converted = 0;  // total proposals the lead has
            $cost = 0;

            $leads = self::where('lead_source', $source)
                ->where(DB::raw('YEAR(created_at)'), '=', $year)
                ->with(['proposals' => function($q){
                    $q->with(['details']);
                }])
                ->orderBy('created_at', 'DESC')
                ->orderBy('lead_source', 'ASC')
                ->orderBy('first_name', 'ASC')
                ->orderBy('last_name', 'ASC')
                ->get();

            foreach ($leads as $lead) {
                $converted += $lead->proposals->count();

                foreach ($lead->proposals as $proposal) {
                    $cost += $proposal->details->sum('cost');
                }
            }

            $leadsArray[] = [
                'source' => $source,
                'leads' => $leads->count(),
                'converted' => $converted,
                'sales_total' => $cost,
                'sales_cost' => Currency::format($cost),
            ];
        }

        return $leadsArray;
    }

}

