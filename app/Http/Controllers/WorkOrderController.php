<?php

namespace App\Http\Controllers;
use App\Http\Requests\SearchRequest;
use App\Models\AcceptedDocuments;
use App\Models\MediaType;
use App\Models\Permit;
use App\Models\Proposal;
use App\Models\ProposalDetail;
use App\Http\Requests\ProposalNoteRequest;
use App\Models\ProposalMedia;
use App\Models\ProposalNote;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Http\Request;


class WorkOrderController extends Controller
{
    public function index(Request $request)
    {
        $needle = $request->needle ?? null;
        $perPage = $request->perPage ?? 50;

        if(auth()->user()->isAdmin()) {
            $workorders = WorkOrder::search($needle)->sortable()->where('proposal_statuses_id', 5)->with(['location', 'contact', 'status', 'salesManager'])->paginate($perPage);
        } else {
            $userid = auth()->user()->id;
/*            $workorders = WorkOrder::search($needle)->sortable()->where('proposal_statuses_id', 5)->where(function($q,$userid) {
                $q->where('salesmanager_id', $userid)->orWhere('salesperson_id', $userid);
            })->with(['location', 'contact', 'status', 'salesManager'])->paginate($perPage);
*/
            $workorders = WorkOrder::search($needle)->sortable()->where('proposal_statuses_id', 5)->where('salesmanager_id', $userid)->orWhere('salesperson_id', $userid)->with(['location', 'contact', 'status', 'salesManager'])->paginate($perPage);

        }
        $data = [
            'workorders' => $workorders,
            'needle'     => $needle,
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
        if($reminderDate){
            $reminderDate = date('Y-m-d', strtotime($reminderDate));
        }

        try {
            \DB::transaction(function () use ($request, $reminderDate){
                $data = [
                    'proposal_id'    => $request->proposal_id,
                    'created_by' => auth()->user()->id,
                    'note'       => $request->note,
                    'reminder'       => $request->reminder ?? 0,
                    'reminder_date'       => $reminderDate,
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
     * @param  \Illuminate\Http\Request  $request
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
        if(auth()->user()->isAdmin()) {
            $proposal = Proposal::find($id);
        } else {

            $proposal = Proposal::where('id', '=', $id)->where(function($q) {
                $q->where('salesmanager_id', auth()->user()->id)->orWhere('salesperson_id', auth()->user()->id);
            })->first()->toArray();
            // managers only show if I am on the proposal
        }


        if($proposal) {

            $data['fieldmanagers'] = User::where('role_id',6)->where('status', 1)->get()->toArray();
            $data['mediatypes'] = MediaType::all()->toArray();

            $accepted_filetypes = AcceptedDocuments::all()->pluck('extension')->toArray();
            $data['doctypes'] = implode(',', $accepted_filetypes);

            $services = ProposalDetail::where('proposal_id', $id)->get();
            $notes = ProposalNote::where('proposal_id', $id)->get();
            $permits = Permit::where('proposal_id', $id)->get();
            $medias = ProposalMedia::where('proposal_id', $id)->get();

            $hostwithHttp = request()->getSchemeAndHttpHost();
            $data['fieldmanagersCB'] = User::fieldmanagersCB(['0'=>'Select a Manager']);
            $data['hostwithHttp'] = $hostwithHttp;

            $data['id'] = $id;
            $data['proposal'] = $proposal;
            $data['permits'] = $permits;
            $data['medias'] = $medias;
            $data['services'] = $services;
            $data['notes'] = $notes;

            return view('workorders.home', $data);

        }
        return view('pages-404');
    }

    public function doassignmanager(Request $request, $id, $detail_id)
    {

        $fieldmanager_id = $request['fieldmanager_id'];
        if($fieldmanager_id > 0)
        {
            $detail = ProposalDetail::find($detail_id);
            $detail['fieldmanager_id'] = $fieldmanager_id;
            $detail->save();

            \Session::flash('success', 'Field Manager Assigned!');

            return redirect()->route('show_workorder',['id' => $id]);


        }
        \Session::flash('error', 'Field Manager Not Assigned!');
        return redirect()->back();


    }

    public function assignmanager($id, $detail_id)
    {

        $data = array();
        //what kind of access do i have
        $proposal = Proposal::find($id);

        if($proposal) {

            $data['service'] = ProposalDetail::where('id', $detail_id)->first();
            $data['managers'] = User::where('role_id',6)->where('status', 1)->get()->toArray();
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
