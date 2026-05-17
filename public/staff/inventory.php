<?php
session_start();

require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

$pageTitle  = 'Inventory Monitor';
$activePage = 'inventory';

// ── Static product data (read-only for staff) ──────────────────────────────
$products = [
    ['id'=>1,  'name'=>'Motorola Mag One VZ-20',         'category'=>'Handheld Radios', 'price'=>3800.00,  'stock'=>10, 'in_stock'=>true],
    ['id'=>2,  'name'=>'Kenwood TK-2402V16P',             'category'=>'Handheld Radios', 'price'=>4500.00,  'stock'=>7,  'in_stock'=>true],
    ['id'=>3,  'name'=>'ICOM IC-F11 VHF Transceiver',    'category'=>'Handheld Radios', 'price'=>6200.00,  'stock'=>5,  'in_stock'=>true],
    ['id'=>4,  'name'=>'Baofeng UV-5R Dual Band HT',     'category'=>'Handheld Radios', 'price'=>1200.00,  'stock'=>25, 'in_stock'=>true],
    ['id'=>5,  'name'=>'Kenwood NX-P500V Digital Radio', 'category'=>'Digital Radios',  'price'=>8500.00,  'stock'=>4,  'in_stock'=>true],
    ['id'=>6,  'name'=>'Motorola DP4600e Digital',       'category'=>'Digital Radios',  'price'=>15000.00, 'stock'=>3,  'in_stock'=>true],
    ['id'=>7,  'name'=>'ICOM IC-M25 Marine VHF',         'category'=>'Marine Radios',   'price'=>7800.00,  'stock'=>6,  'in_stock'=>true],
    ['id'=>8,  'name'=>'Standard Horizon HX290',         'category'=>'Marine Radios',   'price'=>6800.00,  'stock'=>4,  'in_stock'=>true],
    ['id'=>9,  'name'=>'Yaesu FT-7900R Mobile Radio',   'category'=>'Mobile Radios',   'price'=>9500.00,  'stock'=>2,  'in_stock'=>false],
    ['id'=>10, 'name'=>'Motorola CM300d Mobile Radio',  'category'=>'Mobile Radios',   'price'=>12000.00, 'stock'=>3,  'in_stock'=>true],
    ['id'=>11, 'name'=>'Diamond X50 Dual Band Antenna', 'category'=>'Accessories',     'price'=>2200.00,  'stock'=>15, 'in_stock'=>true],
    ['id'=>12, 'name'=>'Kenwood KMC-45 Speaker Mic',    'category'=>'Accessories',     'price'=>850.00,   'stock'=>20, 'in_stock'=>true],
];

$categories = ['Handheld Radios', 'Digital Radios', 'Marine Radios', 'Mobile Radios', 'Accessories'];

// ── Summary stats ───────────────────────────────────────────────────────────
$totalProducts = count($products);
$lowStockItems = array_values(array_filter($products, fn($p) => $p['stock'] <= 5));
$outOfStock    = array_values(array_filter($products, fn($p) => !$p['in_stock']));
$inStockCount  = count(array_filter($products, fn($p) => $p['in_stock']));

require_once __DIR__ . '/../../includes/staff/nav.php';
?>

<!-- ── Read-only notice ──────────────────────────────────────────────────── -->
<div class="bg-amber-50 border border-amber-100 rounded-xl px-4 py-3 mb-5 flex items-start gap-3">
    <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
    </svg>
    <p class="text-sm text-amber-800">
        <span class="font-semibold">View only.</span>
        You can monitor stock levels and flag low inventory here.
        To add, edit, or remove products, contact an administrator.
    </p>
</div>

<!-- ═══════════════════════════════════════════════════ SUMMARY CARDS ═══ -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <!-- Total Products -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500">Total Products</p>
            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900"><?= $totalProducts ?></p>
        <p class="text-xs text-gray-400 mt-1"><?= $inStockCount ?> in stock</p>
    </div>

    <!-- Low Stock -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5
                <?= count($lowStockItems) > 0 ? 'ring-1 ring-orange-200' : '' ?>">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500">Low Stock</p>
            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900"><?= count($lowStockItems) ?></p>
        <p class="text-xs <?= count($lowStockItems) > 0 ? 'text-orange-500 font-medium' : 'text-gray-400' ?> mt-1">
            <?= count($lowStockItems) > 0 ? '≤ 5 units remaining' : 'All levels OK' ?>
        </p>
    </div>

    <!-- Out of Stock -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5
                <?= count($outOfStock) > 0 ? 'ring-1 ring-red-200' : '' ?>">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500">Out of Stock</p>
            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900"><?= count($outOfStock) ?></p>
        <p class="text-xs <?= count($outOfStock) > 0 ? 'text-red-500 font-medium' : 'text-gray-400' ?> mt-1">
            <?= count($outOfStock) > 0 ? 'Needs restocking' : 'All items available' ?>
        </p>
    </div>

    <!-- Categories -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
            <p class="text-sm font-medium text-gray-500">Categories</p>
            <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900"><?= count($categories) ?></p>
        <p class="text-xs text-gray-400 mt-1">product categories</p>
    </div>

