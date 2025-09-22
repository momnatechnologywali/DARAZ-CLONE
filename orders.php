<?php
require 'db.php';
session_start();
 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
 
$role = $_SESSION['role'];
if ($role == 'buyer') {
    $stmt = $pdo->prepare("SELECT o.*, oi.product_id, oi.quantity, oi.price, p.name FROM orders o JOIN order_items oi ON o.id = oi.order_id JOIN products p ON oi.product_id = p.id WHERE o.user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
} else {
    $stmt = $pdo->prepare("SELECT o.*, oi.product_id, oi.quantity, oi.price, p.name FROM orders o JOIN order_items oi ON o.id = oi.order_id JOIN products p ON oi.product_id = p.id WHERE p.seller_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
}
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daraz Clone - Orders</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f1f1;
        }
        header {
            background: linear-gradient(90deg, #f57224, #ff8c00);
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .orders {
            padding: 20px;
        }
        .order {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
        }
        .btn {
            background: #f57224;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background: #ff8c00;
        }
    </style>
</head>
<body>
    <header>
        <h1>Daraz Clone</h1>
        <nav>
            <a href="#" onclick="navigate('index.php')">Home</a>
            <a href="#" onclick="navigate('products.php')">Products</a>
            <a href="#" onclick="navigate('cart.php')">Cart</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'seller'): ?>
                <a href="#" onclick="navigate('seller_products.php')">Seller Dashboard</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="orders">
        <h2>Your Orders</h2>
        <?php foreach ($orders as $order): ?>
            <div class="order">
                <h3>Order #<?php echo $order['id']; ?></h3>
                <p>Product: <?php echo htmlspecialchars($order['name']); ?></p>
                <p>Quantity: <?php echo $order['quantity']; ?></p>
                <p>Total: $<?php echo number_format($order['price'] * $order['quantity'], 2); ?></p>
                <p>Status: <?php echo htmlspecialchars($order['status']); ?></p>
                <?php if ($role == 'seller'): ?>
                    <select onchange="updateStatus(<?php echo $order['id']; ?>, this.value)">
                        <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                        <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                    </select>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <script>
        function navigate(page) {
            window.location.href = page;
        }
        function updateStatus(orderId, status) {
            fetch('update_order_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'order_id=' + orderId + '&status=' + status
            }).then(response => response.json()).then(data => {
                alert(data.message);
                if (data.success) location.reload();
            });
        }
    </script>
</body>
</html>
