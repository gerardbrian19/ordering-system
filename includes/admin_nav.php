<?php
/**
 * Admin HTML shell + sidebar navigation.
 * Requires: $pageTitle (string) and $activePage (string) set before including.
 * Requires: session_start() already called by the consuming page.
 */
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

requireAdmin();

$pageTitle  = $pageTitle  ?? 'Admin';
$activePage = $activePage ?? '';

$adminNavItems = [
    [
        'href'  => '/admin/index.php',
        'label' => 'Dashboard',
        'key'   => 'dashboard',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
    ],
    [
        'href'  => '/admin/products.php',
        'label' => 'Products',
        'key'   => 'products',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
    ],
    [
        'href'  => '/admin/orders.php',
        'label' => 'Orders',
        'key'   => 'orders',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>',
    ],
    [
        'href'  => '/admin/messages.php',
        'label' => 'Messages',
        'key'   => 'messages',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z"/>',
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> — Goldcomm Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Mobile sidebar backdrop -->
<div id="sidebar-backdrop"
     class="fixed inset-0 bg-black/60 z-20 hidden lg:hidden"
     aria-hidden="true"></div>

<!-- ═══════════════════════════════════════════════════════ SIDEBAR ═══ -->
<aside id="admin-sidebar"
       class="fixed inset-y-0 left-0 w-64 bg-gray-900 z-30 flex flex-col
              -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">

    <!-- Brand -->
    <a href="/admin/index.php"
       class="flex items-center gap-3 px-5 py-5 border-b border-white/10 hover:bg-white/5 transition shrink-0">
        <div class="w-9 h-9 bg-[#C8102E] rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905
                         10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
            </svg>
        </div>
        <div>
            <p class="text-white font-bold text-sm leading-tight">
                Gold<span class="text-[#C8102E]">comm</span>
            </p>
            <p class="text-gray-400 text-xs">Control Panel</p>
        </div>
    </a>

    <!-- Navigation links -->
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
        <?php foreach ($adminNavItems as $item):
            $isActive = $activePage === $item['key'];
        ?>
        <a href="<?= $item['href'] ?>"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                  <?= $isActive
                      ? 'bg-[#C8102E] text-white shadow-sm'
                      : 'text-gray-400 hover:bg-white/10 hover:text-white' ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <?= $item['icon'] ?>
            </svg>
            <?= $item['label'] ?>
        </a>
        <?php endforeach; ?>

        <div class="pt-4 pb-1">
            <p class="px-3 text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Account</p>
        </div>

        <a href="/index.php" target="_blank"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium
                  text-gray-400 hover:bg-white/10 hover:text-white transition">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            View Storefront
        </a>

        <a href="/logout.php"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium
                  text-gray-400 hover:bg-white/10 hover:text-white transition">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Logout
        </a>
    </nav>

    <!-- Admin user info -->
    <div class="px-4 py-4 border-t border-white/10 shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-gradient-to-br from-[#C8102E] to-red-700 rounded-full
                        flex items-center justify-center text-white text-sm font-bold shrink-0">
                <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
            </div>
            <div class="min-w-0">
                <p class="text-white text-sm font-semibold truncate"><?= e($_SESSION['user_name'] ?? 'Admin') ?></p>
                <p class="text-gray-400 text-xs">Administrator</p>
            </div>
        </div>
    </div>
</aside>

<!-- ═══════════════════════════════════════════ MAIN CONTENT WRAPPER ═══ -->
<div class="lg:pl-64 min-h-screen flex flex-col">

    <!-- Top header bar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-10 shadow-sm">
        <div class="flex items-center justify-between px-4 sm:px-6 h-16 gap-4">
            <div class="flex items-center gap-3">
                <!-- Hamburger (mobile only) -->
                <button id="sidebar-toggle"
                        class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition"
                        aria-label="Open navigation menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-base font-bold text-gray-900"><?= e($pageTitle) ?></h1>
            </div>

            <div class="flex items-center gap-2">
                <span class="hidden sm:inline-flex items-center gap-2 text-sm text-gray-600
                             bg-gray-50 border border-gray-200 px-3 py-1.5 rounded-lg">
                    <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                    <?= e($_SESSION['user_name'] ?? 'Admin') ?>
                </span>
            </div>
        </div>
    </header>

    <!-- Page content starts here — closed by admin_footer.php -->
    <div class="flex-1 p-4 sm:p-6">
