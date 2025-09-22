<?php
require 'db.php';
session_start();
 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
 
$stmt = $pdo->prepare("SELECT c.*, p.name, p.price, p.image FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll();
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daraz Clone - Cart</title>
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
        .cart {
            padding: 20px;
        }
        .cart-item {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .cart-item img {
            max-width: 100px;
            border-radius: 5px;
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
        .total {
            font-size: 20px;
            font-weight: bold;
            text-align: right;
            padding: 20px;
        }
        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Daraz Clone</h1>
        <nav>
            <a href="#" onclick="navigate('index.php')">Home</a>
            <a href="#" onclick="navigate('products.php')">Products</a>
            <a href="#" onclick="navigate('orders.php')">Orders</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'seller'): ?>
                <a href="#" onclick="navigate('seller_products.php')">Seller Dashboard</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="cart">
        <h2>Your Cart</h2>
        <?php foreach ($cart_items as $item): ?>
            <?php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; ?>
            <div class="cart-item">
                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Product">
                <div>
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
                    <p>Quantity: <?php echo $item['quantity']; ?></p>
                    <p>Subtotal: $<?php echo number_format($subtotal, 2); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="total">
            Total: $<?php echo number_format($total, 2); ?>
        </div>
        <button class="btn" onclick="checkout()">Proceed to Checkout</button>
    </div>
    <script>
        function navigate(page) {
            window.location.href = page;
        }
        function checkout() {
            fetch('checkout.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(response => response.json()).then(data => {
                alert(data.message);
                if (data.success) navigate('orders.php');
            });
        }
    </script>
</body>
</html>
