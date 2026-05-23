<?php
// Message repository — all DB queries related to internal messaging

require_once __DIR__ . '/../db.php';

function getMessages(PDO $pdo, int $userId): array {
    $stmt = $pdo->prepare('
        SELECT m.*, 
               s.name AS sender_name, 
               r.name AS recipient_name
        FROM messages m
        JOIN users s ON m.sender_id = s.id
        JOIN users r ON m.recipient_id = r.id
        WHERE m.sender_id = ? OR m.recipient_id = ?
        ORDER BY m.created_at DESC
    ');
    $stmt->execute([$userId, $userId]);
    return $stmt->fetchAll();
}

function getMessageById(PDO $pdo, int $id): array|false {
    $stmt = $pdo->prepare('SELECT * FROM messages WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function sendMessage(PDO $pdo, int $senderId, int $recipientId, string $subject, string $body): int {
    $stmt = $pdo->prepare('
        INSERT INTO messages (sender_id, recipient_id, subject, body, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ');
    $stmt->execute([$senderId, $recipientId, $subject, $body]);
    return (int) $pdo->lastInsertId();
}

function markMessageRead(PDO $pdo, int $id): void {
    $stmt = $pdo->prepare('UPDATE messages SET read_at = NOW() WHERE id = ?');
    $stmt->execute([$id]);
}

function getUnreadCount(PDO $pdo, int $userId): int {
    $stmt = $pdo->prepare('
        SELECT COUNT(*) FROM messages
        WHERE recipient_id = ? AND read_at IS NULL
    ');
    $stmt->execute([$userId]);
    return (int) $stmt->fetchColumn();
}
