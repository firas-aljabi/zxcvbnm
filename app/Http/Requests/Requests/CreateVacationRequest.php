<?php

namespace App\Http\Requests\Requests;

use App\Statuses\VacationRequestStatus;
use App\Statuses\VacationRequestTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateVacationRequest extends FormRequest
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
            'reason' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'type' => ['required', Rule::in(VacationRequestTypes::$statuses)],
            'start_time' => 'required_if:type,1',
            'end_time' => 'required_if:type,1',
            'payment_type' => 'required'
        ];
    }
}
