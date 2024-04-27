<?php
session_start();
include 'navbar.php';

$conn = mysqli_connect("localhost","root","","tretiaci");
if(!$conn) {
    die("Database connection failed");
}

$meno = $_SESSION['meno'];
$heslo = '';
$passwordChangeSuccess = false;


if (isset($_POST['display_credentials'])) {
    $query = "SELECT heslo FROM tretiaci WHERE meno='$meno'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    if ($row && (password_verify ($_POST['current_password'], $row["heslo"]))) {
        $heslo = $row['heslo']; 
    } else {
        echo "<script>alert('Incorrect password');</script>";
    }
}


if (isset($_POST['change_password'])) {
    $query = "SELECT heslo FROM tretiaci WHERE meno='$meno'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    if (($password_verify ($_POST['current_password'], $row["heslo"]))) {
        if (isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
            if ($_POST['new_password'] == $_POST['confirm_password']) {
                $new_password = $_POST['new_password'];
                $query = "UPDATE tretiaci SET heslo='$new_password' WHERE meno='$meno'";
                mysqli_query($conn, $query);
                $passwordChangeSuccess = true;
            } else {
                echo "<script>alert('The new passwords do not match');</script>";
            }
        }
    } else {
        echo "<script>alert('Incorrect password');</script>";
    }
}


if (isset($_POST['change_username'])) {
    $new_username = $_POST['new_username'];
    $query = "UPDATE tretiaci SET meno='$new_username' WHERE meno='$meno'";
    mysqli_query($conn, $query);
    $_SESSION['meno'] = $new_username;
    echo "<script>alert('Username changed successfully');</script>";
    $meno = $new_username; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Profile</title>
    <style>
       form {
            margin: 20px;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
        }
        form div {
            margin-bottom: 10px;
        }
        label, input, button {
            display: block;
            width: 100%;
        }
        input, button {
            padding: 10px;
            margin-top: 5px;
        }
        button {
            background: #333;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #555;
        }
        h1{
            margin-left: 27px;
        }
        
    </style>
</head>
<body>
    <h1>Welcome to your profile, <?php echo htmlspecialchars($meno); ?>!</h1>
    <form action="profile.php" method="post">
        <label>Enter your current password to see your credentials:</label>
        <input type="password" name="current_password">
        <button type="submit" name="display_credentials">Display Credentials</button>
    </form>
    <?php if ($heslo): ?>
        <div>Your username is: <?php echo htmlspecialchars($meno); ?></div>
        <div>Your password is: <?php echo htmlspecialchars($heslo); ?></div>
    <?php endif; ?>
    <form action="profile.php" method="post">
        <label>Enter your current password:</label>
        <input type="password" name="current_password">
        <label>Enter your new password:</label>
        <input type="password" name="new_password">
        <label>Confirm your new password:</label>
        <input type="password" name="confirm_password">
        <button type="submit" name="change_password">Change Password</button>
    </form>
    <form action="profile.php" method="post">
        <label>Enter your new username:</label>
        <input type="text" name="new_username">
        <button type="submit" name="change_username">Change Username</button>
    </form>
    <?php
    if ($passwordChangeSuccess) {
        echo "<script>alert('Password changed successfully');</script>";
    }
    ?>
</body>
</html>
