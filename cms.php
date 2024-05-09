<?php
session_start();
include 'navbar.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: shop.php");
    exit;
}

$host = "localhost";
$db = "tretiaci";
$user = "root";
$pass = "";
$connection = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

// Function to convert blob data to base64 for image display
function blobToBase64($blobData) {
    return 'data:image/jpeg;base64,' . base64_encode($blobData);
}

// Handle form submission for adding or editing product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['edit'])) {
        $productId = $_POST['edit'];
        $name = $_POST['name_' . $productId];
        $description = $_POST['description_' . $productId];
        $price = $_POST['price_' . $productId];
        $category = $_POST['category_' . $productId]; // Added category handling
        
        if ($_FILES['image_' . $productId]['size'] > 0) {
            $image = file_get_contents($_FILES['image_' . $productId]['tmp_name']);
            $sql = "UPDATE products SET name = :name, description = :description, image = :image, price = :price, category_id = :category WHERE id = :id";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':image', $image, PDO::PARAM_LOB);
        } else {
            $sql = "UPDATE products SET name = :name, description = :description, price = :price, category_id = :category WHERE id = :id";
            $stmt = $connection->prepare($sql);
        }
        
        $stmt->bindParam(':id', $productId);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category', $category); // Bind category parameter
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $productId = $_POST['delete'];
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':id', $productId);
        $stmt->execute();
    } else {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category']; // Added category handling
        $image = file_get_contents($_FILES['image']['tmp_name']);

        $sql = "INSERT INTO products (name, description, image, price, category_id) VALUES (:name, :description, :image, :price, :category)";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $image, PDO::PARAM_LOB);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category', $category); // Bind category parameter
        $stmt->execute();
    }

    header("Location: cms.php"); // Redirect to avoid form resubmission
    exit;
}

// Fetch products from the database
$sql = "SELECT id, name, description, image, price, category_id FROM products ORDER BY id DESC";
$stmt = $connection->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>CMS</title>
    <link rel="stylesheet" type="text/css" href="stylecms.css"> <!-- Link to external CSS file -->
</head>
<body>

<div class="container">
    <h2>Add New Product</h2>
    <form method="post" action="cms.php" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="description">Product Description:</label>
        <input type="text" id="description" name="description" required>

        <label for="price">Product Price:</label>
        <input type="number" id="price" name="price" required step="0.01">

        <label for="image">Product Image:</label>
        <input type="file" id="image" name="image" required>

        <label for="category">Product Category:</label>
        <select name="category" id="category" required>
            <option value="1">Android</option>
            <option value="2">iPhone</option>
        </select>

        <input type="submit" value="Submit">
    </form>
</div>

<?php if (isset($products) && !empty($products)): ?>
    <div class="container edit-container">
        <h2>Edit Products</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <form method="post" action="cms.php" enctype="multipart/form-data">
                            <td><?php echo $product['id']; ?></td>
                            <td><input type="text" name="name_<?php echo $product['id']; ?>" value="<?php echo $product['name']; ?>"></td>
                            <td><input type="text" name="description_<?php echo $product['id']; ?>" value="<?php echo $product['description']; ?>"></td>
                            <td><img src="<?php echo blobToBase64($product['image']); ?>" alt="<?php echo $product['name']; ?>" style="width: 100px;"></td>
                            <td><input type="number" name="price_<?php echo $product['id']; ?>" value="<?php echo $product['price']; ?>" step="0.01"></td>
                            <td>
                                <select name="category_<?php echo $product['id']; ?>" required>
                                    <option value="1" <?php if ($product['category_id'] == 1) echo 'selected'; ?>>Android</option>
                                    <option value="2" <?php if ($product['category_id'] == 2) echo 'selected'; ?>>iPhone</option>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="edit" value="<?php echo $product['id']; ?>">
                                <input type="submit" value="Edit">
                            </td>
                        </form>
                        <td>
                            <form method="post" action="cms.php">
                                <input type="hidden" name="delete" value="<?php echo $product['id']; ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

</body>
</html>
