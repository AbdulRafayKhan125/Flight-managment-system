<?php include 'header.php'; // Include the header ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Flight Booking</title>
    <style>
      /* General Page Styling */
body {
    margin: 0;
    font-family: Arial, sans-serif;
}

/* Hero Section */
.hero {
    background: linear-gradient(135deg, lightcyan, lightskyblue, lightpink);
    text-align: center;
    padding: 100px 20px;
    color: white;
    font-size: 2rem;
}

.hero h1 {
    font-size: 3rem;
    margin: 0;
}

.hero p {
    font-size: 1.25rem;
}

/* Loading Screen */
.loading-screen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.loader {
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-top: 4px solid lightblue;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Flights Table */
.flights {
    width: 80%;
    margin: 50px auto;
    text-align: center;
}

.flights h2 {
    font-size: 2rem;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 10px;
    border: 1px solid #ddd;
}

th {
    background-color: #007bff;
    color: white;
}

td {
    background-color: #f9f9f9;
}

a.btn-book {
    background-color: #28a745;
    color: white;
    padding: 8px 16px;
    text-decoration: none;
    border-radius: 5px;
}

a.btn-book:hover {
    background-color: #218838;
}

    </style>
</head>
<body>

<!-- Loading Animation -->
<div id="loadingScreen" class="loading-screen">
    <div class="loader"></div>
</div>

<!-- Main Content -->
<main>
    <section class="hero">
        <h1>Welcome to Flight Booking</h1>
        <p>Your journey starts here! Browse flights and book your next adventure.</p>
    </section>

    <section class="flights">
        <h2>Scheduled Flights</h2>
        <table>
            <thead>
                <tr>
                    <th>Flight Number</th>
                    <th>From Location</th>
                    <th>To Location</th>
                    <th>Seats Available</th>
                    <th>Price</th>
                    <th>Schedule</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'db.php';  // Include DB connection
                
                // Fetch available flights
                $sql = "SELECT * FROM flights WHERE available_seats > 0";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($flight = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $flight['flight_number'] . "</td>";
                        echo "<td>" . $flight['from_location'] . "</td>";
                        echo "<td>" . $flight['to_location'] . "</td>";
                        echo "<td>" . $flight['available_seats'] . "</td>";
                        echo "<td>" . $flight['price'] . "</td>";
                        echo "<td>" . $flight['schedule'] . "</td>";
                        echo "<td><a href='login.php' class='btn-book'>Login to Book</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No available flights at the moment.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</main>

<script>
    // Hide loading animation after 2 seconds
    window.onload = function() {
        setTimeout(function() {
            document.getElementById('loadingScreen').style.display = 'none';
        }, 2000);
    };
</script>

</body>
</html>
