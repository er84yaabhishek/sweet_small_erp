@extends('layouts.admin')
@section('title', 'Suppliers')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-truck"></i> Suppliers</h2>
    <a href="{{ route('suppliers.create') }}" class="btn btn-success">Add Supplier</a>
</div>
<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <thead class="table-dark"><tr><th>ID</th><th>Name</th><th>Phone</th><th>GSTIN</th><th>Balance</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($suppliers as $supplier)
                <tr><td>{{ $supplier->id }}</td><td>{{ $supplier->name }}</td><td>{{ $supplier->phone }}</td><td>{{ $supplier->gstin }}</td><td>₹{{ number_format($supplier->opening_balance,2) }}</td>
                <td><a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline-block;">@csrf @method('DELETE')<button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button></form></td></tr>
                @endforeach
            </tbody>
        </table>
        {{ $suppliers->links() }}
    </div>
</div>
@endsection
