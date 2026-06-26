<?php

namespace App\Http\Requests\Reward;

use Illuminate\Foundation\Http\FormRequest;

class SpendPointsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true; 
    }


    public function rules(): array
    {
        return [
            'points' => 'required|integer|min:1',
            'item_name' => 'required|string|max:255',
        ];
    }
    

    public function messages(): array
    {
        return [
            'points.required' => 'وارد کردن امتیاز الزامی است.',
            'points.integer' => 'امتیاز باید یک عدد صحیح باشد.',
            'points.min' => 'حداقل امتیاز برای خرج کردن ۱ امتیاز است.',
            'item_name.required' => 'نام جایزه الزامی است.',
        ];
    }
}