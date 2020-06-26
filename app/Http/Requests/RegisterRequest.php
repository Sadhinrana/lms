<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;

class RegisterRequest extends FormRequest
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
            'email'=>'string|email|without_spaces|max:255|unique:users',
            'password'=>'min:7|max:35|string|without_spaces|confirmed',
            'first_name'=>'string|max:15',
            'last_name'=>'string|max:15',
            'captcha_key'=>'required',
            'captcha' => 'required|captcha_api:' . Input::get('captcha_key')
        ];
    }

    public function messages(){
        return [
            'email.required' => 'Please enter email or users',
            'password.required'  => 'Please enter password',
            'first_name.required'   => 'Please enter First name',
            'last_name.required'    => 'Please enter Last name',
            'captcha.captcha_api' => 'The captcha is incorrect'
        ];
    }
}
