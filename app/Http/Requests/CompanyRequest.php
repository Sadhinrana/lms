<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'name'         => 'required|string',
            'description'   => 'required|string',
            // 'txt_address'    => 'required|string',
            'instructor'       => 'required|numeric',
        ];
    }
    public function messages(){
        return [
            'name.required' => 'Please enter company title',
            'description.required'  => 'Please enter company description',
            // 'txt_address.required'    =>  'Please enter link video course',
            'instructor.required'   =>  'Please enter instructor name',
        ];
    }
}
