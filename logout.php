<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        .logout-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #007BFF;
            color: white;
            border-radius: 8px;
            text-align: center;
            font-size: 20px;
        }
    </style>
</head>
<body>
<?php
   session_start(); // Start the session

   unset($_SESSION["heslo"]); // Clear the session

   echo '<div class="logout-message">You have logged out and cleaned session</div>';

   header('Refresh: 2; URL = index.php'); // Redirect to login page
?>

</body>
</html>
