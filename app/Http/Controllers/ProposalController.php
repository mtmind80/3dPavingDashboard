<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\ProposalNoteRequest;
use App\Models\AcceptedDocuments;
use App\Models\County;
use App\Models\Location;
use App\Models\MediaType;
use App\Models\Permit;
use App\Models\Proposal;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\ServiceCategory;
use App\Models\State;
use App\Models\ProposalDetail;
use App\Models\ProposalNote;
use App\Models\ProposalMedia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
            $q->where('salesmanager_id', auth()->user()->id)->orWhere('created_by', auth()->user()->id)->orWhere('salesperson_id', auth()->user()->id);
        })->get()->toArray();

        $data['proposals'] = $proposals;
        $data['proposalcount'] = count($proposals);

        $customersCB = Cache::remember('customersCB', env('CACHE_TIMETOLIVE'), function() {
            $customersCB = Proposal::customersCB();
            return json_encode($customersCB);

        });
        $data['customersCB'] = json_decode($customersCB, true);

        $creatorsCB = Cache::remember('creatorsCB', env('CACHE_TIMETOLIVE'), function() {
            $creatorsCB = Proposal::creatorsCB();
            return json_encode($creatorsCB);

        });

        $data['creatorsCB'] = json_decode($creatorsCB, true);

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

        return redirect()->route('show_proposal', ['id' => $proposal_id]);

    }

    public function new()
    {
        \Session::flash('error', 'To begin a new proposal first select a contact, or create a new one.');
        return \Redirect::to('contacts');

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
        $data['locationTypesCB'] = Location::locationTypesCB();
        $data['countiesCB'] = Location::countiesCB();
        $data['salesPersonsCB'] = json_decode($salesPersonsCB, true);

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

        $data = array();
        //what kind of access do i have
        if(auth()->user()->isAdmin()) {
            $proposal = Proposal::find($id);
        } else {

            $proposal = Proposal::where('id', '=', $id)->where(function($q) {
                $q->where('salesmanager_id', auth()->user()->id)->orWhere('salesperson_id', auth()->user()->id);
            })->first();
            // managers only show if I am on the proposal
        }

        if($proposal) {
            $IsEditable = $proposal->IsEditable;
            $proposal = $proposal->toArray();
            $proposal['IsEditable'] = $IsEditable;

            $data['mediatypes'] = MediaType::all()->toArray();

            $accepted_filetypes = AcceptedDocuments::all()->pluck('extension')->toArray();
            $data['doctypes'] = implode(',', $accepted_filetypes);

            $services = ProposalDetail::where('proposal_id', $id)->get();
            $notes = ProposalNote::where('proposal_id', $id)->get();
            $permits = Permit::where('proposal_id', $id)->get();
            $medias = ProposalMedia::where('proposal_id', $id)->get();

            $hostwithHttp = request()->getSchemeAndHttpHost();


            $data['hostwithHttp'] = $hostwithHttp;

            $data['id'] = $id;
            $data['proposal'] = $proposal;
            $data['permits'] = $permits;
            $data['medias'] = $medias;
            $data['services'] = $services;
            $data['notes'] = $notes;

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
    public function edit($id)
    {

        //what kind of access do i have
        if(auth()->user()->isAdmin()) {
            $proposal = Proposal::where('id', $id)->first()->toArray();
        } else {

            $proposal = Proposal::where('id', '=', $id)->where(function($q) {
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
            $records = Proposal::where('id', $proposalId)->first();
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

        $records = Proposal::where('id', $id)->whereIn('proposal_statuses_id', [3, 7])->get();
        $data['records'] = $records;
        return view('proposals.clone', $data);

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
        if($reminderDate) {
            $reminderDate = date('Y-m-d', strtotime($reminderDate));
        }
        try {
            \DB::transaction(function() use ($request, $reminderDate) {
                $data = [
                    'proposal_id' => $request->proposal_id,
                    'created_by' => auth()->user()->id,
                    'note' => $request->note,
                    'reminder' => $request->reminder ?? 0,
                    'reminder_date' => $reminderDate,
                ];

                ProposalNote::create($data);

            });
        } catch(\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        if(!empty($this->returnTo)) {
            return redirect()->to($this->returnTo)->with('success', 'Proposal note added.');
        } else {
            return redirect()->back()->with('success', 'Proposal note added.');
        }
    }

}
