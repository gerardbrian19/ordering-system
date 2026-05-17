<?php
session_start();
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

$pageTitle  = 'Order Confirmed';
$activePage = 'orders';

require_once __DIR__ . '/../../includes/customer/nav.php';
?>

<main class="flex-1 max-w-2xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10">

    <!-- Success Banner -->
    <div class="text-center mb-8">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Order Placed Successfully!</h1>
        <p class="text-gray-500 text-sm mt-2">
            Thank you for your order. We'll process it right away.
        </p>
    </div>

    <!-- Order Details Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        <!-- Order Meta -->
        <div class="bg-[#FFF5F5] px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <p class="text-xs text-[#C8102E] font-medium uppercase tracking-wide">Order ID</p>
                <p id="order-id" class="font-bold text-[#7F1020] text-lg mt-0.5">—</p>
            </div>
            <div class="text-sm text-right">
                <p class="text-[#C8102E]" id="order-date">—</p>
                <p class="text-[#C8102E] text-xs mt-0.5">Estimated delivery: <span id="order-eta">3–5 business days</span></p>
            </div>
        </div>

        <div class="p-6 space-y-6">

            <!-- Items Ordered -->
            <div>
                <h2 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#C8102E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Items Ordered
                </h2>
                <div id="confirmation-items" class="space-y-3">
                    <p class="text-sm text-gray-400">Loading…</p>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- Price Breakdown -->
            <div>
                <h2 class="font-semibold text-gray-900 mb-3">Price Breakdown</h2>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <dt>Subtotal</dt>
                        <dd id="conf-subtotal" class="font-medium text-gray-900">—</dd>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <dt>Shipping</dt>
                        <dd id="conf-shipping" class="font-medium text-gray-900">—</dd>
                    </div>
                    <div class="flex justify-between font-bold text-gray-900 pt-1 border-t border-gray-100">
                        <dt>Total Paid</dt>
                        <dd id="conf-total">—</dd>
                    </div>
                </dl>
            </div>

            <hr class="border-gray-100">

            <!-- Payment Method -->
            <div>
                <h2 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#C8102E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Payment Method
                </h2>
                <div class="bg-gray-50 rounded-xl p-4 text-sm space-y-1.5">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Method</span>
                        <span id="conf-payment" class="font-semibold text-gray-900">—</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Reference No.</span>
                        <span id="conf-reference" class="font-mono text-gray-900">—</span>
                    </div>
                    <div class="flex items-center gap-1.5 pt-1">
                        <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs text-amber-700">Payment verification pending — we'll confirm within 24 hours.</span>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- Delivery Address -->
            <div>
                <h2 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#C8102E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Delivery Address
                </h2>
                <div class="bg-gray-50 rounded-xl p-4 text-sm">
                    <p id="conf-addr-name" class="font-semibold text-gray-900">—</p>
                    <p id="conf-addr-phone" class="text-gray-500 mt-0.5">—</p>
                    <p id="conf-addr-line" class="text-gray-600 mt-1">—</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Buttons -->
    <div class="mt-6 flex flex-col sm:flex-row gap-3">
        <a href="/customer/orders.php"
           class="flex-1 flex items-center justify-center gap-2 bg-[#C8102E] text-white font-semibold
                  rounded-xl py-3 hover:bg-[#A50D25] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Track My Order
        </a>
        <a href="/index.php"
           class="flex-1 flex items-center justify-center gap-2 border border-gray-300 text-gray-700
                  font-medium rounded-xl py-3 hover:bg-gray-50 transition">
            Continue Shopping
        </a>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const raw = localStorage.getItem('shopease_last_order');
    if (!raw) return;

    const order = JSON.parse(raw);

    document.getElementById('order-id').textContent  = order.id ?? '—';
    document.getElementById('order-date').textContent = order.date
        ? new Date(order.date).toLocaleDateString('en-PH', {year:'numeric', month:'long', day:'numeric'})
        : '—';

    // Items
    const itemsEl = document.getElementById('confirmation-items');
    itemsEl.innerHTML = '';
    (order.items || []).forEach(item => {
        itemsEl.insertAdjacentHTML('beforeend', `
            <div class="flex items-center gap-3">
                <img src="${item.image || 'https://via.placeholder.com/48'}" alt="${item.name}"
                     class="w-12 h-12 rounded-lg object-cover bg-gray-100 shrink-0">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">${item.name}</p>
                    <p class="text-xs text-gray-500">Qty: ${item.qty}</p>
                </div>
                <p class="text-sm font-bold text-gray-900">
                    ₱${(item.price * item.qty).toLocaleString('en-PH', {minimumFractionDigits:2})}
                </p>
            </div>
        `);
    });

    // Pricing
    document.getElementById('conf-subtotal').textContent = '₱' + (order.subtotal ?? 0).toLocaleString('en-PH', {minimumFractionDigits:2});
    document.getElementById('conf-shipping').textContent = order.shipping === 0 ? 'Free' : '₱' + (order.shipping ?? 0).toFixed(2);
    document.getElementById('conf-total').textContent    = '₱' + (order.total ?? 0).toLocaleString('en-PH', {minimumFractionDigits:2});

    // Payment
    document.getElementById('conf-payment').textContent   = order.payment ?? '—';
    document.getElementById('conf-reference').textContent = order.reference ?? '—';

    // Address
    const addr = order.address ?? {};
    document.getElementById('conf-addr-name').textContent  = addr.name ?? '—';
    document.getElementById('conf-addr-phone').textContent = addr.phone ?? '—';
    document.getElementById('conf-addr-line').textContent  = [addr.line1, addr.city, addr.province, addr.zip].filter(Boolean).join(', ');
});
</script>

<?php require_once __DIR__ . '/../../includes/customer/footer.php'; ?>
