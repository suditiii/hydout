<?php
session_start();
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

if (!isset($_SESSION['booking_id'])) {
    echo "No booking found.";
    exit();
}

$bid = $_SESSION['booking_id'];

$sql = "SELECT pbookings.*, packages.title, packages.image_url
        FROM pbookings 
        JOIN packages ON pbookings.pid = packages.pid
        WHERE pbookings.bid = '$bid'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "<h2>Booking Confirmation</h2>";
    echo "<p><strong>Package:</strong> " . $row['title'] . "</p>";
    echo "<p><strong>Booking Date:</strong> " . $row['bdate'] . "</p>";
    echo "<p><strong>Time Slot:</strong> " . $row['time_slot'] . "</p>";
    echo "<p><strong>People:</strong> " . $row['people'] . "</p>";
    echo "<p><strong>Total Cost:</strong> $" . $row['total_cost'] . "</p>";
    echo "<img src='" . $row['image_url'] . "' alt='Package Image' width='200'><br>";
} else {
    echo "Booking details not found.";
}

$conn->close();
?>
