<?php
// Start the session
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please sign in to access this page.'); window.location.href='first.html';</script>";
    exit; // Stop further execution of the script
}
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'hydout'); // Removed ":3306" since it's the default port
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Fetch the event ID from the URL
$eid = isset($_GET['eid']) ? intval($_GET['eid']) : 0;


if ($eid <= 0) {
    die("Error: Invalid event ID.");
}
                                                                   
// Prepare and execute query
$stmt = $conn->prepare("SELECT e.eid, e.title, e.date, e.time, e.location, e.price, e.thumbnail,  e.description1, e.capacity,
            (e.capacity - COALESCE(SUM(b.people), 0)) AS available_seats
        FROM events e
        LEFT JOIN ebooking b ON e.eid = b.eid
        WHERE e.eid = ?
        GROUP BY e.eid");
$stmt->bind_param("i", $eid); // Bind the event ID as an integer
$stmt->execute();


// Bind result to variables (make sure to match the number of columns selected)
$stmt->bind_result($eid, $title, $date, $time, $location, $price, $thumbnail, $description1, $capacity, $available_seats);


// Fetch data
if ($stmt->fetch()) {
    $event = [
        'eid' => $eid,
        'title' => $title,
        'date' => $date,
        'time' => $time,
        'location' => $location,
        'price' => $price,
        'thumbnail' => $thumbnail,
        'description1' =>  $description1,
        'capacity' => $capacity,
        'available_seats' => $available_seats
       
    ];


    // Store event data in session
    $_SESSION['eventDate'] = $event['date']; // Store event date
    $_SESSION['eventTime'] = $event['time']; // Store event time
} else {
    die("Error: Event not found.");
}


$stmt->close();
$conn->close();




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details - <?= htmlspecialchars($event['title']) ?> | HydOut</title>
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&family=Emilys+Candy&family=Yeseva+One&display=swap" rel="stylesheet">
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
            background-color: #FFD700;
            padding: 20px 40px;
        }


        .main-header h1 {
            font-family: 'Emilys Candy', cursive;
            font-size: 2.5rem;
            color: #0A3E8C;
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
            color: #0A3E8C;
        }


        .content {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 20px;
            padding: 35px 45px;
        }


        .event-details {
            width: 60%;
            text-align: left;
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
            margin: 15px 0;
        }


        .event-description {
            font-size: 1.2rem;
            color: #333;
            line-height: 1.5;
        }


        .event-image {
            width: 40%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }


        .event-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #0a3e8c;
            margin: 20px 0;
            font-family: 'Averia Serif Libre', serif;
        }


        .book-now {
            display: inline-block;
            padding: 15px 30px;
            background-color: #FFD700;
            color: #0A3E8C;
            font-size: 1.5rem;
            font-family: 'Averia Serif Libre', serif;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }


        .sold-out {
          display: inline-block;
          font-family:'Averia Serif Libre', serif;
          font-size: 1.5rem;
        }


        .book-now:hover {
            background-color: #0A3E8C;
            color: #FFD700;
        }


        footer {
            text-align: center;
            padding: 20px;
            background-color: #0A3E8C;
            color: white;
            margin-top: auto;
        }


        /* Popup Styles */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
        }


        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            display: none;
            width: 300px;
            text-align: center;
        }


        .popup h2 {
            margin-bottom: 20px;
        }


        .popup input {
            padding: 10px;
            font-size: 1.2rem;
            width: 100%;
            margin-bottom: 20px;
            margin: 10px -14px;
        }


        .popup button {
            padding: 10px 20px;
            font-size: 1.2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .popup button:first-child {
            background-color:rgb(223, 41, 41);
            color:rgb(223, 41, 41);
        }


        .popup button:first-child:hover {
            background-color:rgb(136, 0, 0);
        }
        .popup button:last-child {
            background-color: #FFD700;
            color: #0A3E8C;
        }


        .popup button:last-child:hover {
            background-color: #0A3E8C;
            color: #FFD700;
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
        <a href="home.php" style="text-decoration: none;"><h1>HydOut</h1></a>
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
<div class="content">
    <div class="event-details">
        <h1 class="event-title"><?= htmlspecialchars($event['title']) ?></h1>
        <div class="event-info">
            <p><strong>Date:</strong> <?= htmlspecialchars($event['date']) ?></p>
            <p><strong>Time:</strong> <?= htmlspecialchars($event['time']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
        </div>
        <div class="event-price">
            <?= $event['price'] == 0 ? 'Free' : '₹' . number_format($event['price'], 2) ?>
        </div>
        <p class="event-description"><?= htmlspecialchars($event['description1']) ?></p>
    </div>
    <img src="<?= htmlspecialchars($event['thumbnail']) ?>" alt="<?= htmlspecialchars($event['title']) ?>" class="event-image">
</div>


<!-- Only display the Book Now button if seats are available -->
<?php if ($event['available_seats'] > 0): ?>
    <center>
        <button class="book-now" onclick="openPopup()">Book Now</button>
    </center>
<?php else: ?>
    <center>
        <div class="sold-out" style="color: white; background-color: red; padding: 10px; border-radius: 5px;">SOLD OUT</div>
    </center>
<?php endif; ?>


<div class="popup-overlay" id="popup-overlay"></div>
<div class="popup" id="popup">
    <img src="charminar.png" height=100px width=70px>
    <h2>Select Number of People</h2>
    <p>Yaaro! Kitte loga aare?</p>
    <input type="number" id="num_people" min="1" max="<?= $event['available_seats'] ?>" value="1">
    <button onclick="closePopup()">Cancel</button>
    <button onclick="submitBooking(<?= $event['eid'] ?>)">Confirm</button>
</div>


<script>
    function openPopup() {
        document.getElementById('popup').style.display = 'block';
        document.getElementById('popup-overlay').style.display = 'block';
    }


    function closePopup() {
        document.getElementById('popup').style.display = 'none';
        document.getElementById('popup-overlay').style.display = 'none';
    }


    function submitBooking(eid) {
    const num_people = document.getElementById('num_people').value;
    const maxSeats = <?= $event['available_seats'] ?>;  // Get available seats


    if (!num_people || num_people <= 0) {
        alert("Please select a valid number of people.");
        return;
    }


    if (num_people > maxSeats) {
        alert(`Only ${maxSeats} seats are available.`);
        return;
    }


    // Proceed with booking if valid
    window.location.href = `bill-summary.php?eid=${eid}&num_people=${num_people}&date=<?= $event['date'] ?>&time=<?= $event['time'] ?>`;
}
</script>
<br><br>
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



