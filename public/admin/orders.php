<?php
session_start();

require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

$pageTitle  = 'Order Management';
$activePage = 'orders';

// ── Valid statuses ──────────────────────────────────────────────────────────
$validStatuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];

// ── Handle POST: update order status ───────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!validateCsrfToken($submittedToken)) {
        $_SESSION['admin_error'] = 'Invalid security token. Please try again.';
        header('Location: /admin/orders.php');
        exit;
    }

    $action  = $_POST['action'] ?? '';
    $orderId = trim($_POST['order_id'] ?? '');
    $status  = trim($_POST['status'] ?? '');

    if ($action === 'update_status' && $orderId !== '' && in_array($status, $validStatuses, true)) {
        // TODO: UPDATE orders SET status = :status WHERE id = :id
        $_SESSION['admin_success'] = "Order {$orderId} updated to \"{$status}\".";
    } else {
        $_SESSION['admin_error'] = 'Invalid request.';
    }

    header('Location: /admin/orders.php');
    exit;
}

// ── Flash messages ─────────────────────────────────────────────────────────
$flashSuccess = $_SESSION['admin_success'] ?? null;
$flashError   = $_SESSION['admin_error']   ?? null;
unset($_SESSION['admin_success'], $_SESSION['admin_error']);

// ── Static orders data ─────────────────────────────────────────────────────
$orders = [
    [
        'id'       => 'GC-20260122-004',
        'customer' => 'Ana Gonzales',
        'email'    => 'ana.gonzales@email.com',
        'date'     => 'Jan 22, 2026',
        'payment'  => 'Bank Transfer',
        'shipping' => 'Brgy. 18, Naga City',
        'status'   => 'Pending',
        'subtotal' => 3600.00,
        'shipping_fee' => 0.00,
        'total'    => 3600.00,
        'items'    => [
            ['name' => 'Baofeng UV-5R Dual Band HT', 'qty' => 3, 'price' => 1200.00],
        ],
    ],
    [
        'id'       => 'GC-20260120-003',
        'customer' => 'Pedro Reyes',
        'email'    => 'pedro.reyes@email.com',
        'date'     => 'Jan 20, 2026',
        'payment'  => 'GCash',
        'shipping' => 'Brgy. 5, Legazpi City',
        'status'   => 'Processing',
        'subtotal' => 15000.00,
        'shipping_fee' => 0.00,
        'total'    => 15000.00,
        'items'    => [
            ['name' => 'Motorola DP4600e Digital', 'qty' => 1, 'price' => 15000.00],
        ],
    ],
    [
        'id'       => 'GC-20260118-002',
        'customer' => 'Maria Santos',
        'email'    => 'maria.santos@email.com',
        'date'     => 'Jan 18, 2026',
        'payment'  => 'Bank Transfer',
        'shipping' => 'Brgy. 9, Iriga City',
        'status'   => 'Shipped',
        'subtotal' => 4500.00,
        'shipping_fee' => 0.00,
        'total'    => 4500.00,
        'items'    => [
            ['name' => 'Kenwood TK-2402V16P', 'qty' => 1, 'price' => 4500.00],
        ],
    ],
    [
        'id'       => 'GC-20260115-001',
        'customer' => 'Juan dela Cruz',
        'email'    => 'juan.delacruz@email.com',
        'date'     => 'Jan 15, 2026',
        'payment'  => 'GCash',
        'shipping' => 'Brgy. 1, Daet, Camarines Norte',
        'status'   => 'Delivered',
        'subtotal' => 9300.00,
        'shipping_fee' => 0.00,
        'total'    => 9300.00,
        'items'    => [
            ['name' => 'Motorola Mag One VZ-20',     'qty' => 2, 'price' => 3800.00],
            ['name' => 'Kenwood KMC-45 Speaker Mic', 'qty' => 2, 'price' => 850.00],
        ],
    ],
    [
        'id'       => 'GC-20260110-005',
        'customer' => 'Juan dela Cruz',
        'email'    => 'juan.delacruz@email.com',
        'date'     => 'Jan 10, 2026',
        'payment'  => 'GCash',
        'shipping' => 'Brgy. 1, Daet, Camarines Norte',
        'status'   => 'Delivered',
        'subtotal' => 7800.00,
        'shipping_fee' => 0.00,
        'total'    => 7800.00,
        'items'    => [
            ['name' => 'ICOM IC-M25 Marine VHF', 'qty' => 1, 'price' => 7800.00],
        ],
    ],
    [
        'id'       => 'GC-20260108-006',
        'customer' => 'Carlos Tan',
        'email'    => 'carlos.tan@email.com',
        'date'     => 'Jan 8, 2026',
        'payment'  => 'GCash',
        'shipping' => 'Brgy. 3, Sorsogon City',
        'status'   => 'Cancelled',
        'subtotal' => 3050.00,
        'shipping_fee' => 0.00,
        'total'    => 3050.00,
        'items'    => [
            ['name' => 'Diamond X50 Dual Band Antenna', 'qty' => 1, 'price' => 2200.00],
            ['name' => 'Kenwood KMC-45 Speaker Mic',    'qty' => 1, 'price' => 850.00],
        ],
    ],
];

