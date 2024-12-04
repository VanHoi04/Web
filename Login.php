<?php
session_start();

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "se06303";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Biến để lưu thông báo lỗi
$error = "";

// Xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Kiểm tra nếu bỏ trống username hoặc password
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Kiểm tra tài khoản trong database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User tồn tại, kiểm tra password
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Lưu session sau khi đăng nhập thành công
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role']; // Lưu role vào session

                // Điều hướng theo role của người dùng
                if ($_SESSION['role'] === 'admin') {
                    // Nếu là admin, chuyển hướng tới trang quản lý sản phẩm
                    header("Location: productmanager.php");
                } else {
                    // Nếu là người dùng bình thường, chuyển hướng tới trang chính
                    if (isset($_SESSION['redirect_url'])) {
                        $redirect_url = $_SESSION['redirect_url'];
                        unset($_SESSION['redirect_url']);
                        header("Location: " . $redirect_url);
                    } else {
                        header("Location: home.php");
                    }
                }
                exit();
            }
        }
        // Thông báo lỗi chung
        $error = "Invalid username or password.";
        $stmt->close();
    }
}

// Đóng kết nối database
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('anh nen.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            color: #444;
            margin-bottom: 25px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            background-color: #f9f9f9;
            margin-bottom: 15px;
            color: #333;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
        }

        .error,
        .success {
            font-size: 14px;
            text-align: center;
            margin-bottom: 10px;
        }

        .error {
            color: #ff4d4f;
        }

        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: linear-gradient(135deg, #0056b3, #003d80);
        }

        .info-link {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
        }

        .info-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .info-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Login</h2>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="info-link">
            <p>New to my shop? <a href="register.php">Create an account?</a></p>
        </div>
    </div>
</body>

</html>
