// ─── Cart (localStorage) ────────────────────────────────────────────────────

const CART_KEY = 'shopease_cart';

function getCart() {
    try {
        return JSON.parse(localStorage.getItem(CART_KEY)) ?? [];
    } catch {
        return [];
    }
}

function saveCart(cart) {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
    updateCartBadge();
}

function addToCart(product) {
    const cart = getCart();
    const existing = cart.find(item => item.id === product.id);
    if (existing) {
        existing.qty += 1;
    } else {
        cart.push({ ...product, qty: 1 });
    }
    saveCart(cart);
    showToast(`"${product.name}" added to cart`);
}

function removeFromCart(productId) {
    const cart = getCart().filter(item => item.id !== productId);
    saveCart(cart);
}

function clearCart() {
    localStorage.removeItem(CART_KEY);
    updateCartBadge();
}

function updateCartBadge() {
    const badge = document.getElementById('cart-badge');
    if (!badge) return;
    const total = getCart().reduce((sum, item) => sum + item.qty, 0);
    badge.textContent = total;
    badge.classList.toggle('hidden', total === 0);
    badge.classList.add('cart-badge-animate');
    setTimeout(() => badge.classList.remove('cart-badge-animate'), 300);
}

// ─── Toast Notification ──────────────────────────────────────────────────────

