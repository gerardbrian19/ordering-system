<?php
// Product repository — all DB queries related to products

require_once __DIR__ . '/../db.php';

function getProducts(PDO $pdo): array {
    $stmt = $pdo->query('SELECT * FROM products ORDER BY name ASC');
    return $stmt->fetchAll();
}

function getProductById(PDO $pdo, int $id): array|false {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function createProduct(PDO $pdo, array $data): int {
    $stmt = $pdo->prepare('
        INSERT INTO products (name, sku, description, price, stock, category)
        VALUES (:name, :sku, :description, :price, :stock, :category)
    ');
    $stmt->execute([
        ':name'        => $data['name'],
        ':sku'         => $data['sku'],
        ':description' => $data['description'],
        ':price'       => $data['price'],
        ':stock'       => $data['stock'],
        ':category'    => $data['category'],
    ]);
    return (int) $pdo->lastInsertId();
}

function updateProduct(PDO $pdo, int $id, array $data): void {
    $stmt = $pdo->prepare('
        UPDATE products
        SET name = :name, sku = :sku, description = :description,
            price = :price, stock = :stock, category = :category
        WHERE id = :id
    ');
    $stmt->execute([
        ':name'        => $data['name'],
        ':sku'         => $data['sku'],
        ':description' => $data['description'],
        ':price'       => $data['price'],
        ':stock'       => $data['stock'],
        ':category'    => $data['category'],
        ':id'          => $id,
    ]);
}

function updateProductStock(PDO $pdo, int $id, int $stock): void {
    $stmt = $pdo->prepare('UPDATE products SET stock = ? WHERE id = ?');
    $stmt->execute([$stock, $id]);
}

function deleteProduct(PDO $pdo, int $id): void {
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$id]);
}
