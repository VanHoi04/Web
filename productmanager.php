<?php
// Kết nối cơ sở dữ liệu
include 'config.php';

$message = "";

// Xử lý thêm sản phẩm
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    if ($name && $price && $image) {
        $query = "INSERT INTO products (name, price, image) VALUES (:name, :price, :image)";
        $stmt = $conn->prepare($query);
        $stmt->execute([':name' => $name, ':price' => $price, ':image' => $image]);

        $message = "Product added successfully!";
    } else {
        $message = "All fields are required!";
    }
}

// Xử lý cập nhật sản phẩm
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    if ($id && $name && $price && $image) {
        $query = "UPDATE products SET name = :name, price = :price, image = :image WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->execute([':id' => $id, ':name' => $name, ':price' => $price, ':image' => $image]);

        $message = "Product updated successfully!";
    } else {
        $message = "All fields are required!";
    }
}

// Lấy danh sách sản phẩm
$query = "SELECT * FROM products ORDER BY created_at DESC";
$stmt = $conn->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy thông tin sản phẩm để sửa (nếu có)
$product_to_edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "SELECT * FROM products WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->execute([':id' => $id]);
    $product_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Manager</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            color: #4CAF50;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #4CAF50;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background: #4CAF50;
            color: #fff;
            cursor: pointer;
        }

        button:hover {
            background: #45a049;
        }

        .products {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .product {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: calc(25% - 20px);
            text-align: center;
        }

        .product img {
            width: 100%;
            height: auto;
            border-bottom: 1px solid #ddd;
        }

        .product h3 {
            font-size: 18px;
            margin: 10px 0;
            color: #000;
            text-decoration: none;
            font-weight: bold;
        }

        .product p {
            font-size: 16px;
            color: #000;
        }

        .actions a {
            display: inline-block;
            margin: 10px;
            padding: 10px 15px;
            background: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .actions a:hover {
            background: #45a049;
        }

        .actions a.cancel {
            background: #f44336;
        }

        .actions a.cancel:hover {
            background: #e53935;
        }

        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            background: #f8f9fa;
            color: #333;
            border-left: 5px solid #4CAF50;
        }

        .message.error {
            border-left-color: #f44336;
        }

        @media (max-width: 768px) {
            .product {
                width: calc(50% - 20px);
            }
        }

        @media (max-width: 480px) {
            .product {
                width: 100%;
            }
        }
    </style>
    <script>
        // Hiển thị thông báo xác nhận khi lưu sản phẩm
        function confirmUpdate(event) {
            const confirmed = confirm("Bạn chắc chắn muốn lưu chứ?");
            if (!confirmed) {
                event.preventDefault(); // Ngăn form submit nếu người dùng chọn "Cancel"
            }
        }
    </script>
</head>

<body>
    <h1>Product Manager</h1>

    <!-- Form thêm/sửa sản phẩm -->
    <div class="form-container">
        <?php if ($product_to_edit): ?>
            <h2>Edit Product</h2>
        <?php else: ?>
            <h2>Add New Product</h2>
        <?php endif; ?>

        <?php if (!empty($message)): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST" onsubmit="<?= $product_to_edit ? 'confirmUpdate(event)' : '' ?>">
            <?php if ($product_to_edit): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($product_to_edit['id']) ?>">
            <?php endif; ?>
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="<?= $product_to_edit['name'] ?? '' ?>" required><br><br>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" value="<?= $product_to_edit['price'] ?? '' ?>" required><br><br>

            <label for="image">Image URL:</label>
            <input type="text" id="image" name="image" value="<?= $product_to_edit['image'] ?? '' ?>" required><br><br>

            <button type="submit" name="<?= $product_to_edit ? 'update_product' : 'add_product' ?>">
                <?= $product_to_edit ? 'Update Product' : 'Add Product' ?>
            </button>
        </form>
    </div>

    <!-- Hiển thị danh sách sản phẩm -->
    <h2>Product List</h2>
    <div class="products">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p>Price: $<?= htmlspecialchars($product['price']) ?></p>
                    <div class="actions">
                        <a href="?edit=<?= htmlspecialchars($product['id']) ?>">Edit</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </div>
    <div class="form-container">
        <!-- Kiểm tra trạng thái: sửa hoặc thêm mới -->
        <?php if (isset($product_to_edit) && $product_to_edit): ?>
            <h2>Edit Product</h2>
        <?php else: ?>
            <h2>Add New Product</h2>
        <?php endif; ?>

        <form method="POST" onsubmit="return confirmAction()">
            <!-- Nếu là chế độ sửa, thêm input ẩn để chứa ID sản phẩm -->
            <?php if (isset($product_to_edit) && $product_to_edit): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($product_to_edit['id']) ?>">
            <?php endif; ?>

            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($product_to_edit['name'] ?? '') ?>" required><br><br>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars($product_to_edit['price'] ?? '') ?>" required><br><br>

            <label for="image">Image URL:</label>
            <input type="text" id="image" name="image" value="<?= htmlspecialchars($product_to_edit['image'] ?? '') ?>" required><br><br>

            <button type="submit" name="<?= isset($product_to_edit) && $product_to_edit ? 'update_product' : 'add_product' ?>">
                <?= isset($product_to_edit) && $product_to_edit ? 'Update Product' : 'Add Product' ?>
            </button>

            <!-- Nút hủy chỉnh sửa để trở về trạng thái thêm mới -->
            <?php if (isset($product_to_edit) && $product_to_edit): ?>
                <a href="productmanager.php" style="margin-left: 10px; color: red; text-decoration: none;">Cancel Edit</a>
            <?php endif; ?>
        </form>
    </div>

</body>

</html>