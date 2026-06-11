@extends('layouts.admin')
@section('title', 'Purchase Bill #' . $purchase->id)
@section('content')
<div class="card">
    <div class="card-header bg-info text-white">
        <h3 class="mb-0">Purchase Bill Details</h3>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <strong>Supplier:</strong> {{ $purchase->supplier->name }}<br>
                <strong>Phone:</strong> {{ $purchase->supplier->phone ?? '-' }}<br>
                <strong>GSTIN:</strong> {{ $purchase->supplier->gstin ?? '-' }}
            </div>
            <div class="col-md-6 text-end">
                <strong>Bill No:</strong> {{ $purchase->bill_no ?? 'N/A' }}<br>
                <strong>Date:</strong> {{ $purchase->purchase_date }}<br>
                <strong>Payment Mode:</strong> {{ ucfirst($purchase->payment_mode) }}
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr><th>Item</th><th>Qty</th><th>Unit Price</th><th>Tax</th><th>Total</th></tr>
                </thead>
                <tbody>
                    @foreach($purchase->items as $item)
                    <tr>
                        <td>{{ $item->item->name }}</td>
                        <td>{{ number_format($item->qty, 3) }} {{ $item->item->unit->short_name ?? '' }}</td>
                        <td>₹{{ number_format($item->unit_price, 2) }}</td>
                        <td>₹{{ number_format($item->tax_amount, 2) }}</td>
                        <td>₹{{ number_format($item->line_total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr><th colspan="4" class="text-end">Subtotal:</th><th>₹{{ number_format($purchase->subtotal, 2) }}</th></tr>
                    <tr><th colspan="4" class="text-end">Tax:</th><th>₹{{ number_format($purchase->tax_amount, 2) }}</th></tr>
                    <tr><th colspan="4" class="text-end">Total:</th><th><strong>₹{{ number_format($purchase->total_amount, 2) }}</strong></th></tr>
                    <tr><th colspan="4" class="text-end">Paid:</th><th>₹{{ number_format($purchase->paid_amount, 2) }}</th></tr>
                    <tr><th colspan="4" class="text-end">Due:</th><th class="text-danger">₹{{ number_format($purchase->total_amount - $purchase->paid_amount, 2) }}</th></tr>
                </tfoot>
            </table>
        </div>

        @if($purchase->note)
            <div class="alert alert-info mt-3">
                <strong>Note:</strong> {{ $purchase->note }}
            </div>
        @endif

        <div class="mt-3">
            <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('purchase-returns.create') }}?purchase_id={{ $purchase->id }}" class="btn btn-warning">
                <i class="fas fa-undo"></i> Return Items
            </a>
        </div>
    </div>
</div>
@endsection