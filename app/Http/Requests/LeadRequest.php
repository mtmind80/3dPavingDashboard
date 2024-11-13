<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class LeadRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name'                => 'required|personName',
            'last_name'                 => 'required|personName',
            'community_name'            => 'nullable|plainText',
            'email'                     => 'nullable|email',
            'phone'                     => 'required|phone',
            'address1'                  => 'required|plainText',
            'address2'                  => 'nullable|plainText',
            'city'                      => 'required|plainText',
            'county'                    => 'required|plainText',
            'state'                     => 'required|plainText',
            'postal_code'               => 'required|zipCode',
            'created_by'                => 'nullable|positive',
            'status_id'                 => 'nullable|positive',
            'worked_before'             => 'nullable|boolean',
            'worked_before_description' => 'nullable|plainText|required_if:worked_before,1',
            'previous_assigned_to'      => 'nullable|zeroOrPositive',
            'type_of_work_needed'       => 'required|plainText',
            'lead_source'               => 'required|plainText',
            'how_related'               => 'nullable|plainText',
            'onsite'                    => 'nullable|boolean',
        ];
    }

}
