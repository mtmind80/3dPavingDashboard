<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class NoteRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'note'       => 'required|plainText',
            'contact_id' => 'nullable|positive',
        ];
    }
}
