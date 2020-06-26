<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'id' => 'required|integer',
            'image' => 'imageable', // max 10000kb
            'password'=>'sometimes|min:7|max:35|string|without_spaces',
            'first_name'=>'required|string|max:15',
            'last_name'=>'required|string|max:15',
        ];
    }
}
