<?php
session_start();
include 'navbar.php';

$host = "localhost";
$db = "tretiaci";
$user = "root";
$pass = "";
$connection = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

// Function to convert blob data to base64 for image display
function blobToBase64($blobData) {
    return 'data:image/jpeg;base64,' . base64_encode($blobData);
}

// Check if random products are already stored in session
if (!isset($_SESSION['random_products']) || time() > $_SESSION['random_products_expires']) {
    // Fetch random products from the database
    $randomProducts = $connection->query("SELECT p.name, p.description, p.image, p.price, c.name as category_name 
                                          FROM products p 
                                          JOIN categories c ON p.category_id = c.id 
                                          ORDER BY RAND() LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
    // Store random products in session
    $_SESSION['random_products'] = $randomProducts;
    // Set expiration time for session data (1 minute from now)
    $_SESSION['random_products_expires'] = time() + 60;
} else {
    // Retrieve random products from session
    $randomProducts = $_SESSION['random_products'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="product.css">
    <title>Welcome</title>
</head>
<body>
    <div class="welcome" id="welcome">
        Look at the newest offers, <?php echo isset($_SESSION['meno']) ? $_SESSION['meno'] : 'User not found'; ?>!
    </div>
    
    <div class="container" id="product-container">
        <?php foreach ($randomProducts as $product): ?>
            <div class="product-container">
                <img src="<?php echo blobToBase64($product['image']); ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                <div class="product-details">
                    <h3><?php echo $product['name']; ?></h3>
                    <p><?php echo $product['description']; ?></p>
                    <p class="product-price">€<?php echo $product['price']; ?></p>
                    <p class="product-category">Category: <?php echo $product['category_name']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        // Refresh the page every 1 minute
        setInterval(function() {
            location.reload();
        }, 60000); // 60000 milliseconds = 1 minute
    </script>
</body>
</html>
