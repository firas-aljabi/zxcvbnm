<?php

namespace App\Http\Requests\Deposit;

use App\Statuses\DepositType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateDepositRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'type' => [Rule::in(DepositType::$statuses), 'required'],
            'car_number' => 'required_if:type,1',
            'car_model' => 'required_if:type,1',
            'user_id' => 'required|exists:users,id',
            'manufacturing_year' => 'required_if:type,1',
            'Mechanic_card_number' => 'required_if:type,1',
            'car_image' => 'required_if:type,1|image|mimes:jpeg,png,jpg|max:2048',
            'laptop_type' => 'required_if:type,2',
            'serial_laptop_number' => 'required_if:type,2',
            'laptop_color' => 'required_if:type,2',
            'laptop_image' => 'required_if:type,2|image|mimes:jpeg,png,jpg|max:2048',
            'serial_mobile_number' => 'required_if:type,3',
            'mobile_color' => 'required_if:type,3',
            'mobile_image' => 'required_if:type,3|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}