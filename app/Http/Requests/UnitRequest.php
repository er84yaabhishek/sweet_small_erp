<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('unit');
        return [
            'name' => "required|string|max:50|unique:units,name,{$id}",
            'short_name' => 'required|string|max:10',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Unit name is required',
            'name.unique' => 'This unit name already exists',
            'short_name.required' => 'Short name is required',
        ];
    }
}
