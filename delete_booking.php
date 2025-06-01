<?php
$conn = new mysqli("localhost:3306", "root", "", "hydout");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bid'])) {
    $bid = $conn->real_escape_string($_POST['bid']);
    $sql = "DELETE FROM pbooking WHERE bid = '$bid'";

    if ($conn->query($sql)) {
        echo "<script>alert('Booking deleted successfully'); window.location.href='admin_bookings.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
$conn->close();
?>


