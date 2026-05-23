<?php
require_once __DIR__ . '/../../includes/session.php';

require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

$pageTitle  = 'Order Management';
$activePage = 'orders';

// ── Staff-allowed status transitions ──────────────────────────────────────
$staffTransitions = [
    'Pending'    => 'Processing',
    'Processing' => 'Shipped',
];

// ── Handle POST ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!validateCsrfToken($submittedToken)) {
        $_SESSION['staff_error'] = 'Invalid security token. Please try again.';
        header('Location: /staff/orders.php');
        exit;
    }

    $action     = $_POST['action'] ?? '';
    $orderId    = trim($_POST['order_id'] ?? '');
    $fromStatus = trim($_POST['from_status'] ?? '');

    // ── Prepare order: stock-check + optional item removal ─────────────────
    if ($action === 'prepare_order' && $orderId !== '' && $fromStatus === 'Pending') {
        $removedJson  = $_POST['removed_items'] ?? '[]';
        $removedItems = json_decode($removedJson, true);
        if (!is_array($removedItems)) {
            $removedItems = [];
        }
        // TODO: If $removedItems is non-empty, DELETE those order_items WHERE order_id = :orderId
        // TODO: UPDATE orders SET status = 'Processing' WHERE id = :orderId
        $removedCount = count($removedItems);
        $note = $removedCount > 0
            ? " ({$removedCount} out-of-stock item(s) removed)"
            : '';
        $_SESSION['staff_success'] = "Order {$orderId} is now being prepared{$note}.";
        header('Location: /staff/orders.php');
        exit;
    }

    // ── Confirm ready: Processing → Shipped ───────────────────────────────
    if ($action === 'confirm_ready' && $orderId !== '' && $fromStatus === 'Processing') {
        // TODO: UPDATE orders SET status = 'Shipped' WHERE id = :orderId AND status = 'Processing'
        $_SESSION['staff_success'] = "Order {$orderId} confirmed as Ready to Ship.";
        header('Location: /staff/orders.php');
        exit;
    }

    $_SESSION['staff_error'] = 'Invalid request.';
    header('Location: /staff/orders.php');
    exit;
}

// ── Flash messages ─────────────────────────────────────────────────────────
$flashSuccess = $_SESSION['staff_success'] ?? null;
$flashError   = $_SESSION['staff_error']   ?? null;
unset($_SESSION['staff_success'], $_SESSION['staff_error']);

