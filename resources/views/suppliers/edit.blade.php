@extends('layouts.admin')
@section('title', 'Edit Supplier')
@section('content')
<div class="card">
    <div class="card-header bg-warning">Edit Supplier</div>
    <div class="card-body">
        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Supplier Name *</label>
                        <input type="text" name="name" value="{{ old('name', $supplier->name) }}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" value="{{ $supplier->phone }}" class="form-control">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="3">{{ $supplier->address }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="gstin" class="form-label">GSTIN Number</label>
                        <input type="text" name="gstin" value="{{ $supplier->gstin }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="opening_balance" class="form-label">Opening Balance (₹)</label>
                        <input type="number" step="0.01" name="opening_balance" value="{{ $supplier->opening_balance }}" class="form-control">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-warning">Update Supplier</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection