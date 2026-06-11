@extends('layouts.admin')
@section('title', 'POS - New Bill')
@section('content')
<style>
    .pos-container { display: flex; gap: 20px; flex-wrap: wrap; }
    .pos-items { flex: 2; min-width: 300px; }
    .pos-cart { flex: 1; min-width: 300px; background: #f8f9fa; padding: 15px; border-radius: 10px; position: sticky; top: 20px; height: fit-content; }
    .item-card { cursor: pointer; border: 1px solid #dee2e6; border-radius: 10px; padding: 10px; margin-bottom: 10px; background: white; }
    .item-card:hover { background: #f8b40020; border-color: #f8b400; }
    .cart-item { display: flex; justify-content: space-between; align-items: center; padding: 8px; border-bottom: 1px solid #dee2e6; }
    .qty-control button { width: 30px; height: 30px; border-radius: 50%; background: #f8b400; color: white; font-weight: bold; border: none; }
    .total-amount { font-size: 24px; font-weight: bold; color: #f8b400; }
</style>

<div class="pos-container">
    <div class="pos-items">
        <div class="mb-3">
            <input type="text" id="searchItem" class="form-control form-control-lg" placeholder="Search item...">
        </div>
        <div class="row" id="itemsGrid">
            @foreach($items as $item)
            <div class="col-md-4 col-sm-6">
                <div class="item-card" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-price="{{ $item->sale_price }}" data-stock="{{ $item->currentStock() }}">
                    <div class="text-center">
                        <h6>{{ $item->name }}</h6>
                        <p class="mb-0">₹{{ number_format($item->sale_price, 2) }}</p>
                        <small>Stock: {{ number_format($item->currentStock(), 2) }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="pos-cart">
        <h4 class="mb-3">🛒 Current Bill</h4>
        
        <div class="mb-3">
            <select id="customerId" class="form-select">
                <option value="">Walk-in Customer</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone }})</option>
                @endforeach
            </select>
        </div>

        <div id="cartItems" style="max-height: 400px; overflow-y: auto;">
            <p class="text-muted text-center">No items added</p>
        </div>

        <hr>
        <div class="d-flex justify-content-between"><strong>Subtotal:</strong> <span id="subtotal">₹0.00</span></div>
        <div class="d-flex justify-content-between mt-2"><strong>Discount:</strong> <input type="number" id="discountAmount" class="form-control form-control-sm" style="width: 100px;" value="0"></div>
        <div class="d-flex justify-content-between mt-2"><strong>Tax:</strong> <span id="taxAmount">₹0.00</span></div>
        <hr>
        <div class="d-flex justify-content-between"><strong class="fs-5">Total:</strong> <strong class="total-amount" id="totalAmount">₹0.00</strong></div>

        <hr>
        <h6>💵 Payments</h6>
        <div id="paymentsList">
            <div class="payment-row d-flex gap-2 mb-2">
                <select class="form-select payment-mode" style="width: 100px;"><option value="cash">Cash</option><option value="upi">UPI</option><option value="card">Card</option></select>
                <input type="number" class="form-control payment-amount" placeholder="Amount" value="0">
                <button type="button" class="btn btn-sm btn-danger remove-payment">✕</button>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-secondary mt-2" id="addPaymentBtn">+ Add Payment</button>

        <div class="alert alert-info mt-3" id="balanceInfo">Pending: ₹0.00</div>

        <div class="d-grid gap-2 mt-3">
            <button type="button" class="btn btn-success btn-lg" id="completeSaleBtn">Complete Sale</button>
            <button type="button" class="btn btn-danger" id="clearCartBtn">Clear Cart</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let cart = [];

function renderCart() {
    let html = '', subtotal = 0;
    cart.forEach((item, idx) => {
        let total = item.price * item.qty;
        subtotal += total;
        html += `<div class="cart-item"><div><strong>${item.name}</strong><br>₹${item.price} × ${item.qty}</div>
                <div><strong>₹${total.toFixed(2)}</strong>
                <button class="qty-down" data-idx="${idx}">-</button>
                <span>${item.qty}</span>
                <button class="qty-up" data-idx="${idx}">+</button>
                <button class="remove-item" data-idx="${idx}">🗑️</button></div></div>`;
    });
    $('#cartItems').html(html || '<p class="text-muted text-center">No items added</p>');
    
    let discount = parseFloat($('#discountAmount').val()) || 0;
    let tax = (subtotal - discount) * 0.05;
    let total = subtotal - discount + tax;
    $('#subtotal').text(`₹${subtotal.toFixed(2)}`);
    $('#taxAmount').text(`₹${tax.toFixed(2)}`);
    $('#totalAmount').text(`₹${Math.round(total).toFixed(2)}`);
    updateBalance();
}

function updateBalance() {
    let total = parseFloat($('#totalAmount').text().replace('₹', ''));
    let paid = 0;
    $('.payment-amount').each(function() { paid += parseFloat($(this).val()) || 0; });
    $('#balanceInfo').html(paid >= total ? `✅ Paid: ₹${paid.toFixed(2)}` : `⚠️ Pending: ₹${(total-paid).toFixed(2)}`);
}

$('.item-card').click(function() {
    let id = $(this).data('id'), name = $(this).data('name'), price = $(this).data('price');
    let existing = cart.find(i => i.id === id);
    if (existing) existing.qty++;
    else cart.push({ id, name, price, qty: 1 });
    renderCart();
});

$(document).on('click', '.qty-up', function() {
    let idx = $(this).data('idx');
    cart[idx].qty++;
    renderCart();
});
$(document).on('click', '.qty-down', function() {
    let idx = $(this).data('idx');
    if (cart[idx].qty > 1) cart[idx].qty--;
    else cart.splice(idx, 1);
    renderCart();
});
$(document).on('click', '.remove-item', function() {
    cart.splice($(this).data('idx'), 1);
    renderCart();
});

$('#discountAmount').on('input', renderCart);

$('#addPaymentBtn').click(function() {
    $('#paymentsList').append(`<div class="payment-row d-flex gap-2 mb-2">
        <select class="form-select payment-mode" style="width:100px"><option value="cash">Cash</option><option value="upi">UPI</option><option value="card">Card</option></select>
        <input type="number" class="form-control payment-amount" placeholder="Amount" value="0">
        <button type="button" class="btn btn-sm btn-danger remove-payment">✕</button></div>`);
});
$(document).on('click', '.remove-payment', function() { if ($('.payment-row').length > 1) $(this).closest('.payment-row').remove(); updateBalance(); });
$(document).on('input', '.payment-amount', updateBalance);

$('#clearCartBtn').click(function() { if(confirm('Clear cart?')) { cart = []; renderCart(); } });

$('#completeSaleBtn').click(function() {
    if(cart.length === 0) return alert('Cart empty!');
    let payments = [];
    $('.payment-row').each(function() {
        let amount = parseFloat($(this).find('.payment-amount').val()) || 0;
        if(amount > 0) payments.push({ payment_mode: $(this).find('.payment-mode').val(), amount: amount });
    });
    if(payments.length === 0) return alert('Add payment!');
    
    let saleData = {
        customer_id: $('#customerId').val(),
        sale_date: new Date().toISOString().split('T')[0],
        sale_time: new Date().toLocaleTimeString('en-GB', { hour12: false }),
        discount_amount: parseFloat($('#discountAmount').val()) || 0,
        items: cart.map(i => ({ item_id: i.id, qty: i.qty, unit_price: i.price, tax_amount: 0, line_total: i.price * i.qty })),
        payments: payments
    };
    
    $.ajax({
        url: '{{ route("sales.store") }}', method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        contentType: 'application/json', data: JSON.stringify(saleData),
        success: function(res) { alert('Sale completed!'); window.location.href = '/sales/' + res.sale_id; },
        error: function(xhr) { alert('Error: ' + xhr.responseJSON?.message); }
    });
});
</script>
@endsection
