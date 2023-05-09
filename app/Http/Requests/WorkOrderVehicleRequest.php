<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class WorkOrderVehicleRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'proposal_id'        => 'required|positive',
            'proposal_detail_id' => 'required|positive',
            'vehicle_id'         => 'required|positive',
            'report_date'        => 'required|date',
            'number_of_vehicles' => 'required|positive',
            'note'               => 'nullable|plainText|max:220',
        ];
    }

}
