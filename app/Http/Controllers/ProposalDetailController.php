<?php

namespace App\Http\Controllers;

use App\Helpers\Currency;
use App\Helpers\ExceptionError;
use App\Models\AcceptedDocuments;
use App\Models\Contractor;
use App\Models\Equipment;
use App\Models\LaborRate;
use App\Models\Material;
use App\Models\Proposal;
use App\Models\ProposalDetail;
use App\Models\ProposalDetailAdditionalCost;
use App\Models\ProposalDetailEquipment;
use App\Models\ProposalDetailLabor;
use App\Models\ProposalDetailStripingService;
use App\Models\ProposalDetailSubcontractor;
use App\Models\ProposalDetailVehicle;
use App\Models\ProposalMaterial;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Http\Requests\ScheduleRequest;
use App\Models\ServiceSchedule;
use App\Models\StripingCost;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Exception;
use Google\Service\AdMob\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Traits\UploadFileTrait;

class ProposalDetailController extends Controller
{
    use UploadFileTrait;

    public function index($id)
    {
        $data['proposal_id'] = $id;

        return view('proposaldetail.index', $data);
    }

    public function create(Request $request, $id)
    {

        $proposal = Proposal::where('id', $id)->first()->toArray();
        $service = Service::where('id', $request['servicecat'])->first()->toArray();
        $new_id = 0;
        //create new service on this proposal
        $proposal_detail = new ProposalDetail();
        $proposal_detail->proposal_id = $id;
        $proposal_detail->services_id = $request['servicecat'];
        $proposal_detail->status_id = 1;
        $proposal_detail->location_id = $proposal['location_id'];
        $proposal_detail->service_name = $service['name'];
        $proposal_detail->service_desc = $service['description'];
        $proposal_detail->proposal_text = $service['service_text_en'];
        //$proposal_detail->dsort = 1;
        $proposal_detail->created_by = auth()->user()->id;

        $proposal_detail->save();
        $new_id = $proposal_detail->id;


        if ($request['servicecat'] == 18 && $new_id) { // save base striping costs for this proposal detail

            //$remove_old = ProposalDetailStripingService::where('proposal_detail_id', '=', $new_id)->delete();

            $striping = StripingCost::with(['service'])->get()->toArray();

            foreach ($striping as $stripe) {

                $proposal_striping_costs = new ProposalDetailStripingService();

                $proposal_striping_costs->proposal_detail_id = $new_id;
                $proposal_striping_costs->striping_service_id = $stripe['striping_service_id'];
                $proposal_striping_costs->description = $stripe['description'];
                $proposal_striping_costs->quantity = 0;
                $proposal_striping_costs->name = $stripe['service']['name'];
                $proposal_striping_costs->cost = $stripe['cost'];
                try {

                    $proposal_striping_costs->save();

                } catch (Exception $e) {
                    Log::error('Failure to almost save', [$e->getMessage()]);
                    return back()->withErrors('Striping not saved');

                }

            }

        }


        return redirect()->route('edit_service', ['proposal_id' => $id, 'id' => $proposal_detail->id]);
    }

    public function saveservice($id)
    {
        $proposal = Proposal::find($id)->first()->toArray();
        $data['proposal'] = $proposal;

        print_r($id);
        exit();
    }

    public function show($proposal_id, $id)
    {
        $proposal = Proposal::find($proposal_id);
        $data['proposal'] = $proposal;
        $service = ProposalDetail::find($id)->first()->toArray();
        $data['service'] = $service;
        $data['headername'] = "Services";
        if (!$service) {
            return view('pages-404');
        }

        if ($proposal['IsEditable']) {
            return view('proposaldetails.build_service', $data);
        }

        return view('proposaldetails.show_service', $data);
    }

    public function edit($proposal_id, $id)
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

        $contact = $proposalDetail->proposal->contact;
        $asphaltMaterials = ProposalMaterial::where('proposal_id', $proposal_id)->byServiceCategory(1);
        $rockMaterials = ProposalMaterial::where('proposal_id', $proposal_id)->byServiceCategory(7);
        $sealcoatMaterials = ProposalMaterial::where('proposal_id', $proposal_id)->byServiceCategory(8);
        $materialsCB = ProposalMaterial::where('proposal_id', $proposal_id)->pluck('cost', 'material_id')->toArray();

