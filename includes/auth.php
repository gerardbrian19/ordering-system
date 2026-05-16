<?php
/**
 * Require the user to be logged in.
 * Redirects to login.php if no valid session exists.
 */
function requireLogin(): void
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login.php');
        exit;
    }
}

/**
 * Require the user to be an admin.
 * Redirects to home if role is not 'admin'.
 */
function requireAdmin(): void
{
    requireLogin();
    if (($_SESSION['role'] ?? '') !== 'admin') {
        header('Location: /index.php');
        exit;
    }
}

/**
 * Require the user to be a staff member.
 * Redirects to home if role is not 'staff'.
 */
function requireStaff(): void
{
    requireLogin();
    if (($_SESSION['role'] ?? '') !== 'staff') {
        header('Location: /index.php');
        exit;
    }
}

/**
 * Check if a user is currently logged in.
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * Generate a CSRF token and store it in the session.
 */
function generateCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate a submitted CSRF token against the session value.
 */
function validateCsrfToken(string $token): bool
{
    return isset($_SESSION['csrf_token']) &&
           hash_equals($_SESSION['csrf_token'], $token);
}
