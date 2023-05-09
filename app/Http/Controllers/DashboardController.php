<?php

namespace App\Http\Controllers;

use App\Models\ProposalDetail;
use App\Models\User;
use App\Models\Lead;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Proposal;
use App\Models\WorkOrder;

use App\Helpers\Currency;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{


    public function __construct(Request $request)
    {
        parent::__construct();

    }


    public function dashboard(Request $request)
    {

        $leaddate = new \DateTime();
        $leaddate->modify("-90 day");
        //echo $this->processUsers();  exit();
        $perPage = 25;
        $data['managersCB'] = User::managerCB();
        $data['leads'] = null;
        //leads
        if(auth()->user()->isSuperAdmin()) {
            $data['leads'] = Lead::unassigned()->where('created_at', '>', $leaddate)->with(['status', 'assignedTo', 'previousAssignedTo'])->paginate($perPage);
        } elseif(auth()->user()->isOffice()) {
            $data['leads'] = Lead::unassigned()->where('created_at', '>', $leaddate)->where('assigned_to', auth()->user()->id)->with(['status', 'assignedTo', 'previousAssignedTo'])->paginate($perPage);
        }

        //ready to close
        //SELECT proposal_details.proposal_id, proposals.name FROM proposal_details JOIN proposals ON proposals.id = proposal_details.proposal_id WHERE proposals.proposal_statuses_id = 5 AND proposal_details.status_id > 2 GROUP BY proposal_id 

        $data['readytoclose'] = Proposal::readytoclose()->with('contact')->get();

        //ready to bill
        $data['readytobill'] = Proposal::readytobill()->with('contact')->get();
        $data['activelink'] = 1;

        //onAlert
        $data['onalert'] = Proposal::onalert()->with('contact')->get();

        return view('dashboard', $data);
    }


    public function dashboard2(Request $request)
    {


        $webconfig = \Session::get('web_config');
        //company sales goals
        $salesgoals = $webconfig['webSalesGoals'];
        $creatorId = $request->creator_id ?? null;
        $salesManagerId = $request->sales_manager_id ?? null;
        $salesPersonId = $request->sales_person_id ?? null;
        $selectedYear = empty($request->selected_year) ? date('Y') : $request->selected_year;
        $addonSQL = '';
        $userid = auth()->user()->id;

        if(auth()->user()->isAdmin()) {

            if($salesManagerId) {
                $addonSQL = " AND proposals.salesmanager_id = $salesManagerId ";
                $user = User::where('id', $salesManagerId)->first();
                $salesgoals = $user->sales_goals;
            }
            if($creatorId) {
                $addonSQL = $addonSQL . " AND proposals.created_by = $creatorId ";
            }
            if($salesPersonId) {
                $user = User::where('id', $salesPersonId)->first();
                $salesgoals = $user->sales_goals;
                $addonSQL = $addonSQL . " AND proposals.salesperson_id = $salesPersonId ";
            }

        } else if(auth()->user()->isSalesManager()) {
            $addonSQL = " AND proposals.salesmanager_id = $userid ";
            $salesgoals = auth()->user()->sales_goals;
            $selectedYear = now()->format('Y');

        } else if(auth()->user()->isSalesPerson()) {
            $addonSQL = " AND proposals.salesperson_id =  $userid ";
            $salesgoals = auth()->user()->sales_goals;
            $selectedYear = now()->format('Y');
        } else { // only admin, sales people and office should have dashboard 2

            return redirect()->route('dashboard');
        }


        //SALES
        $totalSalesRevenue = 0;
        $query = "Select sum(cost) as cost from proposal_details
        JOIN proposals on proposals.id = proposal_details.proposal_id 
        WHERE 
        proposal_details.status_id < 4 
        AND proposals.proposal_statuses_id <> 7 
        AND YEAR(proposals.sale_date) = $selectedYear
        AND proposals.job_master_id is not null";
        $query = $query . $addonSQL;
        

        $services = DB::select($query);
        $totalcosts = json_decode(json_encode($services, true), true);
        $totalSalesRevenue = $totalcosts[0]['cost'];
        //$totalSalesRevenue = Currency::format($totalSalesRevenue);
        //show proposals created in this year
        $query = "Select count(*) as proposals from proposals WHERE YEAR(proposals.proposal_date) = $selectedYear ";
        $query = $query . $addonSQL;
        $proposals = DB::select($query);
        $proposals = json_decode(json_encode($proposals, true), true);

        //show number of workorders in this year        
        $query = "Select count(*) as workorders from proposals 
        WHERE proposals.job_master_id is not null 
        AND proposals.proposal_statuses_id <> 7 
        AND YEAR(proposals.sale_date) = $selectedYear";
        $query = $query . $addonSQL;
        $workorders = DB::select($query);
        $workorders = json_decode(json_encode($workorders, true), true);

        $proposalsCreated = $proposals[0]['proposals'];
        $workOrdersCreated = $workorders[0]['workorders'];

        $yearsCB = Proposal::yearsWithWorkOrdersCB();
        $salesnumbers = Currency::format($totalSalesRevenue);
        $ourgoals = Currency::format($salesgoals);

        $sales = [
            $totalSalesRevenue,
            $salesgoals
        ];
        $labels = [
            'Your Sales',
            'Your Goals'
        ];
        $LineChartSales = [
            'data' => $sales,
            'labels' => $labels,
        ];
        $percent = 0;
        $salespercent = 0;
        if($totalSalesRevenue > 0) {
            $salespercent = (int)(($totalSalesRevenue * 100) / $salesgoals);
            $percent = (int)(($workOrdersCreated * 100) / $proposalsCreated);
            //intval(($totalSalesRevenue * 100) / $salesgoals);
        }
        $data = [
            'activelink' => 2,
            'LineChartSales' => json_encode($LineChartSales),
            'percent' => $percent,
            'salesgoals' => $salesgoals,
            'yearsCB' => $yearsCB,
            'salespercent' => $salespercent,
            'selectedYear' => $selectedYear,
            'creatorId' => $creatorId,
            'salesManagerId' => $salesManagerId,
            'salesPersonId' => $salesPersonId,
            'workOrdersCreated' => $workOrdersCreated,
            'proposalsCreated' => $proposalsCreated,
            'totalSalesRevenue' => $totalSalesRevenue,
        ];

        if(auth()->user()->isAdmin()) {
            $data['creatorsCB'] = Proposal::creatorsCB();
            $data['salesManagersCB'] = Proposal::salesManagersCB();
            $data['salesPersonsCB'] = Proposal::salesPersonsCB();
        }

        return view('dashboard2', $data);
    }


    public function dashboard3(Request $request)
    {

        //sales by service
        $creatorId = $request->creator_id ?? null;
        $salesManagerId = $request->sales_manager_id ?? null;
        $salesPersonId = $request->sales_person_id ?? null;
        $selectedYear = empty($request->selected_year) ? date('Y') : $request->selected_year;
        $userid = auth()->user()->id;


        $addonSQL = '';

        if(auth()->user()->isAdmin()) {

            if($salesManagerId) {
                $addonSQL = $addonSQL . " AND proposals.salesmanager_id = $salesManagerId ";
            }
            if($creatorId) {
                $addonSQL = $addonSQL . " AND proposals.created_by = $creatorId ";
            }
            if($salesPersonId) {
                $addonSQL = $addonSQL . " AND proposals.salesperson_id = $salesPersonId ";
            }

        } else if(auth()->user()->isSalesManager()) {
            $selectedYear = now()->format('Y');
            $addonSQL = $addonSQL . " AND proposals.salesmanager_id =  $userid ";

        } else if(auth()->user()->isSalesPerson()) {
            $selectedYear = now()->format('Y');
            $addonSQL = $addonSQL . " AND proposals.salesperson_id =  $userid ";

        } else { // only admin, sales people and office should have dashboard 2
            $selectedYear = now()->format('Y');
            $proposals = [];
            $workOrders = [];
        }


        $query = "Select service_categories.name as service, sum(proposal_details.cost) as cost from proposal_details
        JOIN proposals on proposals.id = proposal_details.proposal_id 
        JOIN services on services.id = proposal_details.services_id 
        JOIN service_categories on service_categories.id = services.service_category_id 
        WHERE YEAR(proposals.sale_date) = $selectedYear
        AND proposals.job_master_id is not null 
        AND proposal_details.status_id < 4
        AND proposals.proposal_statuses_id <> 7";


        $groupby = " group by service_categories.name";
        $orderby = "order by sum(proposal_details.cost)";

        $query = $query . $addonSQL . $groupby;

        $services = DB::select($query);

        $services = json_decode(json_encode($services, true), true);

        /**  Donut charts data */

        $serviceCategories = [];
        $totalSalesRevenue = 0;

        foreach($services as $service) {
            // total all sales
            $totalSalesRevenue = $totalSalesRevenue += $service['cost'];
            $serviceCategories[$service['service']] = [
                'service_category' => $service['service'],
                'sales' => $service['cost'],
            ];

        }

        $serviceCategoriesAssoc = [];
        $eries = [];
        $labels = [];

        $serviceCategoriesNum = \App\Helpers\MultiArray::sort($serviceCategories, 'sales', 'desc');

        foreach($serviceCategoriesNum as $serviceCategory) {
            $serviceCategoriesAssoc[$serviceCategory['service_category']] = [
                'sales' => $serviceCategory['sales'],
            ];

            $eries[] = (integer)$serviceCategory['sales'];  // must be a number
            $labels[] = $serviceCategory['service_category'];
        }

        $donutWorkorderByServiceCategory = [
            'series' => $eries,
            'labels' => $labels,
        ];

        $totalSalesRevenue = Currency::format($totalSalesRevenue);

        $yearsCB = Proposal::yearsWithWorkOrdersCB();

        $data = [
            'activelink' => 3,
            'services' => $services,
            'donutWorkorderByServiceCategory' => json_encode($donutWorkorderByServiceCategory),
            'yearsCB' => $yearsCB,
            'selectedYear' => $selectedYear,
            'creatorId' => $creatorId,
            'salesManagerId' => $salesManagerId,
            'salesPersonId' => $salesPersonId,
            'totalSalesRevenue' => $totalSalesRevenue,
        ];

        if(auth()->user()->isAdmin()) {
            $data['creatorsCB'] = Proposal::creatorsCB();
            $data['salesManagersCB'] = Proposal::salesManagersCB();
            $data['salesPersonsCB'] = Proposal::salesPersonsCB();
        }

        return view('dashboard3', $data);
    }

    public function dashboard4(Request $request)
    {
        //sales by county
        $creatorId = $request->creator_id ?? null;
        $salesManagerId = $request->sales_manager_id ?? null;
        $salesPersonId = $request->sales_person_id ?? null;
        $selectedYear = empty($request->selected_year) ? date('Y') : $request->selected_year;
        $addonSQL = '';
        $userid = auth()->user()->id;

        if(auth()->user()->isAdmin()) {

            if($salesManagerId) {
                $addonSQL = $addonSQL . " AND proposals.salesmanager_id = $salesManagerId ";
            }
            if($creatorId) {
                $addonSQL = $addonSQL . " AND proposals.created_by = $creatorId ";
            }
            if($salesPersonId) {
                $addonSQL = $addonSQL . " AND proposals.salesperson_id = $salesPersonId ";
            }

        } else if(auth()->user()->isSalesManager()) {
            $selectedYear = now()->format('Y');
            $addonSQL = $addonSQL . " AND proposals.salesmanager_id = $userid ";

        } else if(auth()->user()->isSalesPerson()) {
            $selectedYear = now()->format('Y');
            $addonSQL = $addonSQL . " AND proposals.salesperson_id = $userid ";

        } else { // only admin, sales people and office should have dashboard 2
            $selectedYear = now()->format('Y');
            $proposals = [];
            $workOrders = [];
        }

        $query = "Select locations.county, count(distinct(proposals.id)) as workorders, sum(proposal_details.cost) as cost from proposal_details
            JOIN proposals on proposals.id = proposal_details.proposal_id 
            JOIN locations on locations.id = proposals.location_id 
            WHERE YEAR(proposals.sale_date) = $selectedYear
            AND proposals.job_master_id is not null
            AND proposal_details.status_id < 4 
            AND proposals.proposal_statuses_id <> 7";


        $groupby = " group by locations.county";
        $orderby = "order bysum(proposal_details.cost)";

        $query = $query . $addonSQL . $groupby;


        /**  Donut charts data */

        $counties = [];
        $totalSalesRevenue = 0;

        $countys = DB::select($query);

        $countys = json_decode(json_encode($countys, true), true);
        $counties = [];
        $eries = [];
        $labels = [];


        foreach($countys as $county) {
            if($county['county'] != '') {
                $counties[$county['county']] = [
                    'county' => $county['county'],
                    'work_orders' => $county['workorders'],
                    'sales' => $county['cost'],
                ];
                $eries[] = $county['workorders'];  // must be a number
                $labels[] = $county['county'];
            }
        }
        $donutWorkorderByCounty = [
            'series' => $eries,
            'labels' => $labels,
        ];

        // END


        $totalSalesRevenue = Currency::format($totalSalesRevenue);
        $yearsCB = Proposal::yearsWithWorkOrdersCB();


        $data = [
            'activelink' => 4,
            'donutWorkorderByCounty' => json_encode($donutWorkorderByCounty),
            'yearsCB' => $yearsCB,
            'selectedYear' => $selectedYear,
            'creatorId' => $creatorId,
            'salesManagerId' => $salesManagerId,
            'salesPersonId' => $salesPersonId,
            'totalSalesRevenue' => $totalSalesRevenue,
            'counties' => $counties,
        ];

        if(auth()->user()->isAdmin()) {
            $data['creatorsCB'] = Proposal::creatorsCB();
            $data['salesManagersCB'] = Proposal::salesManagersCB();
            $data['salesPersonsCB'] = Proposal::salesPersonsCB();
        }

        return view('dashboard4', $data);
    }

    public function dashboard5(Request $request)
    {

        //ready to close
        //SELECT proposal_details.proposal_id, proposals.name FROM proposal_details JOIN proposals ON proposals.id = proposal_details.proposal_id WHERE proposals.proposal_statuses_id = 5 AND proposal_details.status_id > 2 GROUP BY proposal_id 

        $data['readytoclose'] = Proposal::readytoclose()->with('contact')->get();
        $data['activelink'] = 5;

        return view('dashboard5', $data);
    }

    public function dashboard6(Request $request)
    {

        //ready to bill
        $data['readytobill'] = Proposal::readytobill()->with('contact')->get();
        $data['activelink'] = 6;

        return view('dashboard6', $data);
    }


    public function dashboard7(Request $request)
    {

        $creatorId = $request->creator_id ?? null;
        $salesManagerId = $request->sales_manager_id ?? null;
        $salesPersonId = $request->sales_person_id ?? null;
        $selectedYear = empty($request->selected_year) ? date('Y') : $request->selected_year;
        $addonSQL = '';
        $userid = auth()->user()->id;

        if(auth()->user()->isAdmin()) {

            if($salesManagerId) {
                $addonSQL = $addonSQL . " AND proposals.salesmanager_id = $salesManagerId ";
            }
            if($creatorId) {
                $addonSQL = $addonSQL . " AND proposals.created_by = $creatorId ";
            }
            if($salesPersonId) {
                $addonSQL = $addonSQL . " AND proposals.salesperson_id = $salesPersonId ";
            }

        } else if(auth()->user()->isSalesManager()) {
            $selectedYear = now()->format('Y');
            $addonSQL = $addonSQL . " AND proposals.salesmanager_id = $userid ";

        } else if(auth()->user()->isSalesPerson()) {
            $selectedYear = now()->format('Y');
            $addonSQL = $addonSQL . " AND proposals.salesperson_id = $userid ";

        } else { // only admin, sales people and office should have dashboard 2
            $selectedYear = now()->format('Y');
            $proposals = [];
            $workOrders = [];
        }

        $query = "SELECT Sum(proposal_details.cost) as cost, count(distinct(proposals.id)) as workorders, contact_types.type as county from proposal_details 
JOIN proposals on proposals.id = proposal_details.proposal_id
JOIN contacts on contacts.id = proposals.contact_id
JOIN contact_types on contact_types.id = contacts.contact_type_id
WHERE YEAR(proposals.sale_date) = $selectedYear
            AND proposals.job_master_id is not null
            AND proposal_details.status_id < 4 
            AND proposals.proposal_statuses_id <> 7";


        $groupby = " GROUP by contact_types.type";
        $orderby = " order by sum(proposal_details.cost) DESC";

        $query = $query . $addonSQL . $groupby . $orderby;


        /**  Donut charts data */

        $counties = [];
        $totalSalesRevenue = 0;

        $countys = DB::select($query);

        $countys = json_decode(json_encode($countys, true), true);
        $counties = [];
        $eries = [];
        $labels = [];


        foreach($countys as $county) {
            if($county['county'] != '') {
                $counties[$county['county']] = [
                    'county' => $county['county'],
                    'work_orders' => $county['workorders'],
                    'sales' => $county['cost'],
                ];
                $eries[] = $county['workorders'];  // must be a number
                $labels[] = $county['county'];
            }
        }
        $donutWorkorderByCounty = [
            'series' => $eries,
            'labels' => $labels,
        ];

        // END


        $totalSalesRevenue = Currency::format($totalSalesRevenue);
        $yearsCB = Proposal::yearsWithWorkOrdersCB();


        $data = [
            'activelink' => 7,
            'donutWorkorderByCounty' => json_encode($donutWorkorderByCounty),
            'yearsCB' => $yearsCB,
            'selectedYear' => $selectedYear,
            'creatorId' => $creatorId,
            'salesManagerId' => $salesManagerId,
            'salesPersonId' => $salesPersonId,
            'totalSalesRevenue' => $totalSalesRevenue,
            'counties' => $counties,
        ];

        if(auth()->user()->isAdmin()) {
            $data['creatorsCB'] = Proposal::creatorsCB();
            $data['salesManagersCB'] = Proposal::salesManagersCB();
            $data['salesPersonsCB'] = Proposal::salesPersonsCB();
        }

        return view('dashboard7', $data);
    }

    public function ajaxGetLeads(Request $request)
    {
        //
    }

    public function ajaxGetZipsPerCounty(Request $request)
    {
        if($request->isMethod('post') && $request->ajax()) {

            if(empty($request->county)) {
                return response()->json([
                    'success' => false,
                    'error' => 'County not defined.',
                ], 500);
            }

            $selectedYear = empty($request->selected_year) ? date('Y') : $request->selected_year;
            $creatorId = $request->creator_id ?? null;
            $salesManagerId = $request->sales_manager_id ?? null;
            $salesPersonId = $request->sales_person_id ?? null;

            $locationIds = Location::getIdsPerCountyWithZipCode($request->county);

            if(auth()->user()->isAdmin()) {
                $proposals = Proposal::year($selectedYear)
                    ->filters($creatorId, $salesManagerId, $salesPersonId)
                    ->whereHas('location', function($q) use ($locationIds) {
                        $q->whereIn('id', $locationIds);
                    })
                    ->with(['location' => function($w) use ($locationIds) {
                        $w->whereIn('id', $locationIds);
                    }])
                    ->get();
                $workOrders = WorkOrder::year($selectedYear)
                    ->filters($creatorId, $salesManagerId, $salesPersonId)
                    ->whereHas('location', function($q) use ($locationIds) {
                        $q->whereIn('id', $locationIds);
                    })
                    ->with(['location' => function($w) use ($locationIds) {
                        $w->whereIn('id', $locationIds);
                    }])
                    ->with(['details' => function($e) {
                        $e->with(['additionalCosts']);
                    }])
                    ->get();
            } else if(auth()->user()->isSalesManager()) {
                $currentYear = now()->format('Y');
                $proposals = Proposal::year($currentYear)->salesManagerDashboardData()->with(['location'])->get();
                $workOrders = WorkOrder::year($currentYear)->salesManagerDashboardData()->with(['details' => function($q) {
                    $q->with(['additionalCosts']);
                }, 'location'])->get();
            } else if(auth()->user()->isSalesPerson()) {
                $currentYear = now()->format('Y');
                $proposals = Proposal::year($currentYear)->salesPersonDashboardData()->with(['location'])->get();
                $workOrders = WorkOrder::year($currentYear)->salesPersonDashboardData()->with(['details' => function($q) {
                    $q->with(['additionalCosts']);
                }, 'location'])->get();
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Access denied.',
                ], 500);
            }

            $zips = [];

            $totalSalesRevenue = 0;
            foreach($workOrders as $workOrder) {
                $salesRevenue = 0;
                foreach($workOrder->details as $details) {
                    $salesRevenue += $details->cost;
                    foreach($details->additionalCosts as $additionalCost) {
                        $salesRevenue += $additionalCost->amount;
                    }
                }

                if(!empty($workOrder->location_id) && !empty($workOrder->location->postal_code)) {
                    $zips[$workOrder->location->postal_code] = [
                        'postal_code' => $workOrder->location->postal_code,
                        'work_orders' => !empty($zips[$workOrder->location->postal_code]['work_orders']) ? $zips[$workOrder->location->postal_code]['work_orders'] + 1 : 1,
                        'sales' => !empty($zips[$workOrder->location->postal_code]['sales']) ? $zips[$workOrder->location->postal_code]['sales'] + $salesRevenue : $salesRevenue,
                    ];
                }

                $totalSalesRevenue += $salesRevenue;
            }

            $zipsAssoc = [];

            $zipsNum = \App\Helpers\MultiArray::sort($zips, 'sales', 'desc');

            foreach($zipsNum as $zip) {
                $zipsAssoc[$zip['postal_code']] = [
                    'zipcode' => $zip['postal_code'],
                    'work_orders' => $zip['work_orders'],
                    'sales' => $zip['sales'],
                ];
            }

            foreach($proposals as $proposal) {
                if(!empty($proposal->location_id) && !empty($proposal->location->county)) {
                    if(empty($zipsAssoc[$proposal->location->postal_code])) {
                        $zipsAssoc[$proposal->location->postal_code] = [
                            'zipcode' => $proposal->location->postal_code,
                            'work_orders' => 0,
                            'sales' => 0,
                        ];
                    }
                    $zipsAssoc[$proposal->location->postal_code]['proposals'] = !empty($zipsAssoc[$proposal->location->postal_code]['proposals']) ? $zipsAssoc[$proposal->location->postal_code]['proposals'] + 1 : 1;
                }
            }

            if(empty($zipsAssoc)) {
                return response()->json([
                    'success' => false,
                    'error' => 'No data available.',
                ], 500);
            }

            $data = [
                'zips' => $zipsAssoc,
            ];

            $html = \View::make('_partials.zipcode_data', $data)->render();

            return response()->json([
                'success' => true,
                'county' => $request->county,
                'html' => $html,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Invalid request.',
            ], 500);
        }
    }


    public function ajaxGetZipsPerCity(Request $request)
    {
        if($request->isMethod('post') && $request->ajax()) {

            if(empty($request->county)) {
                return response()->json([
                    'success' => false,
                    'error' => 'County not defined.',
                ], 500);
            }

            $selectedYear = empty($request->selected_year) ? date('Y') : $request->selected_year;
            $creatorId = $request->creator_id ?? null;
            $salesManagerId = $request->sales_manager_id ?? null;
            $salesPersonId = $request->sales_person_id ?? null;

            $locationIds = Location::getIdsPerCountyWithCity($request->county);

            if(auth()->user()->isAdmin()) {
                $proposals = Proposal::year($selectedYear)
                    ->filters($creatorId, $salesManagerId, $salesPersonId)
                    ->whereHas('location', function($q) use ($locationIds) {
                        $q->whereIn('id', $locationIds);
                    })
                    ->with(['location' => function($w) use ($locationIds) {
                        $w->whereIn('id', $locationIds);
                    }])
                    ->get();
                $workOrders = WorkOrder::year($selectedYear)
                    ->filters($creatorId, $salesManagerId, $salesPersonId)
                    ->whereHas('location', function($q) use ($locationIds) {
                        $q->whereIn('id', $locationIds);
                    })
                    ->with(['location' => function($w) use ($locationIds) {
                        $w->whereIn('id', $locationIds);
                    }])
                    ->with(['details' => function($e) {
                        $e->with(['additionalCosts']);
                    }])
                    ->get();
            } else if(auth()->user()->isSalesManager()) {
                $currentYear = now()->format('Y');
                $proposals = Proposal::year($currentYear)->salesManagerDashboardData()->with(['location'])->get();
                $workOrders = WorkOrder::year($currentYear)->salesManagerDashboardData()->with(['details' => function($q) {
                    $q->with(['additionalCosts']);
                }, 'location'])->get();
            } else if(auth()->user()->isSalesPerson()) {
                $currentYear = now()->format('Y');
                $proposals = Proposal::year($currentYear)->salesPersonDashboardData()->with(['location'])->get();
                $workOrders = WorkOrder::year($currentYear)->salesPersonDashboardData()->with(['details' => function($q) {
                    $q->with(['additionalCosts']);
                }, 'location'])->get();
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Access denied.',
                ], 500);
            }

            $zips = [];

            $totalSalesRevenue = 0;
            foreach($workOrders as $workOrder) {
                $salesRevenue = 0;
                foreach($workOrder->details as $details) {
                    $salesRevenue += $details->cost;
                    foreach($details->additionalCosts as $additionalCost) {
                        $salesRevenue += $additionalCost->amount;
                    }
                }

                if(!empty($workOrder->location_id) && !empty($workOrder->location->city)) {
                    $zips[$workOrder->location->city] = [
                        'city' => $workOrder->location->city,
                        'work_orders' => !empty($zips[$workOrder->location->city]['work_orders']) ? $zips[$workOrder->location->city]['work_orders'] + 1 : 1,
                        'sales' => !empty($zips[$workOrder->location->city]['sales']) ? $zips[$workOrder->location->city]['sales'] + $salesRevenue : $salesRevenue,
                    ];
                }

                $totalSalesRevenue += $salesRevenue;
            }

            $zipsAssoc = [];

            $zipsNum = \App\Helpers\MultiArray::sort($zips, 'sales', 'desc');

            foreach($zipsNum as $zip) {
                $zipsAssoc[$zip['city']] = [
                    'city' => $zip['city'],
                    'work_orders' => $zip['work_orders'],
                    'sales' => $zip['sales'],
                ];
            }

            foreach($proposals as $proposal) {
                if(!empty($proposal->location_id) && !empty($proposal->location->county)) {
                    if(empty($zipsAssoc[$proposal->location->city])) {
                        $zipsAssoc[$proposal->location->city] = [
                            'city' => $proposal->location->city,
                            'work_orders' => 0,
                            'sales' => 0,
                        ];
                    }
                    $zipsAssoc[$proposal->location->city]['proposals'] = !empty($zipsAssoc[$proposal->location->city]['proposals']) ? $zipsAssoc[$proposal->location->city]['proposals'] + 1 : 1;
                }
            }

            if(empty($zipsAssoc)) {
                return response()->json([
                    'success' => false,
                    'error' => 'No data available.',
                ], 500);
            }

            $data = [
                'zips' => $zipsAssoc,
            ];

            $html = \View::make('_partials.city_data', $data)->render();

            return response()->json([
                'success' => true,
                'county' => $request->county,
                'html' => $html,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Invalid request.',
            ], 500);
        }
    }

}

/*
 $lineColumn = [
            'series' => [
                (object)[
                    'name' => '2021',
                    'type' => 'column',
                    'data' => [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16],
                ],
                (object)[
                    'name' => '2022',
                    'type' => 'column',
                    'data' => [23, 32, 27, 10],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'legend' => true,
        ];
        $linehar = [
            'series' => [
                (object)[
                    'name' => '2024',
                    'type' => 'column',
                    'data' => [22, 22, 25, 27, 23, 42, 27, 31, 22, 22, 12, 16],
                ],
                (object)[
                    'name' => '2023',
                    'type' => 'column',
                    'data' => [23, 32, 27, 10, 22, 22, 22, 22, 22, 2],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'legend' => true,
        ];
 */
