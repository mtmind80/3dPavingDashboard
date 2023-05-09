<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class WorkOrderMaterialRequest extends Request
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
            'material_id'        => 'required|positive',
            'report_date'        => 'required|date',
            'quantity'           => 'required|float',
            'note'               => 'nullable|plainText|max:220',
        ];
    }

}
