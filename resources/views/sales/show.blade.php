@extends('layouts.admin')
@section('title', 'Bill #' . $sale->invoice_no)
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white text-center">
                <h3>Sweet Shop</h3>
                <p>{{ \App\Models\Setting::get('shop_address', '123 Main Street') }}</p>
                <h4>Tax Invoice</h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Invoice No:</strong> {{ $sale->invoice_no }}<br>
                        <strong>Date:</strong> {{ $sale->sale_date }} {{ \Carbon\Carbon::parse($sale->sale_time)->format('h:i A') }}<br>
                        <strong>Customer:</strong> {{ $sale->customer->name ?? 'Walk-in Customer' }}
                    </div>
                    <div class="col-md-6 text-end">
                        @if($sale->customer && $sale->customer->gstin)
                            <strong>Customer GSTIN:</strong> {{ $sale->customer->gstin }}<br>
                        @endif
                        @if($sale->is_gst_invoice)
                            <strong>GST Invoice</strong>
                        @endif
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr><th>#</th><th>Item</th><th>Qty</th><th>Price</th><th>Discount</th><th>Tax</th><th>Total</th></tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->item->name }}</td>
                                <td>{{ number_format($item->qty, 3) }} {{ $item->item->unit->short_name ?? '' }}</td>
                                <td>₹{{ number_format($item->unit_price, 2) }}</td>
                                <td>₹{{ number_format($item->discount_amount, 2) }}</td>
                                <td>₹{{ number_format($item->tax_amount, 2) }}</td>
                                <td>₹{{ number_format($item->line_total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr><th colspan="6" class="text-end">Subtotal:</th><th>₹{{ number_format($sale->subtotal, 2) }}</th></tr>
                            <tr><th colspan="6" class="text-end">Discount:</th><th>₹{{ number_format($sale->discount_amount, 2) }}</th></tr>
                            <tr><th colspan="6" class="text-end">Tax:</th><th>₹{{ number_format($sale->tax_amount, 2) }}</th></tr>
                            <tr><th colspan="6" class="text-end">Round Off:</th><th>₹{{ number_format($sale->round_off, 2) }}</th></tr>
                            <tr><th colspan="6" class="text-end fw-bold">Total:</th><th class="fw-bold">₹{{ number_format($sale->total_amount, 2) }}</th></tr>
                        </tfoot>
                    </table>
                </div>

                <h5>Payment Details</h5>
                <table class="table table-sm">
                    <thead><tr><th>Mode</th><th>Amount</th><th>Reference</th></tr></thead>
                    <tbody>
                        @foreach($sale->payments as $payment)
                        <tr>
                            <td>{{ ucfirst($payment->payment_mode) }}</td>
                            <td>₹{{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->reference_no ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr><th>Total Paid</th><th colspan="2">₹{{ number_format($sale->payments->sum('amount'), 2) }}</th></tr>
                    </tfoot>
                </table>

                @if($sale->note)
                    <div class="alert alert-secondary mt-3">
                        <strong>Note:</strong> {{ $sale->note }}
                    </div>
                @endif

                <div class="text-center mt-4">
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print"></i> Print Bill
                    </button>
                    <a href="{{ route('sales.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .navbar, .sidebar, .btn, .navbar-top, .d-flex.justify-content-between, .alert, .btn-close {
        display: none !important;
    }
    .content { margin-left: 0 !important; padding: 0 !important; }
    .card { border: none !important; }
}
</style>
@endsection