</div>

<!-- ═══════════════════════════════════════ SEARCH / FILTER BAR ═══ -->
<div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 mb-4
            flex flex-col sm:flex-row gap-3">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" id="search-input" placeholder="Search products…"
               class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg
                      focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </div>
    <select id="category-filter"
            class="text-sm border border-gray-200 rounded-lg px-3 py-2
                   focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
        <option value="<?= e($cat) ?>"><?= e($cat) ?></option>
        <?php endforeach; ?>
    </select>
    <select id="stock-filter"
            class="text-sm border border-gray-200 rounded-lg px-3 py-2
                   focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
        <option value="">All Stock Levels</option>
        <option value="low">Low Stock (≤ 5)</option>
        <option value="out">Out of Stock</option>
        <option value="ok">In Stock</option>
    </select>
</div>

<!-- ═══════════════════════════════════════════════════ PRODUCT TABLE ═══ -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="products-table">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-3.5">#</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-3.5">Product Name</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-3.5 hidden sm:table-cell">Category</th>
                    <th class="text-right text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-3.5 hidden md:table-cell">Price</th>
                    <th class="text-center text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-3.5">Stock</th>
                    <th class="text-center text-xs font-semibold text-gray-400 uppercase tracking-wide px-5 py-3.5">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50" id="products-body">
                <?php foreach ($products as $p):
                    $stockLevel = $p['stock'] <= 0 ? 'out' : ($p['stock'] <= 5 ? 'low' : 'ok');
                    $stockBadge = match($stockLevel) {
                        'out' => 'bg-red-50 text-red-700 border-red-200',
                        'low' => 'bg-orange-50 text-orange-700 border-orange-200',
                        default => 'bg-green-50 text-green-700 border-green-200',
                    };
                ?>
                <tr class="hover:bg-gray-50/50 transition product-row"
                    data-name="<?= strtolower(e($p['name'])) ?>"
                    data-category="<?= e($p['category']) ?>"
                    data-stock-level="<?= $stockLevel ?>">
                    <td class="px-5 py-3.5 text-gray-400 font-medium"><?= $p['id'] ?></td>
                    <td class="px-5 py-3.5">
                        <p class="font-semibold text-gray-800"><?= e($p['name']) ?></p>
                    </td>
                    <td class="px-5 py-3.5 text-gray-500 hidden sm:table-cell"><?= e($p['category']) ?></td>
                    <td class="px-5 py-3.5 text-right font-semibold text-gray-700 hidden md:table-cell"><?= formatPrice($p['price']) ?></td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-flex items-center justify-center text-sm font-bold
                                     w-10 h-7 rounded-lg border <?= $stockBadge ?>">
                            <?= $p['stock'] ?>
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <?php if ($p['in_stock']): ?>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700
                                     bg-green-50 border border-green-200 px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                            In Stock
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-red-700
                                     bg-red-50 border border-red-200 px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                            Out of Stock
                        </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- Empty state (hidden by default) -->
    <div id="empty-state" class="hidden py-16 text-center">
        <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <p class="text-sm text-gray-500">No products match your filters</p>
    </div>
</div>

<script>
// ── Search & filter ────────────────────────────────────────────────────────
const searchInput    = document.getElementById('search-input');
const categoryFilter = document.getElementById('category-filter');
const stockFilter    = document.getElementById('stock-filter');
const rows           = document.querySelectorAll('.product-row');
const emptyState     = document.getElementById('empty-state');

function applyFilters() {
    const search   = searchInput.value.toLowerCase().trim();
    const category = categoryFilter.value;
    const stock    = stockFilter.value;
    let   visible  = 0;

    rows.forEach(row => {
        const matchSearch   = !search   || row.dataset.name.includes(search);
        const matchCategory = !category || row.dataset.category === category;
        const matchStock    = !stock    || row.dataset.stockLevel === stock;

        const show = matchSearch && matchCategory && matchStock;
        row.classList.toggle('hidden', !show);
        if (show) visible++;
    });

    emptyState.classList.toggle('hidden', visible > 0);
}

searchInput.addEventListener('input', applyFilters);
categoryFilter.addEventListener('change', applyFilters);
stockFilter.addEventListener('change', applyFilters);
</script>

<?php require_once __DIR__ . '/../../includes/staff/footer.php'; ?>
