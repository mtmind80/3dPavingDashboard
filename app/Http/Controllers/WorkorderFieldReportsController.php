<?php

namespace App\Http\Controllers;

use App\Helpers\Currency;
use App\Http\Requests\SearchRequest;
use App\Models\AcceptedDocuments;
use App\Models\Contractor;
use App\Models\Equipment;
use App\Models\LaborRate;
use App\Models\Material;
use App\Models\Proposal;
use App\Models\ProposalDetailSubcontractor;
use App\Models\ProposalDetailEquipment;
use App\Models\ProposalMaterial;
use App\Models\ServiceCategory;
use App\Models\StripingService;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\ProposalDetail;
use App\Models\VehicleType;
use App\Models\WorkorderAdditionalCost;
use App\Models\WorkorderEquipment;
use App\Models\WorkorderFieldReport;
use App\Models\WorkorderMaterial;
use App\Models\WorkorderSubcontractor;
use App\Models\WorkorderTimesheets;
use App\Models\WorkorderVehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Validator;

class WorkorderFieldReportsController extends Controller
{
    public function index($proposalDetailId, Request $request)
    {
        if (! $proposalDetail = ProposalDetail::with(['proposal'])->find($proposalDetailId)) {
            return redirect()->back()->with('error', 'Proposal details not found.');
        }

        $needle = $request->needle ?? null;
        $perPage = $request->perPage ?? 10;

        $fieldReports = WorkorderFieldReport::forProposalDetail($proposalDetailId)
            ->search($needle)
            ->sortable('report_date', 'DESC')
            ->paginate($perPage);

        $data = [
            'proposalDetail' => $proposalDetail,
            'proposal' => $proposalDetail->proposal,
            'fieldReports' => $fieldReports,
            'needle' => $needle,
        ];

        return view('workorders.field_report.index', $data);
    }

    public function search(SearchRequest $request)
    {
        return $this->index($request);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->only(['proposal_id', 'proposal_detail_id', 'report_date']), [
                'proposal_id' => 'required|positive',
                'proposal_detail_id' => 'positive|positive',
                'report_date' => 'required|usDate',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid date');
        }

        if (! $fieldReport = WorkorderFieldReport::forProposalDetail($request->proposal_detail_id)->first()) {
            $inputs = $validator->validated();

            $inputs['report_date'] = Carbon::createFromFormat('m/d/Y', $inputs['report_date']);;
            $inputs['created_by'] = auth()->user()->id;

            $fieldReport = WorkorderFieldReport::create($inputs);
        }

        return redirect()->route('workorder_field_report_details', ['workorder_field_report_id' => $fieldReport->id]);
    }

    public function details($fieldReportId, Request $request)
    {
        $fieldReport = WorkorderFieldReport
            ::with([
                'proposal',
                'proposalDetails',
                'vehicles',
                'equipments',
                'subcontractors' ,
                'additionalCosts',
                'timesheets' => fn($q) => $q->with(['employee' => fn($w) => $w->orderBy('fname')->orderBy('lname')]),
            ])
            ->find($fieldReportId);

        $data = [
            'fieldReport' => $fieldReport,
            'returnTo' => route('workorder_field_report_list', ['proposal_detail_id' => $fieldReport->proposal_detail_id]),
            'currentUrl' => route('workorder_field_report_details', ['workorder_field_report_id' => $fieldReport->id]),
            'tabSelected' => 'services',
            'employeesCB' => User::employeesCB(['0' => 'Select employee']),
            'equipmentCB' => Equipment::equipmentCB(['0' => 'Select equipment']),
            'materialsCB' => Material::materialsCB(['0' => 'Select material']),
            'vehiclesCB' => Vehicle::vehiclesCB(['0' => 'Select vehicle']),
            'contractorsCB' => Contractor::contractorsCB(['0' => 'Select contractor']),
        ];

        return view('workorders.field_report.details', $data);
    }

    // Timesheets

