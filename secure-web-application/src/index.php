<?php
session_start(); // Start the session to access session variables
include 'connect.php';
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
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

        /* Features Section */
        .features {
            padding: 50px 20px;
            text-align: center;
            background: rgba(255, 255, 255, 0.0); /* Transparent background */
            margin-top: 80px; /* Moves it down from the hero section */
        }

        .features h2 {
            font-size: 36px;
            margin-bottom: 20px;
			color: #000000; 
        }

        .feature-item {
            display: inline-block;
            width: 30%;
            margin: 20px;
			color: #ffffff;
        }

        .feature-item i {
            font-size: 50px;
            color: #017bf5;
            margin-bottom: 10px;
        }

        .feature-item h3 {
            font-size: 24px;
            margin-bottom: 10px;
			color: #ffffff; 
        }

        /* Contact Section */
        .contact {
            padding: 50px 20px;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
        }

        .contact h2 {
            text-align: center;
            font-size: 36px;
            margin-bottom: 20px;
        }

        .contact form {
            max-width: 500px;
            margin: 0 auto;
        }

        .contact input, .contact textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .contact button {
            background: #017bf5;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .contact button:hover {
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
        <h1>Omar Ayesh Secure Website</h1>
        <h3>Welcome to your secure and modern platform</h3>
        <div class="buttons">
            <a href="index22.php">GET STARTED</a>
            <a href="#">FOLLOW ME</a>
        </div>
    </div>

    <!-- Features Section -->
    <div class="features">
        <h2>Include</h2>
        <div class="feature-item">
            <i class="fas fa-user-shield"></i>
            <h3>hashing</h3>
            <p>Your data is hashed with top security.</p>
        </div>
        <div class="feature-item">
            <i class="fas fa-tachometer-alt"></i>
            <h3>Https</h3>
            <p>we use Https</p>
        </div>
        <div class="feature-item">
            <i class="fas fa-heart"></i>
            <h3>2FA</h3>
            <p>we use 2FA authentication</p>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="contact">
        <h2>Contact Us</h2>
        <form>
            <input type="text" placeholder="Your Name" required>
            <input type="email" placeholder="Your Email" required>
            <textarea placeholder="Your Message" rows="4" required></textarea>
            <button type="submit">Send Message</button>
        </form>
    </div>
</body>
</html>
