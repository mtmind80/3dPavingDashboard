<?php

namespace App\Http\Controllers;

use App\Helpers\ExceptionError;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\ProposalNoteRequest;
use App\Models\AcceptedDocuments;
use App\Models\ChangeOrders;
use App\Models\County;
use Carbon\Carbon;
use App\Models\Location;
use App\Models\MediaType;
use App\Models\Permit;
use App\Models\Proposal;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\ProposalDetailAdditionalCost as AdditionalCost;
use App\Models\ProposalDetailEquipment as Equipment;
use App\Models\ProposalDetailLabor as Labor;
use App\Models\ProposalDetailStripingService as Striping;
use App\Models\ProposalDetailSubcontractor as Subcontractor;
use App\Models\ProposalDetailVehicle as Vehicle;

use App\Models\ProposalActions;
use App\Models\ServiceCategory;
use App\Models\State;
use App\Models\ProposalDetail;
use App\Models\ProposalNote;
use App\Models\ProposalMedia;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Validator;

class ProposalController extends Controller
{


    public function __construct(Request $request)
    {
        parent::__construct();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(!auth()->user()->isOffice()) {
            return redirect()->route('dashboard');
        }

        $data = array();
        //List active proposals
        $proposals = Proposal::where('proposal_statuses_id', '=', 1)->where(function($q) {
            $q->where('salesmanager_id', auth()->user()->id)->orWhere('salesperson_id', auth()->user()->id);
        })->get()->toArray();


            $data['proposals'] = $proposals;
            $data['proposalcount'] = count($proposals);

            $customersCB = Cache::remember('customersCB', env('CACHE_TIMETOLIVE'), function () {
                $customersCB = Proposal::customersCB();
                return json_encode($customersCB);

            });
            $data['customersCB'] = json_decode($customersCB, true);

            $creatorsCB = Cache::remember('creatorsCB', env('CACHE_TIMETOLIVE'), function () {
                $creatorsCB = Proposal::creatorsCB();
                return json_encode($creatorsCB);

            });

            $data['creatorsCB'] = json_decode($creatorsCB, true);

            $salesManagersCB = Cache::remember('salesManagersCB', env('CACHE_TIMETOLIVE'), function () {
                $salesManagersCB = Proposal::salesManagersCB();
                return json_encode($salesManagersCB);

            });

            $data['salesManagersCB'] = json_decode($salesManagersCB, true);

            $salesPersonsCB = Cache::remember('salesPersonsCB', env('CACHE_TIMETOLIVE'), function () {
                $salesPersonsCB = Proposal::salesPersonsCB();
                return json_encode($salesPersonsCB);

            });

            $data['salesPersonsCB'] = json_decode($salesPersonsCB, true);

        if(auth()->user()->isSales()) {
            return view('proposals.index_sales', $data);
        }

            return view('proposals.index', $data);

    }


    //save new proposal
    public function create(Request $request)
    {

        // start new proposal
        $data['lead_id'] = $request['lead_id'] ?? null;
        $data['contact_id'] = $request['contact_id'];
        $data['id'] = $request['proposal_id'] ?? null;
        $data['proposal_statuses_id'] = $request['proposal_statuses_id'];
        $data['proposal_date'] = date("Y-m-d");
        $data['name'] = $request['name'];
        $data['customer_staff_id'] = $request['customer_staff_id'];
        $data['created_by'] = auth()->user()->id;
        $data['salesmanager_id'] = $request['salesmanager_id'];
        $data['salesperson_id'] = $request['salesperson_id'];
        $data['mot_required'] = $request['mot_required'] ?? 0;
        $data['permit_required'] = $request['permit_required'] ?? 0;
        $data['nto_required'] = $request['nto_required'] ?? 0;
        $data['discount'] = $request['discount'] ?? 0;
        $data['progressive_billing'] = $request['progressive_billing'] ?? 0;

        //insert a location
        $location = Location::where('address_line1', '=', $request['address1'])->where('city', '=', $request['city'])->first();
        if($location) {
            $id = $location->id;
        } else {
            //create new location
            $location['address_line1'] = $request['address1'];
            $location['address_line2'] = $request['address2'] ?? null;
            $location['city'] = $request['city'];
            $location['county'] = $request['county'];
            $location['state'] = $request['state'];
            $location['note'] = "Created From Lead " . $data['lead_id'] . " and New Proposal " . $data['name'];
            $location['postal_code'] = $request['postal_code'];
            $location['parcel_number'] = $request['parcel_number'] ?? null;
            $location['location_type_id'] = $request['location_type_id'];
            $id = DB::table('locations')->insertGetId(
                $location
            );
        }
        $data['location_id'] = $id;

        $proposal_id = DB::table('proposals')->insertGetId(
            $data
        );

        //save current material pricing
        $this->setMaterialPricing($proposal_id);
        \Session::flash('message', 'Your proposal was created!');

        return redirect()->route('show_proposal', ['id' => $proposal_id]);

    }

