<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer_id' => 'nullable|exists:customers,id',
            'sale_date' => 'required|date',
            'sale_time' => 'required|date_format:H:i',
            'discount_amount' => 'nullable|numeric|min:0',
            'round_off' => 'nullable|numeric',
            'note' => 'nullable|string',
        ];
    }
}