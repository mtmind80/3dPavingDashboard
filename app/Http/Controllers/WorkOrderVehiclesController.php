<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\WorkOrderVehicleRequest;
use App\Models\Vehicle;
use App\Models\ProposalDetail;
use App\Models\WorkorderVehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkOrderVehiclesController extends Controller
{
    public function entryForm($proposal_detail_id, Request $request)
    {
        if (!$proposalDetail = ProposalDetail::with(['proposal', 'service'])->find($proposal_detail_id)) {
            abort(404);
        }

        $perPage = $request->perPage ?? 50;
        $reportDate = !empty($request->report_date) ? Carbon::createFromFormat('m/d/Y', $request->report_date) : null;

        if (!empty($reportDate)) {
            $vehicles = WorkorderVehicle::where('proposal_detail_id', $proposal_detail_id)
                ->where('report_date', $reportDate->format('Y-m-d'))
                ->orderBy('vehicle_name')
                ->paginate($perPage);
        }

        $data = [
            'proposalDetail' => $proposalDetail,
            'vehicles'       => $vehicles ?? null,
            'reportDate'     => $reportDate,
            'vehiclesCB'     => Vehicle::vehiclesCB(['0' => 'Select vehicle']),
            'returnTo'       => route('show_workorder', ['id' => $proposalDetail->proposal_id]),
            'currentUrl'     => route('workorder_vehicle_entry_form', ['proposal_detail_id' => $proposalDetail->id]),
            'tabSelected'    => 'services',
        ];

        return view('workorders.vehicle.entry_form', $data);
    }

    public function store(WorkOrderVehicleRequest $request)
    {
        $vehicle = Vehicle::find($request->vehicle_id);

        $inputs = $request->all();

        $inputs['vehicle_name'] = $vehicle->name;
        $inputs['report_date'] = Carbon::createFromFormat('m/d/Y', $request->report_date);
        $inputs['created_by'] = auth()->user()->id;

        WorkOrderVehicle::create($inputs);

        if (!empty($request->returnTo)) {
            return redirect()->to($request->returnTo)->with('success', 'Vehicle added.');
        } else {
            return redirect()->back()->with('success', 'Vehicle added.');
        }
    }

    public function destroy($workorder_vehicle_id)
    {
        $workorderVehicle = WorkorderVehicle::find($workorder_vehicle_id);

        $proposal_detail_id = $workorderVehicle->proposal_detail_id;
        $report_date = $workorderVehicle->report_date;

        $workorderVehicle->delete();

        return redirect()->route('workorder_vehicle_entry_form', ['proposal_detail_id' => $proposal_detail_id, 'report_date' => $report_date->format('m/d/Y')])->with('success', 'Vehicle entry removed.');
    }

}
