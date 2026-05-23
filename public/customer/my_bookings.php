<?php
session_start();
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

$pageTitle  = 'My Bookings';
$activePage = 'bookings';

// Static fallback bookings (shown when localStorage has nothing)
$staticBookings = [
    [
        'id'      => 'SB-001',
        'service' => 'Radio Programming',
        'date'    => 'Jan 25, 2026',
        'time'    => '10:00 AM',
        'status'  => 'Confirmed',
        'price'   => 299.00,
    ],
    [
        'id'      => 'SB-002',
        'service' => 'System Installation',
        'date'    => 'Jan 28, 2026',
        'time'    => '8:00 AM',
        'status'  => 'Confirmed',
        'price'   => 5000.00,
    ],
    [
        'id'      => 'SB-003',
        'service' => 'Signal Coverage Survey',
        'date'    => 'Jan 30, 2026',
        'time'    => '9:00 AM',
        'status'  => 'Pending',
        'price'   => 3000.00,
    ],
    [
        'id'      => 'SB-004',
        'service' => 'Radio Repair',
        'date'    => 'Jan 20, 2026',
        'time'    => '9:00 AM',
        'status'  => 'Cancelled',
        'price'   => 500.00,
    ],
];

$statusColors = [
    'Pending'   => 'bg-amber-100 text-amber-800',
    'Confirmed' => 'bg-green-100 text-green-800',
    'Cancelled' => 'bg-red-100 text-red-800',
];

$statusIcons = [
    'Pending'   => '🕐',
    'Confirmed' => '✅',
    'Cancelled' => '❌',
];

require_once __DIR__ . '/../../includes/layouts/customer/nav.php';
?>

