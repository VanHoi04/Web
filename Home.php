<?php
include 'config.php';
session_start();

// Kiểm tra trạng thái đăng nhập
$is_logged_in = isset($_SESSION['user_id']);

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM products WHERE name LIKE :search";
$stmt = $conn->prepare($query);
$stmt->execute([':search' => "%$search%"]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Cars Stores</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Styles for the body and general layout */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .nav-container {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        header nav {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        header nav a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        header nav a:hover {
            background-color: #007BFF;
        }

        .search-form {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .search-form input[type="text"] {
            padding: 8px;
            width: 200px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .search-form button {
            padding: 8px 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-form button:hover {
            background-color: #0056b3;
        }

        main {
            padding: 20px;
        }

        .products {
            display: grid;
            color: #333;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            align-items: stretch;
        }

        .product {
            display: flex;
            color: black;
            flex-direction: column;
            justify-content: space-between;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
            transition: box-shadow 0.3s;
        }

        .product:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .product img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .product h3 a {
            text-decoration: none;
            color: #333;
        }

        .product h3 a:hover {
            color: #333;
        }

        .add-to-cart {
            margin-top: auto;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .add-to-cart:hover {
            background-color: #0056b3;
        }

/* Căn giữa slider */
.slider-container {
    position: relative;
    max-width: 100%; /* Chiều rộng tối đa của slider */
    margin: 20px auto; /* Căn giữa theo chiều ngang */
    overflow: hidden;
    width: 80%; /* Chiều rộng của slider, có thể điều chỉnh theo ý muốn */
}

/* Căn giữa các slide trong slider */
.slider {
    display: flex;
    transition: transform 0.5s ease-in-out;
    width: 100%; /* Đảm bảo slider chiếm hết chiều rộng của .slider-container */
}

.slide {
    min-width: 100%; /* Đảm bảo mỗi slide chiếm toàn bộ chiều rộng của slider */
    position: relative;
}

.slide img {
    width: 100%; /* Đảm bảo ảnh chiếm toàn bộ chiều rộng của slide */
    height: auto; /* Điều chỉnh chiều cao ảnh tự động */
    display: block; /* Đảm bảo ảnh hiển thị đúng */
    object-fit: cover; /* Đảm bảo ảnh phủ đầy slide mà không bị méo */
}

/* Các nút điều khiển */
button.prev, button.next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    padding: 15px;
    cursor: pointer;
    font-size: 20px;
    border-radius: 50%;
    z-index: 1;
}

button.prev {
    left: 10px;
}

button.next {
    right: 10px;
}

button.prev:hover, button.next:hover {
    background-color: rgba(0, 0, 0, 0.7);
}

    </style>
</head>

<body>
    <header>
        <div class="logo">Btec Shop</div>
        <div class="nav-container">
            <nav>
                <a href="home.php">Home</a>
                <a href="contact.php">Contact</a>
                <a href="profile.php">Profile</a>
                <a href="cart.php">Cart</a>
                <?php if ($is_logged_in): ?>
                    <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </nav>
            <form class="search-form" method="GET">
                <input type="text" name="search" placeholder="Search for products..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Search</button>
            </form>
        </div>
    </header>

    <main>
        <!-- Slider Section -->
        <div class="slider-container">
            <div class="slider">
                <div class="slide">
                    <img src="https://res.cloudinary.com/total-dealer/image/upload/w_3840,f_auto,q_75/v1/production/w7tlcm6l01ejosvfjxvxh5d5gce1" alt="Slider Image 1">
                </div>
                <div class="slide">
                    <img src="https://www.vietnamstar-auto.com/wp-content/uploads/2021/11/2040x1336.jpg" alt="Slider Image 2">
                </div>
                <div class="slide">
                    <img src="https://mercedes-truongchinh.com/data/upload/media/mercedes-e180-ex-hinhdaidien-1641186705.png" alt="Slider Image 3">
                </div>
            </div>
            <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
            <button class="next" onclick="moveSlide(1)">&#10095;</button>
        </div>

        <h2>Featured Products</h2>
        <div class="products">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product">
                        <a href="product_detail.php?id=<?= htmlspecialchars($product['id']) ?>">
                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p><strong>Price:</strong> $<?= htmlspecialchars($product['price']) ?></p>
                        </a>
                        <button class="add-to-cart" onclick="addToCart(<?= htmlspecialchars($product['id']) ?>)">Add to Cart</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </main>

    <script>
        let currentSlide = 0;

        function moveSlide(step) {
            const slides = document.querySelectorAll('.slide');
            const totalSlides = slides.length;

            // Calculate new slide index
            currentSlide = (currentSlide + step + totalSlides) % totalSlides;

            // Move the slider to the correct position
            const slider = document.querySelector('.slider');
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
        }

        function addToCart(productId) {
            fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'product_id=' + productId
                })
                .then(response => response.text())
                .then(data => {
                    if (data.includes("Please log in")) {
                        alert("You need to log in to add items to your cart.");
                        window.location.href = "login.php";
                    } else {
                        alert(data);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to add product to cart. Please try again.');
                });
        }
    </script>
</body>

</html>