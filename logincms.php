<?php
session_start();
$host = "localhost";
$db = "tretiaci";
$user = "root";
$pass = "";
$connection = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database to get user information
    $stmt = $connection->prepare("SELECT * FROM tretiaci WHERE meno = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists and verify the password
    if ($user && password_verify($password, $user['heslo'])) {
        // If the username and password are correct, set session and redirect
        $_SESSION['valid'] = true;
        $_SESSION['username'] = $user['meno'];
        
        // Check if the user is an admin
        if ($user['role'] === 'admin') {
            $_SESSION['role'] = 'admin'; // Set the role as admin
        }
        
        // Redirect to the CMS page
        header("Location: cms.php");
        exit;
    } else {
        // If authentication fails, display error message
        echo "Invalid username or password.";
    }
}
?>
