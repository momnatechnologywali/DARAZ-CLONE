<?php
require 'db.php';
session_start();
 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'seller') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
 
    try {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $order_id]);
        echo json_encode(['success' => true, 'message' => 'Order status updated!']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
