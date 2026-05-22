<?php
session_start();
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
requireLogin();

$pageTitle  = 'My Orders';
$activePage = 'orders';

$orders = [
    [
        'id'      => 'GC-20260115-001',
        'date'    => 'Jan 15, 2026',
        'items'   => [
            ['name' => 'Motorola Mag One VZ-20', 'qty' => 2, 'price' => 3800.00],
            ['name' => 'Kenwood KMC-45 Speaker Mic', 'qty' => 2, 'price' => 850.00],
        ],
        'subtotal' => 9300.00,
        'shipping' => 0.00,
        'total'    => 9300.00,
        'status'   => 'Delivered',
        'payment'  => 'GCash',
        'eta'      => 'Delivered Jan 18, 2026',
    ],
    [
        'id'      => 'GC-20260118-002',
        'date'    => 'Jan 18, 2026',
        'items'   => [
            ['name' => 'Kenwood TK-2402V16P', 'qty' => 1, 'price' => 4500.00],
        ],
        'subtotal' => 4500.00,
        'shipping' => 0.00,
        'total'    => 4500.00,
        'status'   => 'Shipped',
        'payment'  => 'Bank Transfer',
        'eta'      => 'Expected Jan 21, 2026',
    ],
    [
        'id'      => 'GC-20260120-003',
        'date'    => 'Jan 20, 2026',
        'items'   => [
            ['name' => 'Motorola DP4600e Digital Radio', 'qty' => 1, 'price' => 15000.00],
        ],
        'subtotal' => 15000.00,
        'shipping' => 0.00,
        'total'    => 15000.00,
        'status'   => 'Processing',
        'payment'  => 'GCash',
        'eta'      => 'Expected Jan 24, 2026',
    ],
    [
        'id'      => 'GC-20260122-004',
        'date'    => 'Jan 22, 2026',
        'items'   => [
            ['name' => 'Baofeng UV-5R Dual Band HT', 'qty' => 3, 'price' => 1200.00],
        ],
        'subtotal' => 3600.00,
        'shipping' => 0.00,
        'total'    => 3600.00,
        'status'   => 'Pending',
        'payment'  => 'Bank Transfer',
        'eta'      => 'Awaiting payment verification',
    ],
    [
        'id'      => 'GC-20260110-005',
        'date'    => 'Jan 10, 2026',
        'items'   => [
            ['name' => 'ICOM IC-M25 Marine VHF', 'qty' => 1, 'price' => 7800.00],
        ],
        'subtotal' => 7800.00,
        'shipping' => 0.00,
        'total'    => 7800.00,
        'status'   => 'Delivered',
        'payment'  => 'GCash',
        'eta'      => 'Delivered Jan 13, 2026',
    ],
    [
        'id'      => 'GC-20260108-006',
        'date'    => 'Jan 8, 2026',
        'items'   => [
            ['name' => 'Diamond X50 Dual Band Antenna', 'qty' => 1, 'price' => 2200.00],
        ],
        'subtotal' => 2200.00,
        'shipping' => 80.00,
        'total'    => 2280.00,
        'status'   => 'Cancelled',
        'payment'  => 'Bank Transfer',
        'eta'      => 'Order cancelled',
    ],
];

$statusColors = [
    'Pending'    => 'bg-amber-100 text-amber-800',
    'Processing' => 'bg-blue-100 text-blue-800',
    'Shipped'    => 'bg-[#FEECEC] text-[#7F1020]',
    'Delivered'  => 'bg-green-100 text-green-800',
    'Cancelled'  => 'bg-red-100 text-red-800',
];

$statusIcons = [
    'Pending'    => '🕐',
    'Processing' => '⚙️',
    'Shipped'    => '🚚',
    'Delivered'  => '✅',
    'Cancelled'  => '❌',
];

require_once __DIR__ . '/../../includes/customer/nav.php';
?>

