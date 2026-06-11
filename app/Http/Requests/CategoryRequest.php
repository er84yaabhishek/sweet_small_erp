<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Use middleware for permission
    }

    public function rules()
    {
        $id = $this->route('category'); // for unique ignore

        return [
            'name' => 'required|string|max:100|unique:categories,name,' . $id,
            'type' => 'required|in:finished_good,raw_material',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Category Name',
            'type' => 'Category Type',
        ];
    }
}