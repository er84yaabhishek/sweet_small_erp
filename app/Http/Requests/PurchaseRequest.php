<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'bill_no' => 'nullable|string|max:50',
            'purchase_date' => 'required|date',
            'payment_mode' => 'required|in:cash,upi,card,credit',
            'paid_amount' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
            // Items are validated in controller dynamically
        ];
    }
}