<main class="flex-1 max-w-5xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Orders</h1>
            <p class="text-sm text-gray-500 mt-1">Track all your orders and their status</p>
        </div>
    </div>

    <!-- Search + Filter -->
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <div class="relative flex-1">
            <input type="search"
                   id="order-search"
                   placeholder="Search by order ID or product name…"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 pl-10 text-sm
                          focus:outline-none focus:ring-2 focus:ring-[#C8102E] transition">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide pb-1 mb-6">
        <?php
        $tabStatuses = ['All', 'Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
        foreach ($tabStatuses as $i => $tab):
        ?>
        <button data-tab="<?= e($tab) ?>"
                class="status-tab shrink-0 px-4 py-2 rounded-xl text-sm font-medium border transition
                       <?= $i === 0
                           ? 'bg-[#C8102E] text-white border-[#C8102E]'
                           : 'bg-white text-gray-600 border-gray-200 hover:border-[#C8102E] hover:text-[#C8102E]' ?>">
            <?= e($tab) ?>
        </button>
        <?php endforeach; ?>
    </div>

    <!-- Orders List -->
    <div id="orders-list" class="space-y-4">
        <?php foreach ($orders as $order):
            $badgeClass = $statusColors[$order['status']] ?? 'bg-gray-100 text-gray-700';
            $icon       = $statusIcons[$order['status']]  ?? '📦';
            $itemNames  = implode(', ', array_column($order['items'], 'name'));
        ?>
        <div class="order-card bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden
                    hover:shadow-md transition"
             data-status="<?= e($order['status']) ?>"
             data-search="<?= e(strtolower($order['id'] . ' ' . $itemNames)) ?>">

            <!-- Order Header -->
            <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3
                        border-b border-gray-50">
                <div class="flex items-center gap-3">
                    <span class="text-xl"><?= $icon ?></span>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm"><?= e($order['id']) ?></p>
                        <p class="text-xs text-gray-500 mt-0.5">Ordered <?= e($order['date']) ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                 <?= $badgeClass ?>">
                        <?= e($order['status']) ?>
                    </span>
                    <span class="text-sm font-bold text-gray-900">
                        ₱<?= number_format($order['total'], 2) ?>
                    </span>
                </div>
            </div>

            <!-- Order Body -->
            <div class="px-5 py-4">
                <div class="flex flex-col sm:flex-row sm:items-start gap-4">

                    <!-- Items list -->
                    <div class="flex-1 space-y-2">
                        <?php foreach ($order['items'] as $item): ?>
                        <div class="flex items-center justify-between gap-2 text-sm">
                            <span class="text-gray-700 truncate"><?= e($item['name']) ?></span>
                            <span class="text-gray-500 shrink-0">
                                <?= $item['qty'] ?> × ₱<?= number_format($item['price'], 2) ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Meta info -->
                    <div class="sm:text-right text-xs text-gray-500 space-y-1 shrink-0">
                        <p><?= e($order['payment']) ?></p>
                        <p class="font-medium <?= $order['status'] === 'Delivered' ? 'text-green-600' : ($order['status'] === 'Cancelled' ? 'text-red-500' : 'text-[#C8102E]') ?>">
                            <?= e($order['eta']) ?>
                        </p>
                    </div>
                </div>

                <!-- Progress Steps (not for Cancelled) -->
                <?php if ($order['status'] !== 'Cancelled'): ?>
                <?php
                $steps    = ['Pending', 'Processing', 'Shipped', 'Delivered'];
                $current  = array_search($order['status'], $steps);
                if ($current === false) $current = 0;
                ?>
                <div class="mt-4 flex items-center gap-0">
                    <?php foreach ($steps as $si => $step): ?>
                    <div class="flex items-center <?= $si < count($steps) - 1 ? 'flex-1' : '' ?>">
                        <div class="flex flex-col items-center">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                                        <?= $si <= $current ? 'bg-[#C8102E] text-white' : 'bg-gray-200 text-gray-400' ?>">
                                <?= $si <= $current ? '✓' : ($si + 1) ?>
                            </div>
                            <span class="text-[10px] text-gray-500 mt-1 hidden sm:block"><?= $step ?></span>
                        </div>
                        <?php if ($si < count($steps) - 1): ?>
                        <div class="flex-1 h-0.5 mx-1 <?= $si < $current ? 'bg-[#C8102E]' : 'bg-gray-200' ?>"></div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Empty State -->
    <div id="orders-empty" class="hidden text-center py-20">
        <div class="text-5xl mb-4">📦</div>
        <h3 class="text-lg font-semibold text-gray-700">No orders found</h3>
        <p class="text-sm text-gray-500 mt-1">Try adjusting your search or filter.</p>
    </div>

</main>

<script>
const orderCards  = document.querySelectorAll('.order-card');
const statusTabs  = document.querySelectorAll('.status-tab');
const ordersEmpty = document.getElementById('orders-empty');
const searchInput = document.getElementById('order-search');

let activeStatus  = 'All';
let searchQuery   = '';

function filterOrders() {
    let visible = 0;
    orderCards.forEach(card => {
        const matchStatus = activeStatus === 'All' || card.dataset.status === activeStatus;
        const matchSearch = card.dataset.search.includes(searchQuery.toLowerCase());
        const show = matchStatus && matchSearch;
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    ordersEmpty.classList.toggle('hidden', visible > 0);
}

statusTabs.forEach(tab => {
    tab.addEventListener('click', () => {
        statusTabs.forEach(t => {
            t.classList.remove('bg-[#C8102E]', 'text-white', 'border-[#C8102E]');
            t.classList.add('bg-white', 'text-gray-600', 'border-gray-200');
        });
        tab.classList.add('bg-[#C8102E]', 'text-white', 'border-[#C8102E]');
        tab.classList.remove('bg-white', 'text-gray-600', 'border-gray-200');
        activeStatus = tab.dataset.tab;
        filterOrders();
    });
});

searchInput.addEventListener('input', e => {
    searchQuery = e.target.value.trim();
    filterOrders();
});
</script>

<?php require_once __DIR__ . '/../../includes/customer/footer.php'; ?>
