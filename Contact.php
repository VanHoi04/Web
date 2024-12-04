<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Thêm thông tin vào cơ sở dữ liệu
    $query = "INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':message', $message);
    $stmt->execute();

    // Thông báo thành công
    echo "<script>alert('Message sent successfully!');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Btec Shop</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff; /* Nền trắng */
            color: #333; /* Chữ màu xám đen */
        }

        header {
            background-color: #000; /* Nền đen */
            color: #fff; /* Chữ trắng */
            padding: 15px 30px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 2em;
            margin: 0;
            font-weight: 600;
        }

        header nav {
            margin-top: 10px;
        }

        header nav a {
            color: #fff; /* Chữ trắng */
            text-decoration: none;
            margin: 0 12px;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        header nav a:hover {
            color: #3498db; /* Chuyển màu sang xanh khi hover */
        }

        main {
            padding: 30px 15px;
            max-width: 700px;
            margin: 0 auto;
        }

        h2 {
            font-size: 1.8em;
            font-weight: bold;
            margin-bottom: 18px;
            color: #000; /* Chữ đen cho tiêu đề */
        }

        form {
            background-color: #fff; /* Nền trắng */
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }

        form label {
            display: block;
            font-size: 1em;
            font-weight: 600;
            color: #333; /* Màu chữ xám đen */
            margin-bottom: 6px;
        }

        form input,
        form textarea {
            width: 100%;
            padding: 12px;
            font-size: 0.95em;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f4f4f4; /* Nền xám nhạt */
            color: #333;
            margin-bottom: 18px;
            box-sizing: border-box;
            transition: border 0.3s ease, background-color 0.3s ease;
        }

        form input:focus,
        form textarea:focus {
            border-color: #3498db;
            background-color: #fff; /* Nền trắng khi focus */
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
        }

        form button {
            background-color: #000; /* Nền đen */
            color: #fff; /* Chữ trắng */
            border: none;
            padding: 12px 22px;
            font-size: 1.1em;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        form button:hover {
            background-color: #333; /* Đổi màu thành đen xám khi hover */
            transform: translateY(-2px);
        }

        form button:active {
            background-color: #000;
            transform: translateY(0);
        }

        footer {
            background-color: #000; /* Nền đen */
            color: #fff; /* Chữ trắng */
            text-align: center;
            padding: 15px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 0.85em;
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
        <h2>Contact Us</h2>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>

            <button type="submit">Submit</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Btec Shop. All rights reserved.</p>
    </footer>
</body>

</html>
