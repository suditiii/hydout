<?php
// Start the session
session_start();
$conn = new mysqli('localhost:3306', 'root', '', 'hydout');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch filters from GET request
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$price = isset($_GET['price']) ? $_GET['price'] : 'all';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$language = isset($_GET['language']) ? $_GET['language'] : 'all';

// Base SQL query to fetch events based on filters
$sql = "SELECT e.*, 
               (e.capacity - COALESCE(SUM(b.people), 0)) AS available_seats 
        FROM events e 
        LEFT JOIN ebooking b 
            ON e.eid = b.eid 
            AND e.date = b.date 
            AND e.time = b.time 
        WHERE DATE(e.date) >= CURDATE()"; // Compare only date, ignoring time

// Apply filters if necessary
if ($category !== 'all') {
    $sql .= " AND e.category = '" . $conn->real_escape_string($category) . "'";
}
if ($price === 'free') {
    $sql .= " AND e.price = 0";
} elseif ($price === 'paid') {
    $sql .= " AND e.price > 0";
}          
if (!empty($date)) {
    $sql .= " AND DATE(e.date) = '" . $conn->real_escape_string($date) . "'"; // Ensure date comparison is correct
}
if ($language !== 'all') {
    $sql .= " AND e.language = '" . $conn->real_escape_string($language) . "'";
}

$sql .= " GROUP BY e.eid
          ORDER BY e.date ASC, e.time ASC";
// Execute the query
$result = $conn->query($sql);

// Check for query errors
if ($result === false) {
    die("Error executing query: " . $conn->error);  // Show error if the query failed
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events - HydOut</title>
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Emilys+Candy&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Yeseva+One&display=swap" rel="stylesheet">
    <style>
       * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Averia Serif Libre', serif;
    background-color: #f4f4f4;
}

.content {
    display: flex;
    width: 100%;
    gap: 40px;
    padding: 30px;
}

.main-container {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    width: 75%;
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


.filters-container {
    background-color: #ffffff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 30px;
    width: 20%;
    border-radius: 8px;
}

.filters-container form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.filters-container select, .filters-container input {
    padding: 9px;
    font-size: 15px;
    border: 0.5spx solid #ddd;
    border-radius: 4px;
}

.filters-container button {
    padding: 14px;
    font-size: 17px;
    background-color: #FFD700;
    border: none;
    border-radius: 6px;
    color: #0A3E8C;
    cursor: pointer;
    font-weight: bold;
    margin-top: 12px;
}

.filters-container button:hover {
    background-color: #0a3e9c;
    color: white;
}

.categories-container {
    display: flex;
    justify-content: center;
    gap: 15px;
    padding: 20px;
    background-color: #ffffff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.categories-container button {
    background: none;
    border: 1px solid #0A3E8C;
    color: #0A3E8C;
    border-radius: 5px;
    padding: 10px 15px;
    cursor: pointer;
    font-weight: bold;
}

.categories-container button:hover {
    background-color: #0a38c0;
    color: white;
}

.events-container {
    flex-grow:1;
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* Exactly 3 columns */
    gap: 40px;
    }

.event-card {
    background-color: #ffffff;
    min-width:348.5px;
    min-height:260px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            text-align: center;
            cursor: pointer;
}


.event-card img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;

}

.event-details {
    padding: 15px;
}

.event-title {
    font-size: 1.2rem;
    font-weight: bold;
    color: #0A3E8C;
}

.event-info {
    margin: 10px 0;
    font-size: 0.9rem;
    color: #555;
}

.event-price {
    font-size: 1rem;
    font-weight: bold;
    display: inline-block;
    padding: 10px 20px;
    background-color: #FFD700;
    color: #0A3E8C;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    margin-top: 10px;
    position: relative;
    overflow: hidden;
}

.event-price::before, .event-price::after {
    content: '';
    width: 10px;
    height: 10px;
    background-color: #f4f4f4;
    position: absolute;
    border-radius: 50%;
}

.event-price::before {
    top: 50%;
    left: -5px;
    transform: translateY(-50%);
}

