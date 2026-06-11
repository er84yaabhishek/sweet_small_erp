@extends('layouts.admin')
@section('title', 'Expenses')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-money-bill"></i> Expenses</h2>
    <a href="{{ route('expenses.create') }}" class="btn btn-success btn-large">
        <i class="fas fa-plus"></i> Add Expense
    </a>
</div>

<form method="GET" class="row g-3 mb-3">
    <div class="col-md-3">
        <select name="category" class="form-select">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="From Date">
    </div>
    <div class="col-md-3">
        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="To Date">
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th><th>Category</th><th>Description</th><th>Amount</th><th>Payment Mode</th><th>Reference</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                    <tr>
                        <td>{{ $expense->expense_date }}</td>
                        <td><span class="badge bg-info">{{ $expense->category }}</span></td>
                        <td>{{ $expense->description ?? '-' }}</td>
                        <td class="text-danger fw-bold">₹{{ number_format($expense->amount, 2) }}</td>
                        <td>{{ ucfirst($expense->payment_mode) }}</td>
                        <td>{{ $expense->reference_no ?? '-' }}</td>
                        <td>
                            <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                            <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete expense?')"><i class="fas fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $expenses->appends(request()->query())->links() }}
    </div>
</div>
@endsection