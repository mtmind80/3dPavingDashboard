<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PermitRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'proposal_id'        => 'required|positive',
            'proposal_detail_id' => 'nullable|integer|min:0',
            'status' => [
                Rule::in(['Not Submitted','Submitted','Under Review','Comments']),
            ],
            'type' => [
                Rule::in(['Building','Engineering','Miscellaneous']),
            ],
            'expires_on'         => 'nullable|date',
            'county'             => 'required|plainText',
            'created_by'         => 'required|plainText',
        ];
    }

}
