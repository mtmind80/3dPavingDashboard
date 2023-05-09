<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ContactLeadRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // contacts fields
            'contact_type_id'           => 'required|positive',
            'first_name'                => 'required|personName',
            'last_name'                 => 'nullable|personName',
            'email'                     => 'required|email',
            'phone'                     => 'required|phone',
            'address1'                  => 'required|plainText',
            'address2'                  => 'nullable|plainText',
            'city'                      => 'required|plainText',
            'county'                    => 'required|plainText',
            'state'                     => 'required|plainText',
            'postal_code'               => 'required|zipCode',
            // contact_lead fields
            'contact_id'                => 'nullable|positive',
            'created_by'                => 'nullable|positive',
            'status_id'                 => 'nullable|positive',
            'worked_before'             => 'required|boolean',
            'worked_before_description' => 'nullable|plainText|required_if:worked_before,1',
            'previous_contact_id'       => 'nullable|zeroOrPositive',
            'type_of_work_needed'       => 'required|plainText',
            'lead_source'               => 'nullable|plainText',
            'how_related'               => 'nullable|plainText',
            'onsite'                    => 'nullable|boolean',
            'best_days'                 => 'required|plainText',
        ];
    }

}
