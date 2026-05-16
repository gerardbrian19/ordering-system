<?php
session_start();

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$pageTitle  = 'Shop Products';
$activePage = 'products';

$products = [
    ['id'=>1,  'name'=>'Motorola Mag One VZ-20',         'category'=>'Handheld Radios',  'price'=>3800.00,  'stock'=>10, 'description'=>'VHF/UHF 16-channel portable radio. IP54 rated, 13-hour battery life, 270g with antenna.',                           'image'=>'https://picsum.photos/seed/moto-vz20/400/300'],
    ['id'=>2,  'name'=>'Kenwood TK-2402V16P',             'category'=>'Handheld Radios',  'price'=>4500.00,  'stock'=>7,  'description'=>'VHF 16-channel FM portable transceiver. IP54/55 rated, 5.5-hour battery, MIL-STD-810 G durability.',              'image'=>'https://picsum.photos/seed/kenwood-tk2402/400/300'],
    ['id'=>3,  'name'=>'ICOM IC-F11 VHF Transceiver',    'category'=>'Handheld Radios',  'price'=>6200.00,  'stock'=>5,  'description'=>'5W 16-channel VHF portable radio. MIL-STD-810 F, IP54 rated. Ideal for security and industrial use.',              'image'=>'https://picsum.photos/seed/icom-f11/400/300'],
    ['id'=>4,  'name'=>'Baofeng UV-5R Dual Band HT',     'category'=>'Handheld Radios',  'price'=>1200.00,  'stock'=>25, 'description'=>'Dual-band VHF/UHF 128-channel budget handheld. 4W output, CTCSS/DCS, 1800mAh Li-ion battery.',                    'image'=>'https://picsum.photos/seed/baofeng-uv5r/400/300'],
    ['id'=>5,  'name'=>'Kenwood NX-P500V Digital Radio', 'category'=>'Digital Radios',   'price'=>8500.00,  'stock'=>4,  'description'=>'NXDN digital & FM analog dual-mode. 64 channels, IP54/55, 14-hour battery. Compact and lightweight.',              'image'=>'https://picsum.photos/seed/kenwood-nxp500/400/300'],
    ['id'=>6,  'name'=>'Motorola DP4600e Digital',       'category'=>'Digital Radios',   'price'=>15000.00, 'stock'=>3,  'description'=>'DMR Tier II professional radio. Integrated GPS, Bluetooth, 32-character display, IP57 waterproof.',                 'image'=>'https://picsum.photos/seed/moto-dp4600/400/300'],
    ['id'=>7,  'name'=>'ICOM IC-M25 Marine VHF',         'category'=>'Marine Radios',    'price'=>7800.00,  'stock'=>6,  'description'=>'6W Class D DSC marine radio. Float\'n Flash feature, 1500mAh Li-ion, IPX7 waterproof, all 57 USA channels.',       'image'=>'https://picsum.photos/seed/icom-m25/400/300'],
    ['id'=>8,  'name'=>'Standard Horizon HX290',         'category'=>'Marine Radios',    'price'=>6800.00,  'stock'=>4,  'description'=>'6W submersible marine handheld. DSC, GPS, floating design, JIS8 waterproof. Includes Li-ion battery & charger.',   'image'=>'https://picsum.photos/seed/stdhorizon-hx290/400/300'],
    ['id'=>9,  'name'=>'Yaesu FT-7900R Mobile Radio',   'category'=>'Mobile Radios',    'price'=>9500.00,  'stock'=>2,  'description'=>'Dual-band VHF/UHF 50W FM transceiver. 1000 memory channels, cross-band repeat, detachable front panel.',          'image'=>'https://picsum.photos/seed/yaesu-ft7900/400/300'],
    ['id'=>10, 'name'=>'Motorola CM300d Mobile Radio',  'category'=>'Mobile Radios',    'price'=>12000.00, 'stock'=>3,  'description'=>'VHF/UHF 40W professional mobile. 255 channels, weatherproof, heavy-duty for fleet and commercial use.',            'image'=>'https://picsum.photos/seed/moto-cm300d/400/300'],
    ['id'=>11, 'name'=>'Diamond X50 Dual Band Antenna', 'category'=>'Accessories',      'price'=>2200.00,  'stock'=>15, 'description'=>'High-gain 144/430 MHz base antenna. 6.5/9.0 dBd gain, 200W power, N-type connector, 1.26m length.',               'image'=>'https://picsum.photos/seed/diamond-x50/400/300'],
    ['id'=>12, 'name'=>'Kenwood KMC-45 Speaker Mic',    'category'=>'Accessories',      'price'=>850.00,   'stock'=>20, 'description'=>'Heavy-duty remote speaker microphone. Noise-cancelling, IP54 rated, suits TK and NX series portables.',            'image'=>'https://picsum.photos/seed/kenwood-kmc45/400/300'],
];

