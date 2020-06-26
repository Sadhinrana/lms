<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class AdminUpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user()->role === config('constant.roles.headmaster') || Auth::user()->role === config('constant.roles.company_head')) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer',
            'image' => 'imageable', // max 10000kb
            'email'=>'string|email|without_spaces|max:255|required',
            'password'=>'nullable|min:7|max:35|string|without_spaces',
            'first_name'=>'required|string|max:15',
            'last_name'=>'required|string|max:15',
			'phone_number'=>'required|numeric',
            // 'website'=>'url',
            'address'=>'string',
            'role' => 'required|integer|between:1,9',
        ];
    }
}
