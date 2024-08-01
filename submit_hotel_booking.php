<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "project"; // Database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$full_name = $_POST['fullName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$check_in_date = $_POST['checkInDate'];
$check_out_date = $_POST['checkOutDate'];
$guests = $_POST['guests'];
$rooms = $_POST['rooms']; // Make sure to change the name to match your input
$room_type = $_POST['roomType'];

// Prepare an SQL statement to insert the form data into the database
$sql = "INSERT INTO hotel_bookings (
    full_name, email, phone, check_in_date, check_out_date, guests, rooms, room_type
) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssss",
    $full_name, $email, $phone, $check_in_date, $check_out_date, $guests, $rooms, $room_type
);

// Execute the statement
if ($stmt->execute()) {
    echo "Hotel reservation submitted successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
