<?php
session_start();
include("connect.php");

// Include the JWT library
require "C:\Program Files\Ampps\www\jwt\php-jwt-main\src\JWT.php";
require "C:\Program Files\Ampps\www\jwt\php-jwt-main\src\key.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Secret key (same as during sign-up or sign-in JWT creation)
$secretKey = "localhost2025"; // Use the same secret key as when the JWT was generated

// Check if the auth_token cookie exists
if (isset($_COOKIE['auth_token'])) {
    try {
        // Decode JWT from the cookie
        $jwt = $_COOKIE['auth_token'];
        $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
        // Get the email from the decoded JWT payload
        $email = $decoded->email;
        
        // Fetch user details from the database using the email
        $query = mysqli_query($conn, "SELECT firstName, lastName FROM users WHERE email='$email'");
        $user = mysqli_fetch_assoc($query);
        
        if ($user) {
            $firstName = $user['firstName'];
            $lastName = $user['lastName'];
        } else {
            // Redirect to login if the user is not found in the database
            header("Location: index22.php");
            exit();
        }
    } catch (Exception $e) {
        // Redirect to login if the JWT is invalid or expired
        header("Location: index22.php");
        exit();
    }
} else {
    // Redirect to login if the auth_token cookie is not found
    header("Location: index22.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <style>
        /* Reset and background setup */
        body {
            margin: 0;
            padding: 0;
            background: url('2.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Lato', sans-serif;
            color: #000;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 0;
            background: rgba(0, 0, 0, 0.6); /* Semi-transparent background */
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar a {
            text-decoration: none;
            color: #fff;
            font-size: 18px;
            margin: 0 15px;
            font-family: 'Cinzel', serif;
            transition: color 0.3s ease;
        }

        .navbar a:hover {
            color: #017bf5;
        }

        /* Hero Section */
        .content {
            margin-top: 100px;
            text-align: center;
        }

        .content h1 {
            font-size: 50px;
            color: #fff;
        }

        .content h3 {
            font-size: 24px;
            color: #fff;
        }

        .content .buttons a {
            text-decoration: none;
            font-size: 20px;
            margin: 10px 20px;
            padding: 10px 20px;
            border: 2px solid #fff;
            border-radius: 5px;
            color: #fff;
            transition: 0.3s;
        }

        .content .buttons a:hover {
            background: #017bf5;
            color: #fff;
        }

        /* Contact Section */
        .contact {
            padding: 50px 20px;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            margin-top: 50px;
            text-align: center;
        }

        .contact h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .contact a {
            font-size: 18px;
            text-decoration: none;
            color: #fff;
            padding: 10px 20px;
            background: #017bf5;
            border-radius: 5px;
        }

        .contact a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <a href="#">HOME</a>
        <a href="#">VIDEOS</a>
        <a href="index22.php">REGISTER</a>
        <a href="#">BLOG</a>
        <a href="#">CONTACT US</a>
    </div>

    <!-- Hero Section -->
    <div class="content">
        <h1>Welcome, <?php echo $firstName . ' ' . $lastName; ?> :)</h1>
        <h3>Enjoy exploring the website!</h3>
    </div>

    <!-- Contact Section -->
    <div class="contact">
        <h2>Ready to logout?</h2>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
