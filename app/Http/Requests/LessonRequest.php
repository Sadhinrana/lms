<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
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
            'txt_description'   => 'required|string',
            'txt_video_link'    => 'required|string',
            'txt_course'    => 'numeric'
        ];
    }

    public function messages(){
        return [
            'txt_description.required'  => 'Please enter description lesson',
            'txt_video_link.required'    =>  'Please enter link video lesson',
            'txt_course.required'       => 'Please enter course'
        ];
    }
}
