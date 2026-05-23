<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

$pageTitle  = 'Messages';
$activePage = 'messages';

// Static conversations
$conversations = [
    [
        'id'     => 1,
        'staff'  => 'Maria Santos',
        'avatar' => 'MS',
        'role'   => 'Support Staff',
        'last'   => 'Sure, I can help you track your order!',
        'time'   => '10:45 AM',
        'unread' => 2,
        'active' => true,
    ],
    [
        'id'     => 2,
        'staff'  => 'Carlo Reyes',
        'avatar' => 'CR',
        'role'   => 'Delivery Coordinator',
        'last'   => 'Your package is out for delivery.',
        'time'   => 'Yesterday',
        'unread' => 0,
        'active' => false,
    ],
];

// Messages for conversation #1
$messages = [
    ['from' => 'staff',    'text' => 'Hello Juan! Welcome to Goldcomm Support. How can I assist you today?', 'time' => '10:30 AM'],
    ['from' => 'customer', 'text' => 'Hi, I wanted to ask about the status of my order SE-20260118-002.',     'time' => '10:31 AM'],
    ['from' => 'staff',    'text' => 'Of course! Let me look that up for you. One moment please.',             'time' => '10:33 AM'],
    ['from' => 'staff',    'text' => 'I can see your order for Running Sneakers Pro is currently shipped and is on its way to you. Expected delivery is January 21, 2026.',  'time' => '10:35 AM'],
    ['from' => 'customer', 'text' => 'Great! Can I change the delivery address?',                              'time' => '10:38 AM'],
    ['from' => 'staff',    'text' => 'Unfortunately, once an order is shipped we are unable to change the delivery address. However, if you have concerns you may call our delivery partner directly.', 'time' => '10:42 AM'],
    ['from' => 'customer', 'text' => 'Alright, thank you for letting me know.',                                'time' => '10:43 AM'],
    ['from' => 'staff',    'text' => 'Sure, I can help you track your order!',                                 'time' => '10:45 AM'],
];

require_once __DIR__ . '/../../includes/layouts/customer/nav.php';
?>

