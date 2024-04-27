<?php
$meno = isset($_SESSION['meno']) ? $_SESSION['meno'] : '';
?>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f4f4;
    margin: 0;
}

.navbar {
    background: #333;
    color: #fff;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar a {
    color: #fff;
    text-decoration: none;
}

.welcome {
    text-align: center;
    margin-top: 50px;
    font-size: 2em;
}
</style>

<div class="navbar">
    <div>Welcome, <?php echo $meno; ?>!</div>
    <a href="welcome.php">Home</a>
    <a href="shop.php">Shop</a>
    <a href="profile.php">Profile</a>
    <div><a href="logout.php">Logout</a></div>
</div>
