<?php
session_start();


$servername = "localhost";
$username = "root"; // Change if necessary
$password = "";
$dbname = "hydout"; // Use your actual database name


$conn = new mysqli($servername, $username, $password, $dbname);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$eid = isset($_SESSION['eid']) ? $_SESSION['eid'] : 0;
$num_people = isset($_SESSION['num_people']) ? $_SESSION['num_people'] : 'Unknown';


// Fetch event details
$query = "SELECT date, time, title, thumbnail FROM events WHERE eid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $eid);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();


// Check if event exists
if ($event) {
    $title = $event['title'];
    $thumbnail = $event['thumbnail'];
    $date = $event['date'];
    $time = $event['time'];
} else {
    // If no event found, set default values or handle the error
    $title = "Event Not Found";
    $thumbnail = "default-thumbnail.jpg";  // Default image if not found
    $date = "N/A";
    $time = "N/A";
}


$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Emilys+Candy&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Yeseva+One&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Averia Serif Libre', serif;
            margin: 0;
            padding: 0;
            background-color: #0A3E8C;
        }


        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #FFD700; /* Gold */
            padding: 20px 40px;
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


        .confirmation-box {
            max-width: 1000px;
            background: #f4f4f4;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 2px 2px 12px rgba(0, 0, 0, 0.2);
            margin: auto;
        }


        h2 {
            font-size: 2rem;
            color: rgba(0, 99, 33, 0.8);
        }


        .content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            padding: 10px 0;
            color: #0A3E8C;
        }


        .event-details {
            width: 60%;
            text-align: left;
            font-size: 1.2rem;
        }


        .event-title {
            font-family: 'Averia Serif Libre', serif;
            font-size: 2.7rem;
            color: #0A3E8C;
            margin-bottom: 20px;
        }


        .event-info {
            font-size: 1.1rem;
            color: #555;
        }


        .event-image img {
            width: 500px;
            height: 250px;
            object-fit: cover; /* Make sure the whole image fits within the container */
            border-radius: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-right: 40px;
            margin-top: -50px;

        }


        .thank-you {
            font-size: 1.5rem;
            color: #0A3E8C;
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
        }


        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background: #FFD700;
            color: #0A3E8C;
            text-decoration: none;
            border-radius: 5px;
        }


        .back-link:hover {
            background: #0A3E8C;
            color: #FFD700;
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
            <li><a href="wishlist.php">Wishlist</a></li>
            <li><a href="spotseeker1.php">SpotSeeker</a></li>
            <li><a href="package.php">Packages</a></li>
            <li><a href="events.php">Latest Events</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>


<br><br>


<div class="confirmation-box">
    <h2>Booking Confirmed!</h2>
    <div class="content">
        <div class="event-details">
            <p><b>Event:</b> <?php echo htmlspecialchars($title); ?></p>
            <p><b>Date:</b> <?php echo htmlspecialchars($date); ?></p>
            <p><b>Time:</b> <?php echo htmlspecialchars($time); ?></p>
            <p><b>Attendees:</b> <?php echo htmlspecialchars($num_people); ?> people</p>
        </div>
        <div class="event-image">
            <img src="<?php echo htmlspecialchars($thumbnail); ?>" alt="Event Thumbnail">
        </div>
    </div>
   
    <p class="thank-you">Thank you for booking! We look forward to seeing you there.</p>
    <center>
    <a href="home.php" class="back-link"><b>Go to Homepage</b></a></center>
</div>


</body>
</html>



