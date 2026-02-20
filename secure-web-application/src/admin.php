<?php
session_start();
include("connect.php");

// Include the JWT library
require "C:\Program Files\Ampps\www\jwt\php-jwt-main\src\JWT.php";
require "C:\Program Files\Ampps\www\jwt\php-jwt-main\src\key.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Secret key (same as when JWT was created)
$secretKey = "localhost2025";  // Use the same secret key as when the JWT was generated

// Check if JWT cookie exists
if (isset($_COOKIE['auth_token'])) {
    try {
        // Decode JWT from cookie
        $jwt = $_COOKIE['auth_token'];
        $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));

        // Extract email and check if the user is an admin
        $email = $decoded->email;
        
        // Fetch user from the database
        $query = mysqli_query($conn, "SELECT firstName, lastName, email FROM users WHERE email='$email'");
        $user = mysqli_fetch_assoc($query);

        if ($user) {
            // Check if the email belongs to the admin
            if ($email === "omar.employee12025@gmail.com") {
                // Admin verified, proceed with admin content
                $firstName = $user['firstName'];
                $lastName = $user['lastName'];
            } else {
                // User is not an admin, deny access
                echo "Access denied. Admins only.";
                exit();
            }
        } else {
            // User not found in the database
            echo "Access denied. Admins only.";
            exit();
        }
    } catch (Exception $e) {
        // JWT is invalid or expired
        echo "Access denied. Admins only.";
        exit();
    }
} else {
    // No JWT cookie found, redirect to login
    echo "Access denied. Admins only.";
    exit();
}

// Fetch all users for admin dashboard
$result = mysqli_query($conn, "SELECT id, firstName, lastName, email FROM users");

// Handle user deletion
if (isset($_POST['deleteUser'])) {
    $userId = intval($_POST['userId']);
    $deleteQuery = mysqli_query($conn, "DELETE FROM users WHERE id='$userId'");
    if ($deleteQuery) {
        $success_message = "User deleted successfully.";
    } else {
        $error_message = "Failed to delete user.";
    }
}

// Logout functionality (logout.php)
if (isset($_GET['logout'])) {
    setcookie('auth_token', '', time() - 3600, '/');  // Expire the JWT cookie
    session_destroy();  // Destroy the session
    header("Location: index.php");  // Redirect to login page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome, Admin <?php echo $firstName . " " . $lastName; ?></h1>
    <a href="?logout=true">Logout</a>  <!-- Logout link -->

    <?php if (isset($success_message)) echo "<p style='color:green;'>$success_message</p>"; ?>
    <?php if (isset($error_message)) echo "<p style='color:red;'>$error_message</p>"; ?>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['firstName']; ?></td>
                    <td><?php echo $row['lastName']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="userId" value="<?php echo $row['id']; ?>">
                            <input type="submit" name="deleteUser" value="Delete">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
