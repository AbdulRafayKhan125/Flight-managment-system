<?php
session_start();
include 'db.php'; // Include database connection
include 'header.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Fetch the booking details to get the associated flight and number of seats
    $sql = "SELECT flight_id, num_seats FROM bookings WHERE booking_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();
        $flight_id = $booking['flight_id'];
        $num_seats = $booking['num_seats'];

        // Debugging step: Check if num_seats is fetched correctly
        if (empty($num_seats)) {
            echo "Error: No seats found for this booking.";
            exit();
        }

        // Debugging step: Print the flight_id and num_seats values to confirm
        echo "Flight ID: " . $flight_id . "<br>";
        echo "Seats to restore: " . $num_seats . "<br>";

        // Restore the seats to the flight
        $update_flight_sql = "UPDATE flights SET available_seats = available_seats + ? WHERE flight_id = ?";
        $stmt_update_flight = $conn->prepare($update_flight_sql);
        $stmt_update_flight->bind_param("ii", $num_seats, $flight_id);

        // Execute the query to restore seats
        if ($stmt_update_flight->execute()) {
            echo "Seats restored successfully!<br>";
        } else {
            echo "Error updating flight available seats: " . $stmt_update_flight->error . "<br>";
            exit();
        }

        // Delete the booking
        $delete_booking_sql = "DELETE FROM bookings WHERE booking_id = ? AND user_id = ?";
        $stmt_delete_booking = $conn->prepare($delete_booking_sql);
        $stmt_delete_booking->bind_param("ii", $booking_id, $user_id);

        // Execute the query to delete the booking
        if ($stmt_delete_booking->execute()) {
            echo "Booking cancelled successfully!<br>";
        } else {
            echo "Error deleting booking: " . $stmt_delete_booking->error . "<br>";
            exit();
        }

        // Redirect back to the dashboard
        header("Location: user_dashboard.php?message=Booking cancelled and seats restored.");
    } else {
        echo "Invalid booking or you are not authorized to cancel this booking.";
    }
} else {
    echo "Booking ID is missing.";
}
?>
