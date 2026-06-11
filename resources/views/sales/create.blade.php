@extends('layouts.admin')
@section('title', 'POS - New Bill')

@section('content')
<style>
    .pos-container {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }
    .pos-items {
        flex: 2;
        min-width: 300px;
    }
    .pos-cart {
        flex: 1;
        min-width: 300px;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        position: sticky;
        top: 20px;
        height: fit-content;
    }
    .item-card {
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 10px;
        background: white;
    }
    .item-card:hover {
        background: #f8b40020;
        border-color: #f8b400;
        transform: scale(1.02);
    }
    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px;
        border-bottom: 1px solid #dee2e6;
    }
    .qty-control {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .qty-control button {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: none;
        background: #f8b400;
        color: white;
        font-weight: bold;
    }
    .payment-row {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }
    .total-amount {
        font-size: 24px;
        font-weight: bold;
        color: #f8b400;
    }
    @media (max-width: 768px) {
        .pos-container {
            flex-direction: column;
        }
        .pos-cart {
            position: static;
        }
    }
</style>

<div class="pos-container">
    <!-- Left Side: Items Grid -->
    <div class="pos-items">
        <div class="mb-3">
            <input type="text" id="searchItem" class="form-control form-control-lg" placeholder="🔍 Search item by name or barcode...">
        </div>
        <div class="row" id="itemsGrid">
            @foreach($items as $item)
            <div class="col-md-4 col-sm-6">
                <div class="item-card" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-price="{{ $item->sale_price }}" data-stock="{{ $item->currentStock() }}">
                    <div class="text-center">
                        @if($item->image)
                            <img src="{{ Storage::url($item->image) }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;">
                        @else
                            <i class="fas fa-box" style="font-size: 40px; color: #f8b400;"></i>
                        @endif
                        <h6 class="mt-2">{{ $item->name }}</h6>
                        <p class="mb-0">₹{{ number_format($item->sale_price, 2) }}</p>
                        <small class="text-muted">Stock: {{ number_format($item->currentStock(), 2) }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Right Side: Cart -->
    <div class="pos-cart">
        <h4 class="mb-3">🛒 Current Bill</h4>
        
        <!-- Customer Selection (Optional) -->
        <div class="mb-3">
            <select id="customerId" class="form-select">
                <option value="">Walk-in Customer</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone }})</option>
                @endforeach
            </select>
        </div>

        <!-- Cart Items -->
        <div id="cartItems" style="max-height: 400px; overflow-y: auto;">
            <p class="text-muted text-center">No items added yet</p>
        </div>

        <!-- Bill Summary -->
        <hr>
        <div class="d-flex justify-content-between">
            <strong>Subtotal:</strong>
            <span id="subtotal">₹0.00</span>
        </div>
        <div class="d-flex justify-content-between mt-2">
            <strong>Discount:</strong>
            <input type="number" id="discountAmount" class="form-control form-control-sm" style="width: 100px;" value="0" step="10">
        </div>
        <div class="d-flex justify-content-between mt-2">
            <strong>Tax (GST):</strong>
            <span id="taxAmount">₹0.00</span>
        </div>
        <div class="d-flex justify-content-between mt-2">
            <strong>Round Off:</strong>
            <span id="roundOff">₹0.00</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
            <strong class="fs-5">Total:</strong>
            <strong class="total-amount" id="totalAmount">₹0.00</strong>
        </div>

        <!-- Payments -->
        <hr>
        <h6>💵 Payments</h6>
        <div id="paymentsList">
            <div class="payment-row">
                <select class="form-select payment-mode" style="width: 100px;">
                    <option value="cash">Cash</option>
                    <option value="upi">UPI</option>
                    <option value="card">Card</option>
                </select>
                <input type="number" class="form-control payment-amount" placeholder="Amount" value="0">
                <input type="text" class="form-control payment-ref" placeholder="Ref (optional)" style="width: 120px;">
                <button type="button" class="btn btn-sm btn-danger remove-payment">✕</button>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-secondary mt-2" id="addPaymentBtn">+ Add Payment</button>

        <!-- Balance -->
        <div class="alert alert-info mt-3" id="balanceInfo">
            Pending: ₹0.00
        </div>

        <!-- Action Buttons -->
        <div class="d-grid gap-2 mt-3">
            <button type="button" class="btn btn-success btn-lg" id="completeSaleBtn">
                <i class="fas fa-check"></i> Complete Sale
            </button>
            <button type="button" class="btn btn-danger" id="clearCartBtn">
                <i class="fas fa-trash"></i> Clear Cart
            </button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let cart = [];
let taxRate = 0; // Will be set from settings

