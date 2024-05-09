<?php
session_start();
include 'navbar.php';

$host = "localhost";
$db = "tretiaci";
$user = "root";
$pass = "";
$connection = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

// Define default sorting order
$sortOrder = "ORDER BY p.id DESC"; // Default to sorting by newest added

// Check if a sorting option is selected
if (isset($_GET['sort'])) {
    $sortOption = $_GET['sort'];
    switch ($sortOption) {
        case 'oldest':
            $sortOrder = "ORDER BY p.id ASC"; // Sort by oldest added
            break;
        case 'price_asc':
            $sortOrder = "ORDER BY p.price ASC"; // Sort by price low to high
            break;
        case 'price_desc':
            $sortOrder = "ORDER BY p.price DESC"; // Sort by price high to low
            break;
        case 'name_asc':
            $sortOrder = "ORDER BY p.name ASC"; // Sort by name A-Z
            break;
        case 'name_desc':
            $sortOrder = "ORDER BY p.name DESC"; // Sort by name Z-A
            break;
        case 'id_asc':
            $sortOrder = "ORDER BY p.id ASC"; // Sort by ID ascending
            break;
        case 'id_desc':
            $sortOrder = "ORDER BY p.id DESC"; // Sort by ID descending
            break;
        default:
            // Handle invalid sorting option
            break;
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
    <link rel="stylesheet" href="forms.css"> 
    <link rel="stylesheet" href="product.css">
</head>
<body>

<div class="filters-sorting-container">
    <form class="category-form" action="" method="GET">
        <label for="category">Filter by Category:</label>
        <select name="category" id="category">
            <option value="all" <?php if (!isset($_GET['category']) || $_GET['category'] === 'all') echo 'selected'; ?>>All</option>
            <option value="android" <?php if (isset($_GET['category']) && $_GET['category'] === 'android') echo 'selected'; ?>>Android</option>
            <option value="iphone" <?php if (isset($_GET['category']) && $_GET['category'] === 'iphone') echo 'selected'; ?>>iPhone</option>
        </select>
        <input type="submit" value="Apply">
    </form>

    <form class="sort-form" action="" method="GET">
        <label for="sort">Sort by:</label>
        <select name="sort" id="sort">
            <option value="newest" <?php if (!isset($_GET['sort']) || $_GET['sort'] === 'newest') echo 'selected'; ?>>Newest added</option>
            <option value="oldest" <?php if (isset($_GET['sort']) && $_GET['sort'] === 'oldest') echo 'selected'; ?>>Oldest added</option>
            <option value="price_asc" <?php if (isset($_GET['sort']) && $_GET['sort'] === 'price_asc') echo 'selected'; ?>>Price Low to High</option>
            <option value="price_desc" <?php if (isset($_GET['sort']) && $_GET['sort'] === 'price_desc') echo 'selected'; ?>>Price High to Low</option>
            <option value="name_asc" <?php if (isset($_GET['sort']) && $_GET['sort'] === 'name_asc') echo 'selected'; ?>>Name A-Z</option>
            <option value="name_desc" <?php if (isset($_GET['sort']) && $_GET['sort'] === 'name_desc') echo 'selected'; ?>>Name Z-A</option>
            <option value="id_asc" <?php if (isset($_GET['sort']) && $_GET['sort'] === 'id_asc') echo 'selected'; ?>>ID Ascending</option>
            <option value="id_desc" <?php if (isset($_GET['sort']) && $_GET['sort'] === 'id_desc') echo 'selected'; ?>>ID Descending</option>
        </select>
        <input type="submit" value="Apply">
    </form>
</div>

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

</body>
</html>
