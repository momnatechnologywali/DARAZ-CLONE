<?php
require 'db.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daraz Clone - Homepage</title>
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
        header h1 {
            margin: 0;
            font-size: 24px;
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
        .hero {
            background: url('https://via.placeholder.com/1200x300') no-repeat center;
            background-size: cover;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            font-size: 28px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        .categories, .featured {
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .category, .product {
            background: white;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .category:hover, .product:hover {
            transform: translateY(-5px);
        }
        .product img {
            max-width: 100%;
            border-radius: 5px;
        }
        .btn {
            background: #f57224;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background: #ff8c00;
        }
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        @media (max-width: 768px) {
            header {
                flex-direction: column;
            }
            nav a {
                margin: 5px;
            }
            .hero {
                font-size: 20px;
                height: 200px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Daraz Clone</h1>
        <nav>
            <a href="#" onclick="navigate('signup.php')">Signup</a>
            <a href="#" onclick="navigate('login.php')">Login</a>
            <a href="#" onclick="navigate('products.php')">Products</a>
            <a href="#" onclick="navigate('cart.php')">Cart</a>
            <a href="#" onclick="navigate('orders.php')">Orders</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'seller'): ?>
                <a href="#" onclick="navigate('seller_products.php')">Seller Dashboard</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="hero">
        <h2>Welcome to Daraz Clone - Shop the Best Deals!</h2>
    </div>
    <section class="categories">
        <h2>Shop by Category</h2>
        <div class="category">Electronics</div>
        <div class="category">Fashion</div>
        <div class="category">Home & Kitchen</div>
        <div class="category">Beauty</div>
    </section>
    <section class="featured">
        <h2>Featured Products</h2>
        <div class="product">
            <img src="https://via.placeholder.com/150" alt="Product">
            <h3>Smartphone</h3>
            <p>$299.99</p>
            <a href="products.php" class="btn">View Details</a>
        </div>
        <div class="product">
            <img src="https://via.placeholder.com/150" alt="Product">
            <h3>Laptop</h3>
            <p>$799.99</p>
            <a href="products.php" class="btn">View Details</a>
        </div>
    </section>
    <footer>
        <p>&copy; 2025 Daraz Clone. All rights reserved.</p>
    </footer>
    <script>
        function navigate(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