<main class="flex-1 max-w-4xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Bookings</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your service appointments</p>
        </div>
        <a href="/customer/services.php"
           class="inline-flex items-center gap-2 bg-[#C8102E] text-white font-semibold rounded-xl
                  px-4 py-2.5 text-sm hover:bg-[#A50D25] transition self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Book a Service
        </a>
    </div>

    <!-- Status Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide pb-1 mb-6">
        <?php foreach (['All', 'Pending', 'Confirmed', 'Cancelled'] as $i => $tab): ?>
        <button data-tab="<?= e($tab) ?>"
                class="booking-tab shrink-0 px-4 py-2 rounded-xl text-sm font-medium border transition
                       <?= $i === 0
                           ? 'bg-[#C8102E] text-white border-[#C8102E]'
                           : 'bg-white text-gray-600 border-gray-200 hover:border-[#C8102E] hover:text-[#C8102E]' ?>">
            <?= e($tab) ?>
        </button>
        <?php endforeach; ?>
    </div>

    <!-- Bookings List — JS renders from localStorage, PHP static is fallback -->
    <div id="bookings-list" class="space-y-4">
        <?php foreach ($staticBookings as $b):
            $badge = $statusColors[$b['status']] ?? 'bg-gray-100 text-gray-700';
            $icon  = $statusIcons[$b['status']] ?? '📋';
        ?>
        <div class="booking-card static-booking bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden
                    hover:shadow-md transition"
             data-status="<?= e($b['status']) ?>">
            <div class="px-5 py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-2xl"><?= $icon ?></span>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm"><?= e($b['service']) ?></p>
                        <p class="text-xs text-gray-500 mt-0.5"><?= e($b['id']) ?> · <?= e($b['date']) ?> at <?= e($b['time']) ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold <?= $badge ?>">
                        <?= e($b['status']) ?>
                    </span>
                    <span class="text-sm font-bold text-gray-900">₱<?= number_format($b['price'], 2) ?></span>
                    <?php if ($b['status'] === 'Pending'): ?>
                    <button class="cancel-booking-btn text-xs text-red-500 hover:text-red-700 border border-red-200
                                   hover:border-red-400 rounded-lg px-2.5 py-1 transition font-medium">
                        Cancel
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Empty State -->
    <div id="bookings-empty" class="hidden text-center py-20">
        <div class="text-5xl mb-4">📋</div>
        <h3 class="text-lg font-semibold text-gray-700">No bookings yet</h3>
        <p class="text-sm text-gray-500 mt-1 mb-5">Book a service to get started.</p>
        <a href="/customer/services.php"
           class="inline-flex items-center gap-2 bg-[#C8102E] text-white font-semibold rounded-xl
                  px-5 py-2.5 text-sm hover:bg-[#A50D25] transition">
            Browse Services
        </a>
    </div>
</main>

<!-- Cancel Confirmation Modal -->
<div id="cancel-modal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full">
        <div class="text-center mb-5">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <h3 class="font-bold text-gray-900 text-lg">Cancel Booking?</h3>
            <p class="text-gray-500 text-sm mt-1">This action cannot be undone.</p>
        </div>
        <div class="flex gap-3">
            <button id="cancel-modal-no"
                    class="flex-1 border border-gray-300 text-gray-700 font-medium rounded-xl py-2.5
                           hover:bg-gray-50 transition text-sm">
                Keep Booking
            </button>
            <button id="cancel-modal-yes"
                    class="flex-1 bg-red-600 text-white font-semibold rounded-xl py-2.5
                           hover:bg-red-700 transition text-sm">
                Cancel It
            </button>
        </div>
    </div>
</div>

<script>
const bookingTabs  = document.querySelectorAll('.booking-tab');
const bookingsEmpty = document.getElementById('bookings-empty');
const cancelModal  = document.getElementById('cancel-modal');
let activeTab      = 'All';
let pendingCancelBtn = null;

// ─── Filter ────────────────────────────────────────────────────────────────
function filterBookings() {
    const cards = document.querySelectorAll('.booking-card');
    let visible = 0;
    cards.forEach(card => {
        const match = activeTab === 'All' || card.dataset.status === activeTab;
        card.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    bookingsEmpty.classList.toggle('hidden', visible > 0);
}

bookingTabs.forEach(tab => {
    tab.addEventListener('click', () => {
        bookingTabs.forEach(t => {
            t.classList.remove('bg-[#C8102E]', 'text-white', 'border-[#C8102E]');
            t.classList.add('bg-white', 'text-gray-600', 'border-gray-200');
        });
        tab.classList.add('bg-[#C8102E]', 'text-white', 'border-[#C8102E]');
        tab.classList.remove('bg-white', 'text-gray-600', 'border-gray-200');
        activeTab = tab.dataset.tab;
        filterBookings();
    });
});

// ─── Cancel Booking ─────────────────────────────────────────────────────────
document.addEventListener('click', e => {
    if (e.target.classList.contains('cancel-booking-btn')) {
        pendingCancelBtn = e.target;
        cancelModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
});

document.getElementById('cancel-modal-no').addEventListener('click', () => {
    cancelModal.classList.add('hidden');
    document.body.style.overflow = '';
    pendingCancelBtn = null;
});

document.getElementById('cancel-modal-yes').addEventListener('click', () => {
    if (!pendingCancelBtn) return;
    const card = pendingCancelBtn.closest('.booking-card');
    card.dataset.status = 'Cancelled';

    // Update badge
    const badgeEl = card.querySelector('span.inline-flex');
    badgeEl.textContent = 'Cancelled';
    badgeEl.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800';

    // Update icon
    const iconEl = card.querySelector('span.text-2xl');
    if (iconEl) iconEl.textContent = '❌';

    // Remove cancel button
    pendingCancelBtn.remove();

    cancelModal.classList.add('hidden');
    document.body.style.overflow = '';
    pendingCancelBtn = null;

    filterBookings();
    if (typeof showToast === 'function') showToast('Booking cancelled.', 'info');
});

cancelModal.addEventListener('click', e => {
    if (e.target === cancelModal) {
        cancelModal.classList.add('hidden');
        document.body.style.overflow = '';
        pendingCancelBtn = null;
    }
});

filterBookings();
</script>

<?php require_once __DIR__ . '/../../includes/layouts/customer/footer.php'; ?>
