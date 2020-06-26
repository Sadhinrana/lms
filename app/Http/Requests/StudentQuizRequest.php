<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentQuizRequest extends FormRequest
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
            'quiz_id'       =>'numeric',
            'time_taken'    =>'string',
            'start_time'    =>'string'
        ];
    }
    public function messages(){
        return [
            'quiz_id.required'        => 'Please enter quiz',
            'time_taken.required'       => 'Please enter time taken',
            'start_time.required'   => 'Start time empty'
        ];
    }
}
