<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\WorkorderSubcontractorRequest;
use App\Models\Contractor;
use App\Models\ProposalDetail;
use App\Models\WorkorderSubcontractor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkOrderSubcontractorsController extends Controller
{
    public function entryForm($proposal_detail_id, Request $request)
    {
        if (!$proposalDetail = ProposalDetail::with(['proposal', 'service'])->find($proposal_detail_id)) {
            abort(404);
        }

        $perPage = $request->perPage ?? 50;
        $reportDate = !empty($request->report_date) ? Carbon::createFromFormat('m/d/Y', $request->report_date) : null;

        if (!empty($reportDate)) {
            $subcontractors = WorkorderSubcontractor::where('proposal_detail_id', $proposal_detail_id)
                ->where('report_date', $reportDate->format('Y-m-d'))
                ->with(['subcontractor' => function ($q){
                    $q->orderBy('name');
                }])
                ->paginate($perPage);
        }

        $data = [
            'proposalDetail' => $proposalDetail,
            'subcontractors' => $subcontractors ?? null,
            'reportDate'     => $reportDate,
            'contractorsCB'  => Contractor::contractorsCB(['0' => 'Select contractor']),
            'returnTo'       => route('show_workorder', ['id' => $proposalDetail->proposal_id]),
            'currentUrl'     => route('workorder_subcontractor_entry_form', ['proposal_detail_id' => $proposalDetail->id]),
            'tabSelected'    => 'services',
        ];

        return view('workorders.subcontractor.entry_form', $data);
    }

    public function store(WorkorderSubcontractorRequest $request)
    {
        $contractor = Contractor::find($request->contractor_id);

        $inputs = $request->all();

        $inputs['report_date'] = Carbon::createFromFormat('m/d/Y', $request->report_date);
        $inputs['created_by'] = auth()->user()->id;

        WorkorderSubcontractor::create($inputs);

        if (!empty($request->returnTo)) {
            return redirect()->to($request->returnTo)->with('success', 'Contractor added.');
        } else {
            return redirect()->back()->with('success', 'Contractor added.');
        }
    }

    public function destroy($workorder_subcontractor_id)
    {
        $WorkorderSubcontractor = WorkorderSubcontractor::find($workorder_subcontractor_id);

        $proposal_detail_id = $WorkorderSubcontractor->proposal_detail_id;
        $report_date = $WorkorderSubcontractor->report_date;

        $WorkorderSubcontractor->delete();

        return redirect()->route('workorder_subcontractor_entry_form', ['proposal_detail_id' => $proposal_detail_id, 'report_date' => $report_date->format('m/d/Y')])->with('success', 'Contractor entry removed.');
    }

}