// ── Status filter (from GET) ────────────────────────────────────────────────
$filterStatus    = $_GET['status'] ?? 'all';
$allowedFilters  = array_merge(['all'], $validStatuses);
if (!in_array($filterStatus, $allowedFilters, true)) {
    $filterStatus = 'all';
}

$filteredOrders = $filterStatus === 'all'
    ? $orders
    : array_filter($orders, fn($o) => $o['status'] === $filterStatus);

$statusCounts = array_count_values(array_column($orders, 'status'));

$csrfToken = generateCsrfToken();

require_once __DIR__ . '/../../includes/layouts/admin/nav.php';
?>

<!-- Flash messages -->
<?php if ($flashSuccess): ?>
<div id="flash-message"
     class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm font-medium">
    <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <?= e($flashSuccess) ?>
</div>
<?php endif; ?>
<?php if ($flashError): ?>
<div id="flash-message"
     class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm font-medium">
    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <?= e($flashError) ?>
</div>
<?php endif; ?>

<!-- ═══════════════════════════════════════ STATUS FILTER TABS ═══ -->
<div class="flex items-center gap-2 overflow-x-auto scrollbar-hide pb-1 mb-5">
    <?php
    $tabConfig = [
        'all'        => ['label' => 'All Orders',  'count' => count($orders)],
        'Pending'    => ['label' => 'Pending',      'count' => $statusCounts['Pending']    ?? 0],
        'Processing' => ['label' => 'Processing',   'count' => $statusCounts['Processing'] ?? 0],
        'Shipped'    => ['label' => 'Shipped',      'count' => $statusCounts['Shipped']    ?? 0],
        'Delivered'  => ['label' => 'Delivered',    'count' => $statusCounts['Delivered']  ?? 0],
        'Cancelled'  => ['label' => 'Cancelled',    'count' => $statusCounts['Cancelled']  ?? 0],
    ];
    foreach ($tabConfig as $key => $tab):
        $isActive = $filterStatus === $key;
    ?>
    <a href="/admin/orders.php<?= $key !== 'all' ? '?status=' . urlencode($key) : '' ?>"
       class="shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition
              <?= $isActive
                  ? 'bg-[#C8102E] text-white shadow-sm'
                  : 'bg-white text-gray-600 border border-gray-200 hover:border-[#C8102E] hover:text-[#C8102E]' ?>">
        <?= $tab['label'] ?>
        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-[10px] font-bold
                     <?= $isActive ? 'bg-white/30 text-white' : 'bg-gray-100 text-gray-500' ?>">
            <?= $tab['count'] ?>
        </span>
    </a>
    <?php endforeach; ?>
</div>