    public function ajaxTimeSheetStore(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $inputs = $request->only(['proposal_id', 'proposal_detail_id', 'workorder_field_report_id', 'report_date_str', 'employee_id', 'start_time', 'end_time']);

            $validator = Validator::make(
                $inputs, [
                    'proposal_id' => 'required|positive',
                    'proposal_detail_id' => 'required|positive',
                    'workorder_field_report_id' => 'required|positive',
                    'report_date_str' => 'required|usDate',
                    'employee_id' => 'required|positive',
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
            $inputs['start_time'] = Carbon::createFromFormat('m/d/Y g:i A', $request->report_date_str . ' ' . $request->start_time);
            $inputs['end_time'] = Carbon::createFromFormat('m/d/Y g:i A', $request->report_date_str . ' ' . $request->end_time);
            $inputs['actual_hours'] = round($inputs['start_time']->floatDiffInHours($inputs['end_time']), 2);
            $inputs['created_by'] = auth()->user()->id;

            WorkOrderTimeSheets::create($inputs);

            $timeSheets = WorkorderTimesheets
                ::where('workorder_field_report_id', $request->workorder_field_report_id)
                ->with(['employee' => fn($q) => $q->orderBy('fname')->orderBy('lname')])
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'New timesheet entry added.',
                'html' => view('workorders.field_report.timesheet._list', ['timeSheets' => $timeSheets])->render(),
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
                $request->only(['workorder_field_report_id', 'timesheet_id']), [
                    'workorder_field_report_id' => 'required|positive',
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
                'message' => 'Timesheet deleted.',
                'timesheet_id' => $request->timesheet_id,
                'total' => WorkorderTimesheets::where('workorder_field_report_id', $request->workorder_field_report_id)->count(),
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.',
            ]);
        }
    }

    // equipment

    public function ajaxEquipmentStore(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $inputs = $request->only(['proposal_id', 'proposal_detail_id', 'workorder_field_report_id', 'report_date_str', 'equipment_id', 'hours', 'number_of_units']);

            $validator = Validator::make(
                $inputs, [
                    'proposal_id' => 'required|positive',
                    'proposal_detail_id' => 'required|positive',
                    'workorder_field_report_id' => 'required|positive',
                    'report_date_str' => 'required|usDate',
                    'equipment_id' => 'required|positive',
                    'hours' => 'required|float',
                    'number_of_units' => 'required|positive',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ]);
            }

            $equipment = Equipment::find($request->equipment_id);

            $inputs['name'] = $equipment->name;
            $inputs['rate_type'] = $equipment->rate_type;
            $inputs['rate'] = $equipment->rate;
            $inputs['created_by'] = auth()->user()->id;

            WorkOrderEquipment::create($inputs);

