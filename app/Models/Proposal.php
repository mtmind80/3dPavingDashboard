<?php

namespace App\Models;

use App\Helpers\Currency;
use App\Traits\SearchTrait;
use App\Traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Proposal extends Model
{
    use SortableTrait, SearchTrait;

    protected $dates = ['proposal_date', 'sale_date'];

    protected $table = 'proposals';

    protected $guarded = ['id'];

    protected $appends = ['work_order_number', 'proposal_year'];

    public $sortable = [
        'name',
        'job_master_id',
        'proposal_date',
        'proposals.created_by|users.fname',
        'proposals.proposal_statuses_id|proposal_statuses.status',
        'proposals.contact_id|contacts.first_name',
        'proposals.salesperson_id|users.fname',
    ];

    public $searchable = [
        'name' => "LIKE",
        'id' => "=",
        'job_master_id' => "LIKE",
        'childModels' => [
            'contact' => [
                'fields' => [
                    'contacts.first_name' => 'LIKE',
                ],
            ],
            'salesPerson' => [
                'fields' => [
                    'users.fname' => 'LIKE',
                    'users.lname' => 'LIKE',
                ],
            ],
            'location' => [
                'fields' => [
                    'address_line1' => 'LIKE',
                    'city' => 'LIKE',
                    'postal_code' => 'LIKE',
                ],
            ],
            'status' => [
                'fields' => [
                    'status' => 'LIKE',
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

    public static function boot()
    {
        static::creating(function($model) {
            if (auth()->user()->isPavementConsultant()) {
                $model->salesperson_id = auth()->user()->id;
            }
        });

        parent::boot();
    }

    // Relationships:

    public function details()
    {
        return $this->hasMany(ProposalDetail::class, 'proposal_id');
    }

    public function notes()
    {
        return $this->hasMany('App\Models\ProposalNote');
    }

    public function documents()
    {
        return $this->hasMany('App\Models\ProposalDocument');
    }

    public function terms()
    {
        return $this->hasOne('App\Models\ProposalTerm');
    }

    public function media()
    {
        return $this->hasMany('App\Models\ProposalMedia');
    }

    public function creator()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function Manager()
    {
        return $this->hasOne(User::class, 'id', 'salesmanager_id');
    }

    public function payments()
    {
        return $this->hasMany(Payments::class, 'id', 'proposal_id');

    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function salesPerson()
    {
        return $this->belongsTo(User::class, 'salesperson_id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function status()
    {
        return $this->belongsTo(ProposalStatus::class, 'proposal_statuses_id');
    }

    public function permits()
    {
        return $this->hasMany(Permit::class);
    }

    public function activeJobs()
    {
        return $this->hasMany(ProposalDetail::class, 'proposal_id')->where('status_id', '!=', 4);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function materials()
    {
        return $this->hasMany(ProposalMaterial::class);
    }

    public function materialsAsphalt()
    {
        return $this->hasMany(ProposalMaterial::class)
            ->with(['serviceCategory'])
            ->where('service_category_id', 1);
    }

    public function materialsRock()
    {
        return $this->hasMany(ProposalMaterial::class)->where('service_category_id', 7);
    }

    public function materialsSealCoat()
    {
        return $this->hasMany(ProposalMaterial::class)->where('service_category_id', 8);
    }

    // Scopes:

    public function scopeSale($query)
    {
        return $query->whereNotNull('sale_date');
    }

    public function scopeActiveJobsWithIncompletedServices($query)
    {
        return $query->where('proposal_statuses_id', 5)->whereHas('details', function ($q) {
            $q->whereNotIn('status_id', [3, 4]);
        });
    }

    public function scopeActiveJobs($query)
    {
        return $query->where('proposal_statuses_id', 5)->whereHas('details', function ($q) {
            $q->where('status_id', '>', 2);
        })->with(['details' => function ($w) {
            $w->where('status_id', '>', 2);
        }]);
    }

    public function scopeYear($query, $year = null)
    {
        if (!empty($year)) {
            $query->whereYear('proposal_date', $year);
        }

        return $query;
    }

    public function scopeReadytoclose($query)
    {
        return $query->where('proposal_statuses_id', 5)->whereHas('details', function ($q) {
            $q->where('status_id', '>', 2);
        })->with('details');
    }

    public function scopeReadytobill($query)
    {
        //ready to bill when status is completed
        $query->where('proposal_statuses_id', 6);
        return $query;
    }

    public function scopeOnalert($query)
    {
        //alerted proposals
        $query->where('on_alert', 1);
        return $query;
    }

    public function scopeRange($query, $startdate = null, $enddate = null)
    {
        if (strtotime($startdate) < strtotime($enddate)) {
            $query->where('proposal_date', '>=', $startdate);
            $query->where('proposal_date', '<=', $enddate);
        }
        return $query;
    }

    public function scopeOwnDashboardData($query)
    {
        if (!auth()->user()->isAdmin()) {
            $query->where(function ($q) {
                $q->orWhere('created_by', auth()->user()->id)
                    ->orWhere('salesmanager_id', auth()->user()->id)
                    ->orWhere('salesperson_id', auth()->user()->id);
            });
        }

        return $query;
    }

    public function scopeSalesManagerDashboardData($query)
    {
        $query->where('salesmanager_id', auth()->user()->id);

        return $query;
    }

    public function scopeSalesPersonDashboardData($query)
    {
        $query->where('salesperson_id', auth()->user()->id);

        return $query;
    }

    public function scopeFilters($query, $creatorId = null, $salesManagerId = null, $salesPersonId = null)
    {
        if (auth()->user()->isAdmin() && (!empty($creatorId) || !empty($salesManagerId) || !empty($salesPersonId))) {
            // AND
            if (!empty($creatorId)) {
                $query->where('created_by', $creatorId);
            }
            if (!empty($salesManagerId)) {
                $query->where('salesmanager_id', $salesManagerId);
            }
            if (!empty($salesPersonId)) {
                $query->where('salesperson_id', $salesPersonId);
            }

            /*  OR
            $query->where(function($q) use ($creatorId, $salesManagerId, $salesPersonId){
                if (!empty($creatorId)) {
                    $q->orWhere('created_by', $creatorId);
                }
                if (!empty($salesManagerId)) {
                    $q->orWhere('salesmanager_id', $salesManagerId);
                }
                if (!empty($salesPersonId)) {
                    $q->orWhere('salesperson_id', $salesPersonId);
                }
            });
            */
        }

        return $query;
    }

    public function scopeSearchFilters($query, $creatorId = null, $salesManagerId = null, $salesPersonId = null, $proposal_name = null, $contact_id = null)
    {
        if (!empty($creatorId)) {
            $query->where('created_by', $creatorId);
        }
        if (!empty($contact_id)) {
            $query->where('contact_id', $contact_id);
        }
        if (!empty($salesManagerId)) {
            $query->where('salesmanager_id', $salesManagerId);
        }
        if (!empty($salesPersonId)) {
            $query->where('salesperson_id', $salesPersonId);
        }
        if (!empty($proposal_name)) {
            $query->where('name', 'like', '%' . $proposal_name . '%');
        }

        return $query;
    }

    // Mutators and Accessors

    public function getProposalYearAttribute()
    {
        return !empty($this->proposal_date) ? $this->proposal_date->format('Y') : null;
    }

    public function getCostActiveJobsAttribute()
    {
        return $this->activeJobs->sum('cost');
    }

    public function getTotalActiveJobsAttribute()
    {
        return $this->getTotalActiveJobs();
    }

    public function getWorkOrderNumberAttribute()
    {
        $pieces = explode(":", $this->job_master_id ?? '');
        if (count($pieces) == 3) {
            return $pieces[0] . '-' . $pieces[1] . '-' . str_pad($pieces[2], 5, "0", STR_PAD_LEFT);
        }
        return null;

    }

    public function getTotalDetailsCostsAttribute()
    {
        return $this->getTotalDetailsCosts();
    }

    public function getCurrencyTotalDetailsCostsAttribute()
    {
        return Currency::format($this->total_details_costs ?? 0);;
    }

    public function getHtmlDateOneLineAttribute()
    {
        return !empty($this->proposal_date) ? $this->proposal_date->format('m/d/Y') . ' - ' . $this->proposal_date->format('h:i A') : null;
    }

    public function getIsEditableAttribute()
    {
        return ($this->proposal_statuses_id < 3) ? true : false;
    }

    public function getHtmlDateTwoLinesAttribute()
    {
        return !empty($this->proposal_date) ? $this->proposal_date->format('m/d/Y') . '<br>' . $this->proposal_date->format('h:i A') : null;
    }

    // Methods:

    public function getCostActiveJobs()
    {
        return $this->activeJobs->sum('cost');
    }

    public function getTotalActiveJobs()
    {
        return $this->activeJobs->count();
    }

    public function getTotalDetailsCosts()
    {
        return $this->details()->sum('cost');
    }

    public static function existingActivityByStatusYearsCB()
    {
        /**
         * 1- Get existing proposal dates in the date range specified.
         * 2- Next, get unique years ($unique) based on proposal_year (as it is not a table field
         *    but a dynamically created property). Check getProposalYearAttribute()
         */
        $unique = Cache::remember('existing_activity_by_status_years', 60 * 24, function () {
            return ($rows = self::select('proposal_date')
                ->whereNotNull('proposal_date')
                ->orderBy('proposal_date', "DESC")
                ->get())
                ? $unique = $rows->unique('proposal_year')
                : null;
        });

        return !empty($unique) ? $unique->pluck('proposal_year', 'proposal_year')->toArray() : null;
    }

    public function getDetailsScheduled()
    {
        return $this->details()->where('status_id', '>', 1);
    }

    public function getIsCompletedAttribute()
    {
        $allservices = $this->details()->get();
        $closedservices = $this->details()->where('status_id', [3, 4])->get();
        return (count($allservices) == count($closedservices)) ? true : false;

    }

    static public function yearsWithProposalsCB()
    {
        return self::select(\DB::raw('DISTINCT(YEAR(`proposal_date`)) AS YEAR'))->pluck('YEAR', 'YEAR')->toArray();
    }

    static public function yearsWithWorkOrdersCB()
    {
        return self::select(\DB::raw('DISTINCT(YEAR(`sale_date`)) AS YEAR'))->pluck('YEAR', 'YEAR')->toArray();
    }

    static public function customersCB()
    {
        if (!$userIds = self::distinct('contact_id')->pluck('contact_id')) {
            return null;
        }

        return Contact::whereIn('id', $userIds)->orderBy('first_name')->get()->pluck('FullName', 'id')->toArray();
    }

    static public function creatorsCB()
    {
        if (!$userIds = self::distinct('created_by')->pluck('created_by')) {
            return null;
        }

        return User::whereIn('id', $userIds)->orderBy('fname')->get()->pluck('full_name', 'id')->toArray();
    }

    static public function salesManagersCB()
    {
        if (!$userIds = self::distinct('salesmanager_id')->pluck('salesmanager_id')) {
            return null;
        }

        return User::whereIn('id', $userIds)->orderBy('fname')->get()->pluck('full_name', 'id')->toArray();
    }

    public static function salesPersonsCB($default = [])
    {
        if (!$userIds = self::distinct('salesperson_id')->pluck('salesperson_id')) {
            return null;
        }

        $users = User::whereIn('id', $userIds)->orderBy('fname')->get()->pluck('full_name', 'id')->toArray();

        if (!empty($default)) {
            return $default + $users;
        }

        return $users;
    }

    public static function getSalesPersonIds($fromDate, $toDate, $salesPersonId = null)
    {
        /**
         *  Get unique sales persons having proposals in the range
         *  (range based on proposal_date field)
         */
        $query = self::distinct('salesperson_id')
            ->where('proposal_statuses_id', '!=', 7)
            ->whereBetween('proposal_date', [$fromDate, $toDate])
            ->whereNotNull('salesperson_id')
            ->with('salesperson');

        // add condition if a specific sales person was selected:

        if (!empty($salesPersonId)) {
            $query->where('salesperson_id', $salesPersonId);
        }

        // create sales person ids array:

        $unorderedSalesPersonIds = $query->pluck('salesperson_id')->toArray();

        /**
         *  Construct the assoc (id => full sales person name) array ordered by first name
         *  Again, it has to be created after pulling users info as full_name is not
         *  an existing table field but a dynamically created property.
         *
         *  Similar approach is done in every function to build a user related assoc array
         *  to fill a dropdown list (salesPersonsCB creatorsCB() salesManagersCB(), etc)
         */

        return User::whereIn('id', $unorderedSalesPersonIds)->orderBy('fname')->orderBy('lname')->get()->pluck('full_name', 'id')->toArray();
    }

    protected static function getActivityByStatus($fromDate, $toDate, $salesPersonId = null)
    {
        if (!$salesPersonIds = self::getSalesPersonIds($fromDate, $toDate, $salesPersonId)) {
            return null;
        }

        /**
         *  Assoc array with all data that will be used to build the report.
         *  Main keys will be the name of the sales person.
         *  ($salesPersonIds is already ordered by first name)
         *  Only proposals that are not cancelled (proposal_statuses_id <> 7)
         */
        $rows = [];

        //The following is done for each sale person:
        foreach ($salesPersonIds as $salesPersonId => $salesPersonFullName) {

            /**
             *  $globalCost will store the total (sum) cost all active jobs for all non cancelled proposals
             *  the sale manager has in the selected date range for proposal_statuses_id in [4, 5, 6,8, 9]
             *
             */
            $globalCost = 0;

            // store sales person id if needed later
            $rows[$salesPersonFullName] = [
                'sales_person_id' => $salesPersonId,
            ];

            // Start building the sale person structure and data needed in the report:

            // First row: Status: "Pending" (proposal_statuses_id: 1, 2)
            $totalJobs = 0;
            $totalCost = 0;
            /**
             *  $totalJobs and $totalCost will store the number and the total cost respectively of all active jobs
             *  the sale manager has in his/her all non cancelled proposals in the selected date range for
             *  the specific proposal_statuses_id
             */
            if ($proposals = self::where('proposal_statuses_id', '!=', 7)
                ->whereBetween('proposal_date', [$fromDate, $toDate])
                ->where('salesperson_id', $salesPersonId)
                ->whereIn('proposal_statuses_id', [1, 2])
                ->get()
            ) {
                /**
                 *  for each proposal matching the criteria (not cancelled, for that sales person in the specified date range)
                 *  get its total active jobs and sum of their costs (those numbers are returned by getTotalActiveJobs()
                 *  and getTotalDetailsCosts() functions), then add those number to $totalJobs and $totalCost.
                 */
                foreach ($proposals as $proposal) {
                    $totalJobs += $proposal->getTotalActiveJobs();
                    $totalCost += $proposal->getTotalDetailsCosts();
                }
            }
            // Add a second level into sales person array to have his/her data per status
            $rows[$salesPersonFullName]['Pending'] = [
                'total_jobs' => $totalJobs,
                'cost' => $totalCost,
                'formatted_cost' => Currency::format($totalCost),
            ];

            // Second row: Status: "Rejected" (proposal_statuses_id: 3)
            $totalJobs = 0;
            $totalCost = 0;
            if ($proposals = self::where('proposal_statuses_id', '!=', 7)
                ->whereBetween('proposal_date', [$fromDate, $toDate])
                ->where('salesperson_id', $salesPersonId)
                ->where('proposal_statuses_id', 3)
                ->get()
            ) {
                foreach ($proposals as $proposal) {
                    $totalJobs += $proposal->getTotalActiveJobs();
                    $totalCost += $proposal->getTotalDetailsCosts();
                }
            }
            $rows[$salesPersonFullName]['Rejected'] = [
                'total_jobs' => $totalJobs,
                'cost' => $totalCost,
                'formatted_cost' => Currency::format($totalCost),
            ];

            // Third row: Status: "Active" (proposal_statuses_id: 4, 5)
            $totalJobs = 0;
            $totalCost = 0;
            if ($proposals = self::where('proposal_statuses_id', '!=', 7)
                ->whereBetween('proposal_date', [$fromDate, $toDate])
                ->where('salesperson_id', $salesPersonId)
                ->whereIn('proposal_statuses_id', [4, 5])
                ->get()
            ) {
                foreach ($proposals as $proposal) {
                    $totalJobs += $proposal->getTotalActiveJobs();
                    $totalCost += $proposal->getTotalDetailsCosts();
                }
            }
            $rows[$salesPersonFullName]['Active'] = [
                'total_jobs' => $totalJobs,
                'cost' => $totalCost,
                'formatted_cost' => Currency::format($totalCost),
            ];
            $globalCost += $totalCost;

            // Fourth row: Status: "Completed" (proposal_statuses_id: 6)
            $totalJobs = 0;
            $totalCost = 0;
            if ($proposals = self::where('proposal_statuses_id', '!=', 7)
                ->whereBetween('proposal_date', [$fromDate, $toDate])
                ->where('salesperson_id', $salesPersonId)
                ->where('proposal_statuses_id', 6)
                ->get()
            ) {
                foreach ($proposals as $proposal) {
                    $totalJobs += $proposal->getTotalActiveJobs();
                    $totalCost += $proposal->getTotalDetailsCosts();
                }
            }
            $rows[$salesPersonFullName]['Completed'] = [
                'total_jobs' => $totalJobs,
                'cost' => $totalCost,
                'formatted_cost' => Currency::format($totalCost),
            ];
            $globalCost += $totalCost;

            // Fifth row: Status: "Cancelled" (proposal_statuses_id: 7)
            $totalJobs = 0;
            $totalCost = 0;
            if ($proposals = self::where('proposal_statuses_id', '!=', 7)
                ->whereBetween('proposal_date', [$fromDate, $toDate])
                ->where('salesperson_id', $salesPersonId)
                ->where('proposal_statuses_id', 7)
                ->get()
            ) {
                foreach ($proposals as $proposal) {
                    $totalJobs += $proposal->getTotalActiveJobs();
                    $totalCost += $proposal->getTotalDetailsCosts();
                }
            }
            $rows[$salesPersonFullName]['Cancelled'] = [
                'total_jobs' => $totalJobs,
                'cost' => $totalCost,
                'formatted_cost' => Currency::format($totalCost),
            ];

            // Sixth row: Status: "Billed" (proposal_statuses_id: 8)
            $totalJobs = 0;
            $totalCost = 0;
            if ($proposals = self::where('proposal_statuses_id', '!=', 7)
                ->whereBetween('proposal_date', [$fromDate, $toDate])
                ->where('salesperson_id', $salesPersonId)
                ->where('proposal_statuses_id', 8)
                ->get()
            ) {
                foreach ($proposals as $proposal) {
                    $totalJobs += $proposal->getTotalActiveJobs();
                    $totalCost += $proposal->getTotalDetailsCosts();
                }
            }
            $rows[$salesPersonFullName]['Billed'] = [
                'total_jobs' => $totalJobs,
                'cost' => $totalCost,
                'formatted_cost' => Currency::format($totalCost),
            ];
            $globalCost += $totalCost;

            // Seventh row: Status: "Paid" (proposal_statuses_id: 9)
            $totalJobs = 0;
            $totalCost = 0;
            if ($proposals = self::where('proposal_statuses_id', '!=', 7)
                ->whereBetween('proposal_date', [$fromDate, $toDate])
                ->where('salesperson_id', $salesPersonId)
                ->where('proposal_statuses_id', 9)
                ->get()
            ) {
                foreach ($proposals as $proposal) {
                    $totalJobs += $proposal->getTotalActiveJobs();
                    $totalCost += $proposal->getTotalDetailsCosts();
                }
            }
            $rows[$salesPersonFullName]['Paid'] = [
                'total_jobs' => $totalJobs,
                'cost' => $totalCost,
                'formatted_cost' => Currency::format($totalCost),
            ];
            $globalCost += $totalCost;

            // Add global cost for that sales person to the array:
            $rows[$salesPersonFullName]['global_cost'] = $globalCost;
            $rows[$salesPersonFullName]['formatted_global_cost'] = Currency::format($globalCost);
        }

        return $rows;
    }

    protected static function getActivityByContactType($fromDate, $toDate, $salesPersonId = null)
    {
        if (!$salesPersonIds = self::getSalesPersonIds($fromDate, $toDate, $salesPersonId)) {
            return null;
        }

        foreach ($salesPersonIds as $salesPersonId => $salesPersonFullName) {
            $globalProposalsCost = 0;
            $globalWorkOrdersCost = 0;

            $rows[$salesPersonFullName] = [
                'sales_person_id' => $salesPersonId,
            ];

            // get existing distinct proposal contact ids:

            $proposalContactIds = self::distinct('contact_id')
                ->whereNotNull('contact_id')
                ->where('proposal_statuses_id', '!=', 7)
                ->whereBetween('proposal_date', [$fromDate, $toDate])
                ->where('salesperson_id', $salesPersonId)
                ->pluck('contact_id')
                ->toArray();

            // get existing distinct work orders contact ids:

            $workOrderContactIds = self::distinct('contact_id')
                ->whereNotNull('contact_id')
                ->where('proposal_statuses_id', '!=', 7)
                ->whereBetween('sale_date', [$fromDate, $toDate])
                ->where('salesperson_id', $salesPersonId)
                ->pluck('contact_id')
                ->toArray();

            // merge two arrays:

            $contactIds = array_unique(array_merge($proposalContactIds, $workOrderContactIds));

            //dd($proposalContactIds, $workOrderContactIds, $contactIds);

            // get existing distinct proposal contact types:

            $unorderedContactTypeIds = Contact::distinct('contact_type_id')
                ->whereIn('id', $contactIds)
                ->with('contactType')
                ->pluck('contact_type_id')
                ->toArray();

            $orderedContactTypeIds = ContactType::whereIn('id', $unorderedContactTypeIds)->orderBy('type')->get()->pluck('type', 'id')->toArray();

            foreach ($orderedContactTypeIds as $contactTypeId => $contactTypeName) {
                $proposalsTotal = 0;
                $proposalsCost = 0;
                if ($proposals = self::where('proposal_statuses_id', '!=', 7)
                    ->where('salesperson_id', $salesPersonId)
                    ->whereBetween('proposal_date', [$fromDate, $toDate])
                    ->whereHas('contact', function ($q) use ($contactTypeId) {
                        $q->where('contact_type_id', $contactTypeId);
                    })
                    ->get()
                ) {
                    foreach ($proposals as $proposal) {
                        $proposalsTotal++;
                        $proposalsCost += $proposal->getTotalDetailsCosts();
                    }
                    $globalProposalsCost += $proposalsCost;
                }

                $workOrdersTotal = 0;
                $workOrdersCost = 0;
                if ($workOrders = self::where('proposal_statuses_id', '!=', 7)
                    ->where('salesperson_id', $salesPersonId)
                    ->whereBetween('sale_date', [$fromDate, $toDate])
                    ->whereHas('contact', function ($q) use ($contactTypeId) {
                        $q->where('contact_type_id', $contactTypeId);
                    })
                    ->get()
                ) {
                    foreach ($workOrders as $workOrder) {
                        $workOrdersTotal++;
                        $workOrdersCost += $workOrder->getTotalDetailsCosts();
                    }
                    $globalWorkOrdersCost += $workOrdersCost;
                }

                $rows[$salesPersonFullName]['contact_types'][$contactTypeName] = [
                    'contact_type_id' => $contactTypeId,
                    'proposal_total' => $proposalsTotal,
                    'proposal_cost' => Currency::format($proposalsCost),
                    'work_order_total' => $workOrdersTotal,
                    'work_order_cost' => Currency::format($workOrdersCost),
                ];
            }

            $rows[$salesPersonFullName]['global_proposal_cost'] = $globalProposalsCost;
            $rows[$salesPersonFullName]['global_formatted_proposal_cost'] = Currency::format($globalProposalsCost);
            $rows[$salesPersonFullName]['global_work_order_cost'] = $globalWorkOrdersCost;
            $rows[$salesPersonFullName]['global_formatted_work_order_cost'] = Currency::format($globalWorkOrdersCost);
        }

        return $rows;
    }

}
