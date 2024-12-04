<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "SE06303";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Simulated registration logic
$success = "";
$error = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate input data
    if (empty($full_name) || empty($phone) || empty($email) || empty($dob) || empty($username) || empty($password) || empty($confirm_password)) {
        $error[] = "Please fill in all required fields.";
    }
    if ($password !== $confirm_password) {
        $error[] = "Password confirmation does not match.";
    }

    if ($stmt->num_rows > 0) {
        $error[] = "Username or email already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (full_name, phone, email, dob, username, password) VALUES (?, ?, ?, ?, ?, ?)");
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Mã hóa mật khẩu
        $stmt->bind_param("ssssss", $full_name, $phone, $email, $dob, $username, $hashed_password);

        if ($stmt->execute()) {
            $success = "Registration successful! You can log in now.";
        } else {
            $error[] = "Error: Unable to register user.";
        }
    }
    $stmt->close();
    try {
        $conn = new mysqli($servername, $username, $password, $dbname);
    
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
    
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    } finally {        
        if (isset($conn) && $conn->connect_errno === 0) {
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Buy & Sell Electronics</title>
    <style>
        /* Body styling */
        body {
            font-family: 'Poppins', sans-serif;
            background: url('anh nen.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 120vh;
        }

        /* Form container */
        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            animation: fadeIn 0.5s ease-in-out;
        }

        /* Fade-in animation */
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

        /* Title styling */
        h2 {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            color: #444;
            margin-bottom: 25px;
        }

        /* Form group styling */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"] {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            background-color: #f9f9f9;
            color: #333;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
            background-color: #fff;
        }

        /* Error message styling */
        .error {
            color: #ff4d4f;
            font-size: 13px;
            margin-top: 5px;
        }

        /* Success message styling */
        .success {
            color: #28a745;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }

        /* Button styling */
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

        button:focus {
            outline: none;
        }

        /* Info link styling */
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
    <script>
        function togglePassword(fieldId, toggleIcon) {
            const field = document.getElementById(fieldId);
            if (field.type === "password") {
                field.type = "text";
                toggleIcon.textContent = "🙈";
            } else {
                field.type = "password";
                toggleIcon.textContent = "👁️";
            }
        }
    </script>
</head>

<body>
    <div class="container">
        <h2>Register</h2>

        <!-- Display error messages -->
        <?php if (!empty($error)): ?>
            <div class="error">
                <?php foreach ($error as $message): ?>
                    <p><?php echo $message; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Display success message -->
        <?php if (!empty($success)): ?>
            <div class="success">
                <?php echo $success; ?>
                <p><a href="login.php" style="color: #007bff; font-weight: bold;">Log in now</a></p>
            </div>
            <script>
                // Auto redirect after 5 seconds
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 5000);
            </script>
        <?php else: ?>
            <form method="post">
                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo isset($full_name) ? htmlspecialchars($full_name) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone" value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" value="<?php echo isset($dob) ? htmlspecialchars($dob) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" required>
                        <span class="toggle-password" onclick="togglePassword('password', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                            👁️
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <div style="position: relative;">
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <span class="toggle-password" onclick="togglePassword('confirm_password', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                            👁️
                        </span>
                    </div>
                </div>

                <button type="submit">Register</button>
            </form>
        <?php endif; ?>

        <div class="info-link">
            <p>Already have an account? <a href="login.php">Log in now</a></p>
        </div>
    </div>
</body>

</html>