    public function new()
    {

       // dd('we are here');

        //\Session::flash('info', 'To begin a new proposal first select a contact, or create a new one.');
        //redirect()->route('contacts');
        return redirect()->route('contact_create');

        //return \Redirect::to('contacts');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    //update proposal w client
    public function update(Request $request)
    {
        // edit new proposal
        $proposal_id = $request['proposal_id'];
        $data['name'] = $request['name'];
        $data['customer_staff_id'] = $request['customer_staff_id'];
        $data['salesmanager_id'] = $request['salesmanager_id'];
        $data['salesperson_id'] = $request['salesperson_id'];
        $data['mot_required'] = $request['mot_required'] ?? 0;
        $data['permit_required'] = $request['permit_required'] ?? 0;
        $data['nto_required'] = $request['nto_required'] ?? 0;
        $data['last_updated_by'] = auth()->user()->id;
        try {
            Proposal::whereId($proposal_id)->update($data);
            \Session::flash('message', 'Your proposal was updated!');
            return redirect()->route('show_proposal', ['id' => $proposal_id]);

        } catch(exception $e) {
            \Session::flash('message', 'Sorry no matching records were found!');
            return redirect()->back();
        }

    }

    //start new proposal w client
    public function startWithContact($contact)
    {


        // start new proposal
        $data['proposal'] = null;
        $contactstaff = [];
        $data['submit_caption'] = "Submit";
        $data['cancel_caption'] = "Cancel";
        $data['states'] = State::all()->toArray();
        $data['counties'] = County::select('county')->distinct()->orderBy('county')->get();
        $contact = Contact::where('id', $contact)->first();
        $data['contact'] = $contact;
        $data['lead_id'] = 0;


        $contactstaff[$contact->id] = $contact->Full_Name;
        $staff = Contact::where('related_to', '=', $contact->id)->get();
        $staff = json_decode(json_encode($staff), true);
        if($staff) {
            foreach($staff as $s) {
                $contactstaff[$s['id']] = $s['first_name'] . ' ' . $s['last_name'];
            }
        }

        $data['staff'] = $contactstaff;

        $salesManagersCB = Cache::remember('salesManagersCB', env('CACHE_TIMETOLIVE'), function() {
            $salesManagersCB = Proposal::salesManagersCB();
            return json_encode($salesManagersCB);

        });

        $data['salesManagersCB'] = json_decode($salesManagersCB, true);

        $salesPersonsCB = Cache::remember('salesPersonsCB', env('CACHE_TIMETOLIVE'), function() {
            $salesPersonsCB = Proposal::salesPersonsCB();
            return json_encode($salesPersonsCB);

        });
        $data['isSales'] = auth()->user()->isSales();
        $data['user_id'] = auth()->user()->id;
        $data['locationTypesCB'] = Location::locationTypesCB();
        $data['countiesCB'] = Location::countiesCB();
        $data['salesPersonsCB'] = json_decode($salesPersonsCB, true);
        \Session::flash('message', 'Your proposal was created!');

        return view('proposals.create_proposal', $data);
    }

    //start new proposal w client
    public function startWithLead(Lead $lead, Request $request)
    {

        $data['proposal'] = null;
        $contactstaff = [];
        $data['submit_caption'] = "Submit";
        $data['cancel_caption'] = "Cancel";
        // start new proposal
        $data['staff'] = null;
        $override = $request['override'];
        $data['states'] = State::all()->toArray();
        $data['counties'] = County::select('county')->distinct()->orderBy('county')->get();
        $data['lead'] = $lead;
        if(!$override) { // check contacts for match
            $contacts = Contact::where('first_name', '=', $lead->first_name)->where('last_name', '=', $lead->last_name)->orWhere('email', '=', $lead->email)->get()->toArray();
            if($contacts) {  // we found a match show the user
                $data['contacts'] = $contacts;
                $data['lead'] = $lead;
                return view('leads.found', $data);
            }
        }

        $contact = new Contact();
        $contact['first_name'] = $lead->first_name;
        $contact['last_name'] = $lead->last_name;
        $contact['email'] = $lead->email;
        $contact['phone'] = $lead->phone;
        $contact['address1'] = $lead->address1;
        $contact['address2'] = $lead->address2;
        $contact['city'] = $lead->city;
        $contact['postal_code'] = $lead->postal_code;
        $contact['state'] = $lead->state;
        $contact['county'] = $lead->county;
        $contact['contact'] = $lead->community_name;
        $contact['contact_type_id'] = $lead->contact_type_id;
        $contact['lead_id'] = $lead->id;
        // Create Contact from lead

        $contact->save();
        $data['contact_id'] = $contact->id;
        $data['contact'] = $contact;
        $data['proposal'] = null;
        $contactstaff[$contact->id] = $contact->FullName;
        $staff = Contact::where('related_to', '=', $contact->id)->get();
        $staff = json_decode(json_encode($staff), true);

        if($staff) {
            foreach($staff as $s) {
                $contactstaff[$s['id']] = $s['first_name'] . ' ' . $s['last_name'];
            }
        }
        $data['staff'] = $contactstaff;

        $salesManagersCB = Cache::remember('salesManagersCB', env('CACHE_TIMETOLIVE'), function() {
            $salesManagersCB = Proposal::salesManagersCB();
            return json_encode($salesManagersCB);

        });
        $data['salesManagersCB'] = json_decode($salesManagersCB, true);

        $salesPersonsCB = Cache::remember('salesPersonsCB', env('CACHE_TIMETOLIVE'), function() {
            $salesPersonsCB = Proposal::salesPersonsCB();
            return json_encode($salesPersonsCB);

        });
        $data['locationTypesCB'] = Location::locationTypesCB();
        $data['countiesCB'] = Location::countiesCB();
        $data['salesPersonsCB'] = json_decode($salesPersonsCB, true);


        return view('proposals.create_proposal', $data);

    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return $array
     */
    public function billproposal($id, Request $request)
    {

        //what kind of access do i have
        if(auth()->user()->isAdmin()) {
            $proposal = Proposal::find($id);
        } else {

            $proposal = Proposal::where('id', '=', $id)->where(function($q) {
                $q->where('salesmanager_id', auth()->user()->id)->orWhere('salesperson_id', auth()->user()->id);
            })->first()->toArray();
            // managers only show if I am on the proposal
        }


        if($proposal) {
            $services = ProposalDetail::where('proposal_id', $id)->get();
            $notes = ProposalNote::where('proposal_id', $id)->get();
            $medias = ProposalMedia::where('proposal_id', $id)->get();

            $data = array();
            $data['id'] = $id;
            $data['proposal'] = $proposal;
            $data['medias'] = $medias;
            $data['services'] = $services;
            $data['notes'] = $notes;

            //print_r($services);
            //exit();

            return view('proposals.proposal_home', $data);

        }

        return view('pages-404');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return $array
     */
    public function alertproposal($id)
    {

        //what kind of access do i have
        if(auth()->user()->isAdmin()) {
            $proposal = Proposal::find($id);
        } else {

            $proposal = Proposal::where('id', '=', $id)->where(function($q) {
                $q->where('salesmanager_id', auth()->user()->id)->orWhere('salesperson_id', auth()->user()->id);
            })->first()->toArray();
            // managers only show if I am on the proposal
        }


        if($proposal) {
            $services = ProposalDetail::where('proposal_id', $id)->get();
            $notes = ProposalNote::where('proposal_id', $id)->get();
            $medias = ProposalMedia::where('proposal_id', $id)->get();

            $data = array();
            $data['id'] = $id;
            $data['proposal'] = $proposal;
            $data['medias'] = $medias;
            $data['services'] = $services;
            $data['notes'] = $notes;
            $data['mediatypes'] = MediaType::all()->toArray();
            $accepted_filetypes = AcceptedDocuments::all()->pluck('extension')->toArray();
            $data['doctypes'] = implode(',', $accepted_filetypes);

            $changeorder = 0;
            //is proposal a change order
            if($proposal->changeorder_id) {
                $changeorder = ChangeOrders::where('id', '=', $proposal->changeorder_id)->first()->toArray();
            }
            $data['changeorder'] = $changeorder;

            $currencyTotalDetailCosts = $proposal->currency_total_details_costs;
            $data['currency_total_details_costs'] = $currencyTotalDetailCosts;

            //print_r($services);
            //exit();

            return view('proposals.proposal_home', $data);

        }

        return view('pages-404');

    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return $array
     */
    public function closeproposal($id)
    {

        //what kind of access do i have
        if(auth()->user()->isAdmin()) {
            $proposal = Proposal::find($id);
        } else {

            $proposal = Proposal::where('id', '=', $id)->where(function($q) {
                $q->where('salesmanager_id', auth()->user()->id)->orWhere('salesperson_id', auth()->user()->id);
            })->first()->toArray();
            // managers only show if I am on the proposal
        }


        if($proposal) {
            $services = ProposalDetail::where('proposal_id', $id)->get();
            $notes = ProposalNote::where('proposal_id', $id)->get();
            $medias = ProposalMedia::where('proposal_id', $id)->get();

            $data = array();
            $data['id'] = $id;
            $data['proposal'] = $proposal;
            $data['medias'] = $medias;
            $data['services'] = $services;
            $data['notes'] = $notes;

            //print_r($services);
            //exit();

            return view('proposals.proposal_home', $data);

        }

        return view('pages-404');

    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return $array
     */
    public function show(Request $request, $id)
    {
        $orderType = $request->order_type ?? 'ASC';

        $query = Proposal::whereIn('proposal_statuses_id', [1,4])->with(['status', 'details' => function($w) use ($orderType){
            $w->orderBy('dsort', $orderType);
        }]);

        if (!auth()->user()->isAdmin()) {
            $query->where(function($q) {
                $q->orWhere('salesmanager_id', auth()->user()->id)
                    ->orWhere('salesperson_id', auth()->user()->id);
            });
            // managers only show if I am on the proposal
        }

        if (!$proposal = $query->find($id)) {

            //try to send it work order
            return redirect()->route('show_workorder', ['id' => $id]);

        }


        $changeorder = 0;
        //is proposal a change order
        if($proposal->changeorder_id) {
            $changeorder = ChangeOrders::where('id', '=', $proposal->changeorder_id)->first()->toArray();
        }

        $currencyTotalDetailCosts = $proposal->currency_total_details_costs;

        $IsEditable = $proposal->IsEditable;

        $services = $proposal->details;

        $proposal = $proposal->toArray();

        $proposal['IsEditable'] = $IsEditable;

        $data = [];

        $data['changeorder'] = $changeorder;

        $data['mediatypes'] = MediaType::all()->toArray();

        $accepted_filetypes = AcceptedDocuments::all()->pluck('extension')->toArray();
        $data['doctypes'] = implode(',', $accepted_filetypes);

        $notes = ProposalNote::where('proposal_id', $id)->get();
        $permits = Permit::where('proposal_id', $id)->get();
        $medias = ProposalMedia::where('proposal_id', $id)->get();
        $proposal_customer = [];
        if($proposal['contact_id'] > 0) {
            $proposal_customer = Contact::where('id', '=', $proposal['contact_id'])->first()->toArray();
        }

        /*

        $proposal_sales = [];
        if($proposal['salesperson_id'] > 0) {
            $proposal_sales = Contact::where('id', '=', $proposal['salesperson_id'])->first()->toArray();
        }
        */

        $proposal_staff = [];
        if($proposal['customer_staff_id'] > 0) {
            $proposal_staff = Contact::where('id', '=', $proposal['customer_staff_id'])->first()->toArray();
        }

        $hostwithHttp = request()->getSchemeAndHttpHost();

        $data['hostwithHttp'] = $hostwithHttp;

       // $data['proposal_sales'] = $proposal_sales;
        $data['proposal_staff'] = $proposal_staff;
        $data['proposal_customer'] = $proposal_customer;


        $data['id'] = $id;
        $data['proposal'] = $proposal;
        $data['permits'] = $permits;
        $data['medias'] = $medias;
        $data['services'] = $services;
        $data['notes'] = $notes;

        $data['bodyClass'] = 'show-proposal-page';
        $data['selectedTab'] = $request->tab ?? null;
        $data['currency_total_details_costs'] = $currencyTotalDetailCosts;

        return view('proposals.proposal_home', $data);
    }

    public function reorderServices(Request $request)
    {
        if (!$request->reorder_str_cid) {
            return redirect()->back()->withError('New order unknown.');
        }

        try {
            DB::transaction(function() use ($request){
                foreach (explode(',', $request->reorder_str_cid) as $index => $id) {
                    ProposalDetail::where('proposal_id', $request->proposal_id)->find($id)->update(['dsort' => $index + 1]);
                }
            });
        } catch (Exception $e) {
            return ExceptionError::handleError($e);
        }

        $redirectTo = route('show_proposal', ['id' => $request->proposal_id]) . '?tab=servicestab';

        return redirect()->to($redirectTo)->with('success', 'Service order updated.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return $array
     */
    public function edit($id)
    {

        //what kind of access do i have
        if(auth()->user()->isAdmin()) {

            $proposal = Proposal::where('id', $id)->whereIN('proposal_statuses_id', [1,4])->first()->toArray();
        } else {

            $proposal = Proposal::where('id', '=', $id)->where('proposal_statuses_id', '=', 1)->where(function($q) {
                $q->where('salesmanager_id', auth()->user()->id)->orWhere('salesperson_id', auth()->user()->id);
            })->first()->toArray();
            // managers only show if I am on the proposal
        }


        if($proposal) {


            $data = array();
            $contact = Contact::where('id', $proposal['contact_id'])->first()->toArray();
            $data['contact'] = $contact;
            $data['id'] = $id;
            $data['proposal'] = $proposal;

            $contactstaff = [];
            $contactstaff[$contact['id']] = $contact['first_name'] . ' ' . $contact['last_name'];
            $staff = Contact::where('related_to', '=', $contact['id'])->get();
            $staff = json_decode(json_encode($staff), true);
            if($staff) {
                foreach($staff as $s) {
                    $contactstaff[$s['id']] = $s['first_name'] . ' ' . $s['last_name'];
                }
            }
            $data['staff'] = $contactstaff;

            $salesManagersCB = Cache::remember('salesManagersCB', env('CACHE_TIMETOLIVE'), function() {
                $salesManagersCB = Proposal::salesManagersCB();
                return json_encode($salesManagersCB);

            });

            $data['salesManagersCB'] = json_decode($salesManagersCB, true);

            $salesPersonsCB = Cache::remember('salesPersonsCB', env('CACHE_TIMETOLIVE'), function() {
                $salesPersonsCB = Proposal::salesPersonsCB();
                return json_encode($salesPersonsCB);

            });

            $data['salesPersonsCB'] = json_decode($salesPersonsCB, true);
            $data['locationTypesCB'] = Location::locationTypesCB();
            $data['countiesCB'] = Location::countiesCB();

            $data['submit_caption'] = "Submit";
            $data['cancel_caption'] = "Cancel";
            $data['states'] = State::all()->toArray();


            return view('proposals.edit_proposal', $data);

        }

        return view('pages-404');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Search the requested resource.
     *
     * @param Request
     * @return data array
     */
    public function inactivesearch(SearchRequest $request)
    {
        return $this->inactive($request);
    }

    /**
     * Search the requested resource.
     *
     * @param Request
     * @return data array
     */
    public function search(Request $request)
    {
        $needle = $request->needle ?? null;
        $perPage = $request->perPage ?? 50;


        $showall = $request->showall ?? null;
        $proposal_name = $request->proposal_name ?? null;
        $proposalId = $request->proposal_id ?? null;
        $creatorId = $request->creator_id ?? null;
        $contact_id = $request->contact_id ?? null;
        $salesManagerId = $request->sales_manager_id ?? null;
        $salesPersonId = $request->sales_person_id ?? null;
        $start_date = $request->start_date ?? null;
        $end_date = $request->end_date ?? null;
        $data['paginate'] = 1;

        if($proposalId) {
            $records = Proposal::where('id', $proposalId)->whereIN('proposal_statuses_id', [1,4])->first();
            $data['paginate'] = 0;

            if($records) {
                if($records['job_master_id']) {
                    return redirect()->route('show_workorder', ['id' => $proposalId]);
                } else {
                    return redirect()->route('show_proposal', ['id' => $proposalId]);
                }
            }

        } else {
            //            $contacts = Contact::search($needle)->sortable()->with(['contactType', 'company'])->paginate($perPage);
            //only pending proposals
            if($showall) {
                $records = Proposal::range($start_date, $end_date)->searchfilters($creatorId, $salesManagerId, $salesPersonId, $proposal_name, $contact_id)->paginate($perPage);
            } else {
                $records = Proposal::whereIn('proposal_statuses_id', array(1, 2, 3, 4, 7))->range($start_date, $end_date)->searchfilters($creatorId, $salesManagerId, $salesPersonId, $proposal_name, $contact_id)->paginate($perPage);
            }
            if(count($records) < $perPage) {
                $data['paginate'] = 0;

            }
        }

        if(!$records) {
            \Session::flash('error', 'Sorry no matching records were found!');
            return redirect()->back();

        }

        $data['needle'] = $needle;
        $data['records'] = $records;
        $data['columns'] = ['id', 'proposal_date', 'name', 'salesmanager_id', 'location_id', 'proposal_statuses_id'];
        $data['headers'] = ['id', 'proposal_date', 'name', 'salesmanager_id', 'location_id', 'proposal_statuses_id'];

        return view('proposals.results', $data);


    }


    /**
     * Search the requested resource.
     *
     * @param Request
     * @return data array
     */
    public function clone($id)
    {

        $proposal = Proposal::find($id);
        $newProposal = $proposal->replicate();
        $newProposal->created_at = Carbon::now();
        $newProposal->proposal_date = Carbon::now();
        $newProposal->proposal_statuses_id = 1;
        $newProposal->job_master_id = null;
        $newProposal->changeorder_id = null;
        $newProposal->sale_date = NULL;
        $newProposal->created_by = auth()->user()->id;
        $newProposal->save();

        $new_id = $newProposal->id;

        $this->setMaterialPricing($new_id);
        //print_r($new_id);

        $proposalDetails = ProposalDetail::where('proposal_id', $id)->get();

        foreach($proposalDetails as $detail)

        {


            // get proposal detail and any other items associated
            $proposaldetail = ProposalDetail::find($detail->id);

            if($proposaldetail) {
                $newDetail = $proposaldetail->replicate();
                $newDetail->proposal_id = $new_id;
                $newDetail->alt_desc = NULL;
                $newDetail->proposal_note = NULL;
                $newDetail->proposal_field_note = NULL;
                $newDetail->scheduled_by = NULL;
                $newDetail->completed_by = NULL;
                $newDetail->completed_date = NULL;
                $newDetail->start_date = NULL;
                $newDetail->end_date = NULL;
                $newDetail->save();

            } else {

                return redirect()->route('show_proposal',['id' => $new_id])->with('success', 'Proposal cloned.');

            }
            //            $newdetails = ProposalDetail::create($newdetail);
            $newdetail_id = $newDetail->id;
            //get any related data

            $additional_details = AdditionalCost::where('proposal_detail_id', '=', $detail->id)->get();

            if($additional_details) { // with new id
                foreach($additional_details as $details) {
                    $d = AdditionalCost::find($details->id);
                    $newRecord = $d->replicate();
                    $newRecord->proposal_detail_id = $newdetail_id;
                    $newRecord->save();
                }

            }

            $additional_details = Equipment::where('proposal_detail_id', '=', $detail->id)->get();

            if($additional_details) { // with new id
                foreach($additional_details as $details) {
                    $d = Equipment::find($details->id);
                    $newRecord = $d->replicate();
                    $newRecord->proposal_detail_id = $newdetail_id;
                    $newRecord->save();
                }

            }

            $additional_details = Labor::where('proposal_detail_id', '=', $detail->id)->get();
            if($additional_details) { // with new id
                foreach($additional_details as $details) {
                    $d = Labor::find($details->id);
                    $newRecord = $d->replicate();
                    $newRecord->proposal_detail_id = $newdetail_id;
                    $newRecord->save();
                }
            }

            $additional_details = Striping::where('proposal_detail_id', '=', $detail->id)->get();
            if($additional_details) { // with new id
                foreach($additional_details as $details) {
                    $d = Striping::find($details->id);
                    $newRecord = $d->replicate();
                    $newRecord->proposal_detail_id = $newdetail_id;
                    $newRecord->save();
                }
            }

            $additional_details = Subcontractor::where('proposal_detail_id', '=', $detail->id)->get();
            if($additional_details) { // with new id
                foreach($additional_details as $details) {
                    $d = Subcontractor::find($details->id);
                    $newRecord = $d->replicate();
                    $newRecord->proposal_detail_id = $newdetail_id;
                    $newRecord->save();
                }
            }

            $additional_details = Vehicle::where('proposal_detail_id', '=', $detail->id)->get();
            if($additional_details) { // with new id
                foreach($additional_details as $details) {
                    $d = Vehicle::find($details->id);
                    $newRecord = $d->replicate();
                    $newRecord->proposal_detail_id = $newdetail_id;
                    $newRecord->save();
                }
            }

        }


        //push materials

        \Session::flash('success', 'Proposal cloned with services. To change the client on this proposal, select an existing client or create a new one!');


        return redirect()->route('change_proposal_client', ['proposal_id' => $new_id]);
        //->with('info', 'Proposal cloned with services. To change client on this proposal, select an existing contact');

//        return redirect()->route('show_proposal',['id' => $new_id])->with('success', 'Proposal cloned with services.');

    }


    /**
     * Search the requested resource.
     *
     * @param Request
     * @return data array
     */
    public function inactive(Request $request)
    {

        $needle = $request->needle ?? null;
        $perPage = $request->perPage ?? 50;


        $records = Proposal::search($needle)->sortable()->whereIn('proposal_statuses_id', [3, 7])->paginate($perPage);
        $data['records'] = $records;
        $data['needle'] = $needle;
        $data['perpage'] = $perPage;
        return view('proposals.deadresults', $data);

    }


    //stuff

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function usewhen(Request $request)
    {
        $user = User::select("*")
            ->when($request->has('user_id'), function($query) use ($request) {
                $query->where('id', $request->user_id);
            })
            ->get();

        dd($user);
    }


    public function changestatus(Request $request)
    {


        if(isset($request['status'])){
            $status = $request['status'];
            $note = $request['note'];
            $reason = $request['reason'];
            $action_id = intval($status) + 1;
            $proposal_id = $request['proposal_id'];
        }
        $proposal = Proposal::where('id', '=', $proposal_id)->first();

        $proposal->proposal_statuses_id = $status;

        if($status == 1) // Proposal sent
        {
            $note = "Proposal Reset to Pending";

        }

        if($status == 2 && $proposal->changeorder_id == null) // approved
        {
            // create job master id and set sale date to taday
            $year = date('Y');
            $maxrec = Proposal::where(DB::raw('YEAR(created_at)'), '=', $year)->get()->count();
            $maxrec = $maxrec + 1;
            $maxrec = str_pad($maxrec, 5, "0", STR_PAD_LEFT);
            $month = date('m');
            $month = str_pad($month, 2, "0", STR_PAD_LEFT);
            $jobMasterId = $year . ":" . $month . ":" . $maxrec;
            //approved create work order
            $proposal->job_master_id = $jobMasterId;
            $proposal->sale_date = date_create()->format('Y-m-d H:i:s');
        }


        if($status == 3) // rejected
        {
            $proposal->rejected_reason = $reason;
            // do something when rejected  email Keith
        }

        if($status == 4) // Proposal sent
        {
            $note = "Proposal Sent to Client";

        }

        $proposal->save();

        $this->globalrecordactions($proposal_id, $action_id, $note);

        if($status == 2) // approved
        {
            return redirect()->route('show_workorder', ['id' => $proposal_id])->with('success', 'Proposal status changed.');
        }
        if($status == 3) // rejected
        {
            return redirect()->route('dashboard')->with('success', 'Proposal was archived. See archived proposals.');
        }
        //reset to pending or sent to customer
        return redirect()->back()->with('success', 'Proposal status changed.');

    }

    public function changeclient(Request $request, $proposal_id)
    {
        $proposal = Proposal::find($proposal_id);

        $needle = $request->needle ?? null;
        $perPage = $request->perPage ?? 25;

        $contacts = Contact::search($needle)->sortable()->paginate($perPage);

        $data = [
            'proposal_id' => $proposal_id,
            'proposal' => $proposal,
            'contacts' => $contacts,
            'needle'   => $needle,
        ];

        return view('contacts.index_change_client', $data);
    }
    public function selectclient($contact_id, $proposal_id)
    {

        $proposal = Proposal::find($proposal_id);
        $proposal->contact_id = $contact_id;
        $proposal->update();

        return redirect()->route('show_proposal', ['id' => $proposal_id]);

    }

    public function refreshMaterialPricing($id)
    {

        $this->setMaterialPricing($id);

        \Session::flash('success', 'Material pricing was updated!');

        return redirect()->route('show_proposal', ['id' => $id]);


    }


    public function setMaterialPricing($id)
    {


        //delete any old material pricing
        DB::statement("DELETE FROM proposal_materials
        WHERE `proposal_id`= $id;");

        //save current material pricing
        DB::statement("INSERT INTO proposal_materials (
        `proposal_id`,
        `material_id`,
        `name`,
        `cost`,
        `service_category_id`
        )
        SELECT
        $id,
        `id`,
        `name`,
        `cost`,
        `service_category_id`
        from materials;");

        return true;

    }


    public function storeNote(ProposalNoteRequest $request)
    {

        $reminderDate = $request->reminder_date ?? null;
        $reminder = 0;
        $msg = 'Proposal note added.';
        if($reminderDate) {
            $reminderDate = date('Y-m-d', strtotime($reminderDate));
            $reminder = 1;
            $msg = 'Proposal note added. And a reminder email will be sent on ' .$reminderDate;
        }
        try {
            DB::transaction(function() use ($request, $reminderDate, $reminder) {
                $data = [
                    'proposal_id' => $request->proposal_id,
                    'created_by' => auth()->user()->id,
                    'note' => $request->note,
                    'reminder' => $reminder,
                    'reminder_date' => $reminderDate,
                ];

                ProposalNote::create($data);

            });
        } catch(\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        $referer = request()->headers->get('referer');
        $this->returnTo = $referer;
        if(strpos($referer,'?type=note') == 0) {
            $this->returnTo = $referer . '?type=note';
        }
        if(!empty($this->returnTo)) {
            return redirect()->to($this->returnTo)->with('success', $msg);
        } else {
            return redirect()->back()->with('success', $msg);
        }
    }

    public function setAlert(Request $request)
    {
        $validator = Validator::make(
            $request->only(['proposal_id', 'alert_reason']), [
                'proposal_id' => 'required|positive',
                'alert_reason' => 'required|text|max:255',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->messages()->first());
        }

        if (!$proposal = Proposal::find($request->proposal_id)) {
            return redirect()->back()->with('error', 'Proposal not found.');
        }

        if (!empty($proposal->on_alert)) {
            redirect()->back()->with('error', 'Proposal has an alert already.');
        }

        try {
            DB::transaction(function () use ($request, & $proposal) {
                $proposal->on_alert = true;
                $proposal->alert_reason = $request->alert_reason;
                $proposal->save();

                $proposalAction = new ProposalActions;
                $proposalAction->proposal_id = $proposal->id;
                $proposalAction->action_id = 6;     // set alert
                $proposalAction->created_by = auth()->user()->id;
                $proposalAction->note = $proposal->alert_reason;
                $proposalAction->save();
            });
        } catch (Exception $e) {
            redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Alert set.');
    }

    public function resetAlert($proposal_id)
    {
        $validator = Validator::make([
                'proposal_id' => $proposal_id,
            ], [
                'proposal_id' => 'required|positive',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->messages()->first());
        }

        if (!$proposal = Proposal::find($proposal_id)) {
            return redirect()->back()->with('error', 'Proposal not found.');
        }

        if (empty($proposal->on_alert)) {
            return redirect()->back()->with('error', 'Proposal does not have an alert.');
        }

        try {
            DB::transaction(function () use (& $proposal) {
                $proposal->on_alert = false;
                $proposal->alert_reason = null;
                $proposal->save();

                $proposalAction = new ProposalActions;
                $proposalAction->proposal_id = $proposal->id;
                $proposalAction->action_id = 7;     // remove alert
                $proposalAction->created_by = auth()->user()->id;
                $proposalAction->note = null;
                $proposalAction->save();
            });
        } catch (Exception $e) {
            redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Alert removed.');
    }

}
