<?php
// User repository — all DB queries related to users

require_once __DIR__ . '/../db.php';

function getUserByEmail(PDO $pdo, string $email): array|false {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function getUserById(PDO $pdo, int $id): array|false {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createUser(PDO $pdo, string $name, string $email, string $password, string $role = 'customer'): int {
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('
        INSERT INTO users (name, email, password, role, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ');
    $stmt->execute([$name, $email, $hash, $role]);
    return (int) $pdo->lastInsertId();
}

function updateUserPassword(PDO $pdo, int $id, string $newPassword): void {
    $hash = password_hash($newPassword, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
    $stmt->execute([$hash, $id]);
}
