<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductionLogRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'recipe_id' => 'required|exists:recipes,id',
            'batches' => 'required|numeric|min:0.01',
            'production_date' => 'required|date',
            'note' => 'nullable|string',
        ];
    }
}