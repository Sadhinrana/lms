<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'     =>'string|email|without_spaces',
            'password'    =>'',
            'first_name'=>'string',
            'last_name' =>'string',
            'avatar_url'   =>'',
            'role'      =>'string',
        ];
    }

    public function messages(){
        return [
            'email.required'        => 'Please enter email or users',
            'passwd.required'       => 'Please enter password',
            'first_name.required'   => 'Please enter first name',
            'last_name.required'    => 'Please enter last name',
            'role.required'         => 'Please select roles'
        ];
    }
}
