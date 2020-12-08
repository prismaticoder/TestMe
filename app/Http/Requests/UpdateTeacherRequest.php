<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherRequest extends FormRequest
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
            'username' => ['required', 'string', 'unique:admins,username,id,'.request()->route('id'), 'max:255'],
            'subjects' => ['required', 'array'],
            'subjects.*.subject_id' => ['required', 'exists:subjects,id'],
            'subjects.*.classes' => ['required', 'array'],
            'subjects.*.classes.*' => ['required', 'exists:classes,id']
        ];
    }
}
