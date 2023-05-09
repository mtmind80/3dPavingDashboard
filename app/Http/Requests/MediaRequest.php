<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class MediaRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'proposal_id' => 'required|positive',
            'proposal_detail_id' => 'nullable|zeroOrPositive',
            'media_type_id'    => 'required|positive',
            'description' => 'nullable|plainText',
        ];
    }
}

