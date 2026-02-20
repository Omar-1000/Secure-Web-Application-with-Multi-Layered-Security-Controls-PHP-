<?php
session_start();
include 'connect.php';

// Retrieve and escape error messages
$error_message = isset($_SESSION['error_message']) ? htmlspecialchars($_SESSION['error_message'], ENT_QUOTES, 'UTF-8') : '';
unset($_SESSION['error_message']); // Clear the error message after displaying it

// CSRF Token Function
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a random token
    }
    return $_SESSION['csrf_token'];
}

// Generate the CSRF token for the form
$csrfToken = generateCSRFToken();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" id="signup" style="display:none;">
        <h1 class="form-title">Register</h1>
        <form method="post" action="register.php">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="fName" placeholder="First Name" required>
                <label for="fname">First Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="lName" placeholder="Last Name" required>
                <label for="lName">Last Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
			<!-- CSRF Token Hidden Field -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="submit" class="btn" value="Sign Up" name="signUp">
        </form>
        <p class="links">Already have an account? <button id="signInButton">Sign In</button></p>
    </div>

    <div class="container" id="signIn">
        <h1 class="form-title">Sign In</h1>
        <?php if (!empty($error_message)) : ?>
            <p class="error-message" style="color:red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="post" action="register.php">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
			<!-- CSRF Token Hidden Field -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="submit" class="btn" value="Sign In" name="signIn">
        </form>
        <p class="links">Don't have an account? <button id="signUpButton">Sign Up</button></p>
    </div>
    <script src="script.js"></script>
</body>
</html>
