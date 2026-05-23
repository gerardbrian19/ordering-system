<?php
require_once __DIR__ . '/../includes/session.php';
session_unset();
session_destroy();
setcookie(session_name(), '', time() - 3600, '/', '', false, true);
header('Location: /login.php');
exit;
