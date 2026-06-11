<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'expense_date' => 'required|date',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'payment_mode' => 'required|in:cash,upi,card,bank',
            'reference_no' => 'nullable|string|max:100',
        ];
    }
}