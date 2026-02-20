<?php
session_start();
require 'connect.php'; // Database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require "C:\Program Files\Ampps\www\PHPMailer-master\src\Exception.php";
require "C:\Program Files\Ampps\www\PHPMailer-master\src\PHPMailer.php";
require "C:\Program Files\Ampps\www\PHPMailer-master\src\SMTP.php";

// Include the JWT library
require "C:\Program Files\Ampps\www\jwt\php-jwt-main\src\JWT.php";
require "C:\Program Files\Ampps\www\jwt\php-jwt-main\src\key.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secretKey = "localhost2025"; // Use a strong secret key for signing JWT

if (isset($_POST['signUp'])) {
	if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // CSRF token validation failed, show an error message
        $_SESSION['error_message'] = "Invalid CSRF token.";
        header("Location: index22.php");
        exit();
    }
    // Sanitize and validate inputs
    $firstName = filter_input(INPUT_POST, 'fName', FILTER_SANITIZE_SPECIAL_CHARS);
    $lastName = filter_input(INPUT_POST, 'lName', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL); // Validate email format
    $password = $_POST['password'];

    // Validate email format
    if (!$email) {
        $_SESSION['error_message'] = "Invalid email format.";
        header("Location: index22.php");
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "Email Address Already Exists!";
        header("Location: index22.php");
        exit();
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into database
        $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);

        if ($stmt->execute()) {
            // Generate JWT Token
            $payload = [
                "email" => $email,
                "iat" => time(), // initial  time
                "exp" => time() + (60 * 60) // Token expiration time (1 hour)
            ];
            $jwt = JWT::encode($payload, $secretKey, 'HS256');

            // Set JWT in secure cookie
            setcookie("auth_token", $jwt, [
                "expires" => time() + (60 * 60), // 1 hour expiration
                "path" => "/", // Available throughout the site
                //"domain" => "localhost", // 
                "secure" => true, // Only sent over HTTPS
                "httponly" => true, // Prevent access via JavaScript
                "samesite" => "Strict" // Prevent CSRF attacks
            ]);

            // Send OTP email using PHPMailer
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'omarayesh2000@gmail.com';                                        // Replace with your email
                $mail->Password = 'jbxl ohsq yujq cvic';                                            // Replace with your email app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('omarayesh2000@gmail.com', '6-digit otp');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Code';
                $mail->Body = "<p>Your OTP code is: <b>$otp</b></p>";

                $mail->send();
                header("Location: otp_verify.php");
                exit();
            } catch (Exception $e) {
                $_SESSION['error_message'] = "OTP email could not be sent. Mailer Error: {$mail->ErrorInfo}";
                header("Location: index22.php");
                exit();
            }

        } else {
            $_SESSION['error_message'] = "Registration failed: " . $stmt->error;
            header("Location: index22.php");
            exit();
        }
    }
    $stmt->close();
}

if (isset($_POST['signIn'])) {
	if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // CSRF token validation failed, show an error message
        $_SESSION['error_message'] = "Invalid CSRF token.";
        header("Location: index22.php");
        exit();
    }
    // Sanitize and validate inputs
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL); // Validate email format
    $password = $_POST['password'];

    // Validate email format
    if (!$email) {
        $_SESSION['error_message'] = "Incorrect Email or Password!";
        header("Location: index22.php");
        exit();
    }

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
			$_SESSION['is_admin'] = $user['is_admin'];
            // Generate JWT Token for authenticated user
            $payload = [
                "email" => $email,
                "iat" => time(), // Issued at time
                "exp" => time() + (60 * 60) // Token expiration time (1 hour)
            ];
            $jwt = JWT::encode($payload, $secretKey, 'HS256');

            // Set JWT in secure cookie
            setcookie("auth_token", $jwt, [
                "expires" => time() + (60 * 60), // 1 hour expiration
                "path" => "/",
                "secure" => true,
                "httponly" => true,
                "samesite" => "Strict"
            ]);

            // Send OTP email using PHPMailer
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'omarayesh2000@gmail.com'; // Replace with your email
                $mail->Password = 'jbxl ohsq yujq cvic'; // Replace with your email app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('omarayesh2000@gmail.com', '6-digit otp');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Code';
                $mail->Body = "<p>Your OTP code is: <b>$otp</b></p>";

                $mail->send();
                header("Location: otp_verify.php");
                exit();
            } catch (Exception $e) {
                $_SESSION['error_message'] = "OTP email could not be sent. Mailer Error: {$mail->ErrorInfo}";
                header("Location: index22.php");
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Incorrect Email or Password!";
            header("Location: index22.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Incorrect Email or Password!";
        header("Location: index22.php");
        exit();
    }
    $stmt->close();
}
?>
