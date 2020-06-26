<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseCategoryRequest extends FormRequest
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
            'txt_name' => 'required',
            'txt_description'   => 'required',
        ];
    }

    public function messages(){
        return [
            'txt_name.required' => 'Please enter name category course',
            'txt_description.required'  => 'Please enter description category'
        ];
    }
}
