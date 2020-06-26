<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
            'title'           => 'required|string',
            'description'     => 'string',
            //'image'           => 'imageable',
            //'audio'           => 'mimes:mpga',
            'type'            => 'string',
            'score'           => 'numeric',
            'quiz_id'          => 'numeric',
            // 'parent_id'        => 'numeric'
        ];
    }

    public function messages(){
        return [
            'title.required'  => 'Please enter title question',
        ];
    }
}
