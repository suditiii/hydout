<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - HydOut</title>
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Emilys+Candy&display=swap" rel="stylesheet">
    <style>

body {
        font-family: 'Averia Serif Libre', serif;
        margin: 0;
        padding: 0;
        background-color:  #0A3E8C;
    }


/* Main Header */
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

        .about-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .about-container h2 {
            color: #0A3E8C;
            margin-bottom: 15px;
        }
        .about-container p {
            font-size: 1.1rem;
            margin-bottom: 15px;
        }
        .about-container img {
            width: 300px;
            max-width: 300px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .main-footer {
    background-color: #FFD700; 
    padding: 20px 40px;
    text-align: center;
    color: black;
}


.footer-content p {
    margin-bottom: 20px;
    font-weight:bold;

}


.footer-links {
    display: flex;
    justify-content: center;
    list-style: none;
    gap: 20px;
    font-weight:bold;
}


.footer-links li {
    margin-right: 20px;
}


.footer-links li a {
    text-decoration: none;
    color: black;
    font-weight: 700;
}


.footer-links li a:hover {
    color: #0A3E8C; 
}
    </style>
</head>
<body>
     <!-- Header Section -->
     <header class="main-header">
        <div class="logo">
<a href="home.php" style="text-decoration: none;"><h1 style="font-family: 'Emilys Candy', cursive;">HydOut</h1></a>
</div>
        <nav>
            <ul class="nav-links">
                <li><a href="profile.php">Your Profile</a></li>
                <li><a href="wishlist.php">Wishlist</a></li><li><a href="spotseeker1.php">SpotSeeker</li>
                <li><a href="package.php">Packages</a></li>
                <li><a href="events.php">Event Planning</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="about-container">
        <h2>Welcome to HydOut</h2>
        <p>HydOut is your ultimate destination for discovering the best events and experiences in Hyderabad. Whether you're looking for cultural events, adventure activities, or serene getaways, we have got you covered.</p>
        
        <h3>Our Mission</h3>
        <p>We aim to provide a seamless and user-friendly platform for people to explore and book unique experiences. Our goal is to connect explorers with the best-hidden gems and thrilling adventures Hyderabad has to offer.</p>
        
        <h3>Why Choose Us?</h3>
        <p>★ Curated experiences tailored for you.<br> ★ Secure and hassle-free booking process.<br>★ Trusted platform with real user reviews.<br>★ Coz we're hydera-baddies</p>
        
        <img src="pic.jpeg" alt="About HydOut">
        <p>The ideators,developers and CFO+CEO+employees</p>
    </div>
    
    <footer class="main-footer">
    <div class="footer-content">
        <p>© 2024 HydOut. All rights reserved.</p>
        <nav>
            <ul class="footer-links">
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="privacy.html">Privacy Policy</a></li>
            </ul>
        </nav>
    </div>
</footer>
</body>
</html>
