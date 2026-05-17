<?php
session_start();
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

$pageTitle  = 'My Cart';
$activePage = 'products';

require_once __DIR__ . '/../../includes/customer/nav.php';
?>

<main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Shopping Cart</h1>
        <p class="text-sm text-gray-500 mt-1" id="cart-header-count">Loading cart…</p>
    </div>

    <!-- Cart Layout -->
    <div class="flex flex-col lg:flex-row gap-8">

        <!-- ── Cart Items ── -->
        <section class="flex-1">

            <!-- Items List (populated by JS) -->
            <div id="cart-items" class="space-y-4"></div>

            <!-- Empty State -->
            <div id="cart-empty" class="hidden text-center py-24 bg-white rounded-2xl border border-gray-100">
                <div class="text-6xl mb-4">🛒</div>
                <h2 class="text-lg font-semibold text-gray-700">Your cart is empty</h2>
                <p class="text-sm text-gray-500 mt-1 mb-6">Browse our products and add items to get started.</p>
                <a href="/index.php"
                   class="inline-flex items-center gap-2 bg-[#C8102E] text-white font-semibold rounded-lg
                          px-6 py-2.5 hover:bg-[#A50D25] transition">
                    Browse Products
                </a>
            </div>

            <!-- Clear Cart -->
            <div id="cart-actions" class="hidden mt-4 flex justify-end">
                <button id="clear-cart-btn"
                        class="flex items-center gap-2 text-sm text-red-500 hover:text-red-700
                               border border-red-200 hover:border-red-400 rounded-lg px-4 py-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5
                                 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Clear Cart
                </button>
            </div>
        </section>

        <!-- ── Order Summary ── -->
        <aside id="cart-summary"
               class="hidden lg:w-80 xl:w-96 bg-white rounded-2xl border border-gray-100 shadow-sm
                      p-6 h-fit">
            <h2 class="font-bold text-gray-900 text-lg mb-5">Order Summary</h2>

            <dl class="space-y-3 text-sm">
                <div class="flex justify-between text-gray-600">
                    <dt>Subtotal (<span id="summary-count">0</span> items)</dt>
                    <dd id="summary-subtotal" class="font-medium text-gray-900">₱0.00</dd>
                </div>
                <div class="flex justify-between text-gray-600">
                    <dt>Shipping</dt>
                    <dd id="summary-shipping" class="font-medium text-gray-900">₱80.00</dd>
                </div>
                <div class="flex justify-between text-gray-600">
                    <dt>Discount</dt>
                    <dd class="font-medium text-green-600">— ₱0.00</dd>
                </div>
            </dl>

            <hr class="my-4 border-gray-100">

            <div class="flex justify-between text-base font-bold text-gray-900">
                <span>Total</span>
                <span id="summary-total">₱0.00</span>
            </div>

            <p id="free-shipping-notice" class="hidden mt-2 text-xs text-green-600 font-medium text-center">
                🎉 You qualify for free shipping!
            </p>

            <a href="/customer/checkout.php"
               class="mt-5 flex items-center justify-center gap-2 w-full bg-[#C8102E] text-white
                      font-semibold rounded-xl py-3 hover:bg-[#A50D25] transition active:scale-95">
                Proceed to Checkout
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="/index.php"
               class="mt-3 flex items-center justify-center gap-1.5 w-full text-sm text-[#C8102E]
                      hover:text-[#A50D25] transition">
                ← Continue Shopping
            </a>
        </aside>
    </div>
</main>

<!-- Cart Item Template (cloned by JS) -->
<template id="cart-item-tpl">
    <div class="cart-item bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex gap-4 items-start">
        <img class="item-img w-20 h-20 rounded-xl object-cover bg-gray-100 shrink-0" alt="">
        <div class="flex-1 min-w-0">
            <p class="item-name font-semibold text-gray-900 text-sm leading-snug"></p>
            <p class="item-price text-[#C8102E] font-bold text-sm mt-0.5"></p>
            <div class="flex items-center gap-3 mt-3">
                <!-- Qty controls -->
                <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                    <button class="qty-dec px-2.5 py-1.5 text-gray-500 hover:bg-gray-50 transition text-lg leading-none">−</button>
                    <span class="item-qty px-3 py-1.5 text-sm font-semibold text-gray-900 min-w-[2.5rem] text-center"></span>
                    <button class="qty-inc px-2.5 py-1.5 text-gray-500 hover:bg-gray-50 transition text-lg leading-none">+</button>
                </div>
                <button class="item-remove flex items-center gap-1 text-xs text-red-500 hover:text-red-700 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Remove
                </button>
            </div>
        </div>
        <p class="item-total font-bold text-gray-900 text-sm shrink-0"></p>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', () => {
const cartItemsEl  = document.getElementById('cart-items');
const cartEmptyEl  = document.getElementById('cart-empty');
const cartSummary  = document.getElementById('cart-summary');
const cartActions  = document.getElementById('cart-actions');
const headerCount  = document.getElementById('cart-header-count');
const summaryCount = document.getElementById('summary-count');
const subtotalEl   = document.getElementById('summary-subtotal');
const shippingEl   = document.getElementById('summary-shipping');
const totalEl      = document.getElementById('summary-total');
const freeNotice   = document.getElementById('free-shipping-notice');
const SHIPPING_FEE = 80;
const FREE_SHIP_THRESHOLD = 2000;

function renderCart() {
    const cart = getCart();
    cartItemsEl.innerHTML = '';

    if (cart.length === 0) {
        cartEmptyEl.classList.remove('hidden');
        cartSummary.classList.add('hidden');
        cartActions.classList.add('hidden');
        headerCount.textContent = '0 items';
        return;
    }

    cartEmptyEl.classList.add('hidden');
    cartSummary.classList.remove('hidden');
    cartActions.classList.remove('hidden');

    const tpl = document.getElementById('cart-item-tpl');
    let subtotal = 0;

    cart.forEach(item => {
        const clone = tpl.content.cloneNode(true);
        const row   = clone.querySelector('.cart-item');

        clone.querySelector('.item-img').src         = item.image || 'https://via.placeholder.com/80';
        clone.querySelector('.item-img').alt         = item.name;
        clone.querySelector('.item-name').textContent = item.name;
        clone.querySelector('.item-price').textContent = '₱' + item.price.toLocaleString('en-PH', {minimumFractionDigits:2});
        clone.querySelector('.item-qty').textContent   = item.qty;
        clone.querySelector('.item-total').textContent = '₱' + (item.price * item.qty).toLocaleString('en-PH', {minimumFractionDigits:2});

        clone.querySelector('.qty-dec').addEventListener('click', () => {
            if (item.qty > 1) { updateQty(item.id, item.qty - 1); } else { removeFromCart(item.id); renderCart(); }
        });
        clone.querySelector('.qty-inc').addEventListener('click', () => {
            updateQty(item.id, item.qty + 1);
        });
        clone.querySelector('.item-remove').addEventListener('click', () => {
            removeFromCart(item.id);
            renderCart();
        });

        subtotal += item.price * item.qty;
        cartItemsEl.appendChild(clone);
    });

    const shipping   = subtotal >= FREE_SHIP_THRESHOLD ? 0 : SHIPPING_FEE;
    const total      = subtotal + shipping;
    const totalItems = cart.reduce((s, i) => s + i.qty, 0);

    headerCount.textContent    = `${totalItems} item${totalItems !== 1 ? 's' : ''}`;
    summaryCount.textContent   = totalItems;
    subtotalEl.textContent     = '₱' + subtotal.toLocaleString('en-PH', {minimumFractionDigits:2});
    shippingEl.textContent     = shipping === 0 ? 'Free' : '₱' + shipping.toFixed(2);
    totalEl.textContent        = '₱' + total.toLocaleString('en-PH', {minimumFractionDigits:2});
    freeNotice.classList.toggle('hidden', subtotal < FREE_SHIP_THRESHOLD);
}

function updateQty(id, newQty) {
    const cart = getCart();
    const item = cart.find(i => i.id === id);
    if (item) { item.qty = newQty; saveCart(cart); renderCart(); }
}

document.getElementById('clear-cart-btn').addEventListener('click', () => {
    if (confirm('Clear all items from your cart?')) {
        clearCart();
        renderCart();
    }
});

renderCart();
}); // DOMContentLoaded
</script>

<?php require_once __DIR__ . '/../../includes/customer/footer.php'; ?>
