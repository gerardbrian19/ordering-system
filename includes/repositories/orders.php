<?php
// Order repository — all DB queries related to orders and order items

require_once __DIR__ . '/../db.php';

function getOrders(PDO $pdo, ?int $customerId = null): array {
    if ($customerId !== null) {
        $stmt = $pdo->prepare('
            SELECT o.*, u.name AS customer_name
            FROM orders o
            JOIN users u ON o.customer_id = u.id
            WHERE o.customer_id = ?
            ORDER BY o.created_at DESC
        ');
        $stmt->execute([$customerId]);
    } else {
        $stmt = $pdo->query('
            SELECT o.*, u.name AS customer_name
            FROM orders o
            JOIN users u ON o.customer_id = u.id
            ORDER BY o.created_at DESC
        ');
    }
    return $stmt->fetchAll();
}

function getOrderById(PDO $pdo, int $id): array|false {
    $stmt = $pdo->prepare('
        SELECT o.*, u.name AS customer_name
        FROM orders o
        JOIN users u ON o.customer_id = u.id
        WHERE o.id = ?
    ');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getOrderItems(PDO $pdo, int $orderId): array {
    $stmt = $pdo->prepare('
        SELECT oi.*, p.name AS product_name, p.sku
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?
    ');
    $stmt->execute([$orderId]);
    return $stmt->fetchAll();
}

function createOrder(PDO $pdo, int $customerId, float $total): int {
    $stmt = $pdo->prepare('
        INSERT INTO orders (customer_id, total, status, created_at)
        VALUES (?, ?, \'pending\', NOW())
    ');
    $stmt->execute([$customerId, $total]);
    return (int) $pdo->lastInsertId();
}

function addOrderItem(PDO $pdo, int $orderId, int $productId, int $qty, float $price): void {
    $stmt = $pdo->prepare('
        INSERT INTO order_items (order_id, product_id, quantity, price)
        VALUES (?, ?, ?, ?)
    ');
    $stmt->execute([$orderId, $productId, $qty, $price]);
}

function updateOrderStatus(PDO $pdo, int $id, string $status): void {
    $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
    $stmt->execute([$status, $id]);
}
