@extends('layouts.admin')
@section('title', 'Purchase Bills')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-shopping-cart"></i> Purchase Bills</h2>
    <a href="{{ route('purchases.create') }}" class="btn btn-success btn-large">
        <i class="fas fa-plus"></i> New Purchase
    </a>
</div>

<form method="GET" class="row g-3 mb-3">
    <div class="col-md-3">
        <select name="supplier_id" class="form-select">
            <option value="">All Suppliers</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="From Date">
    </div>
    <div class="col-md-2">
        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="To Date">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th><th>Bill No</th><th>Supplier</th><th>Date</th><th>Subtotal</th><th>Tax</th><th>Total</th><th>Paid</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->id }}</td>
                        <td>{{ $purchase->bill_no ?? '-' }}</td>
                        <td>{{ $purchase->supplier->name }}</td>
                        <td>{{ $purchase->purchase_date }}</td>
                        <td>₹{{ number_format($purchase->subtotal, 2) }}</td>
                        <td>₹{{ number_format($purchase->tax_amount, 2) }}</td>
                        <td><strong>₹{{ number_format($purchase->total_amount, 2) }}</strong></td>
                        <td>
                            <span class="badge {{ $purchase->paid_amount >= $purchase->total_amount ? 'bg-success' : 'bg-warning' }}">
                                ₹{{ number_format($purchase->paid_amount, 2) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $purchases->appends(request()->query())->links() }}
    </div>
</div>
@endsection