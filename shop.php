<?php
session_start();
include 'navbar.php';

$host = "localhost";
$db = "tretiaci";
$user = "root";
$pass = "";
$connection = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

// Define default sorting order
$sortOrder = "ORDER BY id DESC"; // Default to sorting by newest added

// Check if a sorting option is selected
if (isset($_GET['sort'])) {
    if ($_GET['sort'] === 'oldest') {
        $sortOrder = "ORDER BY id ASC"; // Sort by oldest added
    }
}

// Fetch products from the database with the specified sorting order
$products = $connection->query("SELECT name, description, image, price FROM products $sortOrder")->fetchAll(PDO::FETCH_ASSOC);

// Function to convert blob data to base64 for image display
function blobToBase64($blobData) {
    return 'data:image/jpeg;base64,' . base64_encode($blobData);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="forms.css"> 
</head>
<body>

<form class="sort-form" action="" method="GET">
        <label for="sort">Sort by:</label>
        <select name="sort" id="sort">
            <option value="newest" <?php if (!isset($_GET['sort']) || $_GET['sort'] === 'newest') echo 'selected'; ?>>Newest added</option>
            <option value="oldest" <?php if (isset($_GET['sort']) && $_GET['sort'] === 'oldest') echo 'selected'; ?>>Oldest added</option>
        </select>
        <input type="submit" value="Sort">
    </form>

<div class="container">
<?php foreach ($products as $product): ?>
    <div class="product-container">
        <div class="product-details">
            <h3><?php echo $product['name']; ?></h3>
            <p><?php echo $product['description']; ?></p>
            <p class="product-price">€<?php echo $product['price']; ?></p>
        </div>
        <img class="product-image" src="<?php echo blobToBase64($product['image']); ?>" alt="<?php echo $product['name']; ?>">
    </div>
<?php endforeach; ?>
</div>

<?php
    // Check if the user is logged in and is an admin
    if (isset($_SESSION['valid']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        // Display the CMS login form
        echo '
            <div class="login-form">
                <form action="logincms.php" method="post">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password">
                    <input type="submit" value="Login">
                </form>
            </div>';
    }
?>

</body>
</html>
