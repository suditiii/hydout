<?php
// Start the session
session_start();
$conn = new mysqli('localhost:3306', 'root', '', 'hydout');


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Check if the 'eid' parameter is set
$eid = isset($_GET['eid']) ? intval($_GET['eid']) : 0;


// Retrieve the number of people from query parameters
$num_people = isset($_GET['num_people']) ? intval($_GET['num_people']) : 1;


// Only proceed if 'eid' is valid
if ($eid > 0) {
    // Fetch event details
    $sql = "SELECT * FROM events WHERE eid = $eid";
    $result = $conn->query($sql);


    // Check if the event was found
    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();


        $eventDate = isset($event['event_date']) ? $event['event_date'] : 'No date available';
        $eventTime = isset($event['event_time']) ? $event['event_time'] : 'No time available';
        // Fetch the event price (base cost)
        $basePrice = $event['price'];
        $totalCost = $basePrice * $num_people; // Base cost
        $convenienceFee = 0.10 * $totalCost; // 10% convenience fee
        $gst = 0.05 * ($totalCost + $convenienceFee); // 5% GST
        $finalCost = $totalCost + $convenienceFee + $gst;


    } else {
        die("Event not found. Please check the event ID.");
    }
} else {
    die("Invalid event ID.");
}




$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Summary - HydOut</title>
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




        .summary-container {
            padding: 20px;
            max-width: 800px;
            margin: auto;
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


        .summary-table th, .summary-table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #0A3E8C;
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
            background-color: #0A3E8C;
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
            background-color: #FFD700;
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
            <li><a href="spotseeker1.php">SpotSeeker</a></li>
            <li><a href="package.php">Packages</a></li>
            <li><a href="events.php">Latest Events</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>
<br><br>
<!-- Summary Section -->
<div class="summary-container">
    <h2 class="summary-title">Here's your booking summary!</h2>


    <table class="summary-table">
        <tr>
            <th>Event Title</th>
            <td><?php echo htmlspecialchars($event['title']); ?></td>
        </tr>
        <tr>
            <th>Number of People</th>
            <td><?php echo $num_people; ?></td>
        </tr>
        <tr>
            <th>Base Cost</th>
            <td>₹<?php echo number_format($totalCost, 2); ?></td>
        </tr>
        <tr>
            <th>Convenience Fee (10%)</th>
            <td>₹<?php echo number_format($convenienceFee, 2); ?></td>
        </tr>
        <tr>
            <th>GST (5%)</th>
            <td>₹<?php echo number_format($gst, 2); ?></td>
        </tr>
    </table>


    <div class="total-amount">Total Amount: ₹<?php echo number_format($finalCost, 2); ?></div>


    <form action="epayment.php" method="POST">
    <!-- Hidden fields for essential data -->
    <input type="hidden" name="eid" value="<?php echo $eid; ?>">  <!-- Event ID -->
    <input type="hidden" name="num_people" value="<?php echo $num_people; ?>">  <!-- Number of people -->
   


    <!-- Hidden final cost field -->
    <input type="hidden" name="final_cost" value="<?php echo $finalCost; ?>">  


    <button type="submit">Proceed to Payment</button>
</form>


</div>
</body>
</html>



