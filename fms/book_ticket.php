<?php
session_start();
include 'db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$flight_id = $_POST['flight_id'];
$num_seats = $_POST['num_seats'];

// Fetch the flight price and available seats
$sql = "SELECT price, available_seats FROM flights WHERE flight_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $flight_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($price, $available_seats);
$stmt->fetch();

// Check if there are enough available seats
if ($num_seats > $available_seats) {
    echo "Not enough seats available. Please choose fewer seats.";
    exit();
}

// Calculate the total payment
$total_payment = $num_seats * $price;

// Insert the booking into the bookings table
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "INSERT INTO bookings (user_id, flight_id, num_seats, total_payment, booking_date) 
            VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $user_id, $flight_id, $num_seats, $total_payment);
    if ($stmt->execute()) {
        // Update the available seats in the flights table
        $update_flight_sql = "UPDATE flights SET available_seats = available_seats - ? WHERE flight_id = ?";
        $stmt_update_flight = $conn->prepare($update_flight_sql);
        $stmt_update_flight->bind_param("ii", $num_seats, $flight_id);
        $stmt_update_flight->execute();

        // Redirect to user dashboard after successful booking
        header("Location: user_dashboard.php?message=Booking successful");
    } else {
        echo "Error: Unable to book flight.";
    }
}
?>
