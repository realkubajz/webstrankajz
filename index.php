<?php
session_start(); // Start the session.

$conn = mysqli_connect("localhost", "root", "", "tretiaci");

if (!$conn) {
    echo "Nefunguje databaza";
}

$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : "";
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : "";

unset($_SESSION['error_message']); // Unset the error message when the page is loaded.
unset($_SESSION['success_message']); // Unset the success message when the page is loaded.

if (isset($_POST['meno']) && !empty($_POST['meno']) && isset($_POST['heslo']) && !empty($_POST['heslo'])) {
    $sql = "SELECT heslo, role FROM tretiaci WHERE meno ='" . $_POST['meno'] . "'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify ($_POST['heslo'], $row["heslo"])) {
            $_SESSION['valid'] = true;
            $_SESSION['timeout'] = time();
            $_SESSION['meno'] = $_POST['meno'];
            $_SESSION['role'] = $row['role']; // Set the role in the session

            header("Location: welcome.php", true, 301);
            exit();
        } else {
            $_SESSION['error_message'] = "Zlé meno alebo heslo";
            header("Location: index.php", true, 302);
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Zlé meno alebo heslo";
        header("Location: index.php", true, 302);
        exit();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<style>
    .error-message {
        color: #D8000C;
        background-color: #FFD2D2;
        border: 1px solid #D8000C;
        margin: 20px 0;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
    }
    .success-message {
        color: #4F8A10;
        background-color: #DFF2BF;
        border: 1px solid #4F8A10;
        margin: 20px 0;
        padding: 10px;
        border-radius: 5px;
        text-align: center;
    }
</style>
<body>
    <div class="container">
        <form action="index.php" method="post">
            <div class="top">
                <div class="text">
                    Login
                </div>
                <input type="text" name="meno" placeholder="Meno:">
                <input type="password" name="heslo" placeholder="Heslo:">
                <div class="button-flex">
                    <button type="submit" name="submit">
                        Login
                    </button>
                </div>
                <div class="register"><a href="register.php">Register</a></div>
                <!-- Check if there is an error message and display it -->
                <?php if (!empty($error_message)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>
                <!-- Check if there is a success message and display it -->
                <?php if (!empty($success_message)): ?>
                    <div class="success-message">
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
</body>
</html>
