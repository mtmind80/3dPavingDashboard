<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\WorkOrderTimeSheetRequest;
use App\Models\ProposalDetail;
use App\Models\User;
use App\Models\WorkorderTimesheets;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkOrderTimesheetsController extends Controller
{
    public function entryForm($proposal_detail_id, Request $request)
    {
        if (!$proposalDetail = ProposalDetail::with(['proposal', 'service'])->find($proposal_detail_id)) {
            abort(404);
        }

        $perPage = $request->perPage ?? 50;
        $reportDate = !empty($request->report_date) ? Carbon::createFromFormat('m/d/Y', $request->report_date) : null;

        if (!empty($reportDate)) {
            $timeSheets = WorkorderTimesheets::where('proposal_details_id', $proposal_detail_id)
                ->where('report_date', $reportDate->format('Y-m-d'))
                ->with(['employee' => function($q){
                    $q->orderBy('fname')->orderBy('lname');
                }])
                ->paginate($perPage);
        }

        $data = [
            'proposalDetail' => $proposalDetail,
            'timeSheets'     => $timeSheets ?? null,
            'reportDate'     => $reportDate,
            'employeesCB'    => User::employeesCB(['0' => 'Select employee']),
            'returnTo'       => route('show_workorder', ['id' => $proposalDetail->proposal_id]),
            'currentUrl'     => route('workorder_timesheet_entry_form', ['proposal_detail_id' => $proposalDetail->id]),
            'tabSelected'    => 'services',
        ];

        return view('workorders.timesheet.entry_form', $data);
    }

    public function store(WorkOrderTimeSheetRequest $request)
    {
        $employee = User::find($request->employee_id);

        $inputs = $request->all();

        $inputs['rate'] = $employee->rate_per_hour;
        $inputs['report_date'] = Carbon::createFromFormat('m/d/Y', $request->report_date);
        $inputs['start_time'] = Carbon::createFromFormat('m/d/Y g:i A', $request->report_date.' '. $request->start_time);
        $inputs['end_time'] = Carbon::createFromFormat('m/d/Y g:i A', $request->report_date.' '. $request->end_time);
        $inputs['actual_hours'] = $inputs['start_time']->floatDiffInHours($inputs['end_time']);
        $inputs['created_by'] = auth()->user()->id;

        WorkOrderTimeSheets::create($inputs);

        if (!empty($request->returnTo)) {
            return redirect()->to($request->returnTo)->with('success', 'Timesheet added.');
        } else {
            return redirect()->back()->with('success', 'Timesheet added.');
        }
    }

    public function destroy($workorder_timesheet_id)
    {
        $workorderTimesheet = WorkorderTimesheets::find($workorder_timesheet_id);

        $proposal_details_id = $workorderTimesheet->proposal_details_id;
        $report_date = $workorderTimesheet->report_date;

        $workorderTimesheet->delete();

        return redirect()->route('workorder_timesheet_entry_form', ['proposal_detail_id' => $proposal_details_id, 'report_date' => $report_date->format('m/d/Y')])->with('success', 'Timesheet entry removed.');
    }

}