<!-- ═══════════════════════════════════════ ORDERS TABLE ═══ -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <?php if (empty($filteredOrders)): ?>
    <div class="px-5 py-16 text-center">
        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <p class="text-gray-500 text-sm">No orders with this status.</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Order ID</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Customer</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">Date</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">Items</th>
                    <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Total</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php
                $statusClasses = [
                    'Pending'    => 'bg-yellow-100 text-yellow-700',
                    'Processing' => 'bg-blue-100 text-blue-700',
                    'Shipped'    => 'bg-purple-100 text-purple-700',
                    'Delivered'  => 'bg-green-100 text-green-700',
                    'Cancelled'  => 'bg-red-100 text-red-600',
                ];
                foreach ($filteredOrders as $order):
                    $sc = $statusClasses[$order['status']] ?? 'bg-gray-100 text-gray-600';
                    $itemCount = array_sum(array_column($order['items'], 'qty'));
                ?>
                <tr class="hover:bg-gray-50/60 transition">
                    <td class="px-5 py-4">
                        <span class="font-mono text-xs text-gray-700 font-semibold"><?= e($order['id']) ?></span>
                    </td>
                    <td class="px-5 py-4">
                        <p class="font-medium text-gray-800"><?= e($order['customer']) ?></p>
                        <p class="text-xs text-gray-400"><?= e($order['payment']) ?></p>
                    </td>
                    <td class="px-5 py-4 text-gray-500 hidden md:table-cell"><?= e($order['date']) ?></td>
                    <td class="px-5 py-4 text-center text-gray-600 hidden sm:table-cell">
                        <?= $itemCount ?> item<?= $itemCount !== 1 ? 's' : '' ?>
                    </td>
                    <td class="px-5 py-4 text-right font-bold text-gray-900">
                        <?= formatPrice($order['total']) ?>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold <?= $sc ?>">
                            <?= e($order['status']) ?>
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <!-- View details -->
                            <button onclick="openOrderDetail(<?= htmlspecialchars(json_encode($order), ENT_QUOTES) ?>)"
                                    class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                    title="View details">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            <!-- Update status -->
                            <button onclick="openStatusModal('<?= e($order['id']) ?>', '<?= e($order['status']) ?>')"
                                    class="p-1.5 text-gray-400 hover:text-[#C8102E] hover:bg-red-50 rounded-lg transition"
                                    title="Update status">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>


<!-- ═══════════════════════════════ ORDER DETAIL MODAL ═══ -->
<div id="detail-modal"
     class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden"
     aria-modal="true">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h2 class="text-base font-bold text-gray-900">Order Details</h2>
                <p id="detail-order-id" class="text-xs font-mono text-gray-400 mt-0.5"></p>
            </div>
            <button onclick="closeDetailModal()" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="px-6 py-5 space-y-5">
            <!-- Customer info -->
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Customer</p>
                    <p class="font-semibold text-gray-800" id="detail-customer"></p>
                    <p class="text-gray-500 text-xs" id="detail-email"></p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Order Date</p>
                    <p class="font-semibold text-gray-800" id="detail-date"></p>
                    <p class="text-gray-500 text-xs" id="detail-payment"></p>
                </div>
            </div>

            <!-- Shipping address -->
            <div class="text-sm">
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Shipping Address</p>
                <p class="text-gray-700" id="detail-shipping"></p>
            </div>

            <!-- Status badge -->
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Status</p>
                <span id="detail-status-badge" class="inline-flex px-3 py-1 rounded-full text-xs font-semibold"></span>
            </div>

            <!-- Items -->
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Order Items</p>
                <div id="detail-items" class="space-y-2 rounded-xl border border-gray-100 overflow-hidden"></div>
            </div>

            <!-- Totals -->
            <div class="bg-gray-50 rounded-xl p-4 text-sm space-y-1.5">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span id="detail-subtotal" class="font-medium"></span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Shipping</span>
                    <span id="detail-shipping-fee" class="font-medium"></span>
                </div>
                <div class="flex justify-between font-bold text-gray-900 pt-1.5 border-t border-gray-200">
                    <span>Total</span>
                    <span id="detail-total"></span>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ═══════════════════════════════ UPDATE STATUS MODAL ═══ -->
