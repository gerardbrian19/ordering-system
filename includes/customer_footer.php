<!-- ═══════════════════════════════════════════════ CHATBOT WIDGET ══ -->
<div id="chatbot-container" class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3">

    <!-- Chat Panel -->
    <div id="chatbot-panel"
         class="hidden w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 flex flex-col overflow-hidden chatbot-panel">

        <!-- Panel Header -->
        <div class="bg-[#C8102E] px-4 py-3 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0
                                 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-semibold text-sm">Goldcomm Assistant</p>
                    <p class="text-white/70 text-xs">Ask me anything 🤖</p>
                </div>
            </div>
            <button id="chatbot-close" class="text-white/70 hover:text-white transition p-1" aria-label="Close chat">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div id="chatbot-messages" class="flex-1 overflow-y-auto px-4 py-3 space-y-3 bg-gray-50">
            <!-- Populated by JS on init -->
        </div>

        <!-- Input Area -->
        <div class="px-3 py-2.5 bg-white border-t border-gray-100 shrink-0">
            <div class="flex items-center gap-2">
                <input id="chatbot-input"
                       type="text"
                       placeholder="Ask me anything..."
                       autocomplete="off"
                       class="flex-1 text-sm border border-gray-200 rounded-xl px-3 py-2
                              focus:outline-none focus:ring-2 focus:ring-[#C8102E] transition">
                <button id="chatbot-send"
                        class="p-2 bg-[#C8102E] text-white rounded-xl hover:bg-[#A50D25] transition shrink-0"
                        aria-label="Send message">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </div>
            <p class="text-center text-xs text-gray-400 mt-1.5">
                Need more help?
                <a href="/messages.php" class="text-[#C8102E] hover:underline">Chat with staff →</a>
            </p>
        </div>
    </div>

    <!-- Toggle Bubble -->
    <button id="chatbot-toggle"
            class="w-14 h-14 bg-[#C8102E] text-white rounded-full shadow-xl hover:bg-[#A50D25]
                   transition hover:scale-105 active:scale-95 flex items-center justify-center relative"
            aria-label="Open chat assistant">
        <svg id="chatbot-icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0
                     01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <svg id="chatbot-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<script src="/assets/js/app.js"></script>
</body>
</html>
