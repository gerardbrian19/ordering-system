<?php
session_start();

require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';

$pageTitle  = 'Customer Messages';
$activePage = 'messages';

// ── Handle POST: send reply ────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';
    if (!validateCsrfToken($submittedToken)) {
        $_SESSION['admin_error'] = 'Invalid security token.';
        header('Location: /admin/messages.php');
        exit;
    }

    $conversationId = (int)($_POST['conversation_id'] ?? 0);
    $message        = trim($_POST['message'] ?? '');

    if ($conversationId > 0 && $message !== '') {
        // TODO: INSERT INTO messages (conversation_id, sender_role, message, created_at) VALUES (...)
        $_SESSION['admin_success'] = 'Reply sent.';
    }

    header('Location: /admin/messages.php?conv=' . $conversationId);
    exit;
}

// ── Flash messages ─────────────────────────────────────────────────────────
$flashSuccess = $_SESSION['admin_success'] ?? null;
$flashError   = $_SESSION['admin_error']   ?? null;
unset($_SESSION['admin_success'], $_SESSION['admin_error']);

// ── Static customer conversations ──────────────────────────────────────────
$conversations = [
    [
        'id'       => 1,
        'customer' => 'Juan dela Cruz',
        'avatar'   => 'JC',
        'email'    => 'juan.delacruz@email.com',
        'subject'  => 'Order GC-20260118-002',
        'last'     => 'Alright, thank you for letting me know.',
        'time'     => '10:43 AM',
        'unread'   => 1,
    ],
    [
        'id'       => 2,
        'customer' => 'Ana Gonzales',
        'avatar'   => 'AG',
        'email'    => 'ana.gonzales@email.com',
        'subject'  => 'Product inquiry — Motorola DP4600e',
        'last'     => 'Is the DP4600e compatible with existing Kenwood repeaters?',
        'time'     => '9:15 AM',
        'unread'   => 2,
    ],
    [
        'id'       => 3,
        'customer' => 'Maria Santos',
        'avatar'   => 'MS',
        'email'    => 'maria.santos@email.com',
        'subject'  => 'Return request',
        'last'     => 'The unit I received has a defective PTT button.',
        'time'     => 'Yesterday',
        'unread'   => 0,
    ],
    [
        'id'       => 4,
        'customer' => 'Pedro Reyes',
        'avatar'   => 'PR',
        'email'    => 'pedro.reyes@email.com',
        'subject'  => 'Service booking — Radio Repair',
        'last'     => 'Thank you! I\'ll drop off the unit on Wednesday.',
        'time'     => 'Jan 19',
        'unread'   => 0,
    ],
];

// ── Static message threads ──────────────────────────────────────────────────
$messageThreads = [
    1 => [
        ['from' => 'customer', 'text' => 'Hi, I wanted to ask about the status of my order GC-20260118-002.',                   'time' => '10:31 AM'],
        ['from' => 'admin',    'text' => 'Of course! Let me look that up for you. One moment please.',                            'time' => '10:33 AM'],
        ['from' => 'admin',    'text' => 'Your Kenwood TK-2402V16P is currently shipped and on its way. Expected delivery: Jan 21, 2026.', 'time' => '10:35 AM'],
        ['from' => 'customer', 'text' => 'Great! Can I change the delivery address?',                                             'time' => '10:38 AM'],
        ['from' => 'admin',    'text' => 'Unfortunately, once an order is shipped we cannot change the delivery address. You may call our delivery partner directly.',  'time' => '10:42 AM'],
        ['from' => 'customer', 'text' => 'Alright, thank you for letting me know.',                                               'time' => '10:43 AM'],
    ],
    2 => [
        ['from' => 'customer', 'text' => 'Good morning! I\'m interested in the Motorola DP4600e Digital Radio.',     'time' => '9:10 AM'],
        ['from' => 'admin',    'text' => 'Good morning, Ana! The DP4600e is a great choice. How can I help?',         'time' => '9:12 AM'],
        ['from' => 'customer', 'text' => 'Is the DP4600e compatible with existing Kenwood repeaters?',                'time' => '9:15 AM'],
    ],
    3 => [
        ['from' => 'customer', 'text' => 'Hello, I received my order but the unit has a defective PTT button.',         'time' => 'Jan 16, 10:00 AM'],
        ['from' => 'admin',    'text' => 'We\'re sorry to hear that, Maria. Please send us a photo of the defect and we will arrange a replacement.',  'time' => 'Jan 16, 10:15 AM'],
        ['from' => 'customer', 'text' => 'The unit I received has a defective PTT button.',                              'time' => 'Jan 16, 11:00 AM'],
    ],
    4 => [
        ['from' => 'customer', 'text' => 'Hi, I\'d like to book a radio repair service for my ICOM IC-F11.',          'time' => 'Jan 19, 2:00 PM'],
        ['from' => 'admin',    'text' => 'Hello Pedro! We can schedule you in. What days work best for you?',          'time' => 'Jan 19, 2:10 PM'],
        ['from' => 'customer', 'text' => 'Wednesday afternoon would be perfect.',                                       'time' => 'Jan 19, 2:15 PM'],
        ['from' => 'admin',    'text' => 'Wednesday at 2:00 PM is confirmed. Please bring the unit with its accessories.', 'time' => 'Jan 19, 2:18 PM'],
        ['from' => 'customer', 'text' => 'Thank you! I\'ll drop off the unit on Wednesday.',                           'time' => 'Jan 19, 2:20 PM'],
    ],
];

