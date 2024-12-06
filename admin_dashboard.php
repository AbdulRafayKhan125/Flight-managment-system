<?php
session_start();
include 'header.php';
// Ensure the user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'db.php';

// Fetch data (flight count, ticket sales, etc.)
$flightCountQuery = "SELECT COUNT(*) FROM flights";
$flightCountResult = $conn->query($flightCountQuery);
$flightCount = $flightCountResult->fetch_row()[0];

// Fetch total sales (bookings)
$salesQuery = "SELECT COUNT(*) FROM bookings"; 
$salesResult = $conn->query($salesQuery);
$salesCount = $salesResult->fetch_row()[0];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            margin-top:35vh;
        }
        header {
            background-color: #333;
            color: white;
            padding: 15px 0;
            text-align: center;
        }
        .dashboard {
            display: flex;
            /* align-items:center; */
            /* margin-top:20vh; */
            justify-content: space-around;
            margin: 30px;
        }
        .card {
            background-color: white;
            padding: 20px;
            width: 30%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-radius: 8px;
        }
        .card h3 {
            margin: 10px 0;
        }
        .card p {
            font-size: 1.5em;
            margin: 20px 0;
        }
        .card a {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .card a:hover {
            background-color: #45a049;
        }
        .logout-btn {
            display: block;
            width: 100px;
            margin: 20px auto;
            text-align: center;
            padding: 10px;
            background-color: red;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .logout-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

    <header>
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <h2>Admin Dashboard</h2>
    </header>

    <div class="dashboard">
        <!-- Total Flights Card -->
        <div class="card">
            <h3>Total Flights</h3>
            <p><?php echo $flightCount; ?></p>
            <a href="manage_flights.php">Manage Flights</a>
        </div>

        <!-- Total Tickets Sold Card -->
        <div class="card">
            <h3>Total Tickets Sold</h3>
            <p><?php echo $salesCount; ?></p>
            <a href="manage_flights.php">View Bookings</a>
        </div>

        <!-- Manage Flights Card -->
        <div class="card">
            <h3>Manage Flights</h3>
            <a href="manage_flights.php">View / Add Flights</a>
        </div>
    </div>

    <!-- Logout Button -->
    <a href="logout.php" class="logout-btn">Logout</a>

</body>
</html>
