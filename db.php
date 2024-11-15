<?php
$host = 'localhost';
$db = 'flight_management';
$user = 'root'; // default username for localhost
$pass = ''; // leave empty if there's no password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>