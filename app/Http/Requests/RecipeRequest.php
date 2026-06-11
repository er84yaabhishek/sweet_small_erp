<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'item_id' => 'required|exists:items,id',
            'batch_qty' => 'required|numeric|min:0.001',
            'batch_unit_id' => 'required|exists:units,id',
            'notes' => 'nullable|string',
            // ingredients array will be validated in controller
        ];
    }
}