<?php
require 'db.php';
session_start();
 
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to checkout.']);
    exit();
}
 
try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("SELECT c.*, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $cart_items = $stmt->fetchAll();
 
    $total = 0;
    foreach ($cart_items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
 
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'pending')");
    $stmt->execute([$_SESSION['user_id'], $total]);
    $order_id = $pdo->lastInsertId();
 
    foreach ($cart_items as $item) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
    }
 
    $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$_SESSION['user_id']]);
    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Order placed successfully!']);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
