@extends('layouts.admin')
@section('title', 'Purchase Returns')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-undo"></i> Purchase Returns</h2>
    <a href="{{ route('purchase-returns.create') }}" class="btn btn-success btn-large">
        <i class="fas fa-plus"></i> New Return
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr><th>ID</th><th>Return Date</th><th>Supplier</th><th>Purchase Bill</th><th>Total Amount</th><th>Reason</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($returns as $return)
                    <tr>
                        <td>{{ $return->id }}</td>
                        <td>{{ $return->return_date }}</td>
                        <td>{{ $return->supplier->name }}</td>
                        <td>{{ $return->purchase ? '#' . $return->purchase->id : 'Direct Return' }}</td>
                        <td class="text-danger">₹{{ number_format($return->total_amount, 2) }}</td>
                        <td>{{ Str::limit($return->reason, 30) ?? '-' }}</td>
                        <td>
                            <a href="{{ route('purchase-returns.show', $return->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $returns->links() }}
    </div>
</div>
@endsection