            $equipments = WorkorderEquipment
                ::where('workorder_field_report_id', $request->workorder_field_report_id)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'New equipment added.',
                'html' => view('workorders.field_report.equipment._list', ['equipments' => $equipments])->render(),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.',
            ]);
        }
    }

    public function ajaxEquipmentDestroy(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['workorder_field_report_id', 'equipment_id']), [
                    'workorder_field_report_id' => 'required|positive',
                    'equipment_id' => 'required|positive',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ]);
            }

            WorkorderEquipment::destroy($request->equipment_id);

            return response()->json([
                'success' => true,
                'message' => 'Entry deleted.',
                'equipment_id' => $request->equipment_id,
                'total' => WorkorderEquipment::where('workorder_field_report_id', $request->workorder_field_report_id)->count(),
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.',
            ]);
        }
    }

    // materials

    public function ajaxMaterialStore(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $inputs = $request->only(['proposal_id', 'proposal_detail_id', 'workorder_field_report_id', 'report_date_str', 'material_id', 'quantity', 'note']);

            $validator = Validator::make(
                $inputs, [
                    'proposal_id' => 'required|positive',
                    'proposal_detail_id' => 'required|positive',
                    'workorder_field_report_id' => 'required|positive',
                    'report_date_str' => 'required|usDate',
                    'material_id' => 'required|positive',
                    'quantity' => 'required|float',
                    'note' => 'nullable|text',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ]);
            }

            $material = Material::find($request->material_id);

            $inputs['name'] = $material->name;
            $inputs['cost'] = $material->cost;
            $inputs['created_by'] = auth()->user()->id;

            WorkOrderMaterial::create($inputs);

            $materials = WorkorderMaterial
                ::where('workorder_field_report_id', $request->workorder_field_report_id)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'New material added.',
                'html' => view('workorders.field_report.material._list', ['materials' => $materials])->render(),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.',
            ]);
        }
    }

    public function ajaxMaterialDestroy(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['workorder_field_report_id', 'material_id']), [
                    'workorder_field_report_id' => 'required|positive',
                    'material_id' => 'required|positive',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ]);
            }

            WorkorderMaterial::destroy($request->material_id);

            return response()->json([
                'success' => true,
                'message' => 'Material deleted.',
                'material_id' => $request->material_id,
                'total' => WorkorderMaterial::where('workorder_field_report_id', $request->workorder_field_report_id)->count(),
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.',
            ]);
        }
    }

    // vehicles

    public function ajaxVehicleStore(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $inputs = $request->only(['proposal_id', 'proposal_detail_id', 'workorder_field_report_id', 'report_date_str', 'vehicle_id', 'number_of_vehicles', 'note']);

            $validator = Validator::make(
                $inputs, [
                    'proposal_id' => 'required|positive',
                    'proposal_detail_id' => 'required|positive',
                    'workorder_field_report_id' => 'required|positive',
                    'report_date_str' => 'required|usDate',
                    'vehicle_id' => 'required|positive',
                    'number_of_vehicles' => 'required|float',
                    'note' => 'nullable|text',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ]);
            }

            $vehicle = Vehicle::find($request->vehicle_id);

            $inputs['vehicle_name'] = $vehicle->name;
            $inputs['created_by'] = auth()->user()->id;

            WorkOrderVehicle::create($inputs);

            $vehicles = WorkorderVehicle
                ::where('workorder_field_report_id', $request->workorder_field_report_id)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'New vehicle added.',
                'html' => view('workorders.field_report.vehicle._list', ['vehicles' => $vehicles])->render(),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.',
            ]);
        }
    }

    public function ajaxVehicleDestroy(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['workorder_field_report_id', 'vehicle_id']), [
                    'workorder_field_report_id' => 'required|positive',
                    'vehicle_id' => 'required|positive',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ]);
            }

            WorkorderVehicle::destroy($request->vehicle_id);

            return response()->json([
                'success' => true,
                'message' => 'Vehicle deleted.',
                'vehicle_id' => $request->vehicle_id,
                'total' => WorkorderVehicle::where('workorder_field_report_id', $request->workorder_field_report_id)->count(),
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.',
            ]);
        }
    }

    // subcontractors

    public function ajaxSubcontractorStore(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $inputs = $request->only(['proposal_id', 'proposal_detail_id', 'workorder_field_report_id', 'report_date_str', 'contractor_id', 'cost', 'description']);

            $validator = Validator::make(
                $inputs, [
                    'proposal_id' => 'required|positive',
                    'proposal_detail_id' => 'required|positive',
                    'workorder_field_report_id' => 'required|positive',
                    'report_date_str' => 'required|usDate',
                    'contractor_id' => 'required|positive',
                    'cost' => 'required|float',
                    'description' => 'required|text',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ]);
            }

            $inputs['created_by'] = auth()->user()->id;

            WorkOrderSubcontractor::create($inputs);

            $subcontractors = WorkorderSubcontractor
                ::where('workorder_field_report_id', $request->workorder_field_report_id)
                ->with(['subcontractor' => fn($q) => $q->orderBy('name')])
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'New subcontractor added.',
                'html' => view('workorders.field_report.subcontractor._list', ['subcontractors' => $subcontractors])->render(),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.',
            ]);
        }
    }

    public function ajaxSubcontractorDestroy(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['workorder_field_report_id', 'subcontractor_id']), [
                    'workorder_field_report_id' => 'required|positive',
                    'subcontractor_id' => 'required|positive',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ]);
            }

            WorkorderSubcontractor::destroy($request->subcontractor_id);

            return response()->json([
                'success' => true,
                'message' => 'Subcontractor deleted.',
                'subcontractor_id' => $request->subcontractor_id,
                'total' => WorkorderSubcontractor::where('workorder_field_report_id', $request->workorder_field_report_id)->count(),
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.',
            ]);
        }
    }

    // additionalCosts

    public function ajaxAdditionalCostStore(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $inputs = $request->only(['proposal_id', 'proposal_detail_id', 'workorder_field_report_id', 'report_date_str', 'cost', 'description']);

            $validator = Validator::make(
                $inputs, [
                    'proposal_id' => 'required|positive',
                    'proposal_detail_id' => 'required|positive',
                    'workorder_field_report_id' => 'required|positive',
                    'report_date_str' => 'required|usDate',
                    'cost' => 'required|float',
                    'description' => 'required|text',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ]);
            }

            $inputs['created_by'] = auth()->user()->id;

            WorkOrderAdditionalCost::create($inputs);

            $additionalCosts = WorkorderAdditionalCost
                ::where('workorder_field_report_id', $request->workorder_field_report_id)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'New additional cost added.',
                'html' => view('workorders.field_report.additional_cost._list', ['additionalCosts' => $additionalCosts])->render(),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.',
            ]);
        }
    }

    public function ajaxAdditionalCostDestroy(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['workorder_field_report_id', 'additional_cost_id']), [
                    'workorder_field_report_id' => 'required|positive',
                    'additional_cost_id' => 'required|positive',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ]);
            }

            WorkorderAdditionalCost::destroy($request->additional_cost_id);

            return response()->json([
                'success' => true,
                'message' => 'Additional cost deleted.',
                'additional_cost_id' => $request->additional_cost_id,
                'total' => WorkorderAdditionalCost::where('workorder_field_report_id', $request->workorder_field_report_id)->count(),
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.',
            ]);
        }
    }

    public function view_serviceMIKE($proposal_id, $id)
    {
        if (!$proposalDetail = ProposalDetail::with([
            'proposal' => function ($q) {
                $q->with(['contact']);
            },
            'service',
            'striping',
            'location',
            'vehicles',
            'equipment' => function ($w) {
                $w->with(['equipment']);
            },
            'labor',
            'additionalCosts',
            'subcontractors' => function ($e) {
                $e->with(['contractor']);
            },
            'service' => function ($r) {
                $r->with(['category']);
            },
        ])->find($id)) {
            return view('pages-404');
        }

        $proposalDetailSubcontractors = ProposalDetailSubcontractor::where('proposal_detail_id', $id)->where('accepted', '=', 1)->with('contractor')->get()->toArray();

        $proposalEquipment = ProposalDetailEquipment::where('proposal_detail_id', $id)->with('equipment')->get()->toArray();

        $asphaltMaterials = ProposalMaterial::where('proposal_id', $proposal_id)->byServiceCategory(1);
        $rockMaterials = ProposalMaterial::where('proposal_id', $proposal_id)->byServiceCategory(7);
        $sealcoatMaterials = ProposalMaterial::where('proposal_id', $proposal_id)->byServiceCategory(8);
        $color = ServiceCategory::where('id', '=', $proposalDetail->service->service_category_id)->first();

//echo "<pre>";
//print_r($proposalEquipment);
//exit();



        $data = [
            'proposalDetailSubcontractors' => $proposalDetailSubcontractors,
            'service_id' => $proposalDetail->service->id,
            'service_cat' => $proposalDetail->service->service_category_id,
            'header_name' => 'Build Service Estimate',
            'proposalDetail' => $proposalDetail,
            'proposal' => $proposalDetail->proposal,
            'contact' => $proposalDetail->proposal->contact,
            'sealcoatMaterials' => $sealcoatMaterials,
            'rockMaterials' => $rockMaterials,
            'asphaltMaterials' => $asphaltMaterials,
            'striping' => $proposalDetail->striping,
            'service' => $proposalDetail->service,
            'color' => $color,
            'service_category_name' => $proposalDetail->service->category->name,
            'proposalEquipment' => $proposalEquipment,
            'vehiclesCB' => VehicleType::get(),
            'laborCB' => LaborRate::LaborWithRatesCB(['0' => 'Select labor']),
        ];

        if ($proposalDetail->service->id == 18) { // striping costs

            return view('workorders.field_report.striping', $data);

        }

        return view('workorders.field_report.view_service', $data);


    }

    public function viewService($proposal_id, $id): View
    {
        if (! $proposalDetail = ProposalDetail::with([
            'proposal' => function ($q) {
                $q->with(['contact', 'materials' => fn($q) => $q->with(['material']), 'materialsAsphalt', 'materialsRock', 'materialsSealCoat']);
            },
            'service' => function ($r) {
                $r->with(['category']);
            },
            'striping',
            'location',
            'vehicles',
            'equipment' => function ($w) {
                $w->with(['equipment']);
            },
            'labor',
            'additionalCosts',
            'acceptedSubcontractor' => function ($f) {
                $f->with(['contractor']);
            },
        ])->find($id)) {
            return view('pages-404');
        }

        $stripingServices = StripingService::
            whereHas(
            'services', fn($q) => $q->where('proposal_detail_id', $proposalDetail->id)->where('quantity', '>', 0)
            )
            ->with([
                'services' => fn($q) => $q->where('proposal_detail_id', $proposalDetail->id)->where('quantity', '>', 0)
            ])
            ->orderBy('dsort')
            ->get();

        $data = [
            'proposalDetail' => $proposalDetail,
            'proposal' => $proposalDetail->proposal,
            'service' => $proposalDetail->service,
            'stripingServices' => $stripingServices,
            'location' => $proposalDetail->location,
            'vehicles' => $proposalDetail->vehicles,
            'equipments' => $proposalDetail->equipment,
            'labors' => $proposalDetail->labor,
            'additionalCosts' => $proposalDetail->additionalCosts,
            'acceptedSubcontractor' => $proposalDetail->acceptedSubcontractor,
            'materialsCB' => Material::materialsCB(),

            'vehiclesCB' => VehicleType::get(),
            'laborCB' => LaborRate::LaborWithRatesCB(['0' => 'Select labor']),
        ];

        return view($proposalDetail->services_id === 18
            ? 'workorders.field_report.view_striping'
            : 'workorders.field_report.view_service', $data);
    }

}
