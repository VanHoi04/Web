<?php
include 'config.php';
$message = "";

// Xóa sản phẩm khỏi giỏ hàng
if (isset($_POST['remove_item'])) {
    $cart_id = $_POST['cart_id'];
    $query = "DELETE FROM cart WHERE id = :cart_id";
    $stmt = $conn->prepare($query);
    $stmt->execute([':cart_id' => $cart_id]);
    $message = "Product removed from cart!";
}

// Cập nhật số lượng sản phẩm
if (isset($_POST['update_quantity'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];
    if ($quantity > 0) {
        $query = "UPDATE cart SET quantity = :quantity WHERE id = :cart_id";
        $stmt = $conn->prepare($query);
        $stmt->execute([':quantity' => $quantity, ':cart_id' => $cart_id]);
        $message = "Quantity updated!";
    } else {
        $message = "Quantity must be greater than zero.";
    }
}

// Lấy danh sách sản phẩm trong giỏ hàng
$query = "SELECT * FROM cart";
$stmt = $conn->prepare($query);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - Btec Shop</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
        }

        header nav a {
            color: white;
            margin-right: 15px;
            text-decoration: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #f4f4f4;
        }

        .message {
            color: green;
            margin: 10px 0;
        }

        .btn {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .total {
            text-align: right;
            font-size: 1.2em;
            margin: 20px;
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
        <?php if (!empty($message)): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if (count($cart_items) > 0): ?>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= htmlspecialchars($item['price']) ?> USD</td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="cart_id" value="<?= htmlspecialchars($item['id']) ?>">
                                <input type="number" name="quantity" value="<?= htmlspecialchars($item['quantity']) ?>" min="1" style="width: 60px;">
                                <button type="submit" name="update_quantity" class="btn">Update</button>
                            </form>
                        </td>
                        <td><?= htmlspecialchars($item['price'] * $item['quantity']) ?> USD</td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="cart_id" value="<?= htmlspecialchars($item['id']) ?>">
                                <button type="submit" name="remove_item" class="btn btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                    <?php $total += $item['price'] * $item['quantity']; ?>
                <?php endforeach; ?>
            </table>
            <div class="total">
                <strong>Total:</strong> <?= $total ?> USD
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </main>
</body>

</html>