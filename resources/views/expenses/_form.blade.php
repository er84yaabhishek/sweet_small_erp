<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="expense_date" class="form-label">Expense Date *</label>
            <input type="date" name="expense_date" class="form-control" value="{{ old('expense_date', $expense?->expense_date ?? date('Y-m-d')) }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="category" class="form-label">Category *</label>
            <select name="category" class="form-select" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" @selected(old('category', $expense?->category) === $category)>{{ $category }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $expense?->description) }}</textarea>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label for="amount" class="form-label">Amount (₹) *</label>
            <input type="number" step="0.01" name="amount" value="{{ old('amount', $expense?->amount) }}" class="form-control" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="payment_mode" class="form-label">Payment Mode *</label>
            <select name="payment_mode" class="form-select" required>
                <option value="cash" @selected(old('payment_mode', $expense?->payment_mode ?? 'cash') === 'cash')>Cash</option>
                <option value="upi" @selected(old('payment_mode', $expense?->payment_mode) === 'upi')>UPI</option>
                <option value="card" @selected(old('payment_mode', $expense?->payment_mode) === 'card')>Card</option>
                <option value="bank" @selected(old('payment_mode', $expense?->payment_mode) === 'bank')>Bank Transfer</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="reference_no" class="form-label">Reference / Bill No</label>
            <input type="text" name="reference_no" value="{{ old('reference_no', $expense?->reference_no) }}" class="form-control">
        </div>
    </div>
</div>
<button type="submit" class="btn {{ $submitClass }}">{{ $submitLabel }}</button>
<a href="{{ route('expenses.index') }}" class="btn btn-secondary">Cancel</a>
