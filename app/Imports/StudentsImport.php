<?php

namespace App\Imports;

use App\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
{
    private $class_id;

    public function __construct(int $class_id)
    {
        $this->class_id = $class_id;
    }
    /**
    * @param array $row
    *
    * @return \App\Student|null
    */
    public function model(array $row): Student
    {
        return new Student([
            'lastname' => $row['surname'],
            'firstname' => $row['first_name'],
            'class_id' => $this->class_id
        ]);
    }

    public function rules(): array
    {
        return array(
            'surname' => ['required' , 'string', 'min:2', 'regex:/^(?=.*?[a-zA-Z0-9])/'],
            'first_name' => 'required|string|min:2|regex:/^(?=.*?[a-zA-Z0-9])/'
        );
    }

    /**
     * Custom Validation Messages
     * @return array
     */
    public function customValidationMessages(): array {
        return array(
            'surname.regex' => 'Invalid :attribute provided: :input',
            'surname.min' => 'Invalid :attribute provided: :input',
            'surname.required' => 'Empty cell in :attribute field',
            'surname.string' => 'Invalid :attribute provided: :input',
            'first_name.regex' => 'Invalid :attribute provided: :input',
            'first_name.min' => 'Invalid :attribute provided: :input',
            'first_name.required' => 'Empty cell in :attribute field',
            'first_name.string' => 'Invalid :attribute provided: :input'
        );
    }
}
