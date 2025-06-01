<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['fullname']) || !isset($_SESSION['email'])) {
    echo "<script>alert('Please sign in to access this page.'); window.location.href='first.html';</script>";
    exit;
}

// Store session variables
$fullname = $_SESSION['fullname'];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us Us - HydOut</title>
        
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Emilys+Candy&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Yeseva+One&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Averia Serif Libre', serif;
            background-color: #0A3E8C;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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
        .contact-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .contact-container h2 {
            text-align: center;
            color: #0A3E8C;
        }
        label {
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px -9px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #0A3E8C;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #d1a94d;
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
<header class="main-header">
    <div class="logo">
        <a href="home.php" style="text-decoration: none;">
            <h1 style="font-family: 'Emilys Candy', cursive;">HydOut</h1>
        </a>
    </div>
    <nav>
        <ul class="nav-links">
            <li><a href="profile.php">Your Profile</a></li>
            <li><a href="spotseeker1.php">SpotSeeker</a></li>
            <li><a href="package.php">Packages</a></li>
            <li><a href="events.php">Latest Events</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

    
    <div class="contact-container">
        <h2>Contact Us Us</h2>
        <form action="send_message.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($fullname); ?>" readonly>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
            
            <label for="suggestion">Suggestion:</label>
            <textarea id="suggestion" name="suggestion" rows="5" required></textarea>
            
            <button type="submit">Send </button>
        </form>
    </div>
    <footer class="main-footer">
        <div class="footer-content">
            <p>  2024 HydOut. All rights reserved.</p>
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
