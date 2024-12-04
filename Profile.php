<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $full_name = htmlspecialchars($_POST['full_name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);

    // Cập nhật thông tin người dùng trong cơ sở dữ liệu
    $query = "UPDATE users SET full_name = :full_name, email = :email, phone = :phone WHERE id = 1"; // Giả sử ID người dùng là 1 (demo)
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
}

$query = "SELECT * FROM users WHERE id = 1"; // Giả sử ID người dùng là 1 (demo)
$stmt = $conn->prepare($query);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra nếu không có dữ liệu người dùng
if (!$user) {
    die("User not found!");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Phone Store</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            color: #333;
        }

        header {
            background-color: #000;
            color: white;
            padding: 15px 30px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 2em;
            margin: 0;
        }

        header nav {
            margin-top: 10px;
        }

        header nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }

        header nav a:hover {
            color: #3498db;
        }

        main {
            padding: 30px 15px;
            max-width: 700px;
            margin: 0 auto;
        }

        h2 {
            font-size: 1.8em;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }

        form label {
            display: block;
            font-size: 1em;
            font-weight: 600;
            margin-bottom: 6px;
            color: #333;
        }

        form input {
            width: 100%;
            padding: 12px;
            font-size: 1em;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f4f4f4;
            margin-bottom: 18px;
            box-sizing: border-box;
        }

        form input:focus {
            border-color: #3498db;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
        }

        form button {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 12px 22px;
            font-size: 1.1em;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        form button:hover {
            background-color: #333;
            transform: translateY(-2px);
        }

        form button:active {
            background-color: #000;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <header>
        <h1><a href="home.php" style="color: white; text-decoration: none;">Btec Shop</a></h1>
        <nav>
            <a href="home.php">Home</a>
            <a href="contact.php">Contact</a>
            <a href="profile.php">Profile</a>
            <a href="cart.php">My Cart</a>
        </nav>
    </header>

    <main>
        <h2>Update Your Information</h2>
        <form method="POST">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
            
            <button type="submit">Update</button>
        </form>
    </main>
</body>
</html>
