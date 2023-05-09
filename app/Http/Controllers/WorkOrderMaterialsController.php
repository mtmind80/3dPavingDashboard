<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\WorkOrderMaterialRequest;
use App\Models\Material;
use App\Models\ProposalDetail;
use App\Models\WorkorderMaterial;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkOrderMaterialsController extends Controller
{
    public function entryForm($proposal_detail_id, Request $request)
    {
        if (!$proposalDetail = ProposalDetail::with(['proposal', 'service'])->find($proposal_detail_id)) {
            abort(404);
        }

        $perPage = $request->perPage ?? 50;
        $reportDate = !empty($request->report_date) ? Carbon::createFromFormat('m/d/Y', $request->report_date) : null;

        if (!empty($reportDate)) {
            $materials = WorkorderMaterial::where('proposal_detail_id', $proposal_detail_id)
                ->where('report_date', $reportDate->format('Y-m-d'))
                ->orderBy('name')
                ->paginate($perPage);
        }

        $data = [
            'proposalDetail' => $proposalDetail,
            'materials'     => $materials ?? null,
            'reportDate'     => $reportDate,
            'materialsCB'    => Material::materialsCB(['0' => 'Select material']),
            'returnTo'       => route('show_workorder', ['id' => $proposalDetail->proposal_id]),
            'currentUrl'     => route('workorder_material_entry_form', ['proposal_detail_id' => $proposalDetail->id]),
            'tabSelected'    => 'services',
        ];

        return view('workorders.material.entry_form', $data);
    }

    public function store(WorkOrderMaterialRequest $request)
    {
        $material = Material::find($request->material_id);

        $inputs = $request->all();

        $inputs['name'] = $material->name;
        $inputs['cost'] = $material->cost;
        $inputs['report_date'] = Carbon::createFromFormat('m/d/Y', $request->report_date);
        $inputs['created_by'] = auth()->user()->id;

        WorkOrderMaterial::create($inputs);

        if (!empty($request->returnTo)) {
            return redirect()->to($request->returnTo)->with('success', 'Material added.');
        } else {
            return redirect()->back()->with('success', 'Material added.');
        }
    }

    public function destroy($workorder_material_id)
    {
        $workorderMaterial = WorkorderMaterial::find($workorder_material_id);

        $proposal_detail_id = $workorderMaterial->proposal_detail_id;
        $report_date = $workorderMaterial->report_date;

        $workorderMaterial->delete();

        return redirect()->route('workorder_material_entry_form', ['proposal_detail_id' => $proposal_detail_id, 'report_date' => $report_date->format('m/d/Y')])->with('success', 'Material entry removed.');
    }

}