.event-price::after {
    top: 50%;
    right: -5px;
    transform: translateY(-50%);
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
<!-- Header Section -->
<header class="main-header">
    <div class="logo">
        <a href="home.php" style="text-decoration: none;"><h1 style="font-family: 'Emilys Candy', cursive;">HydOut</h1></a>
    </div>
    <nav>
        <ul class="nav-links">
            <li><a href="profile.php">Your Profile</a></li>
            <li><a href="wishlist.php">Wishlist</a></li>
            <li><a href="home.php">Home</a></li>
            <li><a href="spotseeker1.php">SpotSeeker</a></li>
            <li><a href="package.php">Packages</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<div class="content">
    <div class="filters-container">
        <h2 style="font-family: 'Emilys Candy', cursive; text-align: center;">Filters</h2>
        <form method="GET" action="events.php">
            <!-- Date Filter -->
            <div>
                <h3>Date</h3>
                <input type="date" id="date" name="date" min="<?php echo date('Y-m-d') ?>" value="<?= htmlspecialchars($date) ?>">
                
            </div>
            
            <!-- Language Filter -->
            <div>
                <h3>Languages</h3>
                <select name="language">
                    <option value="all" <?= $language === 'all' ? 'selected' : '' ?>>All Languages</option>
                    <option value="English" <?= $language === 'English' ? 'selected' : '' ?>>English</option>
                    <option value="Hindi" <?= $language === 'Hindi' ? 'selected' : '' ?>>Hindi</option>
                    <option value="Telugu" <?= $language === 'Telugu' ? 'selected' : '' ?>>Telugu</option>
                    <option value="Arabic" <?= $language === 'Arabic' ? 'selected' : '' ?>>Arabic</option>
                    <option value="Urdu" <?= $language === 'Urdu' ? 'selected' : '' ?>>Urdu</option>
                    <option value="French" <?= $language === 'French' ? 'selected' : '' ?>>French</option>
                </select>
            </div>

            <!-- Price Filter -->
            <div>
                <h3>Price</h3>
                <select name="price">
                    <option value="all" <?= $price === 'all' ? 'selected' : '' ?>>All</option>
                    <option value="free" <?= $price === 'free' ? 'selected' : '' ?>>Free</option>
                    <option value="paid" <?= $price === 'paid' ? 'selected' : '' ?>>Paid</option>
                </select>
            </div>

            <button type="submit">Apply Filters</button>
        </form>
    </div>

    <div class="main-container">
        <!-- Categories Section -->
        <div class="categories-container">
            <form method="GET" action="events.php">
                <button type="submit" name="category" value="all" <?= $category === 'all' ? 'style="background-color:#FFD700;color:white"' : '' ?>>All</button>
                <button type="submit" name="category" value="Music" <?= $category === 'Music' ? 'style="background-color:#FFD700;color:white"' : '' ?>>Music</button>
                <button type="submit" name="category" value="Theatre" <?= $category === 'Theatre' ? 'style="background-color:#FFD700;color:white"' : '' ?>>Theatre</button>
                <button type="submit" name="category" value="Comedy" <?= $category === 'Comedy' ? 'style="background-color:#FFD700;color:white"' : '' ?>>Comedy</button>
                <button type="submit" name="category" value="Sports" <?= $category === 'Sports' ? 'style="background-color:#FFD700;color:white"' : '' ?>>Sports</button>
                <button type="submit" name="category" value="Workshops" <?= $category === 'Workshops' ? 'style="background-color:#FFD700;color:white"' : '' ?>>Workshops</button>
            </form>
        </div>

        <!-- Events Grid Section -->
        <div class="events-container">
            <?php
            // Check if there are any events
            if ($result->num_rows > 0) {
                // Start card container
                echo "<div class='events-container'>"; 
            
                // Loop through and display events in cards
                while ($event = $result->fetch_assoc()) {
                    echo "<div class='event-card' onclick=\"window.location.href='event_detail.php?eid=" . $event['eid'] . "'\">";
                    echo "<img src='" . htmlspecialchars($event['thumbnail']) . "' alt='Event Image'>"; // Assuming 'thumbnail' column holds the image path
                    echo "<div class='event-details'>";
                    echo "<h2 class='event-title'>" . htmlspecialchars($event['title']) . "</h2>";
                    echo "<div class='event-info'>";
                    echo "<p>Date: " . htmlspecialchars($event['date']) . "</p>";
                    echo "<p>Time: " . htmlspecialchars($event['time']) . "</p>";
                    echo "<p><strong>Available Seats:</strong> " . ($event['available_seats'] > 0 ? $event['available_seats'] : 'Sold Out') . "</p>";
                    echo "<p>Location: " . htmlspecialchars($event['location']) . "</p>";
                    echo "</div>";
            
                    if ($event['available_seats'] > 0) {
                        echo "<div class='event-price'>" . ($event['price'] == 0 ? 'Free' : '₹' . number_format($event['price'], 2)) . "</div>";
                    } else {
                        echo "<div class='event-price' style='background-color: red; color: white;'>Sold Out</div>";
                    }
            
                    echo "<p class='event-description'>" . htmlspecialchars($event['description']) . "</p>";
                    echo "</div>";
                    echo "</div>";
                }
            
                // End card container
                echo "</div>";
            } else {
                // No events found
                echo "<p style='text-align: center;'>No events found matching your criteria.</p>";
            }
            ?>
        </div>
    </div>
</div>

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
