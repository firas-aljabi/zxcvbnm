<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class CreateComapnyRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            "name" => "required|string",
            "email" => "required|email",
            'start_commercial_record' => 'nullable|date',
            'end_commercial_record' => 'nullable|date',
            'commercial_record' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'longitude' => 'required',
            'latitude' => 'required',
            'radius' => 'required',
        ];
    }
}
