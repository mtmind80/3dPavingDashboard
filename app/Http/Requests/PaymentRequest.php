<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PaymentRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'proposal_id'        => 'required|positive',
            'payment' => 'required|positive',
            'payment_type' => [
                Rule::in(['Deposit','Interim Payment','Additional Payment','Final Payment']),
            ],
            'note'  => 'plainText',
            'check_no'  => 'plainText',
        ];
    }

}