        $color = ServiceCategory::where('id', '=', $proposalDetail->service->service_category_id)->first();

        $data = [
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
            'equipmentCollection' => Equipment::available()->orderBy('name')->get(),
            'materialsCB' => $materialsCB,
            'vehiclesCB' => VehicleType::get(),
            'laborCB' => LaborRate::LaborWithRatesCB(['0' => 'Select labor']),
            'contractorsCB' => Contractor::contractorsCB(['0' => 'Select contractor']),
            'contractors' => Contractor::orderBy('name')->get(),
            'allowedFileExtensions' => AcceptedDocuments::extensionsStrCid(),
            //'strippingCB' => StripingCost::strippingCB(['0' => 'Select contractor']),
            'typesCB' => ['0' => 'Select type', 'Dump Fee' => 'Dump Fee', 'Other' => 'Other'],
        ];

//            'vehiclesCB' => Vehicle::vehiclesCB(['0' => 'Select vehicle']),

        if ($proposalDetail->service->id == 18) { // striping costs

            //$sorted = $data['striping']->sortBy(['service.dsort', 'description']);
            //$data['striping'] = $sorted;
            return view('estimator.striping', $data);

        }

        return view('estimator.index', $data);
    }


    public function checkform(Request $request)
    {

        $formfields = $request->all();

        $proposal_detail = ProposalDetail::where('id', '=', $formfields['id'])->first();
        unset($formfields['_token']);
        unset ($formfields['id']);
        if(!isset($formfields['bill_after']))  {
            $formfields['bill_after'] = 0;
        }
        //unset ($formfields['material_cost']);
        //print_r($formfields);
        //exit();

        $proposal_detail->update($formfields);
        \Session::flash('success', 'Service was saved!');
        if ($formfields['stayorleave'] == 'true') {
            return redirect()->route('show_proposal', ['id' => $formfields['proposal_id']]);

        }
        return redirect()->back();

    }

    // Update from estimator form

    public function ajaxUpdate(Request $request)
    {
        if (!$request->isMethod('post') || !$request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.',
            ]);

        }
        $validator = Validator::make(
            $request->only(['proposal_detail_id', 'services_id', 'service_category_id']), [
                'proposal_detail_id' => 'required|positive',
                'services_id' => 'required|positive',
                'service_category_id' => 'required|positive',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->first(),
            ]);
        }

        if (!$proposalDetail = ProposalDetail::find($request->proposal_detail_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Proposal details not found.',
            ]);
        }

        $servicesId = (int)$request->services_id;
        $serviceCategoryId = (int)$request->service_category_id;
        $inputs = null;

        switch ($serviceCategoryId) {
            // Asphalt
            case 1:
                if ($servicesId === 19) {
                    $inputs = $request->only(['cost_per_day', 'locations', 'square_feet', 'depth', 'days']);
                    $validator = Validator::make(
                        $inputs, [
                            'cost_per_day' => 'required|float',
                            'locations' => 'required|float',
                            'square_feet' => 'required|float',
                            'depth' => 'required|float',
                            'days' => 'required|float',
                        ]
                    );
                } else {    // 3, 4, 5 or 22
                    $inputs = $request->only(['square_feet', 'depth', 'cost_per_day', 'locations']);
                    $validator = Validator::make(
                        $inputs, [
                            'square_feet' => 'required|float',
                            'depth' => 'required|float',
                            'cost_per_day' => 'required|float',
                            'locations' => 'required|float',
                        ]
                    );
                }
                break;

            case 2:
                if ($servicesId < 12) {
                    $inputs = $request->only(['linear_feet', 'locations', 'cost_per_linear_feet']);
                    $validator = Validator::make(
                        $inputs, [
                            'linear_feet' => 'required|float',
                            'locations' => 'required|float',
                            'cost_per_linear_feet' => 'required|float',
                        ]
                    );
                } else {    // > 12
                    $inputs = $request->only(['square_feet', 'depth', 'locations', 'cost_per_linear_feet']);
                    $validator = Validator::make(
                        $inputs, [
                            'square_feet' => 'required|float',
                            'depth' => 'required|float',
                            'locations' => 'required|float',
                            'cost_per_linear_feet' => 'required|float',
                        ]
                    );
                }
                break;

            // Drainage and Catchbasins
            case 3:
                $inputs = $request->only(['catchbasins', 'cost_per_day', 'alt_desc']);
                $validator = Validator::make(
                    $inputs, [
                        'catchbasins' => 'required|integer',
                        'cost_per_day' => 'required|float',
                        'alt_desc' => 'required|float',
                    ]
                );
                break;

            // Excavation
            case 4:
                $inputs = $request->only(['square_feet', 'depth']);
                $validator = Validator::make(
                    $inputs, [
                        'square_feet' => 'required|float',
                        'depth' => 'required|float',
                    ]
                );
                break;

            // Other
            case 5:
                $inputs = $request->only(['cost_per_day', 'alt_desc', 'locations']);
                $validator = Validator::make(
                    $inputs, [
                        'cost_per_day' => 'required|float',
                        'alt_desc' => 'required|float',
                        'locations' => 'required|float',
                    ]
                );
                break;

            // Paver Brick
            case 6:
                $inputs = $request->only(['cost_per_day', 'square_feet', 'tons', 'alt_desc']);
                $validator = Validator::make(
                    $inputs, [
                        'cost_per_day' => 'required|float',
                        'square_feet' => 'required|float',
                        'tons' => 'required|float',
                        'alt_desc' => 'required|float',
                    ]
                );
                break;

            // Rock
            case 7:
                $inputs = $request->only(['square_feet', 'depth', 'cost_per_day']);
                $validator = Validator::make(
                    $inputs, [
                        'square_feet' => 'required|float',
                        'depth' => 'required|float',
                        'cost_per_day' => 'required|float',
                    ]
                );
                break;

            // Seal Coating  these are the user input fields that need to be filled in validated
            case 8:
                $inputs = $request->only(['square_feet', 'yield', 'primer', 'fast_set', 'phases']);
                $validator = Validator::make(
                    $inputs, [
                        'square_feet' => 'required|float',
                        'yield' => 'required|float',
                        'primer' => 'required|float',
                        'fast_set' => 'required|float',
                        'phases' => 'required|float',
                    ]
                );
                break;

            // Striping  not used for this servic
            case 9:
                //
                break;

            // Sub Contractor
            case 10:
                $inputs = $request->only(['additive', 'cost_per_day', 'alt_desc', 'contractor_id']);
                $validator = Validator::make(
                    $inputs, [
                        'additive' => 'required|float',
                        'cost_per_day' => 'required|float',
                        'alt_desc' => 'required|float',
                        'contractor_id' => 'required|float',
                    ]
                );
                break;
        }
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->first(),
            ]);
        }

        if (is_null($inputs)) {
            return response()->json([
                'success' => false,
                'message' => 'Nothing to update.',
            ]);
        }

        try {
            $proposalDetail->update($inputs);

            return response()->json([
                'success' => true,
                'message' => 'Proposal detail updated.',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => app()->environment() === 'local' ? $e->getMessage() : 'Exception error',
            ]);
        }
    }

    // To be updated

    public function ajaxCalculateCombinedCosting(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_id', 'profit', 'overhead', 'break_even']), [
                    'proposal_detail_id' => 'required|positive',
                    'profit' => 'required|float',
                    'overhead' => 'required|float',
                    'break_even' => 'required|float',
                ]
            );
            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    $profit = (float)$request->profit;
                    $overhead = (float)$request->overhead;
                    $break_even = (float)$request->break_even;

                    $combinedCosting = $profit * $overhead * $break_even;

                    $response = [
                        'success' => true,
                        'message' => 'Combined costing updated.',
                        'data' => [
                            'combined_costing' => $combinedCosting,
                            'formatted_combined_costing' => Currency::format($combinedCosting),
                        ],
                    ];
                } catch (Exception $e) {
                    if (env('APP_ENV') === 'local') {
                        $response = [
                            'success' => false,
                            'message' => $e->getMessage(),
                        ];
                    } else {
                        Log::error(get_class() . ' - ' . $e->getMessage());
                        $response = [
                            'success' => false,
                            'message' => 'Exception error',
                        ];
                    }
                }
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

    public function ajaxVehicleAddOrUpdate(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_id', 'vehicle_id', 'number_of_vehicles', 'days', 'hours', 'proposal_detail_vehicle_id']), [
                    'proposal_detail_id' => 'required|positive',
                    'vehicle_id' => 'required|positive',
                    'number_of_vehicles' => 'required|positive',
                    'days' => 'required|float',
                    'hours' => 'required|float',
                    'proposal_detail_vehicle_id' => 'nullable|positive',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if (!$vehicle = VehicleType::find($request->vehicle_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Vehicle not found.',
                        ];
                    } else {
                        $ratePerHour = (float)($vehicle->rate ?? 0);
                        $vehicleName = $vehicle->name;
                        $numberOfVehicles = (integer)$request->number_of_vehicles;
                        $days = (float)$request->days;
                        $hours = (float)$request->hours;

                        $data = [
                            'vehicle_id' => $request->vehicle_id,
                            'vehicle_name' => $vehicleName,
                            'number_of_vehicles' => $numberOfVehicles,
                            'days' => $days,
                            'hours' => $hours,
                            'rate_per_hour' => $ratePerHour,
                        ];

                        if (!empty($request->proposal_detail_vehicle_id)) {
                            // update
                            $proposalDetailVehicle = ProposalDetailVehicle::find($request->proposal_detail_vehicle_id);
                            $proposalDetailVehicle->update($data);
                            $msg = 'Vehicle updated.';
                        } else {
                            // add new
                            $data['proposal_detail_id'] = $request->proposal_detail_id;
                            $data['created_by'] = auth()->user()->id;
                            ProposalDetailVehicle::create($data);
                            $msg = 'Vehicle added.';
                        }

                        $proposalDetailVehicles = ProposalDetailVehicle::where('proposal_detail_id', $request->proposal_detail_id)->get();

                        $response = [
                            'success' => true,
                            'message' => $msg,
                            'html' => view('estimator._form_service_vehicles', ['vehicles' => $proposalDetailVehicles])->render(),
                        ];
                    }
                } catch (Exception $e) {
                    $response = ExceptionError::handleAjaxError($e);
                }
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

    public function ajaxVehicleRemove(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_id', 'proposal_detail_vehicle_id']), [
                    'proposal_detail_id' => 'required|positive',
                    'proposal_detail_vehicle_id' => 'required|positive',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if (!$ProposalDetailVehicle = ProposalDetailVehicle::find($request->proposal_detail_vehicle_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Proposal detail vehicle not found.',
                        ];
                    } else {
                        $ProposalDetailVehicle->delete();

                        $vehicles = ProposalDetailVehicle::where('proposal_detail_id', $request->proposal_detail_id)->get();

                        $response = [
                            'success' => true,
                            'message' => 'Vehicle removed.',
                            'html' => view('estimator._form_service_vehicles', ['vehicles' => $vehicles])->render(),
                        ];
                    }
                } catch (Exception $e) {
                    $response = ExceptionError::handleAjaxError($e);
                }
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

    public function ajaxEquipmentAddOrUpdate(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_id', 'equipment_id', 'number_of_units', 'days', 'hours', 'equipment_rate_type', 'proposal_detail_equipment_id']), [
                    'proposal_detail_id' => 'required|positive',
                    'equipment_id' => 'required|positive',
                    'number_of_units' => 'required|positive',
                    'days' => 'nullable|float|required_if:equipment_rate_type,per day',
                    'hours' => 'nullable|float|required_if:equipment_rate_type,per hour',
                    'equipment_rate_type' => 'required|in:per day,per hour',
                    'proposal_detail_equipment_id' => 'nullable|positive',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if (!$equipment = Equipment::find($request->equipment_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Equipment not found.',
                        ];
                    } else {
                        $rateType = $equipment->rate_type;
                        $rate = (float)$equipment->rate;
                        $numberOfUnits = (integer)$request->number_of_units;
                        $days = (float)$request->days;
                        $hours = (float)$request->hours;

                        $data = [
                            'proposal_detail_id' => $request->proposal_detail_id,
                            'equipment_id' => $equipment->id,
                            'created_by' => auth()->user()->id,
                            'hours' => $hours,
                            'days' => $days,
                            'number_of_units' => $numberOfUnits,
                            'rate_type' => $rateType,
                            'rate' => $rate,
                        ];

                        if (!empty($request->proposal_detail_equipment_id)) {
                            // update
                            $proposalDetailEquipment = ProposalDetailEquipment::find($request->proposal_detail_equipment_id);
                            $proposalDetailEquipment->update($data);
                            $msg = 'Equipment updated.';
                        } else {
                            // add new
                            $data['proposal_detail_id'] = $request->proposal_detail_id;
                            $data['created_by'] = auth()->user()->id;
                            ProposalDetailEquipment::create($data);
                            $msg = 'Equipment added.';
                        }

                        $proposalDetailEquipment = ProposalDetailEquipment::where('proposal_detail_id', $request->proposal_detail_id)->get();

                        $response = [
                            'success' => true,
                            'message' => $msg,
                            'html' => view('estimator._form_service_equipment', ['equipments' => $proposalDetailEquipment])->render(),
                        ];
                    }
                } catch (Exception $e) {
                    $response = ExceptionError::handleAjaxError($e);
                }
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

    public function ajaxEquipmentRemove(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_id', 'proposal_detail_equipment_id']), [
                    'proposal_detail_id' => 'required|positive',
                    'proposal_detail_equipment_id' => 'required|positive',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if (!$ProposalDetailEquipment = ProposalDetailEquipment::find($request->proposal_detail_equipment_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Proposal detail equipment not found.',
                        ];
                    } else {
                        $ProposalDetailEquipment->delete();

                        $proposalDetailEquipment = ProposalDetailEquipment::where('proposal_detail_id', $request->proposal_detail_id)->get();

                        $response = [
                            'success' => true,
                            'message' => 'Equipment removed.',
                            'html' => view('estimator._form_service_equipment', ['equipments' => $proposalDetailEquipment])->render(),
                        ];
                    }
                } catch (Exception $e) {
                    $response = ExceptionError::handleAjaxError($e);
                }
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

    public function ajaxLaborAddOrUpdate(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_id', 'labor_id', 'number', 'days', 'hours', 'proposal_detail_labor_id']), [
                    'proposal_detail_id' => 'required|positive',
                    'labor_id' => 'required|positive',
                    'number' => 'required|positive',
                    'days' => 'required|float',
                    'hours' => 'required|float',
                    'proposal_detail_labor_id' => 'nullable|positive',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if (!$labor = LaborRate::find($request->labor_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Labor not found.',
                        ];
                    } else {
                        $ratePerHour = (float)$labor->rate;
                        $laborName = $labor->name;
                        $number = (integer)$request->number;
                        $days = (float)$request->days;
                        $hours = (float)$request->hours;

                        $data = [
                            'proposal_detail_id' => $request->proposal_detail_id,
                            'labor_name' => $laborName,
                            'number' => $number,
                            'days' => $days,
                            'hours' => $hours,
                            'rate_per_hour' => $ratePerHour,
                            'created_by' => auth()->user()->id,
                        ];

                        if (!empty($request->proposal_detail_labor_id)) {
                            // update
                            $proposalDetailLabor = ProposalDetailLabor::find($request->proposal_detail_labor_id);
                            $proposalDetailLabor->update($data);
                            $msg = 'Labor updated.';
                        } else {
                            // add new
                            $data['proposal_detail_id'] = $request->proposal_detail_id;
                            $data['created_by'] = auth()->user()->id;
                            ProposalDetailLabor::create($data);
                            $msg = 'Labor added.';
                        }

                        $proposalDetailLabors = ProposalDetailLabor::where('proposal_detail_id', $request->proposal_detail_id)->get();

                        $response = [
                            'success' => true,
                            'message' => $msg,
                            'html' => view('estimator._form_service_labor', ['labors' => $proposalDetailLabors])->render(),
                        ];
                    }
                } catch (Exception $e) {
                    $response = ExceptionError::handleAjaxError($e);
                }
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

    public function ajaxLaborRemove(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_id', 'proposal_detail_labor_id']), [
                    'proposal_detail_id' => 'required|positive',
                    'proposal_detail_labor_id' => 'required|positive',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if (!$ProposalDetailLabor = ProposalDetailLabor::find($request->proposal_detail_labor_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Proposal detail labor not found.',
                        ];
                    } else {
                        $ProposalDetailLabor->delete();

                        $labors = ProposalDetailLabor::where('proposal_detail_id', $request->proposal_detail_id)->get();

                        $response = [
                            'success' => true,
                            'message' => 'Labor removed.',
                            'html' => view('estimator._form_service_labor', ['labors' => $labors])->render(),
                        ];
                    }
                } catch (Exception $e) {
                    $response = ExceptionError::handleAjaxError($e);
                }
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

    public function ajaxAdditionalCostAddOrUpdate(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_id', 'amount', 'type', 'description', 'proposal_detail_additional_cost_id']), [
                    'proposal_detail_id' => 'required|positive',
                    'amount' => 'required|float',
                    'type' => 'required|plainText',
                    'description' => 'required|text',
                    'proposal_detail_additional_cost_id' => 'nullable|positive',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    $type = $request->type;
                    $amount = (float)$request->amount;
                    $description = $request->description;

                    $data = [
                        'proposal_detail_id' => $request->proposal_detail_id,
                        'type' => $type,
                        'amount' => $amount,
                        'description' => $description,
                        'created_by' => auth()->user()->id,
                    ];

                    if (!empty($request->proposal_detail_additional_cost_id)) {
                        // update
                        $proposalAdditionalCost = ProposalDetailAdditionalCost::find($request->proposal_detail_additional_cost_id);
                        $proposalAdditionalCost->update($data);
                        $msg = 'Additional cost updated.';
                    } else {
                        // add new
                        $data['proposal_detail_id'] = $request->proposal_detail_id;
                        $data['created_by'] = auth()->user()->id;
                        ProposalDetailAdditionalCost::create($data);
                        $msg = 'Additional cost added.';
                    }

                    $proposalAdditionalCosts = ProposalDetailAdditionalCost::where('proposal_detail_id', $request->proposal_detail_id)->get();

                    $response = [
                        'success' => true,
                        'message' => $msg,
                        'html' => view('estimator._form_service_additional_costs', ['additionalCosts' => $proposalAdditionalCosts])->render(),
                    ];
                } catch (Exception $e) {
                    $response = ExceptionError::handleAjaxError($e);
                }
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

    public function ajaxAdditionalCostRemove(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_additional_cost_id', 'proposal_detail_id']), [
                    'proposal_detail_id' => 'required|positive',
                    'proposal_detail_additional_cost_id' => 'required|positive',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if (!$proposalAdditionalCost = ProposalDetailAdditionalCost::find($request->proposal_detail_additional_cost_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Proposal detail additional cost not found.',
                        ];
                    } else {
                        $proposalAdditionalCost->delete();

                        $proposalAdditionalCosts = ProposalDetailAdditionalCost::where('proposal_detail_id', $request->proposal_detail_id)->get();

                        $response = [
                            'success' => true,
                            'message' => 'Additional cost removed.',
                            'html' => view('estimator._form_service_additional_costs', ['additionalCosts' => $proposalAdditionalCosts])->render(),
                        ];
                    }
                } catch (Exception $e) {
                    $response = ExceptionError::handleAjaxError($e);
                }
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

    public function ajaxSubcontractorAddNew(Request $request)
    {
        // overhead cost subcontractor_id havebid attached_bid description

        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_id', 'subcontractor_id', 'overhead', 'cost', 'accepted', 'description']), [
                    'proposal_detail_id' => 'required|positive',
                    'subcontractor_id' => 'required|positive',
                    'overhead' => 'required|float|min:0|max:100',
                    'cost' => 'required|float',
                    'accepted' => 'nullable|boolean',
                    'description' => 'nullable|text',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if (!$contractor = Contractor::find($request->subcontractor_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Contractor not found.',
                        ];
                    } else if (ProposalDetailSubcontractor::where('proposal_detail_id', $request->proposal_detail_id)->where('contractor_id', $request->subcontractor_id)->count() > 0) {
                        $response = [
                            'success' => false,
                            'message' => 'Contractor already exists.',
                        ];
                    } else {
                        $overhead = (float)$request->overhead;
                        $cost = (float)$request->cost;
                        $accepted = (boolean)$request->accepted;
                        $description = $request->description;

                        $data = [
                            'proposal_detail_id' => $request->proposal_detail_id,
                            'contractor_id' => $request->subcontractor_id,
                            'cost' => $cost,
                            'overhead' => $overhead,
                            'accepted' => $accepted,
                            'description' => $description,
                            'created_by' => auth()->user()->id,
                        ];

                        $uploadError = '';

                        if ($request->hasFile('attached_bid')) {
                            $destinationPath = 'media/bids/';

                            if (
                                ($result = $this->uploadFile('attached_bid', $destinationPath, [
                                    'unique_name' => true,
                                    'allowed_extensions' => AcceptedDocuments::extensionsStrCid(),
                                    'prefix' => $request->proposal_detail_id,
                                ]))
                                && $result['success'] === true
                            ) {
                                $attachedBid = $result['fileName'];
                                $data['attached_bid'] = $attachedBid;
                            } else {
                                $uploadError = ' Error uploading the file. ' . $result['error'] ?? 'Unknown error.';
                            }
                        }

                        ProposalDetailSubcontractor::create($data);

                        $proposalDetailSubcontractors = ProposalDetailSubcontractor::where('proposal_detail_id', $request->proposal_detail_id)->get();

                        $totalCost = 0;
                        foreach ($proposalDetailSubcontractors as $subcontractor) {
                            $totalCost += (float)$subcontractor->total_cost;
                        }

                        $data = [
                            'partialSubcontractors' => $proposalDetailSubcontractors,
                        ];

                        $response = [
                            'success' => true,
                            'message' => 'Subcontractor added.' . $uploadError,
                            'data' => [
                                'grid' => view('estimator._subcontractors_grid', $data)->render(),
                                'totalCost' => $totalCost,
                                'currencyTotalCost' => Currency::format($totalCost),
                                'description' => $description,
                            ],
                        ];
                    }
                } catch (Exception $e) {
                    if (env('APP_ENV') === 'local') {
                        $response = [
                            'success' => false,
                            'message' => $e->getMessage(),
                        ];
                    } else {
                        Log::error(get_class() . ' - ' . $e->getMessage());
                        $response = [
                            'success' => false,
                            'message' => 'Exception error',
                        ];
                    }
                }
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

    public function ajaxSubcontractorRemove(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_subcontractor_id']), [
                    'proposal_detail_subcontractor_id' => 'required|positive',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if (!$proposalDetailSubcontractor = ProposalDetailSubcontractor::find($request->proposal_detail_subcontractor_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Proposal detail subcontractor not found.',
                        ];
                    } else {
                        if (!empty($proposalDetailSubcontractor->attached_bid)) {
                            $fullPathFile = public_path('media/bids/') . $proposalDetailSubcontractor->attached_bid;
                            if (file_exists($fullPathFile)) {
                                unlink($fullPathFile);
                            }
                        }

                        $proposalDetailId = $proposalDetailSubcontractor->proposal_detail_id;

                        $proposalDetailSubcontractor->delete();

                        $proposalDetailSubcontractors = ProposalDetailSubcontractor::where('proposal_detail_id', $proposalDetailId)->get();

                        $totalCost = 0;
                        foreach ($proposalDetailSubcontractors as $subcontractor) {
                            $totalCost += (float)$subcontractor->total_cost;
                        }

                        $data = [
                            'partialSubcontractors' => $proposalDetailSubcontractors,
                        ];

                        $response = [
                            'success' => true,
                            'message' => 'Subcontractor removed.',
                            'data' => [
                                'grid' => view('estimator._subcontractors_grid', $data)->render(),
                                'totalCost' => $totalCost,
                                'currencyTotalCost' => Currency::format($totalCost),
                                'description' => $request->description,
                            ],
                        ];
                    }
                } catch (Exception $e) {
                    if (env('APP_ENV') === 'local') {
                        $response = [
                            'success' => false,
                            'message' => $e->getMessage(),
                        ];
                    } else {
                        Log::error(get_class() . ' - ' . $e->getMessage());
                        $response = [
                            'success' => false,
                            'message' => 'Exception error',
                        ];
                    }
                }
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }


    public function schedule($service_id)
    {

        $service = ProposalDetail::where('id', '=', $service_id)->first();
        if (!$service) {
            return view('pages-404');
        }

        $proposal = Proposal::where('id', '=', $service->proposal_id)->first();

        $schedules = ServiceSchedule::where('proposal_detail_id', '=', $service->id)->get();
        $data['schedules'] = $schedules;
        $data['service_id'] = $service_id;
        $data['service'] = $service;
        $data['proposal'] = $proposal;


        return view('proposaldetails.schedule_service', $data);


    }

    public function removeschedule($schedule)
    {
        $serviceschedule = ServiceSchedule::where('id', '=', $schedule)->delete();

        return redirect()->back()->with('info', 'Schedule Deleted!');

    }


    public function createschedule(ScheduleRequest $request, ProposalDetail $proposal_detail)
    {
        $start_date = strtotime($request['start_date']);
        $request['start_date'] = date('Y-m-d', $start_date);

        $end_date = strtotime($request['end_date']);
        $request['end_date'] = date('Y-m-d', $end_date);

        $newschedule = $request->all();
        $serviceschedule = ServiceSchedule::create($newschedule);

        $id = $serviceschedule->id;

        return redirect()->back()->with('info', 'Schedule Created!');


    }

    public function destroyOLD($id)
    {
        $service = ProposalDetail::where('id', '=', $id)->first()->toArray();
        if (isset($service['proposal_id'])) {
            $proposal_id = $service['proposal_id'];
            ProposalDetail::destroy($id);
            return redirect()->back()->with('success', 'Service was deleted!');
        }
        return redirect()->back()->with('error', 'Sorry no matching records were found!');
    }

    public function destroy(Request $request)
    {
        if (!$item = ProposalDetail::with('service')->find($request->item_id)) {
            return redirect()->back()->with('error', 'Service not found.');
        }

        $name = $item->service->name;
        $proposalId = $item->proposal_id;

        try {
            $item->delete();
        } catch (Exception $e) {
            return ExceptionError::handleError($e);
        }

        $redirectTo = route('show_proposal', ['id' => $proposalId]);

        if (!empty($request->tab)) {
            $redirectTo .= '?tab=servicestab';
        }

        return redirect()->to($redirectTo)->with('success', 'Service "' . $name . '" deleted.');
    }

    public function newservice($proposalId)
    {
        $data['headername'] = "Add New Service";
        $proposal = Proposal::find($proposalId)->first()->toArray();
        $data['servicecats'] = Service::servicesCB();
        $data['proposal'] = $proposal;
        $data['proposalId'] = $proposalId;
        if (!$proposal) {
            echo "no records found";
            exit();
        }

        return view('proposaldetails.select_service', $data);
    }

    public function savestriping(Request $request)
    {
        //

        $cost = $_POST['cost'];

        $material_cost = $_POST['material_cost'];
        $proposal_text = $_POST['x_proposal_text'];
        $proposal_detail_id = $_POST['proposal_detail_id'];
        $profit = $_POST['profit'];
        $service_name = $request['service_name'];
        $proposal_id = $request['proposal_id'];
        $overhead = $request['overhead'];
        $bill_after = $request['bill_after'];
        //echo 'proposal_id:'.$proposal_id. "<br>";
        //echo 'name:'.$service_name. "<br>";
        //echo 'proposal_detail_id:'.$proposal_detail_id. "<br>";
        //echo 'text:'.$proposal_text. "<br>";
        $total_cost = 0;
        foreach ($_POST as $key => $value) {
            //echo $key. "<br/>";
            if (strpos($key, "quantity") === 0) {
                $service_id = explode("_", $key);
                $striping_service_id = $service_id[1];
                $service_cost = $request['cost_' . $striping_service_id];
                $total_cost += $cost;
                ProposalDetailStripingService::where('id', $striping_service_id)->update(['quantity' => $value]);

            }
        }

        $data['cost'] = $cost;
        $data['overhead'] = $overhead;
        $data['profit'] = $profit;
        $data['proposal_text'] = $proposal_text;
        $data['service_name'] = $service_name;
        $data['material_cost'] = $material_cost;
        $data['bill_after'] = $bill_after;

        ProposalDetail::where('id', $proposal_detail_id)->update($data);
        //update proposal_details

        if ($request['stayorleave'] == 'true') {
            return redirect()->route('show_proposal', ['id' => $proposal_id]);

        }


        return back()->withSuccess('Striping saved');

    }

}
