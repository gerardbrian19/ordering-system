<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$pageTitle  = 'Book Services';
$activePage = 'services';

$services = [
    ['id'=>1, 'title'=>'Radio Programming',        'price'=>299.00,   'duration'=>'30 min',   'icon'=>'📡', 'desc'=>'Frequency and channel programming for Motorola, ICOM, Kenwood, Baofeng, and most popular radio brands.'],
    ['id'=>2, 'title'=>'System Installation',      'price'=>5000.00,  'duration'=>'1–2 days', 'icon'=>'🔧', 'desc'=>'Full two-way radio system design and installation for offices, warehouses, fleets, and large facilities.'],
    ['id'=>3, 'title'=>'Radio Repair',             'price'=>500.00,   'duration'=>'2–4 hrs',  'icon'=>'🛠️', 'desc'=>'Diagnosis and repair of handheld, mobile, and base station radios across all major brands.'],
    ['id'=>4, 'title'=>'Repeater Setup',           'price'=>8000.00,  'duration'=>'1 day',    'icon'=>'📶', 'desc'=>'Site survey, installation, and configuration of repeater stations to extend your radio coverage area.'],
    ['id'=>5, 'title'=>'Signal Coverage Survey',   'price'=>3000.00,  'duration'=>'Half day', 'icon'=>'🗺️', 'desc'=>'On-site RF signal strength mapping and coverage analysis for your facility or fleet routes.'],
    ['id'=>6, 'title'=>'Frequency Coordination',   'price'=>1500.00,  'duration'=>'1–2 days', 'icon'=>'📋', 'desc'=>'NTRC frequency application assistance and coordination to ensure legal radio operation.'],
    ['id'=>7, 'title'=>'Annual Maintenance',       'price'=>2500.00,  'duration'=>'1 day',    'icon'=>'✅', 'desc'=>'Comprehensive yearly maintenance check for your entire radio fleet — cleaning, testing, and realignment.'],
    ['id'=>8, 'title'=>'Radio Rental',             'price'=>200.00,   'duration'=>'/ day',    'icon'=>'🔄', 'desc'=>'Short-term rental of handheld radios for events, construction projects, and temporary deployments.'],
];

require_once __DIR__ . '/../includes/customer_nav.php';
?>

