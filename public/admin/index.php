<?php
session_start();

require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

$pageTitle  = 'Dashboard';
$activePage = 'dashboard';

// ── Static product data (mirrors public/index.php) ─────────────────────────
$products = [
    ['id'=>1,  'name'=>'Motorola Mag One VZ-20',         'category'=>'Handheld Radios',  'price'=>3800.00,  'stock'=>10],
    ['id'=>2,  'name'=>'Kenwood TK-2402V16P',             'category'=>'Handheld Radios',  'price'=>4500.00,  'stock'=>7],
    ['id'=>3,  'name'=>'ICOM IC-F11 VHF Transceiver',    'category'=>'Handheld Radios',  'price'=>6200.00,  'stock'=>5],
    ['id'=>4,  'name'=>'Baofeng UV-5R Dual Band HT',     'category'=>'Handheld Radios',  'price'=>1200.00,  'stock'=>25],
    ['id'=>5,  'name'=>'Kenwood NX-P500V Digital Radio', 'category'=>'Digital Radios',   'price'=>8500.00,  'stock'=>4],
    ['id'=>6,  'name'=>'Motorola DP4600e Digital',       'category'=>'Digital Radios',   'price'=>15000.00, 'stock'=>3],
    ['id'=>7,  'name'=>'ICOM IC-M25 Marine VHF',         'category'=>'Marine Radios',    'price'=>7800.00,  'stock'=>6],
    ['id'=>8,  'name'=>'Standard Horizon HX290',         'category'=>'Marine Radios',    'price'=>6800.00,  'stock'=>4],
    ['id'=>9,  'name'=>'Yaesu FT-7900R Mobile Radio',   'category'=>'Mobile Radios',    'price'=>9500.00,  'stock'=>2],
    ['id'=>10, 'name'=>'Motorola CM300d Mobile Radio',  'category'=>'Mobile Radios',    'price'=>12000.00, 'stock'=>3],
    ['id'=>11, 'name'=>'Diamond X50 Dual Band Antenna', 'category'=>'Accessories',      'price'=>2200.00,  'stock'=>15],
    ['id'=>12, 'name'=>'Kenwood KMC-45 Speaker Mic',    'category'=>'Accessories',      'price'=>850.00,   'stock'=>20],
];

// ── Static orders data ─────────────────────────────────────────────────────
$orders = [
    ['id'=>'GC-20260122-004', 'customer'=>'Ana Gonzales',   'date'=>'Jan 22, 2026', 'items'=>3, 'total'=>3600.00,  'status'=>'Pending'],
    ['id'=>'GC-20260120-003', 'customer'=>'Pedro Reyes',    'date'=>'Jan 20, 2026', 'items'=>1, 'total'=>15000.00, 'status'=>'Processing'],
    ['id'=>'GC-20260118-002', 'customer'=>'Maria Santos',   'date'=>'Jan 18, 2026', 'items'=>1, 'total'=>4500.00,  'status'=>'Shipped'],
    ['id'=>'GC-20260115-001', 'customer'=>'Juan dela Cruz', 'date'=>'Jan 15, 2026', 'items'=>2, 'total'=>9300.00,  'status'=>'Delivered'],
    ['id'=>'GC-20260110-005', 'customer'=>'Juan dela Cruz', 'date'=>'Jan 10, 2026', 'items'=>1, 'total'=>7800.00,  'status'=>'Delivered'],
    ['id'=>'GC-20260108-006', 'customer'=>'Carlos Tan',     'date'=>'Jan 8, 2026',  'items'=>2, 'total'=>3050.00,  'status'=>'Cancelled'],
];

// ── KPI Calculations ────────────────────────────────────────────────────────
$totalProducts    = count($products);
$totalOrders      = count($orders);
$totalRevenue     = array_sum(array_column(
    array_filter($orders, fn($o) => $o['status'] !== 'Cancelled'),
    'total'
));
$lowStockItems    = array_filter($products, fn($p) => $p['stock'] <= 5);
$lowStockCount    = count($lowStockItems);
$pendingCount     = count(array_filter($orders, fn($o) => $o['status'] === 'Pending'));
$totalStockValue  = array_sum(array_map(fn($p) => $p['price'] * $p['stock'], $products));

// ── Category stock summary ──────────────────────────────────────────────────
$categoryStats = [];
foreach ($products as $p) {
    $cat = $p['category'];
    if (!isset($categoryStats[$cat])) {
        $categoryStats[$cat] = ['count' => 0, 'stock' => 0, 'value' => 0];
    }
    $categoryStats[$cat]['count']++;
    $categoryStats[$cat]['stock'] += $p['stock'];
    $categoryStats[$cat]['value'] += $p['price'] * $p['stock'];
}

// ── Status counts ───────────────────────────────────────────────────────────
$statusCounts = array_count_values(array_column($orders, 'status'));

require_once __DIR__ . '/../../includes/admin/nav.php';
?>

