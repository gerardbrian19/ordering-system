<?php
/**
 * Customer-facing HTML shell + sticky navbar.
 * Requires: $pageTitle (string) and $activePage (string) set before including.
 * Requires: session_start() already called by the consuming page.
 */
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

$pageTitle  = $pageTitle  ?? 'Goldcomm';
$activePage = $activePage ?? '';

$navItems = [
    ['href' => '/index.php',       'label' => 'Products',  'key' => 'products'],
    ['href' => '/services.php',    'label' => 'Services',  'key' => 'services'],
    ['href' => '/orders.php',      'label' => 'My Orders', 'key' => 'orders'],
    ['href' => '/my_bookings.php', 'label' => 'Bookings',  'key' => 'bookings'],
    ['href' => '/messages.php',    'label' => 'Messages',  'key' => 'messages'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> — Goldcomm Corporation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

<header class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">

            <!-- Logo -->
            <a href="/index.php" class="flex items-center gap-2 shrink-0">
                <div class="w-8 h-8 bg-[#C8102E] rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-900 hidden sm:block">
                    Gold<span class="text-[#C8102E]">comm</span>
                </span>
            </a>

            <!-- Desktop Nav -->
            <nav class="hidden md:flex items-center gap-0.5">
                <?php foreach ($navItems as $item):
                    $isActive = $activePage === $item['key'];
                ?>
                <a href="<?= $item['href'] ?>"
                   class="px-3 py-2 text-sm font-medium rounded-lg transition
                          <?= $isActive
                              ? 'bg-[#FFF5F5] text-[#C8102E]'
                              : 'text-gray-600 hover:text-[#C8102E] hover:bg-gray-50' ?>">
                    <?= $item['label'] ?>
                </a>
                <?php endforeach; ?>
            </nav>

            <!-- Right: Cart + User -->
            <div class="flex items-center gap-1.5 shrink-0">

                <!-- Cart -->
                <a href="/cart.php"
                   class="relative p-2 text-gray-500 hover:text-[#C8102E] hover:bg-[#FFF5F5] rounded-lg transition"
                   aria-label="View cart">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184
                                 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span id="cart-badge"
                          class="hidden absolute -top-1 -right-1 bg-[#F5A800] text-white text-[10px]
                                 font-bold w-4 h-4 rounded-full flex items-center justify-center leading-none">
                        0
                    </span>
                </a>

                <?php if (isLoggedIn()): ?>
                <!-- User Dropdown -->
                <div class="relative" id="user-dropdown-wrapper">
                    <button id="user-menu-btn"
                            class="flex items-center gap-2 p-1.5 text-gray-600 hover:text-[#C8102E]
                                   hover:bg-[#FFF5F5] rounded-lg transition">
                        <div class="w-7 h-7 bg-[#FEECEC] rounded-full flex items-center justify-center">
                            <span class="text-xs font-semibold text-[#C8102E]">
                                <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                            </span>
                        </div>
                        <span class="text-sm font-medium hidden sm:block">
                            <?= e($_SESSION['user_name'] ?? 'Account') ?>
                        </span>
                        <svg class="w-4 h-4 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="user-dropdown"
                         class="hidden absolute right-0 mt-2 w-52 bg-white rounded-xl border border-gray-100
                                shadow-lg py-1 text-sm z-50">
                        <div class="px-4 py-2.5 border-b border-gray-50">
                            <p class="font-semibold text-gray-900 text-sm truncate">
                                <?= e($_SESSION['user_name'] ?? '') ?>
                            </p>
                            <p class="text-gray-400 text-xs capitalize">
                                <?= e($_SESSION['role'] ?? 'customer') ?> account
                            </p>
                        </div>
                        <a href="/shipping_address.php"
                           class="flex items-center gap-2.5 px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Shipping Address
                        </a>
                        <hr class="my-1 border-gray-100">
                        <a href="/logout.php"
                           class="flex items-center gap-2.5 px-4 py-2.5 text-red-600 hover:bg-red-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Sign out
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <a href="/login.php"
                   class="hidden sm:inline-flex items-center gap-1.5 bg-[#C8102E] text-white text-sm
                          font-semibold rounded-lg px-4 py-2 hover:bg-[#A50D25] transition active:scale-95">
                    Sign in
                </a>
                <?php endif; ?>

                <!-- Mobile Toggle -->
                <button id="mobile-menu-btn"
                        class="md:hidden p-2 text-gray-500 hover:text-[#C8102E] hover:bg-[#FFF5F5] rounded-lg transition">
                    <svg id="menu-icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="menu-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white px-4 py-3 space-y-0.5">
        <?php foreach ($navItems as $item):
            $isActive = $activePage === $item['key'];
        ?>
        <a href="<?= $item['href'] ?>"
           class="flex items-center px-3 py-2.5 text-sm rounded-lg
                  <?= $isActive ? 'bg-[#FFF5F5] text-[#C8102E] font-medium' : 'text-gray-700 hover:bg-gray-50' ?>">
            <?= $item['label'] ?>
        </a>
        <?php endforeach; ?>
        <a href="/shipping_address.php"
           class="flex items-center px-3 py-2.5 text-sm text-gray-700 rounded-lg hover:bg-gray-50">
            Shipping Address
        </a>
        <hr class="border-gray-100 my-1">
        <?php if (isLoggedIn()): ?>
        <a href="/logout.php" class="flex items-center px-3 py-2.5 text-sm text-red-600 rounded-lg hover:bg-red-50">
            Sign out
        </a>
        <?php else: ?>
        <a href="/login.php" class="flex items-center px-3 py-2.5 text-sm text-[#C8102E] font-medium rounded-lg hover:bg-[#FFF5F5]">
            Sign in
        </a>
        <?php endif; ?>
    </div>
</header>
