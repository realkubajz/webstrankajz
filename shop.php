<?php
session_start();
include 'navbar.php';

$host = "localhost";
$db = "tretiaci";
$user = "root";
$pass = "";
$connection = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

// Define default sorting order
<<<<<<< HEAD
$sortOrder = "ORDER BY p.id DESC"; // Default to sorting by newest added
=======
$sortOrder = "ORDER BY id DESC"; // Default to sorting by newest added
>>>>>>> ba5f7c5ea2f9a0b4e47538af1c6c560c2ef8ab15

// Check if a sorting option is selected
if (isset($_GET['sort'])) {
    if ($_GET['sort'] === 'oldest') {
<<<<<<< HEAD
        $sortOrder = "ORDER BY p.id ASC"; // Sort by oldest added
    }
}

// Check if a category is selected
$categoryFilter = "";
$categoryName = "";
if (isset($_GET['category'])) {
    $category = $_GET['category'];
    if ($category === 'android') {
        $categoryFilter = "AND c.name = 'Android'"; // Filter for Android category
        $categoryName = "Android";
    } elseif ($category === 'iphone') {
        $categoryFilter = "AND c.name = 'iPhone'"; // Filter for iPhone category
        $categoryName = "iPhone";
    }
}

// Fetch products from the database with the specified sorting order and category filter
$sql = "SELECT p.name, p.description, p.image, p.price, c.name as category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE 1=1 $categoryFilter 
        $sortOrder";
$stmt = $connection->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
=======
        $sortOrder = "ORDER BY id ASC"; // Sort by oldest added
    }
}

// Fetch products from the database with the specified sorting order
$products = $connection->query("SELECT name, description, image, price FROM products $sortOrder")->fetchAll(PDO::FETCH_ASSOC);
>>>>>>> ba5f7c5ea2f9a0b4e47538af1c6c560c2ef8ab15

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
<<<<<<< HEAD
    <link rel="stylesheet" href="forms.css"> 
    <link rel="stylesheet" href="product.css">
</head>
<body>

<form class="category-form" action="" method="GET">
    <label for="category">Filter by Category:</label>
    <select name="category" id="category">
        <option value="all" <?php if (!isset($_GET['category']) || $_GET['category'] === 'all') echo 'selected'; ?>>All</option>
        <option value="android" <?php if (isset($_GET['category']) && $_GET['category'] === 'android') echo 'selected'; ?>>Android</option>
        <option value="iphone" <?php if (isset($_GET['category']) && $_GET['category'] === 'iphone') echo 'selected'; ?>>iPhone</option>
    </select>
    <input type="submit" value="Filter">
</form>

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
                <p class="product-category">Category: <?php echo $product['category_name']; ?></p>
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
=======
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
>>>>>>> ba5f7c5ea2f9a0b4e47538af1c6c560c2ef8ab15
?>

</body>
</html>
