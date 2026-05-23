<?php
session_start();

require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

$pageTitle  = 'Dashboard';
$activePage = 'dashboard';

// ── Static product data ────────────────────────────────────────────────────
$products = [
    ['id'=>1,  'name'=>'Motorola Mag One VZ-20',         'category'=>'Handheld Radios', 'price'=>3800.00,  'stock'=>10],
    ['id'=>2,  'name'=>'Kenwood TK-2402V16P',             'category'=>'Handheld Radios', 'price'=>4500.00,  'stock'=>7],
    ['id'=>3,  'name'=>'ICOM IC-F11 VHF Transceiver',    'category'=>'Handheld Radios', 'price'=>6200.00,  'stock'=>5],
    ['id'=>4,  'name'=>'Baofeng UV-5R Dual Band HT',     'category'=>'Handheld Radios', 'price'=>1200.00,  'stock'=>25],
    ['id'=>5,  'name'=>'Kenwood NX-P500V Digital Radio', 'category'=>'Digital Radios',  'price'=>8500.00,  'stock'=>4],
    ['id'=>6,  'name'=>'Motorola DP4600e Digital',       'category'=>'Digital Radios',  'price'=>15000.00, 'stock'=>3],
    ['id'=>7,  'name'=>'ICOM IC-M25 Marine VHF',         'category'=>'Marine Radios',   'price'=>7800.00,  'stock'=>6],
    ['id'=>8,  'name'=>'Standard Horizon HX290',         'category'=>'Marine Radios',   'price'=>6800.00,  'stock'=>4],
    ['id'=>9,  'name'=>'Yaesu FT-7900R Mobile Radio',   'category'=>'Mobile Radios',   'price'=>9500.00,  'stock'=>2],
    ['id'=>10, 'name'=>'Motorola CM300d Mobile Radio',  'category'=>'Mobile Radios',   'price'=>12000.00, 'stock'=>3],
    ['id'=>11, 'name'=>'Diamond X50 Dual Band Antenna', 'category'=>'Accessories',     'price'=>2200.00,  'stock'=>15],
    ['id'=>12, 'name'=>'Kenwood KMC-45 Speaker Mic',    'category'=>'Accessories',     'price'=>850.00,   'stock'=>20],
];

// ── Static orders data ─────────────────────────────────────────────────────
$orders = [
    ['id'=>'GC-20260122-004', 'customer'=>'Ana Gonzales',   'date'=>'Jan 22, 2026', 'items'=>1, 'total'=>3600.00,  'status'=>'Pending'],
    ['id'=>'GC-20260120-003', 'customer'=>'Pedro Reyes',    'date'=>'Jan 20, 2026', 'items'=>1, 'total'=>15000.00, 'status'=>'Processing'],
    ['id'=>'GC-20260118-002', 'customer'=>'Maria Santos',   'date'=>'Jan 18, 2026', 'items'=>1, 'total'=>4500.00,  'status'=>'Shipped'],
    ['id'=>'GC-20260115-001', 'customer'=>'Juan dela Cruz', 'date'=>'Jan 15, 2026', 'items'=>2, 'total'=>9300.00,  'status'=>'Delivered'],
    ['id'=>'GC-20260110-005', 'customer'=>'Juan dela Cruz', 'date'=>'Jan 10, 2026', 'items'=>1, 'total'=>7800.00,  'status'=>'Delivered'],
    ['id'=>'GC-20260108-006', 'customer'=>'Carlos Tan',     'date'=>'Jan 8, 2026',  'items'=>2, 'total'=>3050.00,  'status'=>'Cancelled'],
];

// ── KPI Calculations ────────────────────────────────────────────────────────
$pendingOrders    = array_values(array_filter($orders, fn($o) => $o['status'] === 'Pending'));
$processingOrders = array_values(array_filter($orders, fn($o) => $o['status'] === 'Processing'));
$lowStockItems    = array_values(array_filter($products, fn($p) => $p['stock'] <= 5));

// Unread message count (static)
$unreadMessages = 3;

require_once __DIR__ . '/../../includes/layouts/staff/nav.php';
?>

<!-- ═══════════════════════════════════════════════════ KPI CARDS ═══ -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <!-- Pending Orders -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5
                <?= count($pendingOrders) > 0 ? 'ring-1 ring-orange-200' : '' ?>">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500">Pending Orders</p>
            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900"><?= count($pendingOrders) ?></p>
        <p class="text-xs text-orange-500 mt-1 font-medium">
            <?= count($pendingOrders) > 0 ? 'Needs preparation' : 'All caught up' ?>
        </p>
    </div>

    <!-- In Progress -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500">In Progress</p>
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900"><?= count($processingOrders) ?></p>
        <p class="text-xs text-gray-400 mt-1">Being prepared</p>
    </div>

    <!-- Unread Messages -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5
                <?= $unreadMessages > 0 ? 'ring-1 ring-indigo-200' : '' ?>">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500">Unread Messages</p>
            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900"><?= $unreadMessages ?></p>
        <p class="text-xs text-indigo-500 mt-1 font-medium">
            <?= $unreadMessages > 0 ? 'Needs reply' : 'Inbox clear' ?>
        </p>
    </div>

    <!-- Low Stock Alerts -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5
                <?= count($lowStockItems) > 0 ? 'ring-1 ring-red-200' : '' ?>">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500">Low Stock</p>
            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900"><?= count($lowStockItems) ?></p>
        <p class="text-xs text-red-500 mt-1 font-medium">
            <?= count($lowStockItems) > 0 ? 'Items need restocking' : 'Stock levels OK' ?>
        </p>
    </div>