<!-- ═══════════════════════════════════════════════════ KPI CARDS ═══ -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <!-- Total Products -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500">Total Products</p>
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900"><?= $totalProducts ?></p>
        <p class="text-xs text-gray-400 mt-1"><?= $lowStockCount ?> low stock alerts</p>
    </div>

    <!-- Total Orders -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500">Total Orders</p>
            <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900"><?= $totalOrders ?></p>
        <p class="text-xs text-gray-400 mt-1"><?= $pendingCount ?> pending confirmation</p>
    </div>

    <!-- Total Revenue -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500">Total Revenue</p>
            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900"><?= formatPrice($totalRevenue) ?></p>
        <p class="text-xs text-gray-400 mt-1">excl. cancelled orders</p>
    </div>

    <!-- Low Stock -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 <?= $lowStockCount > 0 ? 'ring-1 ring-orange-200' : '' ?>">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500">Low Stock</p>
            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold <?= $lowStockCount > 0 ? 'text-orange-600' : 'text-gray-900' ?>">
            <?= $lowStockCount ?>
        </p>
        <p class="text-xs text-gray-400 mt-1">products with ≤ 5 units</p>
    </div>

</div>


<!-- ═══════════════════════════════ MAIN GRID: ORDERS + LOW STOCK ═══ -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    <!-- Recent Orders Table -->
    <div class="xl:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-900">Recent Orders</h2>
            <a href="/admin/orders.php"
               class="text-xs text-[#C8102E] font-medium hover:underline">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Order</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Customer</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">Date</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Total</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
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
                    foreach (array_slice($orders, 0, 5) as $order):
                        $sc = $statusClasses[$order['status']] ?? 'bg-gray-100 text-gray-600';
                    ?>
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-5 py-3.5">
                            <span class="font-mono text-xs text-gray-600"><?= e($order['id']) ?></span>
                        </td>
                        <td class="px-5 py-3.5 font-medium text-gray-800"><?= e($order['customer']) ?></td>
                        <td class="px-5 py-3.5 text-gray-500 hidden sm:table-cell"><?= e($order['date']) ?></td>
                        <td class="px-5 py-3.5 text-right font-semibold text-gray-900">
                            <?= formatPrice($order['total']) ?>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold <?= $sc ?>">
                                <?= e($order['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Low Stock Alerts -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-900">Low Stock Alerts</h2>
            <a href="/admin/products.php"
               class="text-xs text-[#C8102E] font-medium hover:underline">Manage →</a>
        </div>
        <?php if (empty($lowStockItems)): ?>
        <div class="px-5 py-10 text-center">
            <svg class="w-10 h-10 text-green-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-gray-500">All stock levels are healthy!</p>
        </div>
        <?php else: ?>
        <ul class="divide-y divide-gray-50">
            <?php foreach ($lowStockItems as $p):
                $urgency = $p['stock'] <= 2 ? 'text-red-600 bg-red-50' : 'text-orange-600 bg-orange-50';
            ?>
            <li class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50/60 transition">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate"><?= e($p['name']) ?></p>
                    <p class="text-xs text-gray-400"><?= e($p['category']) ?></p>
                </div>
                <span class="ml-3 shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold <?= $urgency ?>">
                    <?= $p['stock'] ?> left
                </span>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>

</div>


<!-- ═══════════════════════════════════ ORDER STATUS + STOCK BY CATEGORY ═══ -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Order Status Breakdown -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">Order Status Breakdown</h2>
        <?php
        $statusConfig = [
            'Delivered'  => ['color' => 'bg-green-500',  'light' => 'bg-green-50 text-green-700'],
            'Shipped'    => ['color' => 'bg-purple-500', 'light' => 'bg-purple-50 text-purple-700'],
            'Processing' => ['color' => 'bg-blue-500',   'light' => 'bg-blue-50 text-blue-700'],
            'Pending'    => ['color' => 'bg-yellow-400', 'light' => 'bg-yellow-50 text-yellow-700'],
            'Cancelled'  => ['color' => 'bg-red-500',    'light' => 'bg-red-50 text-red-600'],
        ];
        foreach ($statusConfig as $status => $cfg):
            $count = $statusCounts[$status] ?? 0;
            $pct   = $totalOrders > 0 ? round(($count / $totalOrders) * 100) : 0;
        ?>
        <div class="flex items-center gap-3 mb-3">
            <span class="w-24 text-xs font-medium text-gray-600 shrink-0"><?= $status ?></span>
            <div class="flex-1 bg-gray-100 rounded-full h-2">
                <div class="<?= $cfg['color'] ?> h-2 rounded-full transition-all" style="width:<?= $pct ?>%"></div>
            </div>
            <span class="w-14 text-right text-xs text-gray-500 shrink-0"><?= $count ?> (<?= $pct ?>%)</span>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Stock by Category -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">Stock by Category</h2>
        <?php
        $maxStock = max(array_column(array_values($categoryStats), 'stock'));
        foreach ($categoryStats as $cat => $stats):
            $pct = $maxStock > 0 ? round(($stats['stock'] / $maxStock) * 100) : 0;
        ?>
        <div class="flex items-center gap-3 mb-3">
            <span class="w-28 text-xs font-medium text-gray-600 shrink-0 truncate" title="<?= e($cat) ?>">
                <?= e($cat) ?>
            </span>
            <div class="flex-1 bg-gray-100 rounded-full h-2">
                <div class="bg-[#C8102E] h-2 rounded-full transition-all" style="width:<?= $pct ?>%"></div>
            </div>
            <span class="w-14 text-right text-xs text-gray-500 shrink-0"><?= $stats['stock'] ?> units</span>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<?php require_once __DIR__ . '/../../includes/admin/footer.php'; ?>
