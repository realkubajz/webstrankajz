<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "tretiaci");
if (!$conn) {
    die("Nefunguje databaza");
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["meno"]) || empty($_POST["heslo"])) {
        $error_message = "Zadajte registračné údaje";
    } else {
        $meno = $_POST['meno'];
        $heslo = $_POST['heslo'];
        $encrypted = password_hash($heslo, PASSWORD_DEFAULT);
        $role = 'user'; // Set the default role as 'user'

        // It's important to use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO tretiaci (meno, heslo, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $meno, $encrypted, $role);
        $result = $stmt->execute();

        if ($result) {
            $_SESSION['success_message'] = "Ste úspešne zaregistrovaný!";
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Chyba pri registrácii";
        }
        
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
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
</style>
<body>
    <div class="container">
        <form action="register.php" method="post">
            <div class="top">
                <div class="text">
                    Register
                </div>
                <input type="text" name="meno" placeholder="Meno: " required>
                <input type="password" name="heslo" placeholder="Heslo:" required>
                <div class="button-flex">
                    <button type="submit" name="submit">
                        Register
                    </button>
                </div>
                <div class="register"><a href="index.php">Login</a></div>
                <?php if (!empty($error_message)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
</body>
</html>
