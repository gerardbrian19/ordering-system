<?php
session_start();

require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

$pageTitle  = 'Product Management';
$activePage = 'products';

// ── Handle POST actions (PRG pattern) ──────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!validateCsrfToken($submittedToken)) {
        $_SESSION['admin_error'] = 'Invalid security token. Please try again.';
        header('Location: /admin/products.php');
        exit;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'toggle_stock') {
        $productId = (int)($_POST['product_id'] ?? 0);
        // TODO: UPDATE products SET in_stock = NOT in_stock WHERE id = :id
        $_SESSION['admin_success'] = 'Stock status updated.';
        header('Location: /admin/products.php');
        exit;
    }

    if ($action === 'add_product') {
        $name     = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $price    = (float)($_POST['price'] ?? 0);
        $stock    = (int)($_POST['stock'] ?? 0);
        $desc     = trim($_POST['description'] ?? '');

        if ($name === '' || $category === '' || $price <= 0) {
            $_SESSION['admin_error'] = 'Please fill in all required fields.';
            header('Location: /admin/products.php');
            exit;
        }
        // TODO: INSERT INTO products (name, category, price, stock, description, in_stock) VALUES (...)
        // TODO: Handle image upload via $_FILES['image']
        $_SESSION['admin_success'] = "Product \"{$name}\" added successfully.";
        header('Location: /admin/products.php');
        exit;
    }

    if ($action === 'edit_product') {
        $productId = (int)($_POST['product_id'] ?? 0);
        $name      = trim($_POST['name'] ?? '');
        $category  = trim($_POST['category'] ?? '');
        $price     = (float)($_POST['price'] ?? 0);
        $stock     = (int)($_POST['stock'] ?? 0);
        $desc      = trim($_POST['description'] ?? '');

        if ($productId <= 0 || $name === '' || $category === '' || $price <= 0) {
            $_SESSION['admin_error'] = 'Please fill in all required fields.';
            header('Location: /admin/products.php');
            exit;
        }
        // TODO: UPDATE products SET name=:name, category=:category, price=:price, stock=:stock, description=:desc WHERE id=:id
        // TODO: Handle optional new image upload
        $_SESSION['admin_success'] = "Product \"{$name}\" updated successfully.";
        header('Location: /admin/products.php');
        exit;
    }

    if ($action === 'delete_product') {
        $productId = (int)($_POST['product_id'] ?? 0);
        $name      = trim($_POST['product_name'] ?? 'Product');
        if ($productId <= 0) {
            $_SESSION['admin_error'] = 'Invalid product.';
            header('Location: /admin/products.php');
            exit;
        }
        // TODO: DELETE FROM products WHERE id = :id
        $_SESSION['admin_success'] = "\"{$name}\" deleted successfully.";
        header('Location: /admin/products.php');
        exit;
    }

    header('Location: /admin/products.php');
    exit;
}

// ── Flash messages ─────────────────────────────────────────────────────────
$flashSuccess = $_SESSION['admin_success'] ?? null;
$flashError   = $_SESSION['admin_error']   ?? null;
unset($_SESSION['admin_success'], $_SESSION['admin_error']);

