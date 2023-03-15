<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateExamRequest extends FormRequest
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
            'subject_id' => ['required', 'int', 'exists:subjects,id'],
            'class_id' => ['required', 'int', 'exists:classes,id'],
            'aggregate_score' => ['required', 'int', 'min:1'],
            'hours' => ['required', 'int', 'in:0,1,2,3,4,5,6'],
            'minutes' => ['required', 'int', 'in:0,5,10,15,20,25,30,35,40,45,50,55'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'from_exam_id' => ['nullable', 'int', 'exists:exams,id'],
            'number_to_import' => ['required_with:from_exam_id', 'int', 'min:1', 'max:60'],
        ];
    }
}
