<?php
session_start();
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

$pageTitle  = 'Shipping Addresses';
$activePage = 'account';

$addresses = [
    [
        'id'       => 1,
        'label'    => 'Home',
        'name'     => 'Juan dela Cruz',
        'phone'    => '09171234567',
        'line1'    => '123 Sampaguita Street, Brgy San Antonio',
        'city'     => 'Pasig City',
        'province' => 'Metro Manila',
        'zip'      => '1600',
        'default'  => true,
    ],
    [
        'id'       => 2,
        'label'    => 'Office',
        'name'     => 'Juan dela Cruz',
        'phone'    => '09289876543',
        'line1'    => '456 Rizal Avenue, Brgy San Isidro',
        'city'     => 'Makati City',
        'province' => 'Metro Manila',
        'zip'      => '1200',
        'default'  => false,
    ],
];

require_once __DIR__ . '/../../includes/customer/nav.php';
?>

<main class="flex-1 max-w-3xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Shipping Addresses</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your saved delivery addresses</p>
        </div>
        <button id="add-address-btn"
                class="inline-flex items-center gap-2 bg-[#C8102E] text-white font-semibold rounded-xl
                       px-4 py-2.5 text-sm hover:bg-[#A50D25] transition active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Address
        </button>
    </div>

    <!-- Addresses -->
    <div class="space-y-4" id="address-list">
        <?php foreach ($addresses as $addr): ?>
        <div class="address-card bg-white rounded-2xl border <?= $addr['default'] ? 'border-[#C8102E]/50' : 'border-gray-100' ?>
                    shadow-sm hover:shadow-md transition overflow-hidden">
            <div class="px-5 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3 flex-1">
                        <div class="w-10 h-10 rounded-xl bg-[#FFF5F5] flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-[#C8102E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-gray-900 text-sm"><?= e($addr['label']) ?></span>
                                <?php if ($addr['default']): ?>
                                <span class="bg-[#FEECEC] text-[#A50D25] text-xs font-semibold px-2 py-0.5 rounded-full">
                                    Default
                                </span>
                                <?php endif; ?>
                            </div>
                            <p class="text-sm font-medium text-gray-800"><?= e($addr['name']) ?></p>
                            <p class="text-xs text-gray-500 mt-0.5"><?= e($addr['phone']) ?></p>
                            <p class="text-sm text-gray-600 mt-1">
                                <?= e($addr['line1']) ?>, <?= e($addr['city']) ?>, <?= e($addr['province']) ?> <?= e($addr['zip']) ?>
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col gap-2 shrink-0">
                        <button class="edit-addr-btn text-xs text-[#C8102E] hover:text-[#7F1020] border border-[#C8102E]/30
                                       hover:border-[#C8102E] rounded-lg px-3 py-1.5 font-medium transition"
                                data-id="<?= $addr['id'] ?>"
                                data-label="<?= e($addr['label'], ENT_QUOTES) ?>"
                                data-name="<?= e($addr['name'], ENT_QUOTES) ?>"
                                data-phone="<?= e($addr['phone'], ENT_QUOTES) ?>"
                                data-line1="<?= e($addr['line1'], ENT_QUOTES) ?>"
                                data-city="<?= e($addr['city'], ENT_QUOTES) ?>"
                                data-province="<?= e($addr['province'], ENT_QUOTES) ?>"
                                data-zip="<?= e($addr['zip'], ENT_QUOTES) ?>">
                            Edit
                        </button>
                        <?php if (!$addr['default']): ?>
                        <button class="delete-addr-btn text-xs text-red-500 hover:text-red-700 border border-red-200
                                       hover:border-red-400 rounded-lg px-3 py-1.5 font-medium transition">
                            Delete
                        </button>
                        <?php else: ?>
                        <span class="text-xs text-gray-300 border border-transparent px-3 py-1.5">
                            &nbsp;
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</main>

<!-- ═══════════════════════════════════════ ADDRESS MODAL ══ -->
<div id="address-modal"
     class="hidden fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4
            bg-black/50 backdrop-blur-sm">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
            <h3 id="modal-title" class="font-bold text-gray-900">Add New Address</h3>
            <button id="address-modal-close"
                    class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="overflow-y-auto p-6 space-y-4">

            <!-- Label -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Label</label>
                <input type="text" id="addr-label" placeholder="e.g. Home, Office"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#C8102E] transition">
            </div>

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="addr-name" placeholder="Juan dela Cruz"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#C8102E] transition">
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Mobile Number <span class="text-red-500">*</span>
                </label>
                <input type="tel" id="addr-phone" placeholder="09XXXXXXXXX"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#C8102E] transition">
            </div>

            <!-- Line 1 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Address Line 1 <span class="text-red-500">*</span>
                </label>
                <input type="text" id="addr-line1"
                       placeholder="House No., Street, Barangay"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#C8102E] transition">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- City -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        City <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="addr-city" placeholder="Quezon City"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#C8102E] transition">
                </div>

                <!-- Zip -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">ZIP Code</label>
                    <input type="text" id="addr-zip" placeholder="1100" maxlength="4"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#C8102E] transition">
                </div>
            </div>

            <!-- Province -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Province / Region</label>
                <input type="text" id="addr-province" placeholder="Metro Manila"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#C8102E] transition">
            </div>

            <!-- Error -->
            <p id="addr-error" class="hidden text-sm text-red-600 font-medium"></p>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-100 flex gap-3 shrink-0">
            <button id="addr-cancel"
                    class="flex-1 border border-gray-300 text-gray-700 font-medium rounded-xl py-2.5
                           hover:bg-gray-50 transition text-sm">
                Cancel
            </button>
            <button id="addr-save"
                    class="flex-1 bg-[#C8102E] text-white font-semibold rounded-xl py-2.5
                           hover:bg-[#A50D25] transition active:scale-95 text-sm">
                Save Address
            </button>
        </div>
    </div>
</div>

<script>
const addressModal = document.getElementById('address-modal');
const errEl        = document.getElementById('addr-error');

function openModal(title = 'Add New Address') {
    document.getElementById('modal-title').textContent = title;
    errEl.classList.add('hidden');
    addressModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    addressModal.classList.add('hidden');
    document.body.style.overflow = '';
    ['addr-label','addr-name','addr-phone','addr-line1','addr-city','addr-zip','addr-province']
        .forEach(id => document.getElementById(id).value = '');
    errEl.classList.add('hidden');
}

document.getElementById('add-address-btn').addEventListener('click', () => openModal('Add New Address'));
document.getElementById('address-modal-close').addEventListener('click', closeModal);
document.getElementById('addr-cancel').addEventListener('click', closeModal);
addressModal.addEventListener('click', e => { if (e.target === addressModal) closeModal(); });

// Edit buttons
document.querySelectorAll('.edit-addr-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('addr-label').value    = btn.dataset.label;
        document.getElementById('addr-name').value     = btn.dataset.name;
        document.getElementById('addr-phone').value    = btn.dataset.phone;
        document.getElementById('addr-line1').value    = btn.dataset.line1;
        document.getElementById('addr-city').value     = btn.dataset.city;
        document.getElementById('addr-province').value = btn.dataset.province;
        document.getElementById('addr-zip').value      = btn.dataset.zip;
        openModal('Edit Address');
    });
});

// Delete buttons
document.querySelectorAll('.delete-addr-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        if (confirm('Remove this address?')) {
            btn.closest('.address-card').remove();
            if (typeof showToast === 'function') showToast('Address removed.', 'info');
        }
    });
});

// Save
document.getElementById('addr-save').addEventListener('click', () => {
    const name  = document.getElementById('addr-name').value.trim();
    const phone = document.getElementById('addr-phone').value.trim();
    const line1 = document.getElementById('addr-line1').value.trim();
    const city  = document.getElementById('addr-city').value.trim();

    if (!name || !phone || !line1 || !city) {
        errEl.textContent = 'Please fill in all required fields.';
        errEl.classList.remove('hidden');
        return;
    }

    closeModal();
    if (typeof showToast === 'function') showToast('Address saved successfully!', 'success');
});
</script>

<?php require_once __DIR__ . '/../../includes/customer/footer.php'; ?>