// ── Product data ───────────────────────────────────────────────────────────
$products = [
    ['id'=>1,  'name'=>'Motorola Mag One VZ-20',         'category'=>'Handheld Radios',  'price'=>3800.00,  'stock'=>10, 'in_stock'=>true,  'description'=>'VHF/UHF 16-channel portable radio. IP54 rated, 13-hour battery life, 270g with antenna.',                  'image'=>'https://picsum.photos/seed/moto-vz20/80/80'],
    ['id'=>2,  'name'=>'Kenwood TK-2402V16P',             'category'=>'Handheld Radios',  'price'=>4500.00,  'stock'=>7,  'in_stock'=>true,  'description'=>'VHF 16-channel FM portable transceiver. IP54/55 rated, 5.5-hour battery, MIL-STD-810 G durability.',       'image'=>'https://picsum.photos/seed/kenwood-tk2402/80/80'],
    ['id'=>3,  'name'=>'ICOM IC-F11 VHF Transceiver',    'category'=>'Handheld Radios',  'price'=>6200.00,  'stock'=>5,  'in_stock'=>true,  'description'=>'5W 16-channel VHF portable radio. MIL-STD-810 F, IP54 rated. Ideal for security and industrial use.',       'image'=>'https://picsum.photos/seed/icom-f11/80/80'],
    ['id'=>4,  'name'=>'Baofeng UV-5R Dual Band HT',     'category'=>'Handheld Radios',  'price'=>1200.00,  'stock'=>25, 'in_stock'=>true,  'description'=>'Dual-band VHF/UHF 128-channel budget handheld. 4W output, CTCSS/DCS, 1800mAh Li-ion battery.',             'image'=>'https://picsum.photos/seed/baofeng-uv5r/80/80'],
    ['id'=>5,  'name'=>'Kenwood NX-P500V Digital Radio', 'category'=>'Digital Radios',   'price'=>8500.00,  'stock'=>4,  'in_stock'=>true,  'description'=>'NXDN digital & FM analog dual-mode. 64 channels, IP54/55, 14-hour battery. Compact and lightweight.',       'image'=>'https://picsum.photos/seed/kenwood-nxp500/80/80'],
    ['id'=>6,  'name'=>'Motorola DP4600e Digital',       'category'=>'Digital Radios',   'price'=>15000.00, 'stock'=>3,  'in_stock'=>true,  'description'=>'DMR Tier II professional radio. Integrated GPS, Bluetooth, 32-character display, IP57 waterproof.',          'image'=>'https://picsum.photos/seed/moto-dp4600/80/80'],
    ['id'=>7,  'name'=>'ICOM IC-M25 Marine VHF',         'category'=>'Marine Radios',    'price'=>7800.00,  'stock'=>6,  'in_stock'=>true,  'description'=>'6W Class D DSC marine radio. Float\'n Flash feature, 1500mAh Li-ion, IPX7 waterproof.',                      'image'=>'https://picsum.photos/seed/icom-m25/80/80'],
    ['id'=>8,  'name'=>'Standard Horizon HX290',         'category'=>'Marine Radios',    'price'=>6800.00,  'stock'=>4,  'in_stock'=>true,  'description'=>'6W submersible marine handheld. DSC, GPS, floating design, JIS8 waterproof.',                               'image'=>'https://picsum.photos/seed/stdhorizon-hx290/80/80'],
    ['id'=>9,  'name'=>'Yaesu FT-7900R Mobile Radio',   'category'=>'Mobile Radios',    'price'=>9500.00,  'stock'=>2,  'in_stock'=>false, 'description'=>'Dual-band VHF/UHF 50W FM transceiver. 1000 memory channels, cross-band repeat, detachable front panel.',     'image'=>'https://picsum.photos/seed/yaesu-ft7900/80/80'],
    ['id'=>10, 'name'=>'Motorola CM300d Mobile Radio',  'category'=>'Mobile Radios',    'price'=>12000.00, 'stock'=>3,  'in_stock'=>true,  'description'=>'VHF/UHF 40W professional mobile. 255 channels, weatherproof, heavy-duty for fleet and commercial use.',      'image'=>'https://picsum.photos/seed/moto-cm300d/80/80'],
    ['id'=>11, 'name'=>'Diamond X50 Dual Band Antenna', 'category'=>'Accessories',      'price'=>2200.00,  'stock'=>15, 'in_stock'=>true,  'description'=>'High-gain 144/430 MHz base antenna. 6.5/9.0 dBd gain, 200W power, N-type connector, 1.26m length.',          'image'=>'https://picsum.photos/seed/diamond-x50/80/80'],
    ['id'=>12, 'name'=>'Kenwood KMC-45 Speaker Mic',    'category'=>'Accessories',      'price'=>850.00,   'stock'=>20, 'in_stock'=>true,  'description'=>'Heavy-duty remote speaker microphone. Noise-cancelling, IP54 rated, suits TK and NX series portables.',      'image'=>'https://picsum.photos/seed/kenwood-kmc45/80/80'],
];