function showToast(message, type = 'success') {
    const colors = {
        success: 'bg-green-600',
        error:   'bg-red-600',
        info:    'bg-[#C8102E]',
    };
    const toast = document.createElement('div');
    toast.className = `fixed bottom-6 right-6 z-50 text-white text-sm font-medium px-4 py-3 rounded-lg shadow-lg transition-all duration-300 ${colors[type] ?? colors.success}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => toast.remove(), 300);
    }, 2800);
}

// ─── Mobile Nav Toggle & User Dropdown ──────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    updateCartBadge();

    // Mobile hamburger menu (customer_nav.php IDs)
    const menuBtn     = document.getElementById('mobile-menu-btn');
    const mobileMenu  = document.getElementById('mobile-menu');
    const iconOpen    = document.getElementById('menu-icon-open');
    const iconClose   = document.getElementById('menu-icon-close');

    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', () => {
            const hidden = mobileMenu.classList.toggle('hidden');
            iconOpen?.classList.toggle('hidden', !hidden);
            iconClose?.classList.toggle('hidden', hidden);
        });
    }

    // User dropdown
    const userBtn      = document.getElementById('user-menu-btn');
    const userDropdown = document.getElementById('user-dropdown');
    if (userBtn && userDropdown) {
        userBtn.addEventListener('click', e => {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });
        document.addEventListener('click', () => userDropdown.classList.add('hidden'));
        userDropdown.addEventListener('click', e => e.stopPropagation());
    }

    // Chatbot
    initChatbot();
});

// ─── Chatbot ─────────────────────────────────────────────────────────────────

const chatbotResponses = [
    { pattern: /\b(hi|hello|hey|good\s*(morning|afternoon|evening))\b/i,
      reply: "Hello! Welcome to Goldcomm Corporation 📡 How can I help you today? Ask me about radios, orders, services, or delivery." },

    { pattern: /\b(track|order|status|where.*order|order.*status)\b/i,
      reply: "You can track your orders on the <a href='/customer/orders.php' class='underline text-[#C8102E]'>My Orders</a> page. Each order shows its current status and estimated delivery date." },

    { pattern: /\b(pay|payment|gcash|bank\s*transfer|paymaya|reference)\b/i,
      reply: "We accept GCash, Bank Transfer, and Paymaya. After checkout, provide your reference number and upload a payment screenshot for verification." },

    { pattern: /\b(ship|deliver|delivery|shipping|how.*long|when.*arrive)\b/i,
      reply: "Standard delivery takes 3–5 business days within Metro Manila. Shipping fee is ₱80 (free for orders ₱2,000 and above)." },

    { pattern: /\b(return|refund|exchange|wrong.*item|damage|defect)\b/i,
      reply: "For returns and refunds on radio equipment, contact our staff via <a href='/customer/messages.php' class='underline text-[#C8102E]'>Messages</a> within 7 days. We honor manufacturer warranties." },

    { pattern: /\b(cancel|cancell?ation)\b/i,
      reply: "Orders can be cancelled while in 'Pending' status. Go to <a href='/customer/orders.php' class='underline text-[#C8102E]'>My Orders</a> to request a cancellation." },

    { pattern: /\b(program|programming|frequency|channel|ctcss|dcs|tone)\b/i,
      reply: "We offer <strong>Radio Programming</strong> for ₱299 — covering Motorola, ICOM, Kenwood, Baofeng, and more. <a href='/customer/services.php' class='underline text-[#C8102E]'>Book now →</a>" },

    { pattern: /\b(repair|broken|fix|not.*work|damage)\b/i,
      reply: "Our <strong>Radio Repair</strong> service starts at ₱500. We service handheld, mobile, and base station radios. <a href='/customer/services.php' class='underline text-[#C8102E]'>Book a repair →</a>" },

    { pattern: /\b(install|installation|repeater|setup|system)\b/i,
      reply: "We handle full radio system installations and repeater setups. Visit our <a href='/customer/services.php' class='underline text-[#C8102E]'>Services</a> page for details and booking." },

    { pattern: /\b(service|book|appointment)\b/i,
      reply: "We offer Radio Programming, Installation, Repair, Repeater Setup, Coverage Survey, and more! <a href='/customer/services.php' class='underline text-[#C8102E]'>Browse our services →</a>" },

    { pattern: /\b(motorola|icom|kenwood|yaesu|baofeng|kirisun|alinco|entel|diamond|furuno|standard\s*horizon)\b/i,
      reply: "Yes, we carry a wide range of brands including Motorola, ICOM, Kenwood, Yaesu, Baofeng, Kirisun, Diamond, and more! <a href='/index.php' class='underline text-[#C8102E]'>Browse our catalogue →</a>" },

    { pattern: /\b(contact|staff|human|agent|talk.*person)\b/i,
      reply: "You can chat directly with our team on the <a href='/customer/messages.php' class='underline text-[#C8102E]'>Messages</a> page. Available Mon–Sat, 8 AM–6 PM." },

    { pattern: /\b(cart|add.*cart|remove.*cart)\b/i,
      reply: "Manage your cart from the <a href='/index.php' class='underline text-[#C8102E]'>Products</a> page, or click the cart icon in the top nav to review your items." },

    { pattern: /\b(address|shipping\s*address|change.*address|delivery\s*address)\b/i,
      reply: "Add or edit delivery addresses on the <a href='/customer/shipping_address.php' class='underline text-[#C8102E]'>Shipping Address</a> page." },

    { pattern: /\b(price|how much|cost|quote)\b/i,
      reply: "Check our latest prices on the <a href='/index.php' class='underline text-[#C8102E]'>Products</a> page. For bulk orders or custom quotations, contact us via <a href='/customer/messages.php' class='underline text-[#C8102E]'>Messages</a>." },

    { pattern: /\b(warranty|guarantee)\b/i,
      reply: "Warranty coverage: 1 year for radio body; 3 months for accessories. For warranty claims, please message our support team." },
];

const fallbackReplies = [
    "I'm not sure about that, but our team can help! Visit the <a href='/customer/messages.php' class='underline text-[#C8102E]'>Messages</a> page.",
    "Great question! Please reach out to our staff via <a href='/customer/messages.php' class='underline text-[#C8102E]'>Messages</a> for a detailed answer.",
    "I don't have that info right now. Try the <a href='/customer/messages.php' class='underline text-[#C8102E]'>Messages</a> page to chat with our radio experts.",
];

function getChatbotReply(input) {
    for (const { pattern, reply } of chatbotResponses) {
        if (pattern.test(input)) return reply;
    }
    return fallbackReplies[Math.floor(Math.random() * fallbackReplies.length)];
}

function initChatbot() {
    const toggleBtn  = document.getElementById('chatbot-toggle');
    const panel      = document.getElementById('chatbot-panel');
    const closeBtn   = document.getElementById('chatbot-close');
    const messagesEl = document.getElementById('chatbot-messages');
    const inputEl    = document.getElementById('chatbot-input');
    const sendBtn    = document.getElementById('chatbot-send');

    if (!toggleBtn || !panel) return;

    toggleBtn.addEventListener('click', () => {
        panel.classList.toggle('hidden');
        if (!panel.classList.contains('hidden')) {
            inputEl?.focus();
            if (messagesEl && messagesEl.children.length === 0) {
                appendChatMessage('Hi! I\'m ShopEase Assistant 🤖 Ask me about orders, shipping, payments, or our services!', 'bot');
            }
            if (messagesEl) messagesEl.scrollTop = messagesEl.scrollHeight;
        }
    });

    closeBtn?.addEventListener('click', () => panel.classList.add('hidden'));

    function sendChatMessage() {
        const text = inputEl.value.trim();
        if (!text) return;
        appendChatMessage(text, 'user');
        inputEl.value = '';

        // Typing indicator
        const typing = appendChatMessage('…', 'bot', true);
        setTimeout(() => {
            typing.remove();
            appendChatMessage(getChatbotReply(text), 'bot');
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }, 700);
    }

    sendBtn?.addEventListener('click', sendChatMessage);
    inputEl?.addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); sendChatMessage(); }
    });
}

function appendChatMessage(html, role, isTyping = false) {
    const messagesEl = document.getElementById('chatbot-messages');
    if (!messagesEl) return null;

    const wrapper = document.createElement('div');
    wrapper.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'}`;

    const bubble = document.createElement('div');
    bubble.className = [
        'max-w-[85%] px-3 py-2 rounded-2xl text-sm leading-relaxed',
        role === 'user'
            ? 'bg-[#C8102E] text-white rounded-br-sm'
            : 'bg-gray-100 text-gray-800 rounded-bl-sm',
        isTyping ? 'opacity-60 italic' : '',
    ].join(' ');

    bubble.innerHTML = html;
    wrapper.appendChild(bubble);
    messagesEl.appendChild(wrapper);
    messagesEl.scrollTop = messagesEl.scrollHeight;
    return wrapper;
}
