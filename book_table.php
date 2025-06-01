<?php
session_start();

// Database connection
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "hydout";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Reservation</title>
        
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Emilys+Candy&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Yeseva+One&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Averia Serif Libre', serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
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
        .summary-container {
            padding: 20px;
            max-width: 800px;
            margin: 40px auto;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .summary-title {
            font-family: 'Emilys Candy', cursive;
            font-size: 2.5rem;
            color: #0A3E8C;
            text-align: center;
            margin-bottom: 20px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            
        }

        .summary-table th, .summary-table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .summary-table th {
            background-color: #FFD700;
            color: #0A3E8C;
        }

        .summary-table td {
            font-family: 'Averia Serif Libre', serif;
            font-weight: bold;
        }

        .total-amount {
            font-weight: bold;
            font-size: 1.5rem;
            color: #0A3E8C;
            text-align: right;
            margin-top: 20px;
        }

        button {
            background-color: #0A3E8C; /* Blue */
            color: white;
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 20px auto;
        }

        button:hover {
            background-color: #FFD700; /* Gold */
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
                 <a href="home.php" style="text-decoration: none;"><h1 style="font-family: 'Emilys Candy', cursive;">HydOut</h1></a>


        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="profile.php">Your Profile</a></li>
                <li><a href="wishlist.php">Wishlist</a></li><li><a href="spotseeker1.php">SpotSeeker</a></li>
                <li><a href="package.php">Packages</a></li>
                <li><a href="events.php">Event Planning</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="summary-container">
            <?php
            // Check if form data is submitted
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Retrieve form data
                $place_id = $_POST['place_id'] ?? null;
                $num_people = $_POST['num_people'] ?? null;
                $date = $_POST['date'] ?? null;

                // Simulate the user ID (in a real app, retrieve this from the logged-in session)
                $user_id = $_SESSION['uid'] ?? 1;

                // Time of reservation (for demonstration purposes, we'll use a static time)
                $time = "12:30 PM"; // Example time

                if ($place_id && $num_people && $date) {
                    // Insert data into the `sreservations` table
                    $sql = "INSERT INTO sreservations (uid, sid, date, time) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);

                    if ($stmt) {
                        $stmt->bind_param("iiss", $user_id, $place_id, $date, $time);

                        if ($stmt->execute()) {
                            // Retrieve the name of the place
                            $place_query = "SELECT name FROM spotseeker WHERE sid = ?";
                            $place_stmt = $conn->prepare($place_query);
                            $place_stmt->bind_param("i", $place_id);
                            $place_stmt->execute();
                            $place_result = $place_stmt->get_result();
                            $place = $place_result->fetch_assoc();

                            // Display reservation details
                            echo "<br><h1 class='summary-title'>Table Reserved Successfully!</h1>";
                            echo "<table class='summary-table'>";
                            echo "<tr><th>Place</th><td>" . htmlspecialchars($place['name']) . "</td></tr>";
                            echo "<tr><th>Date</th><td>" . htmlspecialchars($date) . "</td></tr>";
                            echo "<tr><th>Time</th><td>" . htmlspecialchars($time) . "</td></tr>";
                            echo "<tr><th>Number of People</th><td>" . htmlspecialchars($num_people) . "</td></tr>";
                            echo "</table>";
                        } else {
                            echo "<p>Error: " . $stmt->error . "</p>";
                        }
                        $stmt->close();
                    } else {
                        echo "<p>SQL Error: " . $conn->error . "</p>";
                    }
                } else {
                    echo "<p>Please fill out all the required fields.</p>";
                }
            } else {
                echo "<p>Invalid request.</p>";
            }

            // Close the connection
            $conn->close();
            ?>
            <button onclick="window.location.href='home.php';">Back to Home</button>
        </div>
    </main>
    <br>
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
