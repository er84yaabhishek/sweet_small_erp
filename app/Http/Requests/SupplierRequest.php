<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('supplier');
        return [
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'gstin' => 'nullable|string|max:20|unique:suppliers,gstin,' . $id,
            'opening_balance' => 'nullable|numeric',
        ];
    }
}