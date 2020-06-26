<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'txt_email'     =>'string|email|without_spaces|unique:users,email',
            'txt_passwd'    =>'required',
            'txt_first_name'=>'string',
            'txt_last_name' =>'string',
            'file_avatar'   =>'string',
            'cmb_role'      =>'string',
            'company_id'    =>'numeric',
            'txt_phone_number'    =>'numeric',
        ];
    }

    public function messages(){
        return [
            'txt_email.required'        => 'Please enter email or users',
            'txt_passwd.required'       => 'Please enter password',
            'txt_first_name.required'   => 'Please enter first name',
            'txt_phone_number.required'   => 'Please enter phone number',
            'txt_last_name.required'    => 'Please enter last name',
            'cmb_role.required'         => 'Please select roles'
        ];
    }
}
