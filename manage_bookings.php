<?php
session_start();
include 'header.php';
// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'db.php';

// Handle booking cancellation
if (isset($_GET['cancel_booking_id'])) {
    $booking_id = $_GET['cancel_booking_id'];
    // Cancel the booking by deleting it from the bookings table
    $stmt = $conn->prepare("DELETE FROM bookings WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id);
    if ($stmt->execute()) {
        echo "Booking cancelled successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch all bookings along with the associated user and flight data
$sql = "SELECT b.booking_id, b.num_seats, b.total_payment, b.booking_date, 
               f.flight_number, f.from_location, f.to_location, f.schedule,
               u.username AS customer_name, u.email AS customer_email
        FROM bookings b
        JOIN flights f ON b.flight_id = f.flight_id
        JOIN users u ON b.user_id = u.user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        td {
            background-color: #fff;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .btn-back {
            display: block;
            width: 200px;
            text-align: center;
            padding: 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            margin: 20px auto;
            border-radius: 5px;
        }
        .btn-cancel {
            padding: 5px 10px;
            background-color: #ff0000;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .btn-cancel:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Manage Bookings</h1>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Flight Number</th>
                    <th>From Location</th>
                    <th>To Location</th>
                    <th>Seats Booked</th>
                    <th>Total Payment</th>
                    <th>Booking Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($booking = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $booking['booking_id']; ?></td>
                    <td><?php echo $booking['customer_name']; ?></td>
                    <td><?php echo $booking['customer_email']; ?></td>
                    <td><?php echo $booking['flight_number']; ?></td>
                    <td><?php echo $booking['from_location']; ?></td>
                    <td><?php echo $booking['to_location']; ?></td>
                    <td><?php echo $booking['num_seats']; ?></td>
                    <td><?php echo $booking['total_payment']; ?></td>
                    <td><?php echo $booking['booking_date']; ?></td>
                    <td>
                        <!-- Cancel Booking Button -->
                        <a href="manage_bookings.php?cancel_booking_id=<?php echo $booking['booking_id']; ?>" class="btn-cancel" onclick="return confirm('Are you sure you want to cancel this booking?');">
                            Cancel Booking
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <a href="admin_dashboard.php" class="btn-back">Back to Dashboard</a>
    </div>

</body>
</html>