$(document).ready(function() {
    // Load tax rate from settings via AJAX
    $.get('/api/settings/gst-enabled', function(data) {
        if (data.enabled) {
            $.get('/api/settings/tax-rate', function(tax) {
                taxRate = tax.rate;
            });
        }
    });

    // Search items
    $('#searchItem').on('keyup', function() {
        let search = $(this).val().toLowerCase();
        $('.item-card').each(function() {
            let name = $(this).data('name').toLowerCase();
            $(this).closest('.col-md-4').toggle(name.includes(search));
        });
    });

    // Add item to cart
    $('.item-card').on('click', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let price = $(this).data('price');
        let stock = $(this).data('stock');
        
        let existing = cart.find(i => i.id === id);
        if (existing) {
            if (existing.qty + 1 > stock) {
                alert('Insufficient stock!');
                return;
            }
            existing.qty++;
        } else {
            if (1 > stock) {
                alert('Insufficient stock!');
                return;
            }
            cart.push({ id, name, price, qty: 1 });
        }
        renderCart();
    });

    function renderCart() {
        let html = '';
        let subtotal = 0;
        cart.forEach((item, index) => {
            let total = item.price * item.qty;
            subtotal += total;
            html += `
                <div class="cart-item">
                    <div style="flex:2">
                        <strong>${item.name}</strong><br>
                        <small>₹${item.price} × ${item.qty}</small>
                    </div>
                    <div style="flex:1" class="text-end">
                        <strong>₹${total.toFixed(2)}</strong>
                        <div class="qty-control mt-1">
                            <button class="qty-down" data-index="${index}">-</button>
                            <span class="mx-1">${item.qty}</span>
                            <button class="qty-up" data-index="${index}">+</button>
                            <button class="btn btn-sm btn-link text-danger remove-item" data-index="${index}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        if (cart.length === 0) {
            html = '<p class="text-muted text-center">No items added yet</p>';
        }
        $('#cartItems').html(html);
        
        let discount = parseFloat($('#discountAmount').val()) || 0;
        let tax = (subtotal - discount) * (taxRate / 100);
        let total = subtotal - discount + tax;
        let roundOff = Math.round(total) - total;
        total = Math.round(total);
        
        $('#subtotal').text(`₹${subtotal.toFixed(2)}`);
        $('#taxAmount').text(`₹${tax.toFixed(2)}`);
        $('#roundOff').text(`₹${roundOff.toFixed(2)}`);
        $('#totalAmount').text(`₹${total.toFixed(2)}`);
        
        updateBalance();
    }

    function updateBalance() {
        let total = parseFloat($('#totalAmount').text().replace('₹', ''));
        let paid = 0;
        $('.payment-amount').each(function() {
            paid += parseFloat($(this).val()) || 0;
        });
        let balance = total - paid;
        $('#balanceInfo').html(balance > 0 ? `⚠️ Pending: ₹${balance.toFixed(2)}` : `✅ Paid: ₹${(paid).toFixed(2)}`);
    }

    // Qty controls (event delegation)
    $(document).on('click', '.qty-up', function() {
        let idx = $(this).data('index');
        cart[idx].qty++;
        renderCart();
    });
    $(document).on('click', '.qty-down', function() {
        let idx = $(this).data('index');
        if (cart[idx].qty > 1) {
            cart[idx].qty--;
        } else {
            cart.splice(idx, 1);
        }
        renderCart();
    });
    $(document).on('click', '.remove-item', function() {
        let idx = $(this).data('index');
        cart.splice(idx, 1);
        renderCart();
    });

    // Discount change
    $('#discountAmount').on('input', renderCart);

    // Payment management
    $('#addPaymentBtn').on('click', function() {
        let html = `
            <div class="payment-row">
                <select class="form-select payment-mode" style="width: 100px;">
                    <option value="cash">Cash</option>
                    <option value="upi">UPI</option>
                    <option value="card">Card</option>
                </select>
                <input type="number" class="form-control payment-amount" placeholder="Amount" value="0">
                <input type="text" class="form-control payment-ref" placeholder="Ref" style="width: 120px;">
                <button type="button" class="btn btn-sm btn-danger remove-payment">✕</button>
            </div>
        `;
        $('#paymentsList').append(html);
    });
    $(document).on('click', '.remove-payment', function() {
        if ($('.payment-row').length > 1) {
            $(this).closest('.payment-row').remove();
            updateBalance();
        } else {
            alert('At least one payment method required');
        }
    });
    $(document).on('input', '.payment-amount', updateBalance);

    $('#clearCartBtn').on('click', function() {
        if (confirm('Clear entire cart?')) {
            cart = [];
            renderCart();
        }
    });

    // Complete Sale
    $('#completeSaleBtn').on('click', function() {
        if (cart.length === 0) {
            alert('Cart is empty!');
            return;
        }
        
        let payments = [];
        $('.payment-row').each(function() {
            let mode = $(this).find('.payment-mode').val();
            let amount = parseFloat($(this).find('.payment-amount').val()) || 0;
            let reference = $(this).find('.payment-ref').val();
            if (amount > 0) {
                payments.push({ payment_mode: mode, amount: amount, reference_no: reference });
            }
        });
        
        if (payments.length === 0) {
            alert('At least one payment required');
            return;
        }
        
        let saleData = {
            customer_id: $('#customerId').val(),
            sale_date: new Date().toISOString().split('T')[0],
            sale_time: new Date().toLocaleTimeString('en-GB', { hour12: false }),
            discount_amount: parseFloat($('#discountAmount').val()) || 0,
            items: cart.map(i => ({
                item_id: i.id,
                qty: i.qty,
                unit_price: i.price,
                discount_amount: 0,
                tax_amount: 0,
                line_total: i.price * i.qty
            })),
            payments: payments
        };
        
        $.ajax({
            url: '{{ route("sales.store") }}',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            contentType: 'application/json',
            data: JSON.stringify(saleData),
            success: function(response) {
                alert('Sale completed successfully!');
                window.location.href = '/sales/' + response.sale_id;
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });
});
</script>
@endsection