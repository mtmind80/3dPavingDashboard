<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class LeadNoteRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'lead_id' => 'nullable|positive',
            'note'    => 'required|plainText',
        ];
    }
}
