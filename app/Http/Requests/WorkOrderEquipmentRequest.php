<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class WorkOrderEquipmentRequest extends Request
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
            'equipment_id'       => 'required|positive',
            'report_date'        => 'required|date',
            'hours'              => 'required|float',
            'number_of_units'    => 'required|positive',
        ];
    }

}
