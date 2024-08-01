<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all flights
$sql = "SELECT * FROM flights";
$result = $conn->query($sql);

// Initialize an empty array to hold flight data
$data = [];

if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
} else {
    // Handle SQL query error
    $data = ['error' => 'Failed to fetch data'];
}

$conn->close();

// Return JSON data
header('Content-Type: application/json');
echo json_encode($data);
?>