<div id="status-modal"
     class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden"
     aria-modal="true">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-base font-bold text-gray-900">Update Order Status</h2>
            <button onclick="closeStatusModal()" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="/admin/orders.php">
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <input type="hidden" name="action" value="update_status">
            <input type="hidden" name="order_id" id="status-order-id">

            <p class="text-xs text-gray-500 mb-3">Order: <span id="status-order-label" class="font-mono font-semibold text-gray-700"></span></p>

            <label class="block text-sm font-medium text-gray-700 mb-2">New Status</label>
            <select name="status" id="status-select"
                    class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm
                           focus:outline-none focus:ring-2 focus:ring-[#C8102E] mb-5">
                <?php foreach ($validStatuses as $s): ?>
                <option value="<?= e($s) ?>"><?= e($s) ?></option>
                <?php endforeach; ?>
            </select>

            <div class="flex gap-3">
                <button type="button" onclick="closeStatusModal()"
                        class="flex-1 border border-gray-300 text-gray-700 text-sm font-semibold
                               rounded-xl py-2.5 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 bg-[#C8102E] text-white text-sm font-semibold
                               rounded-xl py-2.5 hover:bg-red-700 transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>


<script>
const statusClasses = {
    Pending:    'bg-yellow-100 text-yellow-700',
    Processing: 'bg-blue-100 text-blue-700',
    Shipped:    'bg-purple-100 text-purple-700',
    Delivered:  'bg-green-100 text-green-700',
    Cancelled:  'bg-red-100 text-red-600',
};

// ── Order detail modal ─────────────────────────────────────────────────────
function openOrderDetail(order) {
    document.getElementById('detail-order-id').textContent   = order.id;
    document.getElementById('detail-customer').textContent   = order.customer;
    document.getElementById('detail-email').textContent      = order.email;
    document.getElementById('detail-date').textContent       = order.date;
    document.getElementById('detail-payment').textContent    = 'via ' + order.payment;
    document.getElementById('detail-shipping').textContent   = order.shipping;

    const badge = document.getElementById('detail-status-badge');
    badge.textContent = order.status;
    badge.className = 'inline-flex px-3 py-1 rounded-full text-xs font-semibold ' + (statusClasses[order.status] ?? 'bg-gray-100 text-gray-600');

    const itemsEl = document.getElementById('detail-items');
    itemsEl.innerHTML = order.items.map((item, i) => `
        <div class="flex items-center justify-between px-4 py-3 ${i > 0 ? 'border-t border-gray-100' : ''}">
            <div>
                <p class="text-sm font-medium text-gray-800">${item.name}</p>
                <p class="text-xs text-gray-400">Qty: ${item.qty}</p>
            </div>
            <p class="text-sm font-semibold text-gray-900">₱${(item.price * item.qty).toLocaleString('en-PH', {minimumFractionDigits:2})}</p>
        </div>
    `).join('');

    const fmt = v => '₱' + parseFloat(v).toLocaleString('en-PH', {minimumFractionDigits:2});
    document.getElementById('detail-subtotal').textContent     = fmt(order.subtotal);
    document.getElementById('detail-shipping-fee').textContent = order.shipping_fee == 0 ? 'Free' : fmt(order.shipping_fee);
    document.getElementById('detail-total').textContent        = fmt(order.total);

    document.getElementById('detail-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeDetailModal() {
    document.getElementById('detail-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

// ── Status update modal ────────────────────────────────────────────────────
function openStatusModal(orderId, currentStatus) {
    document.getElementById('status-order-id').value       = orderId;
    document.getElementById('status-order-label').textContent = orderId;
    const select = document.getElementById('status-select');
    for (const opt of select.options) {
        opt.selected = opt.value === currentStatus;
    }
    document.getElementById('status-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeStatusModal() {
    document.getElementById('status-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close modals on backdrop click
['detail-modal', 'status-modal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });
});
</script>

<?php require_once __DIR__ . '/../../includes/layouts/admin/footer.php'; ?>
