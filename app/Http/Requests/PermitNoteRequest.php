<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class PermitNoteRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'permit_id' => 'nullable|positive',
            'note'      => 'required|plainText',
            'fee'       => 'nullable|float',
        ];
    }
}
