@extends('layouts.admin')
@section('title', 'Edit Item')
@section('content')
<div class="card">
    <div class="card-header bg-warning">Edit Item</div>
    <div class="card-body">
        <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Item Name *</label>
                        <input type="text" name="name" value="{{ old('name', $item->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="sku" class="form-label">SKU</label>
                        <input type="text" name="sku" value="{{ $item->sku }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category *</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="unit_id" class="form-label">Unit *</label>
                        <select name="unit_id" class="form-select @error('unit_id') is-invalid @enderror" required>
                            <option value="">Select Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ $item->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="item_type" class="form-label">Item Type *</label>
                        <select name="item_type" class="form-select @error('item_type') is-invalid @enderror" required>
                            <option value="finished_good" {{ $item->item_type == 'finished_good' ? 'selected' : '' }}>Finished Good</option>
                            <option value="raw_material" {{ $item->item_type == 'raw_material' ? 'selected' : '' }}>Raw Material</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="sale_price" class="form-label">Sale Price (₹)</label>
                        <input type="number" step="0.01" name="sale_price" value="{{ $item->sale_price }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="purchase_price" class="form-label">Purchase Price (₹)</label>
                        <input type="number" step="0.01" name="purchase_price" value="{{ $item->purchase_price }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="reorder_level" class="form-label">Reorder Level</label>
                        <input type="number" step="0.001" name="reorder_level" value="{{ $item->reorder_level }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="hsn_code" class="form-label">HSN Code</label>
                        <input type="text" name="hsn_code" value="{{ $item->hsn_code }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="barcode" class="form-label">Barcode</label>
                        <input type="text" name="barcode" value="{{ $item->barcode }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="image" class="form-label">Item Image</label>
                        @if($item->image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($item->image) }}" width="80" class="rounded">
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_sellable" value="1" class="form-check-input" id="is_sellable" {{ $item->is_sellable ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_sellable">Sellable</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_purchasable" value="1" class="form-check-input" id="is_purchasable" {{ $item->is_purchasable ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_purchasable">Purchasable</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ $item->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-warning">Update Item</button>
            <a href="{{ route('items.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection