<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class WorkOrderTimeSheetRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'proposal_id'         => 'required|positive',
            'proposal_details_id' => 'required|positive',
            'employee_id'         => 'required|positive',
            'report_date'         => 'required|date',
            'start_time'          => 'required|time',
            'end_time'            => 'required|time',
        ];
    }

}