$categories = ['All', 'Handheld Radios', 'Digital Radios', 'Marine Radios', 'Mobile Radios', 'Accessories'];

require_once __DIR__ . '/../includes/customer_nav.php';
?>

<!-- ═══════════════════════════════════════════════════════ HERO ══════ -->
<section class="bg-gradient-to-r from-[#111111] to-[#C8102E] text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 flex flex-col sm:flex-row items-center gap-10">
        <div class="flex-1 text-center sm:text-left">
            <span class="inline-block bg-white/20 text-white text-xs font-semibold rounded-full px-3 py-1 mb-4">
                📶 Philippines' Trusted Radio Specialists
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight mb-4">
                Two-Way Radio <br class="hidden sm:block">Equipment &amp; Systems
            </h1>
            <p class="text-white/80 text-lg mb-8 max-w-md mx-auto sm:mx-0">
                Motorola, ICOM, Kenwood, Yaesu, and more. Trusted by businesses, fleets, and teams.
            </p>
            <div class="flex flex-wrap gap-3 justify-center sm:justify-start">
                <a href="#products"
                   class="bg-white text-[#C8102E] font-semibold rounded-xl px-6 py-3 hover:bg-gray-50 transition">
                    Shop Now
                </a>
                <a href="#categories"
                   class="border border-white/40 text-white font-medium rounded-xl px-6 py-3 hover:bg-white/10 transition">
                    Browse Categories
                </a>
            </div>
        </div>
        <!-- Hero illustration (emoji-based, no external image needed) -->
        <div class="hidden sm:flex items-center justify-center w-64 h-64 bg-white/10 rounded-3xl text-8xl">
            �
        </div>
    </div>
</section>


<!-- ═════════════════════════════════════════════════ CATEGORY FILTER ═ -->
<section id="categories" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-3 overflow-x-auto scrollbar-hide pb-1">
        <?php foreach ($categories as $i => $cat): ?>
        <button
            data-category="<?= htmlspecialchars($cat) ?>"
            class="category-btn shrink-0 px-4 py-2 rounded-xl text-sm font-medium border transition
                   <?= $i === 0
                       ? 'bg-[#C8102E] text-white border-[#C8102E]'
                       : 'bg-white text-gray-600 border-gray-200 hover:border-[#C8102E] hover:text-[#C8102E]' ?>"
        >
            <?= htmlspecialchars($cat) ?>
        </button>
        <?php endforeach; ?>
    </div>
</section>


