<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\WorkOrderEquipmentRequest;
use App\Models\Equipment;
use App\Models\ProposalDetail;
use App\Models\WorkorderEquipment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkOrderEquipmentController extends Controller
{
    public function entryForm($proposal_detail_id, Request $request)
    {
        if (!$proposalDetail = ProposalDetail::with(['proposal', 'service'])->find($proposal_detail_id)) {
            abort(404);
        }

        $perPage = $request->perPage ?? 50;
        $reportDate = !empty($request->report_date) ? Carbon::createFromFormat('m/d/Y', $request->report_date) : null;

        if (!empty($reportDate)) {
            $equipments = WorkorderEquipment::where('proposal_detail_id', $proposal_detail_id)
                ->where('report_date', $reportDate->format('Y-m-d'))
                ->orderBy('name')
                ->paginate($perPage);
        }

        $data = [
            'proposalDetail' => $proposalDetail,
            'equipments'     => $equipments ?? null,
            'reportDate'     => $reportDate,
            'equipmentCB'    => Equipment::equipmentCB(['0' => 'Select equipment']),
            'returnTo'       => route('show_workorder', ['id' => $proposalDetail->proposal_id]),
            'currentUrl'     => route('workorder_equipment_entry_form', ['proposal_detail_id' => $proposalDetail->id]),
            'tabSelected'    => 'services',
        ];

        return view('workorders.equipment.entry_form', $data);
    }

    public function store(WorkOrderEquipmentRequest $request)
    {
        $equipment = Equipment::find($request->equipment_id);

        $inputs = $request->all();

        $inputs['name'] = $equipment->name;
        $inputs['rate_type'] = $equipment->rate_type;
        $inputs['rate'] = $equipment->rate;
        $inputs['created_by'] = auth()->user()->id;

        WorkOrderEquipment::create($inputs);

        if (!empty($request->returnTo)) {
            return redirect()->to($request->returnTo)->with('success', 'Equipment added.');
        } else {
            return redirect()->back()->with('success', 'Equipment added.');
        }
    }

    public function destroy($workorder_equipment_id)
    {
        $workorderEquipment = WorkorderEquipment::find($workorder_equipment_id);

        $proposal_detail_id = $workorderEquipment->proposal_detail_id;
        $report_date = $workorderEquipment->report_date;

        $workorderEquipment->delete();

        return redirect()->route('workorder_equipment_entry_form', ['proposal_detail_id' => $proposal_detail_id, 'report_date' => $report_date->format('m/d/Y')])->with('success', 'Equipment entry removed.');
    }

}