<main class="flex-1 max-w-5xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
         style="height: calc(100vh - 11rem); min-height: 480px;">

        <div class="flex h-full">

            <!-- ─── LEFT: Conversations List ──────────────────────────────────── -->
            <div id="conv-panel" class="w-full sm:w-72 lg:w-80 border-r border-gray-100 flex flex-col shrink-0
                        <?php echo count($conversations) ? '' : 'hidden sm:flex'; ?>">

                <!-- Header -->
                <div class="px-4 py-4 border-b border-gray-100 shrink-0">
                    <h2 class="font-bold text-gray-900">Messages</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Support conversations</p>
                </div>

                <!-- List -->
                <div class="overflow-y-auto flex-1">
                    <?php foreach ($conversations as $conv): ?>
                    <button class="conv-item w-full text-left px-4 py-3.5 hover:bg-gray-50 transition
                                   border-b border-gray-50 flex items-start gap-3
                                   <?= $conv['active'] ? 'bg-[#FFF5F5]' : '' ?>"
                            data-id="<?= $conv['id'] ?>">
                        <!-- Avatar -->
                        <div class="w-10 h-10 rounded-full bg-[#C8102E] text-white text-sm font-bold
                                    flex items-center justify-center shrink-0">
                            <?= e($conv['avatar']) ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-900 truncate"><?= e($conv['staff']) ?></span>
                                <span class="text-xs text-gray-400 shrink-0 ml-2"><?= e($conv['time']) ?></span>
                            </div>
                            <p class="text-xs text-gray-500 truncate mt-0.5"><?= e($conv['last']) ?></p>
                        </div>
                        <?php if ($conv['unread'] > 0): ?>
                        <span class="bg-[#C8102E] text-white text-xs font-bold rounded-full
                                     w-5 h-5 flex items-center justify-center shrink-0">
                            <?= $conv['unread'] ?>
                        </span>
                        <?php endif; ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ─── RIGHT: Chat Window ────────────────────────────────────────── -->
            <div id="chat-panel-wrapper" class="hidden sm:flex flex-1 flex-col min-w-0">

                <!-- Chat Header -->
                <div class="px-4 py-4 border-b border-gray-100 shrink-0 flex items-center gap-2">
                    <!-- Back button (mobile only) -->
                    <button id="mobile-back-btn"
                            class="sm:hidden p-1.5 -ml-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition shrink-0"
                            aria-label="Back to conversations">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <div class="w-10 h-10 rounded-full bg-[#C8102E] text-white text-sm font-bold
                                flex items-center justify-center shrink-0">
                        MS
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">Maria Santos</p>
                        <p class="text-xs text-gray-500">Support Staff · <span class="text-green-500">Online</span></p>
                    </div>
                </div>

                <!-- Messages Area -->
                <div id="chat-messages" class="flex-1 overflow-y-auto px-5 py-4 space-y-3">

                    <!-- Date Divider -->
                    <div class="flex items-center gap-3 my-2">
                        <hr class="flex-1 border-gray-200">
                        <span class="text-xs text-gray-400 shrink-0">Today</span>
                        <hr class="flex-1 border-gray-200">
                    </div>

                    <?php foreach ($messages as $msg): ?>
                    <div class="flex <?= $msg['from'] === 'customer' ? 'justify-end' : 'justify-start' ?> gap-2 items-end">
                        <?php if ($msg['from'] === 'staff'): ?>
                        <div class="w-7 h-7 rounded-full bg-[#C8102E] text-white text-xs font-bold
                                    flex items-center justify-center shrink-0 mb-1">
                            MS
                        </div>
                        <?php endif; ?>
                        <div class="max-w-xs lg:max-w-md">
                            <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed
                                        <?= $msg['from'] === 'customer'
                                            ? 'bg-[#C8102E] text-white rounded-br-sm'
                                            : 'bg-gray-100 text-gray-800 rounded-bl-sm' ?>">
                                <?= e($msg['text']) ?>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1 <?= $msg['from'] === 'customer' ? 'text-right' : '' ?>">
                                <?= e($msg['time']) ?>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Input Area -->
                <div class="px-4 py-3 border-t border-gray-100 shrink-0">
                    <div class="flex items-center gap-2 bg-gray-50 rounded-xl px-3 py-2 border border-gray-200
                                focus-within:border-[#C8102E] focus-within:ring-1 focus-within:ring-[#C8102E] transition">
                        <input type="text"
                               id="chat-input"
                               placeholder="Type a message…"
                               autocomplete="off"
                               class="flex-1 bg-transparent text-sm text-gray-800 outline-none placeholder-gray-400 py-1">
                        <button id="chat-send"
                                class="bg-[#C8102E] text-white rounded-lg p-2 hover:bg-[#A50D25] transition
                                       active:scale-95 shrink-0 disabled:opacity-40">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5 text-center">
                        Staff typically replies within a few minutes during business hours.
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
const chatMessages = document.getElementById('chat-messages');
const chatInput    = document.getElementById('chat-input');
const chatSend     = document.getElementById('chat-send');

// Scroll to bottom on load
chatMessages.scrollTop = chatMessages.scrollHeight;

function addMessage(text, fromCustomer = true) {
    const time = new Date().toLocaleTimeString('en-PH', {hour:'2-digit', minute:'2-digit'});
    const div  = document.createElement('div');
    div.className = `flex ${fromCustomer ? 'justify-end' : 'justify-start'} gap-2 items-end`;
    div.innerHTML = `
        ${!fromCustomer ? `<div class="w-7 h-7 rounded-full bg-[#C8102E] text-white text-xs font-bold
                                flex items-center justify-center shrink-0 mb-1">MS</div>` : ''}
        <div class="max-w-xs lg:max-w-md">
            <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed
                        ${fromCustomer
                            ? 'bg-[#C8102E] text-white rounded-br-sm'
                            : 'bg-gray-100 text-gray-800 rounded-bl-sm'}">
                ${text.replace(/</g, '&lt;').replace(/>/g, '&gt;')}
            </div>
            <p class="text-[10px] text-gray-400 mt-1 ${fromCustomer ? 'text-right' : ''}">
                ${time}
            </p>
        </div>
    `;
    chatMessages.appendChild(div);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function sendMessage() {
    const text = chatInput.value.trim();
    if (!text) return;

    addMessage(text, true);
    chatInput.value = '';

    // Static auto-reply after short delay
    setTimeout(() => {
        addMessage('Thanks for your message! Our team will get back to you shortly. For urgent concerns, please call our hotline at 1800-SHOPEASE.', false);
    }, 1200);
}

chatSend.addEventListener('click', sendMessage);
chatInput.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

// Conversation switching
const convPanel        = document.getElementById('conv-panel');
const chatPanelWrapper = document.getElementById('chat-panel-wrapper');
const mobileBackBtn    = document.getElementById('mobile-back-btn');

document.querySelectorAll('.conv-item').forEach(item => {
    item.addEventListener('click', () => {
        document.querySelectorAll('.conv-item').forEach(c => c.classList.remove('bg-[#FFF5F5]'));
        item.classList.add('bg-[#FFF5F5]');
        // Remove unread badge
        const badge = item.querySelector('span.bg-[#C8102E]');
        if (badge && badge.parentElement === item) badge.remove();
        // Mobile: switch to chat panel
        if (window.innerWidth < 640) {
            convPanel.classList.add('hidden');
            chatPanelWrapper.classList.remove('hidden');
            chatPanelWrapper.classList.add('flex');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });
});

// Mobile: back to conversation list
mobileBackBtn.addEventListener('click', () => {
    chatPanelWrapper.classList.add('hidden');
    chatPanelWrapper.classList.remove('flex');
    convPanel.classList.remove('hidden');
});
</script>

<?php require_once __DIR__ . '/../../includes/layouts/customer/footer.php'; ?>
