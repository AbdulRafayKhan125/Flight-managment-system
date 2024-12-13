<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'db.php';

// Check if flight_id is passed in the URL
if (isset($_GET['flight_id'])) {
    $flight_id = $_GET['flight_id'];

    // Prepare and execute delete statement for the flight
    $stmt = $conn->prepare("DELETE FROM flights WHERE flight_id = ?");
    $stmt->bind_param("i", $flight_id);

    if ($stmt->execute()) {
        // Redirect to manage flights page with a success message
        header("Location: manage_flights.php?message=Flight deleted successfully");
    } else {
        // Error handling if deletion fails
        echo "Error: " . $stmt->error;
    }
} else {
    // Redirect back if no flight_id is specified
    header("Location: manage_flights.php?message=Flight ID not provided");
}
?>