// ── Active conversation ─────────────────────────────────────────────────────
$activeConvId = (int)($_GET['conv'] ?? 1);
if (!isset($messageThreads[$activeConvId])) {
    $activeConvId = 1;
}

$activeConv     = null;
foreach ($conversations as $c) {
    if ($c['id'] === $activeConvId) {
        $activeConv = $c;
        break;
    }
}
$activeMessages = $messageThreads[$activeConvId] ?? [];
$csrfToken      = generateCsrfToken();

require_once __DIR__ . '/../../includes/admin_nav.php';
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

<!-- ═══════════════════════════════════ MESSAGES PANEL ═══ -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
     style="height: calc(100vh - 9.5rem); min-height: 520px;">

    <div class="flex h-full">

        <!-- ── LEFT: Conversation list ──────────────────────────────── -->
        <div class="w-72 lg:w-80 border-r border-gray-100 flex flex-col shrink-0
                    <?= $activeConv ? 'hidden sm:flex' : 'flex' ?>">

            <!-- Header -->
            <div class="px-4 py-4 border-b border-gray-100 shrink-0">
                <h2 class="text-sm font-bold text-gray-900">Support Inbox</h2>
                <p class="text-xs text-gray-400 mt-0.5"><?= count($conversations) ?> conversations</p>
            </div>

            <!-- Search -->
            <div class="px-3 py-2 border-b border-gray-50 shrink-0">
                <input type="text" id="conv-search" placeholder="Search conversations…"
                       class="w-full text-sm border border-gray-200 rounded-lg px-3 py-1.5
                              focus:outline-none focus:ring-2 focus:ring-[#C8102E]">
            </div>

            <!-- Conversation list -->
            <div class="flex-1 overflow-y-auto divide-y divide-gray-50" id="conv-list">
                <?php foreach ($conversations as $conv):
                    $isActive = $conv['id'] === $activeConvId;
                ?>
                <a href="/admin/messages.php?conv=<?= $conv['id'] ?>"
                   class="flex items-start gap-3 px-4 py-3.5 hover:bg-gray-50 transition
                          <?= $isActive ? 'bg-[#FFF5F5] border-l-2 border-[#C8102E]' : '' ?>"
                   data-name="<?= strtolower(e($conv['customer'])) ?>"
                   data-subject="<?= strtolower(e($conv['subject'])) ?>">

                    <!-- Avatar -->
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gray-700 to-gray-500
                                flex items-center justify-center text-white text-xs font-bold shrink-0 mt-0.5">
                        <?= e($conv['avatar']) ?>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <p class="text-sm font-semibold text-gray-800 truncate"><?= e($conv['customer']) ?></p>
                            <span class="text-[10px] text-gray-400 shrink-0"><?= e($conv['time']) ?></span>
                        </div>
                        <p class="text-xs text-[#C8102E] font-medium truncate mb-0.5"><?= e($conv['subject']) ?></p>
                        <p class="text-xs text-gray-500 truncate"><?= e($conv['last']) ?></p>
                    </div>

                    <?php if ($conv['unread'] > 0): ?>
                    <span class="shrink-0 w-5 h-5 bg-[#C8102E] text-white text-[10px] font-bold
                                 rounded-full flex items-center justify-center mt-1">
                        <?= $conv['unread'] ?>
                    </span>
                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>


        <!-- ── RIGHT: Message thread ─────────────────────────────────── -->
        <?php if ($activeConv): ?>
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Thread header -->
            <div class="flex items-center gap-3 px-4 sm:px-5 py-3.5 border-b border-gray-100 shrink-0">
                <!-- Back button (mobile) -->
                <a href="/admin/messages.php"
                   class="sm:hidden p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>

                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gray-700 to-gray-500
                            flex items-center justify-center text-white text-xs font-bold shrink-0">
                    <?= e($activeConv['avatar']) ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900"><?= e($activeConv['customer']) ?></p>
                    <p class="text-xs text-gray-400 truncate"><?= e($activeConv['subject']) ?></p>
                </div>
                <a href="mailto:<?= e($activeConv['email']) ?>"
                   class="hidden sm:inline-flex items-center gap-1.5 text-xs text-gray-500
                          hover:text-[#C8102E] border border-gray-200 rounded-lg px-3 py-1.5
                          hover:border-[#C8102E] transition shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <?= e($activeConv['email']) ?>
                </a>
            </div>

            <!-- Messages area -->
            <div id="messages-area" class="flex-1 overflow-y-auto px-4 sm:px-5 py-4 space-y-4 bg-gray-50">
                <?php foreach ($activeMessages as $msg):
                    $isAdmin = $msg['from'] === 'admin';
                ?>
                <div class="flex <?= $isAdmin ? 'justify-end' : 'justify-start' ?> gap-2.5">

                    <?php if (!$isAdmin): ?>
                    <div class="w-7 h-7 rounded-full bg-gray-600 flex items-center justify-center
                                text-white text-[10px] font-bold shrink-0 mt-1">
                        <?= e($activeConv['avatar']) ?>
                    </div>
                    <?php endif; ?>

                    <div class="max-w-[75%]">
                        <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed
                                    <?= $isAdmin
                                        ? 'bg-[#C8102E] text-white rounded-tr-sm'
                                        : 'bg-white text-gray-800 border border-gray-100 shadow-sm rounded-tl-sm' ?>">
                            <?= e($msg['text']) ?>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1 <?= $isAdmin ? 'text-right' : 'text-left' ?>">
                            <?= $isAdmin ? 'You' : e($activeConv['customer']) ?> · <?= e($msg['time']) ?>
                        </p>
                    </div>

                    <?php if ($isAdmin): ?>
                    <div class="w-7 h-7 rounded-full bg-[#C8102E] flex items-center justify-center
                                text-white text-[10px] font-bold shrink-0 mt-1">
                        <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
                    </div>
                    <?php endif; ?>

                </div>
                <?php endforeach; ?>
            </div>

            <!-- Reply input -->
            <div class="px-4 sm:px-5 py-3.5 bg-white border-t border-gray-100 shrink-0">
                <form method="POST" action="/admin/messages.php" class="flex items-end gap-2.5">
                    <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                    <input type="hidden" name="conversation_id" value="<?= $activeConvId ?>">

                    <textarea name="message"
                              id="reply-input"
                              rows="1"
                              placeholder="Type your reply…"
                              required
                              class="flex-1 resize-none text-sm border border-gray-300 rounded-xl px-3.5 py-2.5
                                     focus:outline-none focus:ring-2 focus:ring-[#C8102E] min-h-[42px] max-h-32
                                     overflow-y-auto transition"></textarea>

                    <button type="submit"
                            class="shrink-0 w-10 h-10 bg-[#C8102E] text-white rounded-xl
                                   flex items-center justify-center hover:bg-red-700 transition"
                            aria-label="Send reply">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </form>
            </div>

        </div>
        <?php else: ?>
        <!-- No conversation selected (fallback) -->
        <div class="flex-1 flex items-center justify-center text-center px-8">
            <div>
                <svg class="w-14 h-14 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z"/>
                </svg>
                <p class="text-gray-400 text-sm">Select a conversation to start replying</p>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<script>
// Auto-scroll messages to bottom
const messagesArea = document.getElementById('messages-area');
if (messagesArea) {
    messagesArea.scrollTop = messagesArea.scrollHeight;
}

// Auto-grow textarea
const replyInput = document.getElementById('reply-input');
if (replyInput) {
    replyInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 128) + 'px';
    });

    // Submit on Ctrl+Enter / Cmd+Enter
    replyInput.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            this.closest('form').submit();
        }
    });
}

// Conversation search filter
const convSearch = document.getElementById('conv-search');
if (convSearch) {
    convSearch.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('#conv-list a').forEach(el => {
            const name    = el.dataset.name    ?? '';
            const subject = el.dataset.subject ?? '';
            el.style.display = (name.includes(q) || subject.includes(q)) ? '' : 'none';
        });
    });
}
</script>

<?php require_once __DIR__ . '/../../includes/admin_footer.php'; ?>
