<?php namespace App\Http\Requests;

use App\Helpers\EnumFieldValues;
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

        $statusRule = EnumFieldValues::get('permits', 'status');
        $typeRule = EnumFieldValues::get('permits', 'type');
        return [
            'proposal_id'        => 'required|positive',
            'proposal_detail_id' => 'nullable|integer|min:0',
            'status' => [
                Rule::in($statusRule),
            ],
            'type' => [
                Rule::in($typeRule),
            ],
            'expires_on'         => 'nullable|date',
            'county'             => 'required|plainText',
            'created_by'         => 'required|plainText',
        ];
    }

}
