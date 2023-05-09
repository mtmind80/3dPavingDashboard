<?php

namespace App\Http\Controllers\Reports;

use App\Exports\LeadsExport;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class LeadsReportController extends Controller
{
    public function index()
    {
        $yearsCB = Lead::existingLeadsYearsCB();

        $data = [
            'yearsCB' => $yearsCB,
            'rows'    => [],
        ];

        return view('reports.leads', $data);
    }

    public function ajaxView(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['year']), [
                    'year' => 'required|year',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error'   => $validator->messages()->first(),
                ]);
            }

            $leadsArray = Lead::getLeads($request->year);

            if (!empty($leadsArray)) {
                $response = [
                    'success' => true,
                    'rows'    => $leadsArray,
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
            $request->only(['year']), [
                'year' => 'required|year',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->messages()->first());
        }

        $leadsArray = Lead::getLeads($request->year);

        if (!empty($leadsArray)) {

            ini_set('max_execution_time', 300);

            $reportFileName = 'Leads_report_' . $request->year;
            $title = 'Leads Report - '.$request->year;

            return Excel::download(new LeadsExport($leadsArray, $request->year, $title), $reportFileName . '.xlsx');
        } else {
            return redirect()->back()->with('error', 'No info found.');
        }
    }

}