<!-- ══════════════════════════════════════════════════ PRODUCT GRID ═══ -->
<main id="products" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">

    <!-- Section header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Featured Products</h2>
            <p class="text-sm text-gray-500 mt-0.5" id="product-count">
                    Showing <?= count($products) ?> products
            </p>
        </div>
        <div class="flex items-center gap-2">
            <label for="sort-select" class="sr-only">Sort by</label>
            <select id="sort-select"
                    class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 text-gray-700
                           focus:outline-none focus:ring-2 focus:ring-[#C8102E]">
                <option value="default">Featured</option>
                <option value="price-asc">Price: Low to High</option>
                <option value="price-desc">Price: High to Low</option>
                <option value="name-asc">Name: A–Z</option>
            </select>
        </div>
    </div>

    <!-- Grid -->
    <div id="product-grid"
         class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

        <?php foreach ($products as $product): ?>
        <div class="product-card bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden
                    hover:shadow-lg hover:-translate-y-1 transition-all duration-200 group
                    flex flex-col"
             data-category="<?= htmlspecialchars($product['category']) ?>"
             data-name="<?= htmlspecialchars(strtolower($product['name'])) ?>"
             data-price="<?= $product['price'] ?>">

            <!-- Image -->
            <div class="relative overflow-hidden aspect-square bg-gray-100">
                <img
                    src="<?= htmlspecialchars($product['image']) ?>"
                    alt="<?= htmlspecialchars($product['name']) ?>"
                    class="w-full h-full object-cover"
                    loading="lazy"
                >
                <!-- Category badge -->
                <span class="absolute top-3 left-3 bg-white/90 text-[#C8102E] text-xs font-semibold
                             rounded-full px-2.5 py-1 backdrop-blur-sm">
                    <?= htmlspecialchars($product['category']) ?>
                </span>
                <!-- Out of stock overlay -->
                <?php if ($product['stock'] === 0): ?>
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                    <span class="bg-white text-gray-800 font-semibold text-sm rounded-lg px-3 py-1">
                        Out of Stock
                    </span>
                </div>
                <?php endif; ?>
            </div>

            <!-- Info -->
            <div class="p-4 flex flex-col flex-1">
                <h3 class="font-semibold text-gray-900 text-sm leading-snug mb-1 line-clamp-2">
                    <?= htmlspecialchars($product['name']) ?>
                </h3>
                <!-- Description -->
                <p class="text-xs text-gray-500 mt-1 mb-2 line-clamp-2"><?= e($product['description']) ?></p>
                <!-- Stock indicator -->
                <?php if ($product['stock'] > 0 && $product['stock'] <= 5): ?>
                <p class="text-xs text-orange-600 font-medium mb-2">Only <?= $product['stock'] ?> left!</p>
                <?php endif; ?>

                <div class="mt-auto flex items-center justify-between gap-2 pt-3">
                    <span class="text-base font-bold text-gray-900">
                        ₱<?= number_format($product['price'], 2) ?>
                    </span>
                    <button
                        <?= $product['stock'] === 0 ? 'disabled' : '' ?>
                        onclick="addToCart({
                            id: <?= $product['id'] ?>,
                            name: '<?= htmlspecialchars($product['name'], ENT_QUOTES) ?>',
                            price: <?= $product['price'] ?>,
                            image: '<?= htmlspecialchars($product['image'], ENT_QUOTES) ?>'
                        })"
                        class="flex items-center gap-1.5 bg-[#C8102E] text-white text-sm font-medium
                               rounded-lg px-3 py-1.5 hover:bg-[#A50D25] transition active:scale-95
                               disabled:opacity-40 disabled:cursor-not-allowed disabled:active:scale-100"
                        aria-label="Add <?= htmlspecialchars($product['name']) ?> to cart"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v16m8-8H4"/>
                        </svg>
                        Add
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

    </div>

    <!-- Empty state (hidden by default, shown by JS) -->
    <div id="empty-state" class="hidden text-center py-24">
        <div class="text-5xl mb-4">🔍</div>
        <h3 class="text-lg font-semibold text-gray-700">No products found</h3>
        <p class="text-sm text-gray-500 mt-1">Try a different search or category.</p>
    </div>

</main>


<!-- ════════════════════════════════════════════════════════ FOOTER ════ -->
<footer class="bg-white border-t border-gray-200 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 bg-[#C8102E] rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                        </svg>
                    </div>
                    <span class="font-bold text-gray-900">Gold<span class="text-[#C8102E]">comm</span></span>
                </div>
                <p class="text-sm text-gray-500">Two-way radio equipment, systems, and installations.</p>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Quick Links</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="/index.php"    class="hover:text-[#C8102E] transition">Home</a></li>
                    <li><a href="/cart.php"     class="hover:text-[#C8102E] transition">Cart</a></li>
                    <li><a href="/orders.php"   class="hover:text-[#C8102E] transition">My Orders</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Account</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="/login.php"    class="hover:text-[#C8102E] transition">Sign In</a></li>
                    <li><a href="/register.php" class="hover:text-[#C8102E] transition">Register</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-8 pt-6 border-t border-gray-100 text-center text-xs text-gray-400">
            &copy; <?= date('Y') ?> Goldcomm Corporation &mdash; Two-Way Radio Specialists
        </div>
    </div>
</footer>

<script>
// ─── Category Filter ─────────────────────────────────────────────────────────
const categoryBtns = document.querySelectorAll('.category-btn');
const productCards = document.querySelectorAll('#product-grid [data-category]');
const emptyState   = document.getElementById('empty-state');
const productCount = document.getElementById('product-count');

let activeCategory = 'All';
let searchQuery    = '';

function filterProducts() {
    let visible = 0;
    productCards.forEach(card => {
        const matchCat    = activeCategory === 'All' || card.dataset.category === activeCategory;
        const matchSearch = card.dataset.name.includes(searchQuery.toLowerCase());
        const show        = matchCat && matchSearch;
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    emptyState.classList.toggle('hidden', visible > 0);
    productCount.textContent = `Showing ${visible} product${visible !== 1 ? 's' : ''}`;
}

categoryBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        categoryBtns.forEach(b => {
            b.classList.remove('bg-[#C8102E]', 'text-white', 'border-[#C8102E]');
            b.classList.add('bg-white', 'text-gray-600', 'border-gray-200');
        });
        btn.classList.add('bg-[#C8102E]', 'text-white', 'border-[#C8102E]');
        btn.classList.remove('bg-white', 'text-gray-600', 'border-gray-200');
        activeCategory = btn.dataset.category;
        filterProducts();
    });
});

document.getElementById('search-input').addEventListener('input', e => {
    searchQuery = e.target.value.trim();
    filterProducts();
});

document.getElementById('sort-select').addEventListener('change', e => {
    const grid  = document.getElementById('product-grid');
    const cards = [...productCards].filter(c => c.style.display !== 'none');
    cards.sort((a, b) => {
        switch (e.target.value) {
            case 'price-asc':  return +a.dataset.price - +b.dataset.price;
            case 'price-desc': return +b.dataset.price - +a.dataset.price;
            case 'name-asc':   return a.dataset.name.localeCompare(b.dataset.name);
            default:           return 0;
        }
    });
    cards.forEach(card => grid.appendChild(card));
});
</script>
<?php require_once __DIR__ . '/../includes/customer_footer.php'; ?>
