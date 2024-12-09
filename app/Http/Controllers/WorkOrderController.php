<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Requests\SearchRequest;
use App\Models\AcceptedDocuments;
use App\Models\ChangeOrders;
use App\Models\Contact;
use App\Models\Location;
use App\Models\MediaType;
use App\Models\Payment;
use App\Models\Permit;
use App\Models\Proposal;
use App\Models\ProposalDetail;
use App\Http\Requests\ProposalNoteRequest;
use App\Models\ProposalMedia;
use App\Models\ProposalNote;
use App\Models\State;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class WorkOrderController extends Controller
{
    public function index(Request $request)
    {
        $needle = $request->needle ?? null;
        $perPage = $request->perPage ?? 50;

        $query = WorkOrder::search($needle)->sortable()->where('proposal_statuses_id', 5);

        if (!auth()->user()->isAdmin()) {
            $query->where('salesperson_id', auth()->user()->id);
        }

        $workorders = $query->with(['location', 'contact', 'status', 'salesPerson'])->orderBy('proposal_date', 'DESC')->paginate($perPage);

        $data = [
            'workorders' => $workorders,
            'needle' => $needle,
        ];

        return view('workorders.index', $data);
    }

    public function search(SearchRequest $request)
    {
        return $this->index($request);
    }


    public function storeNote(ProposalNoteRequest $request)
    {

        $reminderDate = $request->reminder_date ?? null;
        if ($reminderDate) {
            $reminderDate = date('Y-m-d', strtotime($reminderDate));
        }

        try {
            \DB::transaction(function () use ($request, $reminderDate) {
                $data = [
                    'proposal_id' => $request->proposal_id,
                    'created_by' => auth()->user()->id,
                    'note' => $request->note,
                    'reminder' => $request->reminder ?? 0,
                    'reminder_date' => $reminderDate,
                ];

                ProposalNote::create($data);

            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        if (!empty($this->returnTo)) {
            return redirect()->to($this->returnTo)->with('success', 'Workorder note added.');
        } else {
            return redirect()->back()->with('success', 'Work Order note added.');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        if (auth()->user()->isAdmin()) {
            $proposal = Workorder::find($id);
        } else {

            $proposal = Workorder::where('id', '=', $id)->where(function ($q) {
                $q->where('salesmanager_id', auth()->user()->id)->orWhere('salesperson_id', auth()->user()->id);
            })->first();
            // managers only show if I am on the Workorder
        }


        if ($proposal) {

            $currencyTotalDetailCosts = $proposal->currency_total_details_costs;

            $data['currency_total_details_costs'] = $currencyTotalDetailCosts;

            $data['allowSchedule'] = false;

            $data['permitsOK'] = $proposal['HasPermits'];

            $data['paymentsOK'] = $proposal['HasPayments'];

            if ($data['paymentsOK'] && $data['permitsOK']) {
                $data['allowSchedule'] = true;
            }


            //does this work order have change orders?
            $changeorders = ChangeOrders::where('proposal_id', '=', $proposal->id)->get();

            $data['fieldmanagers'] = User::where('role_id', 6)->where('status', 1)->get()->toArray();
            $data['mediatypes'] = MediaType::all()->toArray();

            $accepted_filetypes = AcceptedDocuments::all()->pluck('extension')->toArray();
            $data['doctypes'] = implode(',', $accepted_filetypes);

            $services = ProposalDetail::where('proposal_id', $id)->get();
            $notes = ProposalNote::where('proposal_id', $id)->get();
            $permits = Permit::where('proposal_id', $id)->get();
            $medias = ProposalMedia::where('proposal_id', $id)->get();

            $hostwithHttp = request()->getSchemeAndHttpHost();
            $data['fieldmanagersCB'] = User::fieldmanagersCB(['0' => 'Select a Manager']);
            $data['hostwithHttp'] = $hostwithHttp;

            $data['changeorders'] = $changeorders;
            $data['id'] = $id;
            $data['proposal'] = $proposal;
            $data['permits'] = $permits;
            $data['medias'] = $medias;
            $data['services'] = $services;
            $data['notes'] = $notes;

            return view('workorders.workorder_home', $data);

        }
        return view('pages-404');
    }


public function cancelworkorder($id)
{
        if (auth()->user()->isAdmin()) {
            $proposal = Workorder::find($id);
        } else {

    $proposal = Workorder::where('id', '=', $id)->where(function ($q) {
        $q->where('salesmanager_id', auth()->user()->id)->orWhere('salesperson_id', auth()->user()->id);
    })->first();
    // managers do only if I am on the Workorder
    if ($proposal) {
        $proposal->status = 7;
        $proposal->update();
    }
    return true;
    //send email to manager and keith
}
    return view('pages-404');

}
    public function view_service($proposal_id, $id)
    {


        $data = array();


        return view('workorders.view_service', $data);

    }

    public function changeorder($id)
    {
        $workorder = WorkOrder::where('id', '=', $id)->first();

        $changeorder = new ChangeOrders();
        $changeorder->proposal_id = $id;
        $changeorder->job_master_id = $workorder->job_master_id;
        $changeorder->created_by = auth()->user()->id;
        $changeorder->created_at = date('Y-m-d');
        $changeorder->save();
        $changeorder_id = $changeorder->id;

        $proposal = new Proposal();
        // set the job id when converted to a work order
        // $proposal->job_master_id = $workorder->job_master_id;
        $proposal->name = "Change Order: " . $workorder->name;
        $proposal->proposal_statuses_id = 1;
        $proposal->job_master_id = $workorder->job_master_id;
        $proposal->proposal_date = $workorder->proposal_date;
        $proposal->sale_date = $workorder->sale_date;
        $proposal->created_by = auth()->user()->id;
        $proposal->contact_id = $workorder->contact_id;
        $proposal->customer_staff_id = $workorder->customer_staff_id;
        $proposal->salesmanager_id = $workorder->salesmanager_id;
        $proposal->salesperson_id = $workorder->salesperson_id;
        $proposal->location_id = $workorder->location_id;
        $proposal->lead_id = $workorder->lead_id;
        $proposal->changeorder_id = $changeorder_id;

        $proposal->save();

        $proposal_id = $proposal->id;
        //update changeorder with proposal id
        $changeorder = ChangeOrders::where('id', '=', $changeorder_id)->first();

        $changeorder->new_proposal_id = $proposal_id;
        //dd($changeorder);
        $changeorder->update();

        \Session::flash('info', 'Your change order was created');

        return redirect()->route('show_proposal', ['id' => $proposal->id]);
    }

    public function doassignmanager(Request $request, $id, $detail_id)
    {

        $fieldmanager_id = $request['fieldmanager_id'];
        if ($fieldmanager_id > 0) {
            $detail = ProposalDetail::find($detail_id);
            $detail['fieldmanager_id'] = $fieldmanager_id;
            $detail->save();

            \Session::flash('success', 'Field Manager Assigned!');

            return redirect()->route('show_workorder', ['id' => $id]);


        }
        \Session::flash('error', 'Field Manager Not Assigned!');
        return redirect()->back();


    }


    public function permits($id)
    {
        $workorder = WorkOrder::find($id)->first();
        $permits = Permit::where('proposal_id', '=', $id)->get()->toArray();
        $data['permits'] = $permits;
        $data['workorder'] = $workorder;
        return view('permit.permitmanager', $data);

    }

    public function payments($id)
    {


        $workorder = WorkOrder::where('id', '=', $id)->first();

        $payments = Payment::where('proposal_id', '=', $id)->get()->toArray();
        $data['payments'] = $payments;
        $data['workorder'] = $workorder;
        $data['payment_types'] = ['Deposit' => 'Deposit', 'Progress Billing' => 'Progress Billing', 'Final Payment' => 'Final Payment', 'Permit Fee' => 'Permit Fee'];
        return view('workorders.paymentmanager', $data);

    }

    public function add_payments(PaymentRequest $request)
    {
        $id = $request['proposal_id'];
        $workorder = WorkOrder::find($id)->first();
        //create new payment
        Payment::create($request->all());
        //get all payments
        $payments = Payment::where('proposal_id', '=', $id)->get()->toArray();

        $data['id'] = $id;
        $data['payments'] = $payments;
        $data['workorder'] = $workorder;
        $data['payment_types'] = ['Deposit' => 'Deposit', 'Progress Billing' => 'Progress Billing', 'Final Payment' => 'Final Payment', 'Permit Fee' => 'Permit Fee'];

        return $this->payments($id)->with('success', "Nice Work!");;
//        return view('workorders.paymentmanager', $data)->with('success', "Nice Work!");

    }

    public function delete_payment($proposal_id, $id)
    {

        $payment = Payment::where('id', '=', $id)->firstorfail()->delete();
        return $this->payments($proposal_id);

    }

    public function assignmanager($id, $detail_id)
    {

        $data = array();
        //what kind of access do i have
        $proposal = Proposal::find($id);

        if ($proposal) {

            $data['service'] = ProposalDetail::where('id', $detail_id)->first();
            $data['managers'] = User::where('role_id', 6)->where('status', 1)->get()->toArray();
            $hostwithHttp = request()->getSchemeAndHttpHost();
            $data['hostwithHttp'] = $hostwithHttp;


            $data['id'] = $id;
            $data['detail_id'] = $detail_id;
            $data['proposal'] = $proposal;

            return view('workorders.assignmanager', $data);

        }
        return view('pages-404');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


        $proposal = WorkOrder::where('id', $id)->where('proposal_statuses_id', 5)->first()->toArray();


        if ($proposal) {

            $data = array();
            $contact = Contact::where('id', $proposal['contact_id'])->first()->toArray();
            $data['contact'] = $contact;
            $data['id'] = $id;
            $data['proposal'] = $proposal;

            $contactstaff = [];
            $contactstaff[$contact['id']] = $contact['first_name'] . ' ' . $contact['last_name'];
            $staff = Contact::where('related_to', '=', $contact['id'])->get();
            $staff = json_decode(json_encode($staff), true);
            if ($staff) {
                foreach ($staff as $s) {
                    $contactstaff[$s['id']] = $s['first_name'] . ' ' . $s['last_name'];
                }
            }
            $data['staff'] = $contactstaff;

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
            $data['locationTypesCB'] = Location::locationTypesCB();
            $data['countiesCB'] = Location::countiesCB();

            $data['submit_caption'] = "Submit";
            $data['cancel_caption'] = "Cancel";
            $data['states'] = State::all()->toArray();


            return view('workorders.edit_workorder', $data);

        }

        return view('pages-404')->with('message', 'You know it');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // edit workoder
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
            \Session::flash('message', 'Your workorder was updated!');
            return redirect()->route('show_workorder', ['id' => $proposal_id]);

        } catch (exception $e) {
            \Session::flash('message', 'Sorry no matching records were found!');
            return redirect()->back();
        }


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
}
