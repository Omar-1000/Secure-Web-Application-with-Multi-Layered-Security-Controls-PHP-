<?php
session_start();
require 'connect.php';

// Include the JWT library
require "C:\Program Files\Ampps\www\jwt\php-jwt-main\src\JWT.php";
require "C:\Program Files\Ampps\www\jwt\php-jwt-main\src\key.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secretKey = "localhost2025"; // Use a strong secret key

// Function to verify JWT
function verifyJWT($jwt, $secretKey) {
    try {
        // Decode the JWT
        $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
        return $decoded; // Return decoded data if JWT is valid
    } catch (Exception $e) {
        return null; // Return null if the JWT is invalid
    }
}

// Check if the JWT is provided (from cookie, header, or query parameter)
$jwt = isset($_COOKIE['auth_token']) ? $_COOKIE['auth_token'] : null;

// If JWT is provided, verify it
if ($jwt) {
    $decoded = verifyJWT($jwt, $secretKey);
    if ($decoded) {
        // JWT is valid, continue with OTP verification
        $email = $decoded->email; // Extract email from decoded JWT
    } else {
        // Invalid JWT, redirect to login
        $_SESSION['error_message'] = "Invalid or expired session. Please log in again.";
        header("Location: index22.php");
        exit();
    }
} else {
    // No JWT provided, redirect to login
    $_SESSION['error_message'] = "No session found. Please log in.";
    header("Location: index22.php");
    exit();
}

// Check if OTP is submitted
if (isset($_POST['verifyOtp'])) {
    $enteredOtp = $_POST['otp'];

    // Validate the OTP
    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $enteredOtp) {
        // OTP is correct, log the user in
        $_SESSION['email'] = $email; // Ensure session email is set
        unset($_SESSION['otp']); // Remove OTP from the session
		if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
            // Redirect to admin page if user is admin
            header("Location: admin.php");
        } else {
            // Redirect to homepage if user is not admin
            header("Location: homepage.php");
        }
        exit();
    }
        //header("Location: homepage.php"); // Redirect to the homepage
        //exit();
     else {
        // OTP is incorrect
        $error_message = "Invalid OTP. Please try again.";
    }
}

// Check if the session has expired or no OTP is set
if (!isset($_SESSION['otp']) || !isset($_SESSION['email'])) {
    header("Location: index22.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1 class="form-title">Verify OTP(2fa)</h1>
		<p class="otp-message"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;please enter your 6-digit code sent to your Gmail</p>
        <?php if (isset($error_message)) : ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <div class="input-group">
                <i class="fas fa-key"></i>
                <input type="text" name="otp" placeholder="Enter OTP" required>
                <label for="otp">OTP</label>
            </div>
            <input type="submit" class="btn" value="Verify OTP" name="verifyOtp">
        </form>
    </div>
</body>
</html>