// ── Static orders data ─────────────────────────────────────────────────────
$orders = [
    [
        'id'           => 'GC-20260122-004',
        'customer'     => 'Ana Gonzales',
        'email'        => 'ana.gonzales@email.com',
        'date'         => 'Jan 22, 2026',
        'payment'      => 'Bank Transfer',
        'shipping'     => 'Brgy. 18, Naga City',
        'status'       => 'Pending',
        'subtotal'     => 13100.00,
        'shipping_fee' => 0.00,
        'total'        => 13100.00,
        'items'        => [
            ['name' => 'Baofeng UV-5R Dual Band HT',   'qty' => 3, 'price' => 1200.00, 'stock' => 25, 'in_stock' => true],
            ['name' => 'Yaesu FT-7900R Mobile Radio',  'qty' => 1, 'price' => 9500.00, 'stock' => 0,  'in_stock' => false],
        ],
    ],
    [
        'id'           => 'GC-20260120-003',
        'customer'     => 'Pedro Reyes',
        'email'        => 'pedro.reyes@email.com',
        'date'         => 'Jan 20, 2026',
        'payment'      => 'GCash',
        'shipping'     => 'Brgy. 5, Legazpi City',
        'status'       => 'Processing',
        'subtotal'     => 15000.00,
        'shipping_fee' => 0.00,
        'total'        => 15000.00,
        'items'        => [
            ['name' => 'Motorola DP4600e Digital', 'qty' => 1, 'price' => 15000.00, 'stock' => 3, 'in_stock' => true],
        ],
    ],
    [
        'id'           => 'GC-20260118-002',
        'customer'     => 'Maria Santos',
        'email'        => 'maria.santos@email.com',
        'date'         => 'Jan 18, 2026',
        'payment'      => 'Bank Transfer',
        'shipping'     => 'Brgy. 9, Iriga City',
        'status'       => 'Shipped',
        'subtotal'     => 4500.00,
        'shipping_fee' => 0.00,
        'total'        => 4500.00,
        'items'        => [
            ['name' => 'Kenwood TK-2402V16P', 'qty' => 1, 'price' => 4500.00, 'stock' => 7, 'in_stock' => true],
        ],
    ],
    [
        'id'           => 'GC-20260115-001',
        'customer'     => 'Juan dela Cruz',
        'email'        => 'juan.delacruz@email.com',
        'date'         => 'Jan 15, 2026',
        'payment'      => 'GCash',
        'shipping'     => 'Brgy. 1, Daet, Camarines Norte',
        'status'       => 'Delivered',
        'subtotal'     => 7800.00,
        'shipping_fee' => 0.00,
        'total'        => 7800.00,
        'items'        => [
            ['name' => 'ICOM IC-M25 Marine VHF', 'qty' => 1, 'price' => 7800.00, 'stock' => 6, 'in_stock' => true],
        ],
    ],
    [
        'id'           => 'GC-20260110-005',
        'customer'     => 'Juan dela Cruz',
        'email'        => 'juan.delacruz@email.com',
        'date'         => 'Jan 10, 2026',
        'payment'      => 'Bank Transfer',
        'shipping'     => 'Brgy. 1, Daet, Camarines Norte',
        'status'       => 'Delivered',
        'subtotal'     => 9300.00,
        'shipping_fee' => 0.00,
        'total'        => 9300.00,
        'items'        => [
            ['name' => 'ICOM IC-F11 VHF Transceiver',  'qty' => 1, 'price' => 6200.00, 'stock' => 5,  'in_stock' => true],
            ['name' => 'Kenwood KMC-45 Speaker Mic',    'qty' => 1, 'price' => 850.00,  'stock' => 20, 'in_stock' => true],
            ['name' => 'Diamond X50 Dual Band Antenna', 'qty' => 1, 'price' => 2250.00, 'stock' => 15, 'in_stock' => true],
        ],
    ],
    [
        'id'           => 'GC-20260108-006',
        'customer'     => 'Carlos Tan',
        'email'        => 'carlos.tan@email.com',
        'date'         => 'Jan 8, 2026',
        'payment'      => 'GCash',
        'shipping'     => 'Brgy. 3, Sipocot, Camarines Sur',
        'status'       => 'Cancelled',
        'subtotal'     => 3050.00,
        'shipping_fee' => 0.00,
        'total'        => 3050.00,
        'items'        => [
            ['name' => 'Motorola Mag One VZ-20', 'qty' => 1, 'price' => 3800.00, 'stock' => 10, 'in_stock' => true],
        ],
    ],
];

