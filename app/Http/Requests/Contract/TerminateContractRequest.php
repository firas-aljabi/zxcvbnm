<?php

namespace App\Http\Requests\Contract;

use Illuminate\Foundation\Http\FormRequest;

class TerminateContractRequest extends FormRequest
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


    public function rules()
    {
        return [
            "user_id" => "required|exists:users,id",
            "contract_termination_period" => "required",
            "contract_termination_reason" => "required"
        ];
    }
}
