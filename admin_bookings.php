<?php
session_start();
$conn = new mysqli("localhost:3306", "root", "", "hydout");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT b.bid, p.title, b.bdate, b.time_slot, b.status
        FROM pbooking b
        JOIN packages p ON b.pid = p.pid
        JOIN user u ON b.uid = u.uid";

$result = $conn->query($sql);

if (!$result) {
    die("Query Failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #bc5d6e;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        select, button {
            padding: 6px 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            background-color: #28a745;
            color: #fff;
            cursor: pointer;
            border: none;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <h1>All Bookings</h1>

    <table>
        <tr>
            <th>Booking ID</th>
            <th>Package</th>
            <th>Date</th>
            <th>Time Slot</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['bid']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['bdate']; ?></td>
                <td><?php echo $row['time_slot']; ?></td>
                <td><?php echo ucfirst($row['status']); ?></td>
                <td>
                    <form method="POST" action="update_booking.php">
                        <input type="hidden" name="bid" value="<?php echo $row['bid']; ?>">
                        <select name="status">
                            <option value="confirmed" <?php if ($row['status'] == 'confirmed') echo 'selected'; ?>>Confirmed</option>
                            <option value="pending" <?php if ($row['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                            <option value="canceled" <?php if ($row['status'] == 'canceled') echo 'selected'; ?>>Canceled</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                    </form>
<form method="POST" action="delete_booking.php" onsubmit="return confirm('Are you sure you want to delete this booking?');">
    <input type="hidden" name="bid" value="<?php echo $row['bid']; ?>">
    <button type="submit" style="background-color: #dc3545; color: white;">Delete</button>
</form>

                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>


