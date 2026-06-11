<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('item');
        return [
            'name' => 'required|string|max:150',
            'sku' => 'nullable|string|max:50|unique:items,sku,' . $id,
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'item_type' => 'required|in:raw_material,finished_good',
            'sale_price' => 'nullable|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|numeric|min:0',
            'hsn_code' => 'nullable|string|max:20',
            'tax_rate_id' => 'nullable|exists:tax_rates,id',
            'barcode' => 'nullable|string|max:100|unique:items,barcode,' . $id,
            'image' => 'nullable|image|max:2048',
            'is_sellable' => 'boolean',
            'is_purchasable' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Item Name',
            'sku' => 'SKU',
            'category_id' => 'Category',
            'unit_id' => 'Unit',
            'item_type' => 'Item Type',
            'sale_price' => 'Sale Price',
            'purchase_price' => 'Purchase Price',
            'reorder_level' => 'Reorder Level',
            'hsn_code' => 'HSN Code',
            'tax_rate_id' => 'Tax Rate',
            'barcode' => 'Barcode',
            'image' => 'Image',
            'is_sellable' => 'Sellable',
            'is_purchasable' => 'Purchasable',
            'is_active' => 'Active',
        ];
    }
}