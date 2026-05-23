<?php
/**
 * session.php
 * Centralised session bootstrap — require this instead of calling session_start() directly.
 *
 * Security settings applied:
 *   - HttpOnly cookie flag   → JS cannot read the session cookie
 *   - SameSite=Lax           → CSRF protection for cross-site requests
 *   - Secure flag            → Cookie only sent over HTTPS (auto-detected)
 *   - use_strict_mode        → Server rejects unrecognised session IDs
 *   - use_only_cookies       → Prevents session ID fixation via URL
 *
 * The session_status() guard makes this safe to require_once more than once.
 */

if (session_status() === PHP_SESSION_NONE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
           || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

    session_set_cookie_params([
        'lifetime' => 0,        // session cookie — expires when browser closes
        'path'     => '/',
        'domain'   => '',
        'secure'   => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    ini_set('session.use_strict_mode',  '1');
    ini_set('session.use_only_cookies', '1');

    session_name('goldcomm_sess');
    session_start();
}
