<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'first_name' => 'required|personName',
            'last_name'  => 'required|personName',
            'phone'      => 'nullable|phone',
            'role_id'    => 'required|positive',
            'image'      => 'fileName',
            'notify_me'  => 'boolean',
            'disabled'   => 'boolean',
        ];

        if ($this->isMethod('post')) {                                                      // creating new user
            $rules['email'] = 'required|email|unique:users';
            $rules['password'] = 'required|password|min:6';
            $rules['repeat_password'] = 'required|same:password';

        } else if ($this->isMethod('patch')) {                                              // updating user
            $rules['email'] = 'required|email|unique:users,email,' . $this->get('id');
            $rules['password'] = 'nullable|password|min:6';
            $rules['repeat_password'] = 'nullable|same:password';
        }

        return $rules;
    }
}
