<?php
// Kết nối cơ sở dữ liệu
include 'config.php';

// Lấy ID sản phẩm từ URL
$product_id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($product_id == 0) {
    echo "Product not found!";
    exit;
}

// Truy vấn thông tin sản phẩm từ cơ sở dữ liệu
$query = "SELECT * FROM products WHERE id = :product_id";
$stmt = $conn->prepare($query);
$stmt->execute([':product_id' => $product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Nếu sản phẩm không tồn tại
if (!$product) {
    echo "Product not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - Product Details</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Product Details</h1>
        <nav>
            <a href="home.php">Home</a>
            <a href="contact.php">Contact</a>
            <a href="cart.php">My Cart</a>
            <a href="profile.php">Profile</a>
        </nav>
    </header>

    <main>
        <h2><?= htmlspecialchars($product['name']) ?></h2>
        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="max-width: 500px;">
        <p><strong>Price:</strong> $<?= htmlspecialchars($product['price']) ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($product['description']) ?></p>

        <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
            <button type="submit">Add to Cart</button>
        </form>
    </main>
</body>

</html>
