@extends('layouts.admin')
@section('title', 'Edit Expense')
@section('content')
<div class="card">
    <div class="card-header bg-warning">Edit Expense</div>
    <div class="card-body">
        <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="expense_date" class="form-label">Expense Date *</label>
                        <input type="date" name="expense_date" value="{{ $expense->expense_date }}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="category" class="form-label">Category *</label>
                        <select name="category" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ $expense->category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ $expense->description }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (₹) *</label>
                        <input type="number" step="0.01" name="amount" value="{{ $expense->amount }}" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="payment_mode" class="form-label">Payment Mode *</label>
                        <select name="payment_mode" class="form-select" required>
                            <option value="cash" {{ $expense->payment_mode == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="upi" {{ $expense->payment_mode == 'upi' ? 'selected' : '' }}>UPI</option>
                            <option value="card" {{ $expense->payment_mode == 'card' ? 'selected' : '' }}>Card</option>
                            <option value="bank" {{ $expense->payment_mode == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="reference_no" class="form-label">Reference / Bill No</label>
                        <input type="text" name="reference_no" value="{{ $expense->reference_no }}" class="form-control">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-warning">Update Expense</button>
            <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection