<?php

namespace App\Http\Controllers;

use App\Helpers\Currency;
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
use App\Models\ProposalDetailSubcontractor;
use App\Models\ProposalDetailVehicle;
use App\Models\ProposalMaterial;
use App\Models\Service;
use App\Models\StripingCost;
use App\Models\Vehicle;
use Exception;
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
        //create new service on this proposal
        $proposal_detail = new ProposalDetail();
        $proposal_detail->proposal_id = $id;
        $proposal_detail->services_id = $request['servicecat'];
        $proposal_detail->status_id = 1;
        $proposal_detail->location_id = $proposal['location_id'];
        $proposal_detail->service_name = $service['name'];
        $proposal_detail->service_desc = $service['description'];
        $proposal_detail->dsort = 1;
        $proposal_detail->created_by = auth()->user()->id;
        $proposal_detail->save();

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
        if(!$service) {
            return view('pages-404');
        }

        if($proposal['IsEditable']) {
            return view('proposaldetails.build_service', $data);
        }

        return view('proposaldetails.show_service', $data);
    }

    public function edit($proposal_id, $id)
    {
        if(!$proposalDetail = ProposalDetail::with([
            'proposal' => function($q) {
                $q->with(['contact']);
            },
            'service',
            'location',
            'vehicles',
            'equipment' => function($w) {
                $w->with(['equipment']);
            },
            'labor',
            'additionalCosts',
            'subcontractors' => function($e) {
                $e->with(['contractor']);
            },
            'service' => function($r) {
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

        $data = [
            'header_name' => 'Build Service Estimate',
            'proposalDetail' => $proposalDetail,
            'proposal' => $proposalDetail->proposal,
            'contact' => $proposalDetail->proposal->contact,
            'sealcoatMaterials'  => $sealcoatMaterials,
            'rockMaterials'  => $rockMaterials,
            'asphaltMaterials' => $asphaltMaterials,
            'service' => $proposalDetail->service,
            'service_category_name' => $proposalDetail->service->category->name,
            'equipmentCollection' => Equipment::available()->orderBy('name')->get(),
            'materialsCB' => $materialsCB,
            'vehiclesCB' => Vehicle::vehiclesCB(['0' => 'Select vehicle']),
            'laborCB' => LaborRate::LaborWithRatesCB(['0' => 'Select labor']),
            'contractorsCB' => Contractor::contractorsWithOverheadCB(['0' => 'Select contractor']),
            'contractors' => Contractor::orderBy('name')->get(),
            'allowedFileExtensions' => AcceptedDocuments::extensionsStr(),
            'strippingCB' => StripingCost::strippingCB(['0' => 'Select contractor']),
            'typesCB' => ['0' => 'Select type', 'Dump Fee' => 'Dump Fee', 'Other' => 'Other'],
        ];

        return view('estimator.index', $data);
    }


    public function checkform(Request $request)
    {

        $formfields = $request->all();
        echo "<pre>";

        $proposal_detail = ProposalDetail::where('id', '=', $formfields['id'])->first();

        unset($formfields['_token']);
        unset ($formfields['id']);

        $proposal_detail->update($formfields);
        \Session::flash('error', 'Service was saved!');

        return redirect()->back();

    }


    // To be updated

    public function ajaxCalculateCombinedCosting(Request $request)
    {
        if($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_id', 'profit', 'overhead', 'break_even']), [
                    'proposal_detail_id' => 'required|positive',
                    'profit' => 'required|float',
                    'overhead' => 'required|float',
                    'break_even' => 'required|float',
                ]
            );

            if($validator->fails()) {
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
                } catch(Exception $e) {
                    if(env('APP_ENV') === 'local') {
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

    public function ajaxVehicleAddNew(Request $request)
    {
        if($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_id', 'vehicle_id', 'number_of_vehicles', 'days', 'hours']), [
                    'proposal_detail_id' => 'required|positive',
                    'vehicle_id' => 'required|positive',
                    'number_of_vehicles' => 'required|positive',
                    'days' => 'required|float',
                    'hours' => 'required|float',
                ]
            );

            if($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if(!$vehicle = Vehicle::with(['type'])->find($request->vehicle_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Vehicle not found.',
                        ];
                    } else {
                        $ratePerHour = (float)($vehicle->type->rate ?? 0);
                        $vehicleName = $vehicle->name;
                        $numberOfVehicles = (integer)$request->number_of_vehicles;
                        $days = (float)$request->days;
                        $hours = (float)$request->hours;
                        $cost = $numberOfVehicles * $days * $hours * $ratePerHour;

                        $data = [
                            'proposal_detail_id' => $request->proposal_detail_id,
                            'vehicle_id' => $request->vehicle_id,
                            'vehicle_name' => $vehicleName,
                            'number_of_vehicles' => $numberOfVehicles,
                            'days' => $days,
                            'hours' => $hours,
                            'rate_per_hour' => $ratePerHour,
                            'created_by' => auth()->user()->id,
                        ];
                        $proposalDetailVehicle = ProposalDetailVehicle::create($data);

                        $response = [
                            'success' => true,
                            'message' => 'Vehicle added.',
                            'data' => [
                                'vehicle_name' => $vehicleName,
                                'number_of_vehicles' => $numberOfVehicles,
                                'days' => $days,
                                'hours' => $hours,
                                'rate_per_hour' => $ratePerHour,
                                'cost' => $cost,
                                'formatted_cost' => Currency::format($cost),
                                'proposal_detail_vehicle_id' => $proposalDetailVehicle->id,
                            ],
                        ];
                    }
                } catch(Exception $e) {
                    if(env('APP_ENV') === 'local') {
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

    public function ajaxVehicleRemove(Request $request)
    {
        if($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_vehicle_id']), [
                    'proposal_detail_vehicle_id' => 'required|positive',
                ]
            );

            if($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if(!$ProposalDetailVehicle = ProposalDetailVehicle::find($request->proposal_detail_vehicle_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Proposal detail vehicle not found.',
                        ];
                    } else {
                        $ProposalDetailVehicle->delete();

                        $response = [
                            'success' => true,
                            'message' => 'Vehicle removed.',
                            'data' => [
                                'proposal_detail_vehicle_id' => $request->proposal_detail_vehicle_id,
                            ],
                        ];
                    }
                } catch(Exception $e) {
                    if(env('APP_ENV') === 'local') {
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

    public function ajaxEquipmentAddNew(Request $request)
    {
        if($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_id', 'equipment_id', 'number_of_units', 'days', 'hours', 'equipment_rate_type']), [
                    'proposal_detail_id' => 'required|positive',
                    'equipment_id' => 'required|positive',
                    'number_of_units' => 'required|positive',
                    'days' => 'nullable|float|required_if:equipment_rate_type,per day',
                    'hours' => 'nullable|float|required_if:equipment_rate_type,per hour',
                    'equipment_rate_type' => 'required|in:per day,per hour',
                ]
            );

            if($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => implode(' ', $validator->messages()->all()),
                ];
            } else {
                try {
                    if(!$equipment = Equipment::find($request->equipment_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Equipment not found.',
                        ];
                    } else {
                        $name = $equipment->name;
                        $rateType = $equipment->rate_type;
                        $rate = (float)$equipment->rate;
                        $formattedRate = Currency::format($rate);
                        $numberOfUnits = (integer)$request->number_of_units;
                        $days = (float)$request->days;
                        $hours = (float)$request->hours;
                        $minCost = $equipment->min_cost;

                        if($equipment->rate_type === 'per day') {
                            $cost = $numberOfUnits * $days * $rate;
                        } else {
                            $cost = $numberOfUnits * $hours * $rate;
                            if(!empty($days)) {
                                $cost *= $days;
                            }
                        }

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
                        $proposalDetailEquipment = ProposalDetailEquipment::create($data);

                        $response = [
                            'success' => true,
                            'message' => 'Equipment added.',
                            'data' => [
                                'equipment_name' => $name,
                                'formatted_name_and_rate_type' => $equipment->html_name_and_rate_type,
                                'hours' => $hours,
                                'days' => $days,
                                'number_of_units' => $numberOfUnits,
                                'rate_type' => $rateType,
                                'rate' => $rate,
                                'formatted_rate' => $formattedRate,
                                'cost' => $cost,
                                'formatted_cost' => Currency::format($cost),
                                'min_cost' => $minCost,
                                'formatted_min_cost' => '<span class="status ' . ($cost < $minCost ? 'danger' : '') . '">' . Currency::format($minCost) . '</span>',
                                'proposal_detail_equipment_id' => $proposalDetailEquipment->id,
                            ],
                        ];
                    }
                } catch(Exception $e) {
                    if(env('APP_ENV') === 'local') {
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

    public function ajaxEquipmentRemove(Request $request)
    {
        if($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_equipment_id']), [
                    'proposal_detail_equipment_id' => 'required|positive',
                ]
            );

            if($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if(!$ProposalDetailEquipment = ProposalDetailEquipment::find($request->proposal_detail_equipment_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Proposal detail equipment not found.',
                        ];
                    } else {
                        $ProposalDetailEquipment->delete();

                        $response = [
                            'success' => true,
                            'message' => 'Equipment removed.',
                            'data' => [
                                'proposal_detail_equipment_id' => $request->proposal_detail_equipment_id,
                            ],
                        ];
                    }
                } catch(Exception $e) {
                    if(env('APP_ENV') === 'local') {
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

    public function ajaxLaborAddNew(Request $request)
    {
        if($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_id', 'labor_id', 'number', 'days', 'hours']), [
                    'proposal_detail_id' => 'required|positive',
                    'labor_id' => 'required|positive',
                    'number' => 'required|positive',
                    'days' => 'required|float',
                    'hours' => 'required|float',
                ]
            );

            if($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if(!$labor = LaborRate::find($request->labor_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Labor not found.',
                        ];
                    } else {
                        $ratePerHour = (float)$labor->rate;
                        $laborName = $labor->name;
                        $formattedRatePerHour = Currency::format($ratePerHour);
                        $laborNameAndformattedRate = $labor->name . ' - ' . $formattedRatePerHour;
                        $number = (integer)$request->number;
                        $days = (float)$request->days;
                        $hours = (float)$request->hours;
                        $cost = $number * $days * $hours * $ratePerHour;

                        $data = [
                            'proposal_detail_id' => $request->proposal_detail_id,
                            'labor_name' => $laborName,
                            'number' => $number,
                            'days' => $days,
                            'hours' => $hours,
                            'rate_per_hour' => $ratePerHour,
                            'created_by' => auth()->user()->id,
                        ];
                        $proposalDetailLabor = ProposalDetailLabor::create($data);

                        $response = [
                            'success' => true,
                            'message' => 'Labor added.',
                            'data' => [
                                'labor_name' => $laborName,
                                'formatted_labor_name_and_rate' => $laborNameAndformattedRate,
                                'number' => $number,
                                'days' => $days,
                                'hours' => $hours,
                                'rate_per_hour' => $ratePerHour,
                                'formatted_rate_per_hour' => $formattedRatePerHour,
                                'cost' => $cost,
                                'formatted_cost' => Currency::format($cost),
                                'proposal_detail_additional_cost_id' => $proposalDetailLabor->id,
                            ],
                        ];
                    }
                } catch(Exception $e) {
                    if(env('APP_ENV') === 'local') {
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

    public function ajaxLaborRemove(Request $request)
    {
        if($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_labor_id']), [
                    'proposal_detail_labor_id' => 'required|positive',
                ]
            );

            if($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if(!$ProposalDetailLabor = ProposalDetailLabor::find($request->proposal_detail_labor_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Proposal detail labor not found.',
                        ];
                    } else {
                        $ProposalDetailLabor->delete();

                        $response = [
                            'success' => true,
                            'message' => 'Labor removed.',
                            'data' => [
                                'proposal_detail_labor_id' => $request->proposal_detail_labor_id,
                            ],
                        ];
                    }
                } catch(Exception $e) {
                    if(env('APP_ENV') === 'local') {
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

    public function ajaxAdditionalCostAddNew(Request $request)
    {
        if($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_id', 'amount', 'type', 'description']), [
                    'proposal_detail_id' => 'required|positive',
                    'amount' => 'required|float',
                    'type' => 'required|plainText',
                    'description' => 'required|text',
                ]
            );

            if($validator->fails()) {
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
                    $proposalAdditionalCost = ProposalDetailAdditionalCost::create($data);

                    $response = [
                        'success' => true,
                        'message' => 'Additional cost added.',
                        'data' => [
                            'type' => $type,
                            'description' => $description,
                            'short_description' => Str::limit($description, 100),
                            'cost' => $amount,
                            'formatted_cost' => Currency::format($amount),
                            'proposal_detail_additional_cost_id' => $proposalAdditionalCost->id,
                        ],
                    ];
                } catch(Exception $e) {
                    if(env('APP_ENV') === 'local') {
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

    public function ajaxAdditionalCostRemove(Request $request)
    {
        if($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_additional_cost_id']), [
                    'proposal_detail_additional_cost_id' => 'required|positive',
                ]
            );

            if($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if(!$proposalAdditionalCost = ProposalDetailAdditionalCost::find($request->proposal_detail_additional_cost_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Proposal detail additional cost not found.',
                        ];
                    } else {
                        $proposalAdditionalCost->delete();

                        $response = [
                            'success' => true,
                            'message' => 'Additional cost removed.',
                            'data' => [
                                'proposal_detail_additional_cost_id' => $request->proposal_detail_additional_cost_id,
                            ],
                        ];
                    }
                } catch(Exception $e) {
                    if(env('APP_ENV') === 'local') {
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

    public function ajaxSubcontractorAddNew(Request $request)
    {
        // overhead cost subcontractor_id havebid attached_bid description

        if($request->isMethod('post') && $request->ajax()) {
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

            if($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if(!$contractor = Contractor::find($request->subcontractor_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Contractor not found.',
                        ];
                    } else {
                        $contractorName = $contractor->name;
                        $overhead = (float)$request->overhead;
                        $cost = (float)$request->cost;
                        $formattedCost = Currency::format($cost);
                        $totalCost = $cost * (1 + $overhead / 100);
                        $formattedTotalCost = Currency::format($totalCost);
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

                        $destinationPath = 'media/bids/';

                        $uploadError = '';
                        $attachedBid = null;

                        if (
                            ($result = $this->uploadFile('attached_bid', $destinationPath, [
                                'unique_name' => true,
                                'allowed_extensions' => AcceptedDocuments::extensionsArray(),
                                'prefix' => $request->proposal_detail_id,
                            ]))
                            && $result['success'] === true
                        ) {
                            $attachedBid = $result['fileName'];
                            $data['attached_bid'] = $attachedBid;
                        } else {
                            if (!$result['success']) {
                                $response = [
                                    'success' => false,
                                    'message' => $result['error'],
                                ];
                            }
                            $uploadError = ' Error uploading the file. '.$result['error'] ?? 'Unknown error.';
                        }

                        $proposalDetailSubcontractor = ProposalDetailSubcontractor::create($data);

                        $response = [
                            'success' => true,
                            'message' => 'Subcontractor added.'.$uploadError,
                            'data' => [
                                'subcontractor_name' => $contractorName,
                                'overhead' => $overhead,
                                'overhead_in_percent' => round($overhead, 1).'%',
                                'cost' => $cost,
                                'formatted_cost' => $formattedCost,
                                'total_cost' => $totalCost,
                                'formatted_total_cost' => $formattedTotalCost,
                                'accepted' => (integer)$accepted,
                                'formatted_accepted' => !empty($accepted) ? '<i class="fa fa-check color-green"></i>' : '',
                                'link_attached_bid' => !empty($attachedBid) ? '<a href="'.asset('media/bids/').'/'.$attachedBid.'" target="_blank">'.$attachedBid.'</a>' : '',
                                'description' => $description,
                                'proposal_detail_subcontractor_id' => $proposalDetailSubcontractor->id,
                            ],
                        ];
                    }
                } catch(Exception $e) {
                    if(env('APP_ENV') === 'local') {
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
        if($request->isMethod('post') && $request->ajax()) {
            $validator = Validator::make(
                $request->only(['proposal_detail_subcontractor_id']), [
                    'proposal_detail_subcontractor_id' => 'required|positive',
                ]
            );

            if($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if(!$proposalDetailSubcontractor = ProposalDetailSubcontractor::find($request->proposal_detail_subcontractor_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Proposal detail subcontractor not found.',
                        ];
                    } else {
                        $proposalDetailSubcontractor->delete();

                        $response = [
                            'success' => true,
                            'message' => 'Subcontractor removed.',
                            'data' => [
                                'proposal_detail_subcontractor_id' => $request->proposal_detail_subcontractor_id,
                            ],
                        ];
                    }
                } catch(Exception $e) {
                    if(env('APP_ENV') === 'local') {
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

    public function destroy($id)
    {
        // remove service , return to proposal services
        $service = ProposalDetail::find($id)->first()->toArray();
        if($service) {
            $proposal_id = $service['proposal_id'];
            ProposalDetail::destroy($id);
            \Session::flash('error', 'Service was deleted!');
            return route('show_proposal',['id'=> $proposal_id]);
        }

        \Session::flash('error', 'Sorry no matching records were found!');
        return redirect()->back();

    }

    public function newservice($proposalId)
    {
        $data['headername'] = "Add New Service";
        $proposal = Proposal::find($proposalId)->first()->toArray();
        $data['servicecats'] = Service::servicesCB();
        $data['proposal'] = $proposal;
        $data['proposalId'] = $proposalId;
        if(!$proposal) {
            echo "no records found";
            exit();
        }

        return view('proposaldetails.select_service', $data);
    }

    public function editservice($id)
    {
        //
    }

}
