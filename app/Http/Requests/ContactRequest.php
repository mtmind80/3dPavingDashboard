<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ContactRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'contact_type_id'      => 'required|positive',
            'lead_id'              => 'nullable|positive',
            'first_name'           => 'required|personName',
            'last_name'            => 'nullable|personName',
            'email'                => 'required|email',
            'alt_email'            => 'nullable|email',
            'phone'                => 'required|phone',
            'alt_phone'            => 'nullable|phone',
            'address1'             => 'required|plainText',
            'address2'             => 'nullable|plainText',
            'city'                 => 'required|plainText',
            'county'               => 'required|plainText',
            'state'                => 'required|plainText',
            'postal_code'          => 'required|zipCode',
            'billing_address1'     => 'nullable|plainText',
            'billing_address2'     => 'nullable|plainText',
            'billing_city'         => 'nullable|plainText',
            'billing_state'        => 'nullable|plainText',
            'billing_postal_code'  => 'nullable|zipCode',
            'contact'              => 'nullable|plainText',
            'note'                 => 'nullable|plainText',
        ];
    }
}
