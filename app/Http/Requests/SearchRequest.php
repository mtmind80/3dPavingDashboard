<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class SearchRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'needle' => 'required|text|min:3',
        ];

        return $rules;
    }
    
    /* Custom error messages:
     * 
    public function messages()
    {
        return [
            'needle.required' => 'No se ha especificado ningún criterio de búsqueda.',
            'needle.min'      => 'El criterio de búsqueda debe tener al menos 3 caracteres.',
        ];
    }
    */

}