$categories = ['Handheld Radios', 'Digital Radios', 'Marine Radios', 'Mobile Radios', 'Accessories'];

$csrfToken = generateCsrfToken();

require_once __DIR__ . '/../../includes/admin/nav.php';
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

<!-- ═══════════════════════════════════════ HEADER ROW ═══ -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
    <div>
        <p class="text-sm text-gray-500"><?= count($products) ?> products in inventory</p>
    </div>
    <button onclick="openAddModal()"
            class="inline-flex items-center gap-2 bg-[#C8102E] text-white text-sm font-semibold
                   rounded-xl px-4 py-2.5 hover:bg-red-700 transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Product
    </button>
</div>

<!-- ═══════════════════════════════════════ SEARCH / FILTER BAR ═══ -->
<div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 mb-4 flex flex-col sm:flex-row gap-3">
    <input type="text" id="product-search" placeholder="Search products…"
           class="flex-1 text-sm border border-gray-300 rounded-lg px-3 py-2
                  focus:outline-none focus:ring-2 focus:ring-[#C8102E] focus:border-transparent">
    <select id="category-filter"
            class="text-sm border border-gray-300 rounded-lg px-3 py-2
                   focus:outline-none focus:ring-2 focus:ring-[#C8102E]">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
        <option value="<?= e($cat) ?>"><?= e($cat) ?></option>
        <?php endforeach; ?>
    </select>
    <select id="stock-filter"
            class="text-sm border border-gray-300 rounded-lg px-3 py-2
                   focus:outline-none focus:ring-2 focus:ring-[#C8102E]">
        <option value="">All Stock</option>
        <option value="in">In Stock</option>
        <option value="out">Out of Stock</option>
        <option value="low">Low Stock (≤ 5)</option>
    </select>
</div>

<!-- ═══════════════════════════════════════ PRODUCTS TABLE ═══ -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="products-table">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide w-12">#</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Product</th>
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">Category</th>
                    <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Price</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Stock</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50" id="products-tbody">
                <?php foreach ($products as $p):
                    $stockClass = $p['stock'] <= 2
                        ? 'text-red-600 font-bold'
                        : ($p['stock'] <= 5 ? 'text-orange-500 font-semibold' : 'text-gray-700');
                ?>
                <tr class="hover:bg-gray-50/60 transition product-row"
                    data-name="<?= strtolower(e($p['name'])) ?>"
                    data-category="<?= e($p['category']) ?>"
                    data-stock="<?= $p['stock'] ?>"
                    data-in-stock="<?= $p['in_stock'] ? '1' : '0' ?>">

                    <td class="px-5 py-3.5 text-gray-400 text-xs"><?= $p['id'] ?></td>

                    <!-- Product name + thumbnail -->
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <img src="<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>"
                                 class="w-10 h-10 rounded-lg object-cover bg-gray-100 shrink-0">
                            <div class="min-w-0">
                                <p class="font-medium text-gray-800 truncate max-w-[180px]"><?= e($p['name']) ?></p>
                                <p class="text-xs text-gray-400 md:hidden"><?= e($p['category']) ?></p>
                            </div>
                        </div>
                    </td>

                    <td class="px-5 py-3.5 text-gray-500 hidden md:table-cell"><?= e($p['category']) ?></td>

                    <td class="px-5 py-3.5 text-right font-semibold text-gray-900">
                        <?= formatPrice($p['price']) ?>
                    </td>

                    <!-- Stock qty with color coding -->
                    <td class="px-5 py-3.5 text-center">
                        <span class="<?= $stockClass ?>">
                            <?= $p['stock'] ?>
                        </span>
                        <?php if ($p['stock'] <= 5 && $p['stock'] > 0): ?>
                        <span class="ml-1 text-[10px] text-orange-500">⚠</span>
                        <?php endif; ?>
                    </td>

                    <!-- In-stock toggle -->
                    <td class="px-5 py-3.5 text-center">
                        <form method="POST" action="/admin/products.php">
                            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                            <input type="hidden" name="action" value="toggle_stock">
                            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                                           transition cursor-pointer
                                           <?= $p['in_stock']
                                               ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                               : 'bg-gray-100 text-gray-500 hover:bg-gray-200' ?>"
                                    title="Click to toggle stock status">
                                <span class="w-1.5 h-1.5 rounded-full <?= $p['in_stock'] ? 'bg-green-500' : 'bg-gray-400' ?>"></span>
                                <?= $p['in_stock'] ? 'In Stock' : 'Out of Stock' ?>
                            </button>
                        </form>
                    </td>

                    <!-- Actions -->
                    <td class="px-5 py-3.5 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <button onclick="openEditModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES) ?>)"
                                    class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                    title="Edit product">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button onclick="confirmDelete(<?= $p['id'] ?>, '<?= e(addslashes($p['name'])) ?>')"
                                    class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                    title="Delete product">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Empty state (shown by JS when search returns nothing) -->
    <div id="empty-state" class="hidden px-5 py-16 text-center">
        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-gray-500 text-sm">No products match your filters.</p>
    </div>
</div>


<!-- ═══════════════════════════════ ADD PRODUCT MODAL ═══ -->
<div id="add-modal"
     class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden"
     aria-modal="true">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-900">Add New Product</h2>
            <button onclick="closeAddModal()" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="/admin/products.php" enctype="multipart/form-data" class="px-6 py-5 space-y-4">
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <input type="hidden" name="action" value="add_product">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#C8102E]"
                       placeholder="e.g. Motorola Mag One VZ-20">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                <select name="category" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-[#C8102E]">
                    <option value="">Select a category…</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= e($cat) ?>"><?= e($cat) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" min="0" step="0.01" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#C8102E]"
                           placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock Qty</label>
                    <input type="number" name="stock" min="0" value="0"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#C8102E]">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full text-sm text-gray-500 border border-gray-300 rounded-lg px-3 py-2
                              file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0
                              file:text-xs file:font-semibold file:bg-[#C8102E] file:text-white
                              hover:file:bg-red-700">
                <p class="text-xs text-gray-400 mt-1">TODO: file upload — JPG, PNG, WebP (max 2MB)</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                 focus:outline-none focus:ring-2 focus:ring-[#C8102E] resize-none"
                          placeholder="Product specifications and features…"></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeAddModal()"
                        class="flex-1 border border-gray-300 text-gray-700 text-sm font-semibold
                               rounded-xl px-4 py-2.5 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 bg-[#C8102E] text-white text-sm font-semibold
                               rounded-xl px-4 py-2.5 hover:bg-red-700 transition">
                    Add Product
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ═══════════════════════════════ EDIT PRODUCT MODAL ═══ -->
<div id="edit-modal"
     class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden"
     aria-modal="true">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-900">Edit Product</h2>
            <button onclick="closeEditModal()" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="/admin/products.php" enctype="multipart/form-data" class="px-6 py-5 space-y-4">
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <input type="hidden" name="action" value="edit_product">
            <input type="hidden" name="product_id" id="edit-product-id">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="edit-name" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#C8102E]">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                <select name="category" id="edit-category" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-[#C8102E]">
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= e($cat) ?>"><?= e($cat) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (₱) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" id="edit-price" min="0" step="0.01" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#C8102E]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock Qty</label>
                    <input type="number" name="stock" id="edit-stock" min="0"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#C8102E]">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Replace Image</label>
                <div id="edit-current-image" class="mb-2 hidden">
                    <img id="edit-image-preview" src="" alt="Current" class="w-16 h-16 rounded-lg object-cover border border-gray-200">
                    <p class="text-xs text-gray-400 mt-1">Current image — upload a new one to replace</p>
                </div>
                <input type="file" name="image" accept="image/*"
                       class="w-full text-sm text-gray-500 border border-gray-300 rounded-lg px-3 py-2
                              file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0
                              file:text-xs file:font-semibold file:bg-[#C8102E] file:text-white
                              hover:file:bg-red-700">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="edit-description" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                                 focus:outline-none focus:ring-2 focus:ring-[#C8102E] resize-none"></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeEditModal()"
                        class="flex-1 border border-gray-300 text-gray-700 text-sm font-semibold
                               rounded-xl px-4 py-2.5 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 bg-[#C8102E] text-white text-sm font-semibold
                               rounded-xl px-4 py-2.5 hover:bg-red-700 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ═══════════════════════════════ DELETE CONFIRM MODAL ═══ -->
<div id="delete-modal"
     class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden"
     aria-modal="true">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <h3 class="text-base font-bold text-gray-900 mb-1">Delete Product</h3>
        <p class="text-sm text-gray-500 mb-5">
            Are you sure you want to delete <strong id="delete-product-name"></strong>?
            This action cannot be undone.
        </p>
        <form method="POST" action="/admin/products.php" id="delete-form">
            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <input type="hidden" name="action" value="delete_product">
            <input type="hidden" name="product_id" id="delete-product-id">
            <input type="hidden" name="product_name" id="delete-product-name-input">
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 border border-gray-300 text-gray-700 text-sm font-semibold
                               rounded-xl py-2.5 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 bg-red-600 text-white text-sm font-semibold
                               rounded-xl py-2.5 hover:bg-red-700 transition">
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>


<script>
// ── Add modal ──────────────────────────────────────────────────────────────
function openAddModal() {
    document.getElementById('add-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeAddModal() {
    document.getElementById('add-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

// ── Edit modal ─────────────────────────────────────────────────────────────
function openEditModal(product) {
    document.getElementById('edit-product-id').value  = product.id;
    document.getElementById('edit-name').value        = product.name;
    document.getElementById('edit-price').value       = product.price;
    document.getElementById('edit-stock').value       = product.stock;
    document.getElementById('edit-description').value = product.description ?? '';

    const catSelect = document.getElementById('edit-category');
    for (const opt of catSelect.options) {
        opt.selected = opt.value === product.category;
    }

    if (product.image) {
        document.getElementById('edit-image-preview').src = product.image;
        document.getElementById('edit-current-image').classList.remove('hidden');
    }

    document.getElementById('edit-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeEditModal() {
    document.getElementById('edit-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

// ── Delete modal ───────────────────────────────────────────────────────────
function confirmDelete(id, name) {
    document.getElementById('delete-product-id').value    = id;
    document.getElementById('delete-product-name').textContent = name;
    document.getElementById('delete-product-name-input').value = name;
    document.getElementById('delete-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close modals on backdrop click
['add-modal', 'edit-modal', 'delete-modal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });
});

// ── Search / filter logic ──────────────────────────────────────────────────
const searchInput    = document.getElementById('product-search');
const categoryFilter = document.getElementById('category-filter');
const stockFilter    = document.getElementById('stock-filter');
const emptyState     = document.getElementById('empty-state');

function filterProducts() {
    const query    = searchInput.value.toLowerCase();
    const category = categoryFilter.value;
    const stock    = stockFilter.value;
    let visible    = 0;

    document.querySelectorAll('.product-row').forEach(row => {
        const name      = row.dataset.name;
        const rowCat    = row.dataset.category;
        const rowStock  = parseInt(row.dataset.stock, 10);
        const rowIn     = row.dataset.inStock === '1';

        const matchQuery    = name.includes(query);
        const matchCategory = !category || rowCat === category;
        const matchStock    = !stock ||
            (stock === 'in'  && rowIn) ||
            (stock === 'out' && !rowIn) ||
            (stock === 'low' && rowStock <= 5);

        const show = matchQuery && matchCategory && matchStock;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    emptyState.classList.toggle('hidden', visible > 0);
}

searchInput.addEventListener('input', filterProducts);
categoryFilter.addEventListener('change', filterProducts);
stockFilter.addEventListener('change', filterProducts);
</script>

<?php require_once __DIR__ . '/../../includes/admin/footer.php'; ?>
