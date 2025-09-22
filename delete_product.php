<?php
require 'db.php';
session_start();
 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'seller') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
 
    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
        $stmt->execute([$product_id, $_SESSION['user_id']]);
        echo json_encode(['success' => true, 'message' => 'Product deleted!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
