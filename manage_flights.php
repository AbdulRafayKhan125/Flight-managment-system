<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>manage flights</title>
    <style>
        /* Global Styles */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
}

.header {
    text-align: center;
    background-color: #007bff;
    color: white;
    padding: 20px 0;
    border-radius: 8px;
    margin-bottom: 20px;
}

.header h1 {
    margin: 0;
}

.header .back-btn {
    text-decoration: none;
    color: white;
    font-size: 16px;
    background-color: #333;
    padding: 8px 20px;
    border-radius: 5px;
    margin-top: 10px;
}

.header .back-btn:hover {
    background-color: #555;
}

/* Flight Form Styles */
.flight-form {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

.flight-form h2 {
    text-align: center;
    margin-bottom: 20px;
}

.flight-form form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.flight-form input {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.flight-form button {
    padding: 10px 20px;
    font-size: 16px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.flight-form button:hover {
    background-color: #218838;
}

/* Existing Flights Table */
.existing-flights {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.existing-flights table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.existing-flights th,
.existing-flights td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}

.existing-flights th {
    background-color: #007bff;
    color: white;
}

.existing-flights tr:nth-child(even) {
    background-color: #f9f9f9;
}

.existing-flights .btn {
    padding: 8px 16px;
    background-color: #dc3545;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.existing-flights .btn:hover {
    background-color: #c82333;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
    .flight-form form {
        width: 100%;
    }

    .existing-flights table {
        font-size: 14px;
    }

    .header h1 {
        font-size: 24px;
    }
}


    </style>
</head>
<body>
    
</body>
</html>
<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'db.php';

// Handle flight addition
if (isset($_POST['add_flight'])) {
    $flight_number = $_POST['flight_number'];
    $from_location = $_POST['from_location'];
    $to_location = $_POST['to_location'];
    $seat_count = $_POST['seat_count'];
    $available_seats = $seat_count; // Initially, available seats are equal to total seats
    $price = $_POST['price'];
    $schedule = $_POST['schedule'];

    $stmt = $conn->prepare("INSERT INTO flights (flight_number, from_location, to_location, seat_count, available_seats, price, schedule) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiids", $flight_number, $from_location, $to_location, $seat_count, $available_seats, $price, $schedule);

    if ($stmt->execute()) {
        echo "Flight added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch existing flights
$query = "SELECT * FROM flights";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Flights</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="container">
        <header class="header">
            <h1>Manage Flights</h1>
            <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
        </header>

        <div class="flight-form">
            <h2>Add New Flight</h2>
            <form method="POST" action="manage_flights.php">
                <input type="text" name="flight_number" placeholder="Flight Number" required>
                <input type="text" name="from_location" placeholder="From Location" required>
                <input type="text" name="to_location" placeholder="To Location" required>
                <input type="number" name="seat_count" placeholder="Seat Count" required>
                <input type="number" step="0.01" name="price" placeholder="Ticket Price" required>
                <input type="datetime-local" name="schedule" required>
                <button type="submit" name="add_flight" class="btn">Add Flight</button>
            </form>
        </div>

        <div class="existing-flights">
            <h2>Existing Flights</h2>
            <table>
                <thead>
                    <tr>
                        <th>Flight Number</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Seats Available</th>
                        <th>Price</th>
                        <th>Schedule</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($flight = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $flight['flight_number']; ?></td>
                        <td><?php echo $flight['from_location']; ?></td>
                        <td><?php echo $flight['to_location']; ?></td>
                        <td><?php echo $flight['available_seats']; ?></td>
                        <td><?php echo $flight['price']; ?></td>
                        <td><?php echo $flight['schedule']; ?></td>
                        
                        <td><a href="delete_flight.php?flight_id=<?php echo $flight['flight_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this flight?');">Delete</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
