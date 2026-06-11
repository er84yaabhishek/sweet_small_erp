<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <style>
        body { font-family: monospace; font-size: 12px; width: 80mm; margin: 0; padding: 5px; }
        .center { text-align: center; }
        .shop-name { font-size: 16px; font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; }
        .text-right { text-align: right; }
        .total { font-size: 14px; font-weight: bold; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="center">
        <div class="shop-name">{{ $settings['shop_name'] ?? 'Sweet Shop' }}</div>
        <div>{{ $settings['address'] ?? '' }}</div>
        <div>Ph: {{ $settings['phone_number'] ?? '' }}</div>
        <div class="divider"></div>
        <div>Bill No: {{ $sale->invoice_no }}</div>
        <div>Date: {{ $sale->sale_date }}</div>
        <div class="divider"></div>
    </div>
    <table>
        @foreach($sale->items as $item)
        <tr><td>{{ \Str::limit($item->item->name, 20) }}</td><td>{{ $item->qty }}</td><td class="text-right">₹{{ $item->line_total }}</td></tr>
        @endforeach
    </table>
    <div class="divider"></div>
    <div><strong>Total: ₹{{ $sale->total_amount }}</strong></div>
    <div class="divider"></div>
    <div class="center">Thank you! Visit Again</div>
    <div class="center no-print"><button onclick="window.print()">Print</button></div>
</body>
</html>
