<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyCourseRequest extends FormRequest
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
            'txt_company' => 'required|integer',
            'txt_course'  => 'required|integer'
        ];
    }
    public function messages(){
        return [
            'txt_company.required' => 'Please enter name company',
            'txt_course.required'  => 'Please enter name course'
        ];
    }
}
