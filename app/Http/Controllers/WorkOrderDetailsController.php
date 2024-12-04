<?php

namespace App\Http\Controllers;

use App\Helpers\Currency;
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
use App\Models\User;
use App\Models\Vehicle;
use App\Models\ProposalDetail;
use App\Models\VehicleType;
use App\Models\WorkorderEquipment;
use App\Models\WorkorderMaterial;
use App\Models\WorkorderSubcontractor;
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
            'equipment',
            'subcontractors',
        ])->find($proposal_detail_id)
        ) {
            abort(404);
        }

        // time sheets
        $timeSheets = WorkorderTimesheets::where('proposal_detail_id', $proposal_detail_id)
            ->with(['employee' => fn($q) => $q->orderBy('fname')->orderBy('lname')])
            ->orderBy('report_date')
            ->get();

        // equipment
        $equipments = WorkorderEquipment::where('proposal_detail_id', $proposal_detail_id)
            ->orderBy('report_date')
            ->get();

        // materials
        $materials = WorkorderMaterial::where('proposal_detail_id', $proposal_detail_id)
            ->orderBy('report_date')
            ->get();

        // vehicles
        $vehicles = WorkorderVehicle::where('proposal_detail_id', $proposal_detail_id)
            ->orderBy('report_date')
            ->get();

        // subcontractors
        $subcontractors = WorkorderSubcontractor::where('proposal_detail_id', $proposal_detail_id)
            ->with(['subcontractor' => fn($q) => $q->orderBy('name')])
            ->orderBy('report_date')
            ->get();

        $data = [
            'today' => now(config('app.timezone')),
            'proposalDetail' => $proposalDetail,

            'returnTo' => route('show_workorder', ['id' => $proposalDetail->proposal_id]),
            'currentUrl' => route('workorder_details', ['proposal_detail_id' => $proposalDetail->id]),
            'tabSelected' => 'services',

            // time sheets
            'timeSheets' => $timeSheets ?? null,
            'employeesCB' => User::employeesCB(['0' => 'Select employee']),
            // equipment
            'equipments' => $equipments ?? null,
            'equipmentCB' => Equipment::equipmentCB(['0' => 'Select equipment']),
            // materials
            'materials' => $materials ?? null,
            'materialsCB' => Material::materialsCB(['0' => 'Select material']),
            // vehicles:
            'vehicles' => $vehicles ?? null,
            'vehiclesCB' => Vehicle::vehiclesCB(['0' => 'Select vehicle']),
            // subcontractors:
            'subcontractors' => $subcontractors ?? null,
            'contractorsCB' => Contractor::contractorsCB(['0' => 'Select contractor']),
        ];

        return view('workorders.field_report', $data);
    }

    // Timesheets

    public function ajaxTimeSheetStore(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $inputs = $request->only(['report_date', 'proposal_id', 'proposal_detail_id', 'employee_id', 'start_time', 'end_time']);

            $validator = Validator::make(
                $inputs, [
                    'report_date' => 'required|date',
                    'proposal_id' => 'required|positive',
                    'proposal_detail_id' => 'required|positive',
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
            $inputs['report_date'] = Carbon::createFromFormat('m/d/Y', $request->report_date);
            $inputs['start_time'] = Carbon::createFromFormat('m/d/Y g:i A', $request->report_date . ' ' . $request->start_time);
            $inputs['end_time'] = Carbon::createFromFormat('m/d/Y g:i A', $request->report_date . ' ' . $request->end_time);
            $inputs['actual_hours'] = round($inputs['start_time']->floatDiffInHours($inputs['end_time']), 2);
            $inputs['created_by'] = auth()->user()->id;

            WorkOrderTimeSheets::create($inputs);

            $timeSheets = WorkorderTimesheets::where('proposal_detail_id', $request->proposal_detail_id)
                ->with(['employee' => fn($q) => $q->orderBy('fname')->orderBy('lname')])
                ->orderBy('report_date')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'New timesheet added.',
                'html' => view('workorders.timesheet._list', ['timeSheets' => $timeSheets])->render(),
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
                'message' => 'Timesheet deleted.',
                'timesheet_id' => $request->timesheet_id,
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
            $inputs = $request->only(['report_date', 'proposal_id', 'proposal_detail_id', 'equipment_id', 'hours', 'number_of_units']);

            $validator = Validator::make(
                $inputs, [
                    'report_date' => 'required|date',
                    'proposal_id' => 'required|positive',
                    'proposal_detail_id' => 'required|positive',
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

            $equipments = WorkorderEquipment::where('proposal_detail_id', $request->proposal_detail_id)
                ->orderBy('report_date')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'New equipment added.',
                'html' => view('workorders.equipment._list', ['equipments' => $equipments])->render(),
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
                $request->only(['equipment_id']), [
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
            $inputs = $request->only(['report_date', 'proposal_id', 'proposal_detail_id', 'material_id', 'quantity', 'note']);

            $validator = Validator::make(
                $inputs, [
                    'report_date' => 'required|date',
                    'proposal_id' => 'required|positive',
                    'proposal_detail_id' => 'required|positive',
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

            $inputs['report_date'] = Carbon::createFromFormat('m/d/Y', $request->report_date);
            $inputs['name'] = $material->name;
            $inputs['cost'] = $material->cost;
            $inputs['created_by'] = auth()->user()->id;

            WorkOrderMaterial::create($inputs);

            $materials = WorkorderMaterial::where('proposal_detail_id', $request->proposal_detail_id)
                ->orderBy('report_date')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'New material added.',
                'html' => view('workorders.material._list', ['materials' => $materials])->render(),
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
                $request->only(['material_id']), [
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
            $inputs = $request->only(['report_date', 'proposal_id', 'proposal_detail_id', 'vehicle_id', 'number_of_vehicles', 'note']);

            $validator = Validator::make(
                $inputs, [
                    'report_date' => 'required|date',
                    'proposal_id' => 'required|positive',
                    'proposal_detail_id' => 'required|positive',
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

            $inputs['report_date'] = Carbon::createFromFormat('m/d/Y', $request->report_date);
            $inputs['vehicle_name'] = $vehicle->name;
            $inputs['created_by'] = auth()->user()->id;

            WorkOrderVehicle::create($inputs);

            $vehicles = WorkorderVehicle::where('proposal_detail_id', $request->proposal_detail_id)
                ->orderBy('report_date')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'New vehicle added.',
                'html' => view('workorders.vehicle._list', ['vehicles' => $vehicles])->render(),
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
                $request->only(['vehicle_id']), [
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
            $inputs = $request->only(['report_date', 'proposal_id', 'proposal_detail_id', 'contractor_id', 'cost', 'description']);

            $validator = Validator::make(
                $inputs, [
                    'report_date' => 'required|date',
                    'proposal_id' => 'required|positive',
                    'proposal_detail_id' => 'required|positive',
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

            $contractor = Contractor::find($request->contractor_id);

            $inputs['report_date'] = Carbon::createFromFormat('m/d/Y', $request->report_date);
            $inputs['created_by'] = auth()->user()->id;

            WorkOrderSubcontractor::create($inputs);

            $subcontractors = WorkorderSubcontractor::where('proposal_detail_id', $request->proposal_detail_id)
                ->with(['subcontractor' => fn($q) => $q->orderBy('name')])
                ->orderBy('report_date')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'New subcontractor added.',
                'html' => view('workorders.subcontractor._list', ['subcontractors' => $subcontractors])->render(),
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
                $request->only(['subcontractor_id']), [
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
                'total' => WorkorderSubcontractor::where('proposal_detail_id', $request->proposal_detail_id)->count(),
            ]);

        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.',
            ]);
        }
    }



    public function view_service($proposal_id, $id)
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
            //'allowedFileExtensions' => AcceptedDocuments::extensionsStrCid(),
            //'strippingCB' => StripingCost::strippingCB(['0' => 'Select contractor']),
        ];

        if ($proposalDetail->service->id == 18) { // striping costs

            return view('workorders.striping', $data);

        }

        return view('workorders.view_service', $data);


    }


}
