<?php
session_start();

require_once __DIR__ . '/../includes/auth.php';

// If already logged in, redirect to home
if (isLoggedIn()) {
    header('Location: /index.php');
    exit;
}

$csrfToken = generateCsrfToken();
$error = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Goldcomm Corporation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-[#FFF5F5] via-white to-[#FFF8E6] flex items-center justify-center px-4">

    <!-- Card -->
    <div class="w-full max-w-md">

        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/index.php" class="inline-flex items-center gap-2">
                <div class="w-10 h-10 bg-[#C8102E] rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold text-gray-900">Gold<span class="text-[#C8102E]">comm</span></span>
            </a>
            <p class="mt-2 text-sm text-gray-500">Two-Way Radio Specialists</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <h1 class="text-xl font-bold text-gray-900 mb-1">Welcome back</h1>
            <p class="text-sm text-gray-500 mb-6">Sign in to your account to continue</p>

            <!-- Error Alert -->
            <?php if ($error): ?>
            <div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                          clip-rule="evenodd"/>
                </svg>
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="/login_handler.php" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email address
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        autocomplete="email"
                        required
                        placeholder="you@example.com"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-[#C8102E] focus:border-transparent transition"
                    >
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <a href="#" class="text-xs text-[#C8102E] hover:text-[#A50D25] font-medium">
                            Forgot password?
                        </a>
                    </div>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            autocomplete="current-password"
                            required
                            placeholder="••••••••"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 pr-10 text-sm text-gray-900 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-[#C8102E] focus:border-transparent transition"
                        >
                        <!-- Toggle password visibility -->
                        <button
                            type="button"
                            id="toggle-password"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600"
                            aria-label="Toggle password visibility"
                        >
                            <svg id="eye-open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                         -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7
                                         a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878
                                         l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29
                                         M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7
                                         a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="mb-6 flex items-center gap-2">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="w-4 h-4 text-[#C8102E] border-gray-300 rounded focus:ring-[#C8102E]"
                    >
                    <label for="remember" class="text-sm text-gray-600">Remember me for 30 days</label>
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="w-full bg-[#C8102E] text-white font-semibold rounded-lg px-4 py-2.5 text-sm
                           hover:bg-[#A50D25] focus:outline-none focus:ring-2 focus:ring-[#C8102E] focus:ring-offset-2
                           transition active:scale-95"
                >
                    Sign in
                </button>
            </form>
        </div>

        <!-- Register Link -->
        <p class="text-center text-sm text-gray-500 mt-6">
            Don't have an account?
            <a href="/register.php" class="text-[#C8102E] font-medium hover:text-[#A50D25]">Create one free</a>
        </p>

        <!-- Demo Accounts -->
        <div class="mt-5 bg-amber-50 border border-amber-200 rounded-xl p-4">
            <p class="text-xs font-semibold text-amber-800 mb-2 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                Demo Accounts
            </p>
            <div class="space-y-1.5 text-xs text-amber-700 font-mono">
                <div class="flex justify-between"><span>customer@shopease.com</span><span class="text-amber-500">customer123</span></div>
                <div class="flex justify-between"><span>staff@shopease.com</span><span class="text-amber-500">staff123</span></div>
                <div class="flex justify-between"><span>admin@shopease.com</span><span class="text-amber-500">admin123</span></div>
            </div>
        </div>

    </div>

    <script>
        // Toggle password visibility
        const toggleBtn = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');

        toggleBtn.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            eyeOpen.classList.toggle('hidden', isHidden);
            eyeClosed.classList.toggle('hidden', !isHidden);
        });
    </script>
</body>
</html>
