<?php
/**
 * login_handler.php
 * Processes the login form POST.
 * TODO: Connect to the database and verify credentials.
 */
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/logger.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /login.php');
    exit;
}

// CSRF check
$submittedToken = $_POST['csrf_token'] ?? '';
if (!validateCsrfToken($submittedToken)) {
    getLogger('security')->warning('CSRF token mismatch on login', ['ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown']);
    $_SESSION['login_error'] = 'Invalid request. Please try again.';
    header('Location: /login.php');
    exit;
}

// Sanitize inputs (validation only — never trust user input)
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    $_SESSION['login_error'] = 'Email and password are required.';
    header('Location: /login.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['login_error'] = 'Please enter a valid email address.';
    header('Location: /login.php');
    exit;
}

// ── Static users (frontend phase — replace with DB lookup later) ──────────
$staticUsers = [
    'customer@goldcomm.com' => ['id' => 1, 'name' => 'Juan dela Cruz', 'password' => 'customer123', 'role' => 'customer'],
    'staff@goldcomm.com'    => ['id' => 2, 'name' => 'Maria Santos',   'password' => 'staff123',    'role' => 'staff'],
    'admin@goldcomm.com'    => ['id' => 3, 'name' => 'Admin User',     'password' => 'admin123',    'role' => 'admin'],
];

$user = $staticUsers[strtolower($email)] ?? null;

if (!$user || $user['password'] !== $password) {
    getLogger('security')->warning('Failed login attempt', ['email' => $email, 'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown']);
    $_SESSION['login_error'] = 'Incorrect email or password.';
    header('Location: /login.php');
    exit;
}

session_regenerate_id(true);
getLogger('security')->info('User logged in', ['user_id' => $user['id'], 'role' => $user['role'], 'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown']);
$_SESSION['user_id']   = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['role']      = $user['role'];

$redirects = [
    'customer' => '/index.php',
    'staff'    => '/staff/index.php',
    'admin'    => '/admin/index.php',
];

header('Location: ' . ($redirects[$user['role']] ?? '/index.php'));
exit;
