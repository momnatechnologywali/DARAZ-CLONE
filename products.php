<?php
require 'db.php';
session_start();
 
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';
 
$query = "SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE 1=1";
$params = [];
 
if ($search) {
    $query .= " AND p.name LIKE ?";
    $params[] = "%$search%";
}
if ($category) {
    $query .= " AND c.id = ?";
    $params[] = $category;
}
if ($min_price) {
    $query .= " AND p.price >= ?";
    $params[] = $min_price;
}
if ($max_price) {
    $query .= " AND p.price <= ?";
    $params[] = $max_price;
}
 
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll();
 
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daraz Clone - Products</title>
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
        .search-filter {
            padding: 20px;
            background: white;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .search-filter form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .search-filter input, .search-filter select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .product {
            background: white;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .product:hover {
            transform: translateY(-5px);
        }
        .product img {
            max-width: 100%;
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
        @media (max-width: 768px) {
            .search-filter form {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Daraz Clone</h1>
        <nav>
            <a href="#" onclick="navigate('index.php')">Home</a>
            <a href="#" onclick="navigate('cart.php')">Cart</a>
            <a href="#" onclick="navigate('orders.php')">Orders</a>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'seller'): ?>
                <a href="#" onclick="navigate('seller_products.php')">Seller Dashboard</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="search-filter">
        <form method="GET">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
            <select name="category">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo $category == $cat['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="min_price" placeholder="Min Price" value="<?php echo htmlspecialchars($min_price); ?>">
            <input type="number" name="max_price" placeholder="Max Price" value="<?php echo htmlspecialchars($max_price); ?>">
            <button type="submit" class="btn">Search</button>
        </form>
    </div>
    <div class="products">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product">
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p><?php echo htmlspecialchars($product['category_name']); ?></p>
                <p>$<?php echo number_format($product['price'], 2); ?></p>
                <button class="btn" onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
            </div>
        <?php endforeach; ?>
    </div>
    <script>
        function navigate(page) {
            window.location.href = page;
        }
        function addToCart(productId) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'product_id=' + productId + '&quantity=1'
            }).then(response => response.json()).then(data => {
                alert(data.message);
                if (data.success) navigate('cart.php');
            });
        }
    </script>
</body>
</html>
