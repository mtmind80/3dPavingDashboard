<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProposalMediaRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'proposal_id' => 'required|positive',
            'created_by' => 'required|positive',
            'proposal_detail_id' => 'nullable|positive',
            'media_type_id' => 'nullable|positive',
            'description'    => 'required|plainText',
            'file_name'    => 'required|plainText',
            'file_type'    => 'required|plainText',
            'file_path'    => 'required|plainText',
            'original_name'    => 'required|plainText',
            'file_ext'    => 'required|plainText',
            'file_size'    => 'required|plainText',
            'IsImage'    => 'nullable|boolean',
            'image_height'    => 'nullable|plainText',
            'image_width'    => 'nullable|plainText',
            'admin_only'    => 'nullable|boolean',

        ];
    }


}