// ── Filter by status tab ────────────────────────────────────────────────────
$filterStatus = $_GET['status'] ?? 'all';
$filterMap    = ['all', 'Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
if (!in_array($filterStatus, $filterMap, true)) {
    $filterStatus = 'all';
}

$filteredOrders = $filterStatus === 'all'
    ? $orders
    : array_values(array_filter($orders, fn($o) => $o['status'] === $filterStatus));

// ── Status counts for tab badges ───────────────────────────────────────────
$statusCounts = array_count_values(array_column($orders, 'status'));

$csrfToken = generateCsrfToken();

require_once __DIR__ . '/../../includes/layouts/staff/nav.php';
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

<!-- ═══════════════════════════════════════════ WORKFLOW GUIDE ═══ -->
<div class="bg-indigo-50 border border-indigo-100 rounded-xl px-4 py-3 mb-5 flex items-start gap-3">
    <svg class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <div class="text-sm text-indigo-800">
        <span class="font-semibold">Staff Workflow:</span>
        Click <span class="font-semibold">Prepare</span> on a Pending order to start preparation,
        then <span class="font-semibold">Confirm Ready</span> once the order is packed and ready to ship.
    </div>
</div>

<!-- ═══════════════════════════════════════════ STATUS FILTER TABS ═══ -->
<div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 mb-4">
    <div class="flex gap-1 overflow-x-auto no-scrollbar flex-wrap">
        <?php
        $tabs = [
            'all'         => ['label' => 'All Orders',  'count' => count($orders)],
            'Pending'     => ['label' => 'Pending',     'count' => $statusCounts['Pending']    ?? 0],
            'Processing'  => ['label' => 'Preparing',   'count' => $statusCounts['Processing'] ?? 0],
            'Shipped'     => ['label' => 'Shipped',     'count' => $statusCounts['Shipped']    ?? 0],
            'Delivered'   => ['label' => 'Delivered',   'count' => $statusCounts['Delivered']  ?? 0],
            'Cancelled'   => ['label' => 'Cancelled',   'count' => $statusCounts['Cancelled']  ?? 0],
        ];
        foreach ($tabs as $key => $tab):
            $isActive = $filterStatus === $key;
        ?>
        <a href="/staff/orders.php?status=<?= urlencode($key) ?>"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-sm font-medium transition whitespace-nowrap
                  <?= $isActive
                      ? 'bg-indigo-600 text-white shadow-sm'
                      : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' ?>">
            <?= e($tab['label']) ?>
            <?php if ($tab['count'] > 0): ?>
            <span class="text-[11px] px-1.5 py-0.5 rounded-full font-semibold
                         <?= $isActive ? 'bg-white/25 text-white' : 'bg-gray-100 text-gray-500' ?>">
                <?= $tab['count'] ?>
            </span>
            <?php endif; ?>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- ═══════════════════════════════════════════ ORDERS TABLE ═══ -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <?php if (empty($filteredOrders)): ?>
    <div class="py-16 text-center">
        <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <p class="text-sm text-gray-500">No orders found for this filter</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-3.5">Order ID</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-3.5">Customer</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-3.5 hidden sm:table-cell">Date</th>
                    <th class="text-right text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-3.5 hidden md:table-cell">Total</th>
                    <th class="text-center text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-3.5">Status</th>
                    <th class="text-center text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-3.5">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($filteredOrders as $order):
                    $statusMap = [
                        'Pending'    => ['bg-orange-50 text-orange-700 border-orange-200', 'bg-orange-400'],
                        'Processing' => ['bg-blue-50 text-blue-700 border-blue-200',       'bg-blue-400'],
                        'Shipped'    => ['bg-indigo-50 text-indigo-700 border-indigo-200', 'bg-indigo-400'],
                        'Delivered'  => ['bg-green-50 text-green-700 border-green-200',    'bg-green-400'],
                        'Cancelled'  => ['bg-gray-50 text-gray-500 border-gray-200',       'bg-gray-300'],
                    ];
                    [$badgeCls, $dotCls] = $statusMap[$order['status']] ?? ['bg-gray-50 text-gray-500 border-gray-200', 'bg-gray-300'];
                    $nextStatus = $staffTransitions[$order['status']] ?? null;
                ?>
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-3.5">
                        <button onclick="openDetail(<?= htmlspecialchars(json_encode($order), ENT_QUOTES) ?>)"
                                class="font-mono text-xs text-indigo-600 hover:text-indigo-700 font-semibold hover:underline transition">
                            <?= e($order['id']) ?>
                        </button>
                    </td>
                    <td class="px-5 py-3.5 font-medium text-gray-800"><?= e($order['customer']) ?></td>
                    <td class="px-5 py-3.5 text-gray-500 hidden sm:table-cell"><?= e($order['date']) ?></td>
                    <td class="px-5 py-3.5 text-right font-semibold text-gray-800 hidden md:table-cell"><?= formatPrice($order['total']) ?></td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-flex items-center gap-1 text-xs font-semibold border px-2.5 py-1 rounded-full <?= $badgeCls ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?= $dotCls ?>"></span>
                            <?= $order['status'] === 'Processing' ? 'Preparing' : e($order['status']) ?>
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <?php if ($order['status'] === 'Pending'): ?>
                        <!-- Prepare: opens preparation workflow modal -->
                        <button onclick="openPrepare(<?= htmlspecialchars(json_encode($order), ENT_QUOTES) ?>)"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-700
                                       bg-blue-50 border border-blue-200 hover:bg-blue-100 px-3 py-1.5
                                       rounded-lg transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8l2 2 4-4"/>
                            </svg>
                            Prepare
                        </button>
                        <?php elseif ($order['status'] === 'Processing'): ?>
                        <!-- Confirm Ready: one-click form submit -->
                        <form method="POST" action="/staff/orders.php" class="inline">
                            <input type="hidden" name="csrf_token"  value="<?= e($csrfToken) ?>">
                            <input type="hidden" name="action"      value="confirm_ready">
                            <input type="hidden" name="order_id"    value="<?= e($order['id']) ?>">
                            <input type="hidden" name="from_status" value="Processing">
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-700
                                           bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 px-3 py-1.5
                                           rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M5 13l4 4L19 7"/>
                                </svg>
                                Confirm Ready
                            </button>
                        </form>
                        <?php else: ?>
                        <span class="text-xs text-gray-300">—</span>
                        <?php endif; ?>

                        <!-- View details -->
                        <button onclick="openDetail(<?= htmlspecialchars(json_encode($order), ENT_QUOTES) ?>)"
                                class="ml-1 inline-flex items-center text-xs text-gray-400 hover:text-gray-600
                                       bg-gray-50 border border-gray-200 hover:bg-gray-100 px-2.5 py-1.5
                                       rounded-lg transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- ═══════════════════════════════════════════ PREPARE ORDER MODAL ═══ -->
<div id="prepare-modal"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden"
     aria-modal="true" role="dialog">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" id="prepare-backdrop"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[92vh] flex flex-col">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
            <div>
                <p class="text-xs font-medium text-blue-500 uppercase tracking-wide">Preparing Order</p>
                <h3 id="prep-order-id" class="text-base font-bold text-gray-900 font-mono mt-0.5"></h3>
            </div>
            <button onclick="closePrep()"
                    class="p-2 rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Customer info bar -->
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-100 shrink-0">
            <div class="flex flex-wrap gap-x-6 gap-y-1 text-xs text-gray-500">
                <span><span class="font-semibold text-gray-700">Customer:</span> <span id="prep-customer"></span></span>
                <span><span class="font-semibold text-gray-700">Ship to:</span> <span id="prep-shipping"></span></span>
                <span><span class="font-semibold text-gray-700">Payment:</span> <span id="prep-payment"></span></span>
            </div>
        </div>

        <!-- Stock warning banner (shown only when out-of-stock items exist) -->
        <div id="prep-warning"
             class="hidden mx-6 mt-4 flex items-start gap-3 bg-red-50 border border-red-200
                    text-red-800 rounded-xl px-4 py-3 text-sm shrink-0">
            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p id="prep-warning-text"></p>
        </div>

        <!-- All-clear banner (shown once all issues resolved) -->
        <div id="prep-allclear"
             class="hidden mx-6 mt-4 flex items-center gap-3 bg-green-50 border border-green-200
                    text-green-800 rounded-xl px-4 py-3 text-sm shrink-0">
            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p>All items are available. Check off each item as you pick it, then confirm ready.</p>
        </div>

        <!-- Items checklist -->
        <div class="flex-1 overflow-y-auto px-6 pt-4 pb-2">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Order Items</p>
            <div id="prep-items" class="space-y-2"></div>
        </div>

        <!-- Removed items summary (hidden until something is removed) -->
        <div id="prep-removed-summary" class="hidden px-6 py-2 shrink-0">
            <p class="text-xs text-gray-400">
                <span class="font-semibold text-red-600" id="prep-removed-count">0</span>
                item(s) removed from this order.
            </p>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/60 rounded-b-2xl shrink-0">
            <form method="POST" action="/staff/orders.php" id="prep-form">
                <input type="hidden" name="csrf_token"    value="<?= e($csrfToken) ?>">
                <input type="hidden" name="action"        value="prepare_order">
                <input type="hidden" name="from_status"   value="Pending">
                <input type="hidden" id="prep-order-id-input" name="order_id" value="">
                <input type="hidden" id="prep-removed-input"  name="removed_items" value="[]">

                <div class="flex items-center justify-between gap-3">
                    <p id="prep-progress-text" class="text-xs text-gray-400"></p>
                    <div class="flex gap-2">
                        <button type="button" onclick="closePrep()"
                                class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border
                                       border-gray-200 rounded-xl hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="submit" id="prep-submit"
                                disabled
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold
                                       text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition
                                       disabled:opacity-40 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Mark as In Preparation
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════ ORDER DETAIL MODAL ═══ -->
<div id="detail-modal"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden"
     aria-modal="true" role="dialog">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" id="detail-backdrop"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 sticky top-0 bg-white rounded-t-2xl z-10">
            <div>
                <p class="text-xs text-gray-400 font-medium">Order Details</p>
                <h3 id="detail-order-id" class="text-base font-bold text-gray-900 font-mono"></h3>
            </div>
            <button onclick="closeDetail()"
                    class="p-2 rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="px-6 py-5 space-y-5">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Customer</p>
                    <p id="detail-customer" class="text-sm font-semibold text-gray-800"></p>
                    <p id="detail-email"    class="text-xs text-gray-400 mt-0.5"></p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Date</p>
                    <p id="detail-date" class="text-sm text-gray-700"></p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Payment</p>
                    <p id="detail-payment" class="text-sm text-gray-700"></p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Status</p>
                    <p id="detail-status" class="text-sm font-semibold"></p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Shipping Address</p>
                    <p id="detail-shipping" class="text-sm text-gray-700"></p>
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Items Ordered</p>
                <div id="detail-items" class="divide-y divide-gray-50 border border-gray-100 rounded-xl overflow-hidden"></div>
            </div>
            <div class="bg-gray-50 rounded-xl px-4 py-3 space-y-1.5">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span><span id="detail-subtotal" class="font-medium"></span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Shipping Fee</span><span id="detail-shipping-fee" class="font-medium"></span>
                </div>
                <div class="flex justify-between text-sm font-bold text-gray-900 pt-1 border-t border-gray-200">
                    <span>Total</span><span id="detail-total"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const fmt = (n) => '₱' + Number(n).toLocaleString('en-PH', { minimumFractionDigits: 2 });

// ══════════════════════════════════════════════ PREPARE MODAL ══
let prepOrder     = null;    // current order object
let removedItems  = [];      // names of removed items

function openPrepare(order) {
    prepOrder    = order;
    removedItems = [];

    document.getElementById('prep-order-id').textContent      = order.id;
    document.getElementById('prep-order-id-input').value      = order.id;
    document.getElementById('prep-customer').textContent      = order.customer;
    document.getElementById('prep-shipping').textContent      = order.shipping;
    document.getElementById('prep-payment').textContent       = order.payment;
    document.getElementById('prep-removed-input').value       = '[]';

    renderPrepItems();

    document.getElementById('prepare-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function renderPrepItems() {
    const order       = prepOrder;
    const container   = document.getElementById('prep-items');
    const warningEl   = document.getElementById('prep-warning');
    const allclearEl  = document.getElementById('prep-allclear');
    const submitBtn   = document.getElementById('prep-submit');
    const progressEl  = document.getElementById('prep-progress-text');
    const removedSumEl= document.getElementById('prep-removed-summary');
    const removedCntEl= document.getElementById('prep-removed-count');

    const activeItems = order.items.filter(i => !removedItems.includes(i.name));
    const outOfStock  = activeItems.filter(i => !i.in_stock || i.stock === 0);
    const lowStock    = activeItems.filter(i => i.in_stock && i.stock > 0 && i.stock <= 5);

    container.innerHTML = order.items.map((item, idx) => {
        const isRemoved  = removedItems.includes(item.name);
        const stockOk    = item.in_stock && item.stock > 0;
        const isLow      = stockOk && item.stock <= 5;
        const checkId    = `pick-${idx}`;

        // Stock badge
        let stockBadge;
        if (isRemoved) {
            stockBadge = `<span class="text-xs text-gray-400 line-through">Removed</span>`;
        } else if (!stockOk) {
            stockBadge = `<span class="inline-flex items-center gap-1 text-xs font-semibold text-red-700
                                bg-red-50 border border-red-200 px-2 py-0.5 rounded-full">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>Out of Stock
                          </span>`;
        } else if (isLow) {
            stockBadge = `<span class="inline-flex items-center gap-1 text-xs font-semibold text-orange-700
                                bg-orange-50 border border-orange-200 px-2 py-0.5 rounded-full">
                            <span class="w-1.5 h-1.5 bg-orange-400 rounded-full"></span>${item.stock} left
                          </span>`;
        } else {
            stockBadge = `<span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700
                                bg-green-50 border border-green-200 px-2 py-0.5 rounded-full">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>In Stock (${item.stock})
                          </span>`;
        }

        // Remove button (for out-of-stock active items)
        const removeBtn = (!isRemoved && !stockOk)
            ? `<button type="button"
                       onclick="removeItem('${item.name.replace(/'/g, "\\'")}')"
                       class="text-xs font-semibold text-red-600 hover:text-red-700
                              bg-red-50 border border-red-200 hover:bg-red-100
                              px-2.5 py-1 rounded-lg transition whitespace-nowrap">
                   Remove
               </button>`
            : '';

        // Undo remove button
        const undoBtn = isRemoved
            ? `<button type="button"
                       onclick="undoRemove('${item.name.replace(/'/g, "\\'")}')"
                       class="text-xs text-gray-500 hover:text-gray-700 underline transition">
                   Undo
               </button>`
            : '';

        const rowOpacity = isRemoved ? 'opacity-40' : '';

        // Pick checkbox (only for in-stock active items)
        const checkbox = (!isRemoved && stockOk)
            ? `<label for="${checkId}" class="flex items-center gap-2 cursor-pointer">
                   <input type="checkbox" id="${checkId}"
                          class="prep-pick w-4 h-4 rounded text-blue-600 cursor-pointer
                                 focus:ring-blue-500 border-gray-300"
                          onchange="refreshPrepState()">
                   <span class="text-xs text-gray-500 select-none">Picked</span>
               </label>`
            : `<span class="text-xs text-gray-300 italic">${isRemoved ? '' : 'N/A'}</span>`;

        return `
        <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-white ${rowOpacity}"
             id="prep-row-${idx}">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800 ${isRemoved ? 'line-through text-gray-400' : ''}">${item.name}</p>
                <div class="flex flex-wrap items-center gap-2 mt-1">
                    <span class="text-xs text-gray-400">Qty: ${item.qty}</span>
                    <span class="text-xs text-gray-300">·</span>
                    <span class="text-xs font-medium text-gray-600">${fmt(item.price * item.qty)}</span>
                    ${stockBadge}
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                ${removeBtn}
                ${undoBtn}
                ${checkbox}
            </div>
        </div>`;
    }).join('');

    // Warning / all-clear banner
    const outCount = outOfStock.length;
    if (outCount > 0) {
        document.getElementById('prep-warning-text').textContent =
            `${outCount} item${outCount > 1 ? 's are' : ' is'} out of stock. Remove ${outCount > 1 ? 'them' : 'it'} from the order before proceeding.`;
        warningEl.classList.remove('hidden');
        allclearEl.classList.add('hidden');
    } else {
        warningEl.classList.add('hidden');
        allclearEl.classList.remove('hidden');
    }

    // Removed summary
    if (removedItems.length > 0) {
        removedCntEl.textContent = removedItems.length;
        removedSumEl.classList.remove('hidden');
    } else {
        removedSumEl.classList.add('hidden');
    }

    refreshPrepState();
}

function removeItem(name) {
    if (!removedItems.includes(name)) {
        removedItems.push(name);
    }
    document.getElementById('prep-removed-input').value = JSON.stringify(removedItems);
    renderPrepItems();
}

function undoRemove(name) {
    removedItems = removedItems.filter(n => n !== name);
    document.getElementById('prep-removed-input').value = JSON.stringify(removedItems);
    renderPrepItems();
}

function refreshPrepState() {
    const order       = prepOrder;
    const submitBtn   = document.getElementById('prep-submit');
    const progressEl  = document.getElementById('prep-progress-text');

    const activeItems = order.items.filter(i => !removedItems.includes(i.name));
    const stockIssues = activeItems.filter(i => !i.in_stock || i.stock === 0);

    if (stockIssues.length > 0) {
        submitBtn.disabled = true;
        progressEl.textContent = `Remove ${stockIssues.length} out-of-stock item(s) to continue.`;
        return;
    }

    const pickBoxes    = document.querySelectorAll('.prep-pick');
    const totalPicks   = pickBoxes.length;
    const checkedPicks = [...pickBoxes].filter(cb => cb.checked).length;

    progressEl.textContent = `${checkedPicks} of ${totalPicks} item${totalPicks !== 1 ? 's' : ''} picked`;
    submitBtn.disabled = (checkedPicks < totalPicks);
}

function closePrep() {
    document.getElementById('prepare-modal').classList.add('hidden');
    document.body.style.overflow = '';
    prepOrder    = null;
    removedItems = [];
}

document.getElementById('prepare-backdrop')?.addEventListener('click', closePrep);

// ══════════════════════════════════════════════ DETAIL MODAL ══
function openDetail(order) {
    document.getElementById('detail-order-id').textContent     = order.id;
    document.getElementById('detail-customer').textContent     = order.customer;
    document.getElementById('detail-email').textContent        = order.email;
    document.getElementById('detail-date').textContent         = order.date;
    document.getElementById('detail-payment').textContent      = order.payment;
    document.getElementById('detail-shipping').textContent     = order.shipping;
    document.getElementById('detail-subtotal').textContent     = fmt(order.subtotal);
    document.getElementById('detail-shipping-fee').textContent = order.shipping_fee === 0 ? 'Free' : fmt(order.shipping_fee);
    document.getElementById('detail-total').textContent        = fmt(order.total);

    const statusEl    = document.getElementById('detail-status');
    const statusColors = {
        Pending: 'text-orange-600', Processing: 'text-blue-600',
        Shipped: 'text-indigo-600', Delivered: 'text-green-600', Cancelled: 'text-gray-500',
    };
    statusEl.textContent = order.status === 'Processing' ? 'Preparing' : order.status;
    statusEl.className   = 'text-sm font-semibold ' + (statusColors[order.status] ?? 'text-gray-700');

    document.getElementById('detail-items').innerHTML = order.items.map(item => `
        <div class="flex items-center justify-between px-4 py-3 bg-white text-sm">
            <div>
                <p class="font-medium text-gray-800">${item.name}</p>
                <p class="text-xs text-gray-400">Qty: ${item.qty}</p>
            </div>
            <p class="font-semibold text-gray-700">${fmt(item.price * item.qty)}</p>
        </div>`).join('');

    document.getElementById('detail-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDetail() {
    document.getElementById('detail-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

document.getElementById('detail-backdrop')?.addEventListener('click', closeDetail);
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') { closeDetail(); closePrep(); }
});
</script>

<?php require_once __DIR__ . '/../../includes/layouts/staff/footer.php'; ?>
