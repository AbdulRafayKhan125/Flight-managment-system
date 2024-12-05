<?php
session_start();
include 'db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch available flights for booking
$sql = "SELECT * FROM flights WHERE available_seats > 0";
$result = $conn->query($sql);

// Fetch user's bookings
$booking_sql = "SELECT b.booking_id, f.flight_number, f.from_location, f.to_location, f.price, b.booking_date, f.schedule
                FROM bookings b
                JOIN flights f ON b.flight_id = f.flight_id
                WHERE b.user_id = ?";
$booking_stmt = $conn->prepare($booking_sql);
$booking_stmt->bind_param("i", $user_id);
$booking_stmt->execute();
$booking_result = $booking_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h2, h3 {
            color: #333;
        }
        .flight-table, .booking-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-danger {
            background-color: red;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-danger:hover {
            background-color: darkred;
        }

        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Fixed/sticky position */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4); /* Background color with transparency */
            overflow: auto;
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            right: 10px;
            top: 0;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        <p><a href="logout.php">Logout</a></p>

        <!-- Available Flights Section -->
        <h3>Available Flights</h3>
        <table class="flight-table">
            <tr>
                <th>Flight Number</th>
                <th>From Location</th>
                <th>To Location</th>
                <th>Seats Available</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($flight = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $flight['flight_number'] . "</td>";
                    echo "<td>" . $flight['from_location'] . "</td>";
                    echo "<td>" . $flight['to_location'] . "</td>";
                    echo "<td>" . $flight['available_seats'] . "</td>";
                    echo "<td>" . $flight['price'] . "</td>";
                    echo "<td><button class='btn' onclick='openModal(" . $flight['flight_id'] . ", " . $flight['price'] . ", " . $flight['available_seats'] . ")'>Book</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No available flights at the moment.</td></tr>";
            }
            ?>
        </table>

        <!-- User's Bookings Section -->
        <h3>Your Bookings</h3>
        <table class="booking-table">
            <tr>
                <th>Flight Number</th>
                <th>From Location</th>
                <th>To Location</th>
                <th>Price</th>
                <th>Booking Date</th>
                <th>Schedule</th>
                <th>Action</th>
            </tr>
            <?php
            if ($booking_result->num_rows > 0) {
                while ($booking = $booking_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $booking['flight_number'] . "</td>";
                    echo "<td>" . $booking['from_location'] . "</td>";
                    echo "<td>" . $booking['to_location'] . "</td>";
                    echo "<td>" . $booking['price'] . "</td>";
                    echo "<td>" . $booking['booking_date'] . "</td>";
                    echo "<td>" . $booking['schedule'] . "</td>";
                    echo "<td><a href='cancel_booking.php?booking_id=" . $booking['booking_id'] . "' class='btn btn-danger'>Cancel Booking</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>You have no bookings yet.</td></tr>";
            }
            ?>
        </table>

        <!-- Modal for booking -->
        <div id="bookingModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h3>Book Your Flight</h3>
                <form method="post" action="book_ticket.php">
                    <input type="hidden" id="flight_id" name="flight_id">
                    <label for="num_seats">Select number of seats:</label>
                    <input type="number" id="num_seats" name="num_seats" min="1" max="" required><br><br>
                    <p>Total Payment: $<span id="total_payment">0</span></p>
                    <input type="submit" value="Book Flight" class="btn">
                </form>
            </div>
        </div>

        <script>
            let modal = document.getElementById("bookingModal");

            function openModal(flight_id, price, available_seats) {
                document.getElementById("flight_id").value = flight_id;
                document.getElementById("num_seats").max = available_seats;
                document.getElementById("num_seats").addEventListener('input', function() {
                    const numSeats = this.value;
                    const totalPayment = numSeats * price;
                    document.getElementById("total_payment").innerText = totalPayment;
                });
                modal.style.display = "block";
            }

            function closeModal() {
                modal.style.display = "none";
            }

            // Close the modal if the user clicks anywhere outside of the modal content
            window.onclick = function(event) {
                if (event.target == modal) {
                    closeModal();
                }
            }
        </script>
    </div>
</body>
</html>
