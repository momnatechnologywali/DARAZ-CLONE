<?php
require 'db.php';
session_start();
 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'seller') {
    header("Location: login.php");
    exit();
}
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $seller_id = $_SESSION['user_id'];
 
    $image = $_FILES['image']['name'];
    $target = "Uploads/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);
 
    try {
        $stmt = $pdo->prepare("INSERT INTO products (seller_id, category_id, name, price, stock, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$seller_id, $category_id, $name, $price, $stock, $target]);
        header("Location: seller_products.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
