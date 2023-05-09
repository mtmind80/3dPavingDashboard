<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class WorkOrderSubcontractorRequest extends Request
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
            'contractor_id'      => 'required|positive',
            'report_date'        => 'required|date',
            'cost'               => 'required|currency',
            'description'        => 'required|plainText|max:220',
        ];
    }

}
