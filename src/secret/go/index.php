<?php
session_start();

// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login page
    header('Location: /');
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect to login page if not authenticated
    header('Location: /secret/index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protected Area</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
        }
        .welcome {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            color: #007cba;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="welcome">
        <h1>Welcome to the Protected Area!</h1>
        <p>You have successfully authenticated and can now access this secure content.</p>
    </div>
    
    <p>This is a sample protected page that can only be accessed after successful authentication.</p>
    
    <a href="?logout=true" style="margin-left: 20px; background-color: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 3px;">Logout</a>
</body>
</html>