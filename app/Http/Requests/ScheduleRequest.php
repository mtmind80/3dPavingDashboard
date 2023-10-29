<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ScheduleRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ];

        return $rules;
    }


}
