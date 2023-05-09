<?php

namespace App\Http\Controllers\Reports;

use App\Exports\SalesExport;
use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public $forms = [
        "sales"    => "sales",
        "activity" => "activity",
        "labor"    => "labor",
    ];

    public function index(Request $request)
    {
        $data = [];

        return view('reports.index', $data);
    }

    public function showForm($name)
    {
        switch ($name) {
            case 'sales':
                return $this->salesReport();
            break;
            default:
                $data = ["name" => $name];
                $view = "reports." . $this->forms[$name];

                return view($view, $data);
            break;
        }
    }

    public function salesReport()
    {
        $radios = [
            ['label' => '1Q <span class=hint>(Jan1-Mar31)</span>', 'value' => 1],
            ['label' => '2Q <span class=hint>(Apr1-Jun30)</span>', 'value' => 2],
            ['label' => '3Q <span class=hint>(Jul1-Sep30)</span>', 'value' => 3],
            ['label' => '4Q <span class=hint>(Oct1-Dec31)</span>', 'value' => 4],
        ];

        $yearsCB = WorkOrder::existingSalesYearsCB();
        $firstYear = end($yearsCB);
        $minDate = $firstYear . '-01-01';

        $data = [
            'radios'         => $radios,
            'yearsCB'        => WorkOrder::existingSalesYearsCB(),
            'salespersonsCB' => WorkOrder::salesPersonsCB([null => 'Select Sales Person']),
            'minDate'        => $minDate,
            'rows'           => [],
        ];

        return view('reports.sales', $data);
    }


    public function ajaxViewReport(Request $request)
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

            $result = WorkOrder::getSales($request->from_date, $request->to_date, $request->salesperson_id);

            if (!empty($result['rows'])) {
                $response = [
                    'success'     => true,
                    'rows'        => $result['rows'],
                    'global_cost' => $result['global_cost'],
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

    public function exportReport(Request $request)
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

        $result = WorkOrder::getSales($request->from_date, $request->to_date, $request->salesperson_id);

        if (!empty($result['rows'])) {

            ini_set('max_execution_time', 300);

            $reportFileName = 'Sales_report_' . $request->from_date . '_' . $request->to_date;
            $title = 'Sales Report';

            return Excel::download(new SalesExport($result['rows'], $result['global_cost'], $title), $reportFileName . '.xlsx');
        } else {
            return redirect()->back()->with('error', 'No info found.');
        }
    }

}