</div>

<!-- ═══════════════════════════════════ TWO-COLUMN ROW ═══ -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

    <!-- Orders Needing Action -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-900">Orders Needing Action</h2>
            <a href="/staff/orders.php"
               class="text-xs text-indigo-600 hover:text-indigo-700 font-medium transition">
                View all →
            </a>
        </div>
        <div class="divide-y divide-gray-50">
            <?php
            $actionOrders = array_values(array_filter(
                $orders,
                fn($o) => in_array($o['status'], ['Pending', 'Processing'], true)
            ));
            ?>
            <?php if (empty($actionOrders)): ?>
            <div class="px-5 py-8 text-center">
                <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-500">No orders needing action</p>
            </div>
            <?php else: ?>
            <?php foreach ($actionOrders as $order): ?>
            <div class="flex items-center gap-3 px-5 py-3.5">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate"><?= e($order['id']) ?></p>
                    <p class="text-xs text-gray-400"><?= e($order['customer']) ?> · <?= e($order['date']) ?></p>
                </div>
                <?php if ($order['status'] === 'Pending'): ?>
                <span class="inline-flex items-center gap-1 text-xs font-semibold text-orange-700
                             bg-orange-50 border border-orange-200 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 bg-orange-400 rounded-full"></span>
                    Pending
                </span>
                <?php else: ?>
                <span class="inline-flex items-center gap-1 text-xs font-semibold text-blue-700
                             bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 bg-blue-400 rounded-full"></span>
                    Processing
                </span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Low Stock Alerts -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-900">Low Stock Alerts</h2>
            <a href="/staff/inventory.php"
               class="text-xs text-indigo-600 hover:text-indigo-700 font-medium transition">
                View inventory →
            </a>
        </div>
        <div class="divide-y divide-gray-50">
            <?php if (empty($lowStockItems)): ?>
            <div class="px-5 py-8 text-center">
                <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-sm text-gray-500">All stock levels are healthy</p>
            </div>
            <?php else: ?>
            <?php foreach ($lowStockItems as $item): ?>
            <div class="flex items-center gap-3 px-5 py-3.5">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate"><?= e($item['name']) ?></p>
                    <p class="text-xs text-gray-400"><?= e($item['category']) ?></p>
                </div>
                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full
                             <?= $item['stock'] <= 2
                                 ? 'text-red-700 bg-red-50 border border-red-200'
                                 : 'text-orange-700 bg-orange-50 border border-orange-200' ?>">
                    <?= $item['stock'] ?> left
                </span>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- ═══════════════════════════════════ RECENT ORDERS TABLE ═══ -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
        <h2 class="text-sm font-bold text-gray-900">Recent Orders</h2>
        <a href="/staff/orders.php"
           class="text-xs text-indigo-600 hover:text-indigo-700 font-medium transition">
            Manage orders →
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-3">Order ID</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-3">Customer</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-3 hidden sm:table-cell">Date</th>
                    <th class="text-right text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-3 hidden md:table-cell">Total</th>
                    <th class="text-center text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach (array_slice($orders, 0, 5) as $order):
                    $statusMap = [
                        'Pending'    => ['bg-orange-50 text-orange-700 border-orange-200', 'bg-orange-400'],
                        'Processing' => ['bg-blue-50 text-blue-700 border-blue-200',       'bg-blue-400'],
                        'Shipped'    => ['bg-indigo-50 text-indigo-700 border-indigo-200', 'bg-indigo-400'],
                        'Delivered'  => ['bg-green-50 text-green-700 border-green-200',    'bg-green-400'],
                        'Cancelled'  => ['bg-gray-50 text-gray-500 border-gray-200',       'bg-gray-400'],
                    ];
                    [$badgeCls, $dotCls] = $statusMap[$order['status']] ?? ['bg-gray-50 text-gray-500 border-gray-200', 'bg-gray-400'];
                ?>
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-5 py-3 font-mono text-xs text-gray-600 font-medium"><?= e($order['id']) ?></td>
                    <td class="px-5 py-3 font-medium text-gray-800"><?= e($order['customer']) ?></td>
                    <td class="px-5 py-3 text-gray-500 hidden sm:table-cell"><?= e($order['date']) ?></td>
                    <td class="px-5 py-3 text-right font-semibold text-gray-800 hidden md:table-cell"><?= formatPrice($order['total']) ?></td>
                    <td class="px-5 py-3 text-center">
                        <span class="inline-flex items-center gap-1 text-xs font-semibold border px-2.5 py-1 rounded-full <?= $badgeCls ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?= $dotCls ?>"></span>
                            <?= e($order['status']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/layouts/staff/footer.php'; ?>
