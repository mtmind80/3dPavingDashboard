<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProposalNoteRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'proposal_id' => 'required|positive',
            'note'    => 'required|plainText',
            'reminder' => 'nullable|boolean',
            'reminder_date' => 'nullable|date',
        ];
    }
}
