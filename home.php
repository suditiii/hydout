<?php
// Start the session to access session variables
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='home.html';</script>";
    exit; // Stop further execution of the script
}
// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['fullname'])) {
    // Redirect to login page if not logged in
    header("Location: signin.php");
    exit();
}

// Store session variables
$fullname = $_SESSION['fullname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HydOut - Home</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Emilys+Candy&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Yeseva+One&display=swap" rel="stylesheet">
 
<style>
/* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body styling */
body {
    font-family: 'Averia Serif Libre', serif;
    background-color: #f5f5f5;
    color: #333;
    line-height: 1.6;
}

.main-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #FFD700; /* Gold */
    padding: 20px 40px;
}

.logo h1 {
    font-family: 'Emilys Candy', cursive, sans-serif;
}

.main-header h1 {
    font-family: 'Emilys Candy', cursive;
    font-size: 2.5rem;
    color: #0A3E8C; /* Blue */
}

nav ul {
    display: flex;
    list-style: none;
}

nav ul li {
    margin-right: 25px;
}

nav ul li a {
    text-decoration: none;
    color: #333;
    font-weight: 700;
    font-size: 1.1rem;
}

nav ul li a:hover {
    color: #0A3E8C; /* Blue */
}

/* Hero Section */
.hero-section {
    position: relative;
    width: 100%;
    height: 75vh; /* Full height of the screen */
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: white; /* Ensures text remains readable */
}

.hero-video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensures video covers the entire section without black bars */
    z-index: -1;
}

.hero-content {
    position: absolute;
    top: 5%; /* Moves text near the top */
    left: 2%; /* Aligns text to the left */
    z-index: 2;
    max-width: 100%; /* Controls text width */
    text-align: left;
    padding: 20px;
    border-radius: 10px;
 
}

.hero-content h2 {
    font-size: 2.7rem;
    color: #0a3e8c;

}

.hero-content p {
    font-size: 1.8rem;
    color: #0a3e8c;

}
.hero-section .cta-button {
    background-color: #FFD700; /* Blue */
    color: #0a3e8c;
    padding: 10px 30px;
    font-weight: 700;
    text-decoration: none;
    border-radius: 30px;
    transition: background-color 0.3s ease;
}

.hero-section .cta-button:hover {
    background-color: #0a3e8c; /* Gold */
    color: #FFD700
}

/* Video Frame */
.video-frame {
    width: 50%;
    position: relative;
    overflow: hidden;
    border-radius: 10px;
}

.video-frame video {
    width: 100%;
    height: 50%;
    object-fit: cover;
    display: block;
}

/* Features Section */
.features {
    padding: 60px 40px;
    text-align: center;
    background-color: #0A3E8C; /* Blue */
}


.features h2 {
    font-size: 2rem;
    margin-bottom: 40px;
    color: #FFD700;
}


.feature-grid {
    display: flex;
    justify-content: space-around;
    gap: 10px;
}


.feature-box {
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        margin: 10px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
        width: 400px; /* Adjust as needed */
        height: 700px;
        text-decoration: none;
        color: black;
    }

    .feature-box:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }

    .feature-box img {
        width: 100%;
        height: 75%;
        object-fit: cover;
    }

    .feature-box a {
    text-decoration: none;
    color: black;
}



.feature-box h3 {
    font-size: 1.5rem;
    margin-bottom: 10px;
    color: #0A3E8C; /* Blue */
}


.feature-box p {
    font-size: 1.17rem;
    
}

.main-footer {
        background-color: #0A3E8C; /* Blue */
        padding: 20px 40px;
        text-align: center;
        color: white;
    }

    .footer-content p {
        margin-bottom: 20px;
    }

    .footer-links {
        display: flex;
        justify-content: center;
        list-style: none;
        gap: 20px;
    }

    .footer-links li {
        margin-right: 20px;
    }

    .footer-links li a {
        text-decoration: none;
        color: white;
        font-weight: 700;
    }

    .footer-links li a:hover {
        color: #FFD700; /* Gold */
    }

</style>
</head>
<body>
    <header class="main-header">
        <div class="logo">
            <h1 style="font-family: 'Emilys Candy', cursive;">HydOut</h1>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="profile.php">Your Profile</a></li>
                <li><a href="wishlist.php">Wishlist</a></li>
                <li><a href="spotseeker1.php">SpotSeeker</a></li>
                <li><a href="package.php">Packages</a></li>
                <li><a href="events.php">Latest events</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <section class="hero-section">
    <video class="hero-video" autoplay muted loop playsinline>
        <source src="hydbg.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="hero-content">
        <h2>
            <?php 
            if ($fullname) {
                echo "Welcome to HydOut,<br> $fullname!";
            } else {
                echo "Welcome to HydOut";
            }
            ?>
        </h2>
        <p>Your gateway to exploring the vibrant city of Hyd. <br>From historic spots to hidden gems, <br>HydOut helps you plan unforgettable experiences.</p>
       <br>
        <a href="spotseeker1.php" class="cta-button">Start Exploring</a>
    </div>
</section>
<section class="features">
        <h2>Discover Hyderabad</h2>
        <div class="feature-grid">
            <a href="spotseeker1.php" class="feature-box">
                <img src="https://i.pinimg.com/736x/96/a6/f5/96a6f541eee4f2ccb657e65724bf66bc.jpg" alt="Culture Icon">
                <h3>Seeking a Spot?</h3>
                <p>Whether your hungry for history or just some of our iconic biryani, HydOut has got you covered!</p>
        </a>
            <a href="package.php" class="feature-box">
                <img src="https://i.pinimg.com/736x/b5/ec/41/b5ec415bf493a8b1f404c6fd7be8490f.jpg" alt="Food Icon">
                <h3>Paisa-Vasool Packages</h3>
                <p>Get an unforgettable experience with our handcrafted packages!<br>Socho math, bas book kardo!</p>
        </a>
            <a href="events.php" class="feature-box">
                <img src="https://i.pinimg.com/736x/0b/26/ae/0b26ae977b96d82bd590f8b6d3587ebe.jpg" alt="Nightlife Icon">
                <h3>Live Events wale scenes</h3>
                <p>Dive into the vibrant events scene that Hyderabad has to offer!</p>
        </a>
        </div>
    </section>

    <footer class="main-footer">
    <div class="footer-content">
        <p>© 2024 HydOut. All rights reserved.</p>
        <nav>
            <ul class="footer-links">
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="privacy.php">Privacy Policy</a></li>
            </ul>
        </nav>
    </div>
</footer>

</body>
</html>

