<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "tretiaci");
if (!$conn) {
    die("Nefunguje databaza");
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["meno"]) || empty($_POST["heslo"]) || empty($_POST["repeat_heslo"]) || empty($_POST["email"])) {
        $error_message = "Zadajte registračné údaje";
    } else {
        $meno = $_POST['meno'];
        $heslo = $_POST['heslo'];
        $repeat_heslo = $_POST['repeat_heslo'];
        $email = $_POST['email'];

        if ($heslo !== $repeat_heslo) {
            $error_message = "Heslá sa nezhodujú";
        } else {
            $encrypted = password_hash($heslo, PASSWORD_DEFAULT);
            $role = 'user'; // Set the default role as 'user'

            // It's important to use prepared statements to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO tretiaci (meno, heslo, email, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $meno, $encrypted, $email, $role);
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
                <input type="email" name="email" placeholder="Email:" required>
                <input type="password" name="heslo" placeholder="Heslo:" required>
                <input type="password" name="repeat_heslo" placeholder="Zopakujte heslo:" required>
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
