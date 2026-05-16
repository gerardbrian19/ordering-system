<?php
/**
 * Safely escape a string for HTML output.
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Format a price value to 2 decimal places with currency symbol.
 */
function formatPrice(float $amount, string $currency = '₱'): string
{
    return $currency . number_format($amount, 2);
}

/**
 * Get the current cart item count from localStorage-synced session (fallback to 0).
 * Cart is primarily managed client-side in localStorage.
 */
function getCartCount(): int
{
    return $_SESSION['cart_count'] ?? 0;
}

/**
 * Redirect to a URL and exit.
 */
function redirect(string $url): never
{
    header("Location: $url");
    exit;
}
