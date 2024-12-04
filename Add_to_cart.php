<?php
// Kết nối cơ sở dữ liệu
include 'config.php';
session_start(); // Đảm bảo session được bắt đầu để lấy user_id

// Kiểm tra yêu cầu là POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy thông tin từ POST
    $product_id = $_POST['product_id'];

    // Kiểm tra nếu user đã đăng nhập
    if (!isset($_SESSION['user_id'])) {
        echo "You must be logged in to add products to the cart!";
        exit;
    }

    $user_id = $_SESSION['user_id'];

    try {
        // Lấy thông tin sản phẩm từ bảng `products`
        $query = "SELECT name, price FROM products WHERE id = :product_id";
        $stmt = $conn->prepare($query);
        $stmt->execute([':product_id' => $product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $product_name = $product['name'];
            $price = $product['price'];

            // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
            $check_query = "SELECT id, quantity FROM cart WHERE product_id = :product_id AND user_id = :user_id";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->execute([':product_id' => $product_id, ':user_id' => $user_id]);
            $cart_item = $check_stmt->fetch(PDO::FETCH_ASSOC);

            if ($cart_item) {
                // Nếu sản phẩm đã có trong giỏ, tăng số lượng
                $update_query = "UPDATE cart SET quantity = quantity + 1 WHERE id = :id";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->execute([':id' => $cart_item['id']]);
                echo "Product quantity updated in the cart!";
            } else {
                // Nếu sản phẩm chưa có trong giỏ, thêm mới
                $insert_query = "INSERT INTO cart (product_id, product_name, price, quantity, user_id) VALUES (:product_id, :product_name, :price, 1, :user_id)";
                $insert_stmt = $conn->prepare($insert_query);
                $insert_stmt->execute([
                    ':product_id' => $product_id,
                    ':product_name' => $product_name,
                    ':price' => $price,
                    ':user_id' => $user_id
                ]);
                echo "Product added to the cart!";
            }
        } else {
            echo "Product not found!";
        }
    } catch (PDOException $e) {
        // Xử lý lỗi
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request!";
}
?>
