<?php
require 'db.php';
session_start();
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
 
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password, $role]);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['role'] = $role;
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daraz Clone - Signup</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #f1f1f1, #ddd);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .signup-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            color: #f57224;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn {
            background: #f57224;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background: #ff8c00;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
        }
        a {
            color: #f57224;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 10px;
        }
        a:hover {
            text-decoration: underline;
        }
        @media (max-width: 600px) {
            .signup-container {
                padding: 20px;
                margin: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Signup</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form id="signupForm" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role">
                    <option value="buyer">Buyer</option>
                    <option value="seller">Seller</option>
                </select>
            </div>
            <button type="submit" class="btn">Signup</button>
        </form>
        <a href="#" onclick="navigate('login.php')">Already have an account? Login</a>
    </div>
    <script>
        function navigate(page) {
            window.location.href = page;
        }
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            let username = document.getElementById('username').value;
            let password = document.getElementById('password').value;
            if (username.length < 3 || password.length < 6) {
                e.preventDefault();
                alert('Username must be at least 3 characters and password at least 6 characters.');
            }
        });
    </script>
</body>
</html>
