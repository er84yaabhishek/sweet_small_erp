@extends('layouts.admin')
@section('title', 'Purchase Return Details')
@section('content')
<div class="card">
    <div class="card-header bg-warning">
        <h3>Purchase Return #{{ $return->id }}</h3>
    </div>
    <div class="card-body">
        <p><strong>Supplier:</strong> {{ $return->supplier->name }}</p>
        <p><strong>Return Date:</strong> {{ $return->return_date }}</p>
        <p><strong>Reason:</strong> {{ $return->reason ?? 'N/A' }}</p>
        <h5>Items Returned</h5>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr><th>Item</th><th>Quantity</th><th>Unit Price</th><th>Total</th></tr>
            </thead>
            <tbody>
                @foreach($return->items as $item)
                <tr>
                    <td>{{ $item->item->name }}</td>
                    <td>{{ number_format($item->qty, 3) }}</td>
                    <td>₹{{ number_format($item->unit_price, 2) }}</td>
                    <td>₹{{ number_format($item->line_total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr><th colspan="3" class="text-end">Total:</th><th>₹{{ number_format($return->total_amount, 2) }}</th></tr>
            </tfoot>
        </table>
        <a href="{{ route('purchase-returns.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection
