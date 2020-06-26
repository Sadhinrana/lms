<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
            'title'         => 'required|string',
            'description'   => 'required|string',
            'category_id'   => 'required|numeric',
            'duration'      => 'required|string',
            'image'         => 'imageable', // max 10000kb
            'grade_to_pass' => 'required|numeric',
            // 'instructor'    => 'numeric',
            //'is_published'     => 'in:1,0',
        ];
    }

    public function messages(){
        return [
            'txt_title.required' => 'Please enter title course',
            'txt_description.required'  => 'Please enter description course',
            'txt_video_link.required'    =>  'Please enter link video course'
        ];
    }
}
