<?php

namespace App\Http\Requests\Requests;

use App\Statuses\EmployeeRequestsType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class CreateEmployeeRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            "type" => ["required", Rule::in(EmployeeRequestsType::$statuses)],
            'reason' => 'required',
            'attachments' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
