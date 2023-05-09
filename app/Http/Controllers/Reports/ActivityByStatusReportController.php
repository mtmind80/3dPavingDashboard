<?php

namespace App\Http\Controllers\Reports;

use App\Exports\ActivityByStatusExport;
use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ActivityByStatusReportController extends Controller
{
    public function index()
    {
        $radios = [
            ['label' => '1Q <span class=hint>(Jan1-Mar31)</span>', 'value' => 1],
            ['label' => '2Q <span class=hint>(Apr1-Jun30)</span>', 'value' => 2],
            ['label' => '3Q <span class=hint>(Jul1-Sep30)</span>', 'value' => 3],
            ['label' => '4Q <span class=hint>(Oct1-Dec31)</span>', 'value' => 4],
        ];

        $yearsCB = Proposal::existingActivityByStatusYearsCB();
        $firstYear = end($yearsCB);
        $minDate = $firstYear . '-01-01';
        $maxDate = now()->format('Y-m-d');

        $data = [
            'radios'         => $radios,
            'yearsCB'        => $yearsCB,
            'salespersonsCB' => Proposal::salesPersonsCB([null => 'Select Sales Person']),
            'minDate'        => $minDate,
            'maxDate'        => $maxDate,
            'rows'           => [],
        ];

        return view('reports.activity_by_status', $data);
    }

    public function ajaxView(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['from_date', 'to_date']), [
                    'from_date'      => 'required|isoDate',
                    'to_date'        => 'required|isoDate',
                    'salesperson_id' => 'nullable|zeroOrPositive',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error'   => $validator->messages()->first(),
                ]);
            }

            $rows = Proposal::getActivityByStatus($request->from_date, $request->to_date, $request->salesperson_id);

            if (!empty($rows)) {
                $response = [
                    'success'     => true,
                    'rows'        => $rows,
                ];
            } else {
                $response = [
                    'success' => false,
                    'error'   => 'Data not found.',
                ];
            }
        } else {
            $response = [
                'success' => false,
                'error'   => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

    public function export(Request $request)
    {
        $validator = Validator::make(
            $request->only(['from_date', 'to_date', 'salesperson_id']), [
                'from_date'      => 'required|isoDate',
                'to_date'        => 'required|isoDate',
                'salesperson_id' => 'nullable|zeroOrPositive',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->messages()->first());
        }

        $rows = Proposal::getActivityByStatus($request->from_date, $request->to_date, $request->salesperson_id);

        if (!empty($rows)) {

            ini_set('max_execution_time', 300);

            $reportFileName = 'Activity_by_status_report_' . $request->from_date . '_' . $request->to_date;
            $title = 'Activity Status Report';

            return Excel::download(new ActivityByStatusExport($rows, $request->from_date, $request->to_date, $title), $reportFileName . '.xlsx');
        } else {
            return redirect()->back()->with('error', 'No info found.');
        }
    }

}
