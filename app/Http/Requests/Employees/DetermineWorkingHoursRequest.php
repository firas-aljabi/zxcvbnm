<?php

namespace App\Http\Requests\Employees;

use Illuminate\Foundation\Http\FormRequest;

class DetermineWorkingHoursRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'hours_daily' => 'nullable',
            'days_annual' => 'nullable'
        ];
    }
}
