<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$pageTitle  = 'Checkout';
$activePage = 'products';

// Static default shipping address
$defaultAddress = [
    'name'     => 'Juan dela Cruz',
    'phone'    => '09171234567',
    'line1'    => '123 Sampaguita Street, Brgy. San Antonio',
    'city'     => 'Pasig City',
    'province' => 'Metro Manila',
    'zip'      => '1600',
];

require_once __DIR__ . '/../includes/customer_nav.php';
?>

<main class="flex-1 max-w-5xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="/cart.php" class="hover:text-[#C8102E] transition">Cart</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-900 font-medium">Checkout</span>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">

        <!-- ── Left Column ── -->
        <div class="flex-1 space-y-6">

            <!-- Shipping Address -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#C8102E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Shipping Address
                    </h2>
                    <a href="/shipping_address.php"
                       class="text-sm text-[#C8102E] hover:text-[#A50D25] font-medium transition">
                        Change
                    </a>
                </div>
                <div class="bg-[#FFF5F5] rounded-xl p-4 border border-[#FEECEC]">
                    <p class="font-semibold text-gray-900"><?= e($defaultAddress['name']) ?></p>
                    <p class="text-sm text-gray-600 mt-0.5"><?= e($defaultAddress['phone']) ?></p>
                    <p class="text-sm text-gray-600 mt-1">
                        <?= e($defaultAddress['line1']) ?>,
                        <?= e($defaultAddress['city']) ?>,
                        <?= e($defaultAddress['province']) ?>
                        <?= e($defaultAddress['zip']) ?>
                    </p>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#C8102E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Order Items
                </h2>
                <div id="checkout-items" class="space-y-3">
                    <p class="text-sm text-gray-400">Loading…</p>
                </div>
            </div>
        </div>

        <!-- ── Right Column: Summary ── -->
        <aside class="lg:w-80 xl:w-96">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-20">
                <h2 class="font-bold text-gray-900 text-lg mb-5">Payment Summary</h2>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <dt>Subtotal</dt>
                        <dd id="co-subtotal" class="font-medium text-gray-900">₱0.00</dd>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <dt>Shipping</dt>
                        <dd id="co-shipping" class="font-medium text-gray-900">₱80.00</dd>
                    </div>
                </dl>

                <hr class="my-4 border-gray-100">

                <div class="flex justify-between text-base font-bold text-gray-900 mb-5">
                    <span>Total</span>
                    <span id="co-total">₱0.00</span>
                </div>

                <button id="place-order-btn"
                        class="w-full bg-[#C8102E] text-white font-semibold rounded-xl py-3.5
                               hover:bg-[#A50D25] transition active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Place Order
                </button>

                <p class="text-xs text-gray-400 text-center mt-3">
                    You will select a payment method in the next step.
                </p>
            </div>
        </aside>
    </div>
</main>

<!-- ═══════════════════════════════════════════ PAYMENT MODAL ══ -->
<div id="payment-modal"
     class="hidden fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">

        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
            <h3 class="font-bold text-gray-900 text-lg">Select Payment Method</h3>
            <button id="modal-close"
                    class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="overflow-y-auto p-6 space-y-6">

            <!-- Method Selection -->
            <div class="grid grid-cols-2 gap-3">
                <label class="payment-method-card cursor-pointer" data-method="gcash">
                    <input type="radio" name="payment_method" value="gcash" class="sr-only" checked>
                    <div class="border-2 border-[#C8102E] bg-[#FFF5F5] rounded-xl p-4 text-center transition">
                        <div class="text-2xl mb-1">💙</div>
                        <p class="font-semibold text-gray-900 text-sm">GCash</p>
                        <p class="text-xs text-gray-500 mt-0.5">Mobile wallet</p>
                    </div>
                </label>
                <label class="payment-method-card cursor-pointer" data-method="bank">
                    <input type="radio" name="payment_method" value="bank" class="sr-only">
                    <div class="border-2 border-gray-200 bg-white rounded-xl p-4 text-center transition">
                        <div class="text-2xl mb-1">🏦</div>
                        <p class="font-semibold text-gray-900 text-sm">Bank Transfer</p>
                        <p class="text-xs text-gray-500 mt-0.5">BDO / BPI</p>
                    </div>
                </label>
            </div>

            <!-- Payment Instructions -->
            <div id="payment-instructions" class="bg-gray-50 rounded-xl p-4 text-sm space-y-1.5">
                <p class="font-semibold text-gray-800" id="instr-title">GCash Instructions</p>
                <p class="text-gray-600" id="instr-line1">Send payment to: <strong>0917-123-4567</strong></p>
                <p class="text-gray-600" id="instr-line2">Account name: <strong>ShopEase Store</strong></p>
                <p class="text-gray-600 text-xs" id="instr-note">After sending, enter the reference number and upload your screenshot below.</p>
            </div>

            <!-- Reference Number -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Reference / Transaction Number <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="reference-number"
                       placeholder="e.g. 1234567890"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#C8102E] transition">
            </div>

            <!-- Screenshot Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Payment Screenshot <span class="text-red-500">*</span>
                </label>
                <label id="upload-area"
                       class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300
                              rounded-xl p-6 cursor-pointer hover:border-[#C8102E] hover:bg-[#FFF5F5]/40
                              transition text-center">
                    <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828
                                 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-gray-600"><span class="text-[#C8102E] font-medium">Click to upload</span> or drag & drop</p>
                    <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF up to 5MB</p>
                    <input type="file" id="screenshot-upload" accept="image/*" class="hidden">
                    <p id="file-name" class="hidden text-xs text-green-600 font-medium mt-2"></p>
                </label>
            </div>

            <!-- Error message -->
            <p id="modal-error" class="hidden text-sm text-red-600 font-medium"></p>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-100 flex gap-3 shrink-0">
            <button id="modal-cancel"
                    class="flex-1 border border-gray-300 text-gray-700 font-medium rounded-xl py-2.5
                           hover:bg-gray-50 transition text-sm">
                Cancel
            </button>
            <button id="confirm-payment-btn"
                    class="flex-1 bg-[#C8102E] text-white font-semibold rounded-xl py-2.5
                           hover:bg-[#A50D25] transition active:scale-95 text-sm">
                Confirm & Place Order
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
const SHIPPING_FEE   = 80;
const FREE_THRESHOLD = 2000;

// ── Render checkout items ─────────────────────────────────────────────────────
function renderCheckout() {
    const cart        = getCart();
    const itemsEl     = document.getElementById('checkout-items');
    const coSubtotal  = document.getElementById('co-subtotal');
    const coShipping  = document.getElementById('co-shipping');
    const coTotal     = document.getElementById('co-total');

    if (cart.length === 0) {
        itemsEl.innerHTML = '<p class="text-sm text-gray-500">Your cart is empty. <a href="/index.php" class="text-[#C8102E] underline">Shop now</a></p>';
        document.getElementById('place-order-btn').disabled = true;
        return;
    }

    itemsEl.innerHTML = '';
    let subtotal = 0;

    cart.forEach(item => {
        subtotal += item.price * item.qty;
        itemsEl.insertAdjacentHTML('beforeend', `
            <div class="flex items-center gap-3">
                <img src="${item.image || 'https://via.placeholder.com/56'}" alt="${item.name}"
                     class="w-14 h-14 rounded-lg object-cover bg-gray-100 shrink-0">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">${item.name}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Qty: ${item.qty}</p>
                </div>
                <p class="text-sm font-bold text-gray-900 shrink-0">
                    ₱${(item.price * item.qty).toLocaleString('en-PH', {minimumFractionDigits:2})}
                </p>
            </div>
        `);
    });

    const shipping = subtotal >= FREE_THRESHOLD ? 0 : SHIPPING_FEE;
    const total    = subtotal + shipping;

    coSubtotal.textContent = '₱' + subtotal.toLocaleString('en-PH', {minimumFractionDigits:2});
    coShipping.textContent = shipping === 0 ? 'Free' : '₱' + shipping.toFixed(2);
    coTotal.textContent    = '₱' + total.toLocaleString('en-PH', {minimumFractionDigits:2});
}

renderCheckout();

// ── Payment Modal ─────────────────────────────────────────────────────────────
const modal        = document.getElementById('payment-modal');
const instrTitle   = document.getElementById('instr-title');
const instrLine1   = document.getElementById('instr-line1');
const instrLine2   = document.getElementById('instr-line2');

const paymentDetails = {
    gcash: {
        title: 'GCash Instructions',
        line1: 'Send payment to: <strong>0917-123-4567</strong>',
        line2: 'Account name: <strong>ShopEase Store</strong>',
    },
    bank: {
        title: 'Bank Transfer Instructions',
        line1: 'BDO Savings Account: <strong>1234-5678-9012</strong>',
        line2: 'Account name: <strong>ShopEase Corporation</strong>',
    },
};

document.getElementById('place-order-btn').addEventListener('click', () => {
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
});

function closeModal() {
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

document.getElementById('modal-close').addEventListener('click', closeModal);
document.getElementById('modal-cancel').addEventListener('click', closeModal);
modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });

// Method selection UI
document.querySelectorAll('.payment-method-card').forEach(card => {
    card.addEventListener('click', () => {
        const method = card.dataset.method;
        card.querySelector('input').checked = true;

        document.querySelectorAll('.payment-method-card > div').forEach(d => {
            d.classList.remove('border-[#C8102E]', 'bg-[#FFF5F5]');
            d.classList.add('border-gray-200', 'bg-white');
        });
        card.querySelector('div').classList.add('border-[#C8102E]', 'bg-[#FFF5F5]');
        card.querySelector('div').classList.remove('border-gray-200', 'bg-white');

        const info = paymentDetails[method];
        instrTitle.textContent = info.title;
        instrLine1.innerHTML   = 'Send payment to: ' + info.line1.replace('Send payment to: ', '');
        instrLine1.innerHTML   = info.line1;
        instrLine2.innerHTML   = info.line2;
    });
});

// File upload label
document.getElementById('screenshot-upload').addEventListener('change', e => {
    const fileNameEl = document.getElementById('file-name');
    if (e.target.files[0]) {
        fileNameEl.textContent = '✓ ' + e.target.files[0].name;
        fileNameEl.classList.remove('hidden');
    }
});

// Confirm payment
document.getElementById('confirm-payment-btn').addEventListener('click', () => {
    const refNum = document.getElementById('reference-number').value.trim();
    const file   = document.getElementById('screenshot-upload').files[0];
    const method = document.querySelector('input[name="payment_method"]:checked')?.value || 'gcash';
    const errEl  = document.getElementById('modal-error');

    if (!refNum) {
        errEl.textContent = 'Please enter the reference / transaction number.';
        errEl.classList.remove('hidden');
        return;
    }
    if (!file) {
        errEl.textContent = 'Please upload your payment screenshot.';
        errEl.classList.remove('hidden');
        return;
    }

    errEl.classList.add('hidden');

    // Build order object and save to localStorage
    const cart     = getCart();
    const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
    const shipping = subtotal >= FREE_THRESHOLD ? 0 : SHIPPING_FEE;
    const orderId  = 'SE-' + Date.now().toString().slice(-8);

    const order = {
        id:        orderId,
        date:      new Date().toISOString(),
        items:     cart,
        subtotal:  subtotal,
        shipping:  shipping,
        total:     subtotal + shipping,
        payment:   method === 'gcash' ? 'GCash' : 'Bank Transfer',
        reference: refNum,
        address: {
            name:     '<?= e($defaultAddress['name']) ?>',
            phone:    '<?= e($defaultAddress['phone']) ?>',
            line1:    '<?= e($defaultAddress['line1']) ?>',
            city:     '<?= e($defaultAddress['city']) ?>',
            province: '<?= e($defaultAddress['province']) ?>',
            zip:      '<?= e($defaultAddress['zip']) ?>',
        },
        status: 'Pending',
    };

    localStorage.setItem('shopease_last_order', JSON.stringify(order));
    clearCart();
    window.location.href = '/order_confirmation.php';
});
}); // DOMContentLoaded
</script>

<?php require_once __DIR__ . '/../includes/customer_footer.php'; ?>