<main class="flex-1 max-w-6xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Available Services</h1>
        <p class="text-sm text-gray-500 mt-1">Browse our services and book an appointment in seconds.</p>
    </div>

    <!-- Services Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        <?php foreach ($services as $svc): ?>
        <div class="service-card bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg
                    hover:-translate-y-1 transition-all duration-200 flex flex-col p-5">
            <div class="text-3xl mb-3"><?= $svc['icon'] ?></div>
            <h3 class="font-bold text-gray-900 text-base"><?= e($svc['title']) ?></h3>
            <p class="text-xs text-gray-500 mt-1.5 flex-1 leading-relaxed"><?= e($svc['desc']) ?></p>
            <div class="mt-4 flex items-center justify-between">
                <div>
                    <span class="text-lg font-bold text-[#C8102E]">₱<?= number_format($svc['price'], 2) ?></span>
                    <span class="text-xs text-gray-400 ml-1">/ <?= e($svc['duration']) ?></span>
                </div>
                <button class="book-btn bg-[#C8102E] text-white text-sm font-semibold rounded-lg px-3.5 py-1.5
                               hover:bg-[#A50D25] transition active:scale-95"
                        data-id="<?= $svc['id'] ?>"
                        data-title="<?= e($svc['title'], ENT_QUOTES) ?>"
                        data-price="<?= $svc['price'] ?>">
                    Book Now
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- My Bookings Link -->
    <div class="mt-8 text-center">
        <a href="/my_bookings.php"
           class="inline-flex items-center gap-2 text-sm text-[#C8102E] hover:text-[#A50D25] font-medium transition">
            View my bookings
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</main>

<!-- ═══════════════════════════════════════ BOOKING MODAL ══ -->
<div id="booking-modal"
     class="hidden fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4
            bg-black/50 backdrop-blur-sm">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">

        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
            <div>
                <h3 class="font-bold text-gray-900">Book a Service</h3>
                <p id="modal-service-title" class="text-sm text-[#C8102E] mt-0.5">—</p>
            </div>
            <button id="booking-modal-close"
                    class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="overflow-y-auto p-6 space-y-4">

            <div class="grid grid-cols-2 gap-4">
                <!-- Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Preferred Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                           id="booking-date"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#C8102E] transition">
                </div>

                <!-- Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Preferred Time <span class="text-red-500">*</span>
                    </label>
                    <select id="booking-time"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-[#C8102E] transition">
                        <option value="">Select time</option>
                        <option>8:00 AM</option>
                        <option>9:00 AM</option>
                        <option>10:00 AM</option>
                        <option>11:00 AM</option>
                        <option>1:00 PM</option>
                        <option>2:00 PM</option>
                        <option>3:00 PM</option>
                        <option>4:00 PM</option>
                    </select>
                </div>
            </div>

            <!-- Full Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="booking-name"
                       placeholder="Juan dela Cruz"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#C8102E] transition">
            </div>

            <!-- Email or Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Email or Mobile Number <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="booking-contact"
                       placeholder="email@example.com or 09XX-XXX-XXXX"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#C8102E] transition">
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Additional Notes</label>
                <textarea id="booking-notes"
                          rows="3"
                          placeholder="Any special instructions or details…"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm resize-none
                                 focus:outline-none focus:ring-2 focus:ring-[#C8102E] transition"></textarea>
            </div>

            <!-- Price Info -->
            <div class="bg-[#FFF5F5] rounded-xl p-3.5 flex items-center justify-between">
                <span class="text-sm text-gray-700">Service Fee</span>
                <span id="modal-price" class="font-bold text-[#A50D25]">—</span>
            </div>

            <!-- Error -->
            <p id="booking-error" class="hidden text-sm text-red-600 font-medium"></p>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-100 flex gap-3 shrink-0">
            <button id="booking-cancel"
                    class="flex-1 border border-gray-300 text-gray-700 font-medium rounded-xl py-2.5
                           hover:bg-gray-50 transition text-sm">
                Cancel
            </button>
            <button id="booking-submit"
                    class="flex-1 bg-[#C8102E] text-white font-semibold rounded-xl py-2.5
                           hover:bg-[#A50D25] transition active:scale-95 text-sm">
                Confirm Booking
            </button>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════ SUCCESS TOAST ══ -->
<div id="booking-success"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h3 class="font-bold text-gray-900 text-lg">Booking Confirmed!</h3>
        <p class="text-gray-500 text-sm mt-2 mb-5">
            Your appointment has been submitted. We'll confirm it within 24 hours via email or SMS.
        </p>
        <div class="flex gap-3">
            <button id="success-close"
                    class="flex-1 border border-gray-300 text-gray-700 font-medium rounded-xl py-2.5
                           hover:bg-gray-50 transition text-sm">
                Close
            </button>
            <a href="/my_bookings.php"
               class="flex-1 bg-[#C8102E] text-white font-semibold rounded-xl py-2.5 text-sm
                      hover:bg-[#A50D25] transition text-center">
                View Bookings
            </a>
        </div>
    </div>
</div>

<script>
const bookingModal   = document.getElementById('booking-modal');
const successModal   = document.getElementById('booking-success');
let selectedService  = null;

// Set min date to today
document.getElementById('booking-date').min = new Date().toISOString().split('T')[0];

function openBookingModal(id, title, price) {
    selectedService = { id, title, price };
    document.getElementById('modal-service-title').textContent = title;
    document.getElementById('modal-price').textContent = '₱' + parseFloat(price).toLocaleString('en-PH', {minimumFractionDigits:2});
    bookingModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeBookingModal() {
    bookingModal.classList.add('hidden');
    document.body.style.overflow = '';
    document.getElementById('booking-error').classList.add('hidden');
}

document.querySelectorAll('.book-btn').forEach(btn => {
    btn.addEventListener('click', () => openBookingModal(btn.dataset.id, btn.dataset.title, btn.dataset.price));
});

document.getElementById('booking-modal-close').addEventListener('click', closeBookingModal);
document.getElementById('booking-cancel').addEventListener('click', closeBookingModal);
bookingModal.addEventListener('click', e => { if (e.target === bookingModal) closeBookingModal(); });

document.getElementById('booking-submit').addEventListener('click', () => {
    const date    = document.getElementById('booking-date').value;
    const time    = document.getElementById('booking-time').value;
    const name    = document.getElementById('booking-name').value.trim();
    const contact = document.getElementById('booking-contact').value.trim();
    const errEl   = document.getElementById('booking-error');

    if (!date || !time || !name || !contact) {
        errEl.textContent = 'Please fill in all required fields.';
        errEl.classList.remove('hidden');
        return;
    }

    // Save booking to localStorage
    const bookings = JSON.parse(localStorage.getItem('shopease_bookings') || '[]');
    bookings.push({
        id:       'SB-' + Date.now().toString().slice(-6),
        service:  selectedService.title,
        price:    selectedService.price,
        date:     date,
        time:     time,
        name:     name,
        contact:  contact,
        notes:    document.getElementById('booking-notes').value.trim(),
        status:   'Pending',
        created:  new Date().toISOString(),
    });
    localStorage.setItem('shopease_bookings', JSON.stringify(bookings));

    closeBookingModal();
    successModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
});

document.getElementById('success-close').addEventListener('click', () => {
    successModal.classList.add('hidden');
    document.body.style.overflow = '';
});
successModal.addEventListener('click', e => {
    if (e.target === successModal) {
        successModal.classList.add('hidden');
        document.body.style.overflow = '';
    }
});
</script>

<?php require_once __DIR__ . '/../includes/customer_footer.php'; ?>
