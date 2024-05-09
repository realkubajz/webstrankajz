<?php
session_start();
include 'navbar.php';

$host = "localhost";
$db = "tretiaci";
$user = "root";
$pass = "";
$connection = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

// Fetch statistics using SQL aggregate functions
$sql = "SELECT 
            COUNT(*) AS total_products, 
            SUM(price) AS total_price, 
            AVG(price) AS average_price,
            MIN(price) AS min_price,
            MAX(price) AS max_price
        FROM products";
$stmt = $connection->query($sql);
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

// Additional statistics
$totalProducts = $stats['total_products'];
$totalPrice = $stats['total_price'];
$averagePrice = $stats['average_price'];
$minPrice = $stats['min_price'];
$maxPrice = $stats['max_price'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
    <link rel="stylesheet" href="stats.css"> 
</head>
<body>

<div class="container">
    <div class="stats-container">
        <h2>Product Statistics</h2>
        <div class="stats">
            <div class="stat">
                <h3>Total Products:</h3>
                <p><?php echo $totalProducts; ?></p>
            </div>
            <div class="stat">
                <h3>Total Price:</h3>
                <p><?php echo "€" . number_format($totalPrice, 2); ?></p>
            </div>
            <div class="stat">
                <h3>Average Price:</h3>
                <p><?php echo "€" . number_format($averagePrice, 2); ?></p>
            </div>
            <div class="stat">
                <h3>Minimum Price:</h3>
                <p><?php echo "€" . number_format($minPrice, 2); ?></p>
            </div>
            <div class="stat">
                <h3>Maximum Price:</h3>
                <p><?php echo "€" . number_format($maxPrice, 2); ?></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
