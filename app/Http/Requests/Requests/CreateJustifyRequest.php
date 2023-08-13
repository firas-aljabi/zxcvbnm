<?php

namespace App\Http\Requests\Requests;

use App\Statuses\JustifyStatus;
use App\Statuses\JustifyTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateJustifyRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "reason" => "required|string",
            "type" => ["required", Rule::in(JustifyTypes::$statuses)],
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            "medical_report_file" => "nullable|mimes:pdf|max:2048"
        ];
    }
}
