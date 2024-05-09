<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session if it's not already started.
}

$meno = isset($_SESSION['meno']) ? $_SESSION['meno'] : '';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

?>

<link rel="stylesheet" href="navbar.css">

<header class="navbar">
    <div>Welcome, <?php echo $meno; ?>!</div>
    <a href="welcome.php">Home</a>
    <a href="shop.php">Shop</a>
    <a href="stats.php">Stats</a>
    <a href="profile.php">Profile</a>
    <?php if ($role === 'admin'): ?>
        <a href="cms.php">CMS</a>
    <?php endif; ?>
    <div><a href="logout.php">Logout</a></div>
    </header>
