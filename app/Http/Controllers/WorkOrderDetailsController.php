<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\WorkOrderTimeSheetRequest;
use App\Http\Requests\WorkOrderVehicleRequest;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\ProposalDetail;
use App\Models\WorkorderTimesheets;
use App\Models\WorkorderVehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class WorkOrderDetailsController extends Controller
{
    public function details($proposal_detail_id, Request $request)
    {
        if (!$proposalDetail = ProposalDetail::with([
                'proposal',
                'service',
                'vehicles',
                'labor',
                'equipment',
                'subcontractors',
            ])->find($proposal_detail_id)
        ) {
            abort(404);
        }

        $reportDate = Carbon::createFromFormat('m/d/Y', $request->report_date ?? now(config('app.timezone'))->format('m/d/Y'));

        // time sheets

        if (!empty($reportDate)) {
            $timeSheets = WorkorderTimesheets::where('proposal_details_id', $proposal_detail_id)
                ->where('report_date', $reportDate->toDateString())
                ->with(['employee' => function($q){
                    $q->orderBy('fname')->orderBy('lname');
                }])
                ->get();
        }

        // equipment


        // materials


        // vehicles


        // subcontractors


        $data = [
            'proposalDetail' => $proposalDetail,
            'reportDate'     => $reportDate,
            'returnTo'       => route('show_workorder', ['id' => $proposalDetail->proposal_id]),
            'currentUrl'     => route('workorder_details', ['proposal_detail_id' => $proposalDetail->id]),
            'tabSelected'    => 'services',

            // time sheets
            'timeSheets'     => $timeSheets ?? null,
            'employeesCB'    => User::employeesCB(['0' => 'Select employee']),



        ];

        return view('workorders.details', $data);
    }

    // Timesheets

    public function ajaxTimeSheetStore(WorkOrderTimeSheetRequest $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $inputs = $request->only(['report_date', 'proposal_id', 'proposal_details_id', 'employee_id', 'start_time', 'end_time']);

            $validator = Validator::make(
                $inputs, [
                    'report_date' => 'required|date',
                    'proposal_id' => 'required|positive',
                    'proposal_details_id' => 'required|positive',
                    'start_time' => 'required|time',
                    'end_time' => 'required|time',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ]);
            }

            $employee = User::find($request->employee_id);

            $inputs['rate'] = $employee->rate_per_hour;
            $inputs['report_date'] = Carbon::createFromFormat('m/d/Y', $request->report_date);
            $inputs['start_time'] = Carbon::createFromFormat('m/d/Y g:i A', $request->report_date.' '. $request->start_time);
            $inputs['end_time'] = Carbon::createFromFormat('m/d/Y g:i A', $request->report_date.' '. $request->end_time);
            $inputs['actual_hours'] = round($inputs['start_time']->floatDiffInHours($inputs['end_time']), 2);
            $inputs['created_by'] = auth()->user()->id;

            $timesheet = WorkOrderTimeSheets::create($inputs);

            return response()->json([
                'success' => true,
                'message' => 'New entry added.',
                'data' => [
                    'id' => $timesheet->id,
                    'employee_full_name' => $employee->full_name ?? '',
                    'html_start' => $timesheet->html_start,
                    'html_finish' => $timesheet->html_finish,
                    'actual_hours' => $timesheet->actual_hours,
                ],
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.',
            ]);
        }
    }

    public function ajaxTimeSheetDestroy(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['timesheet_id']), [
                    'timesheet_id' => 'required|positive',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ]);
            }

            WorkorderTimesheets::destroy($request->timesheet_id);

            return response()->json([
                'success' => true,
                'message' => 'Entry deleted.',
                'timesheet_id' => $request->timesheet_id,
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.',
            ]);
        }
    }


}
