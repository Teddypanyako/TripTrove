<?php
require_once '../backend/dbconfig/dbconfig.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $checkInDate = $_POST['checkInDate'];
    $checkOutDate = $_POST['checkOutDate'];
    $guests = $_POST['guests'];
    $rooms = $_POST['rooms'];
    $roomType = $_POST['roomType'];

    $sql = "INSERT INTO hotel_booking (fullName, email, phone, checkInDate, checkOutDate, guests, rooms, roomType)
    VALUES ('$fullName', '$email', '$phone', '$checkInDate', '$checkOutDate', '$guests', '$rooms', '$roomType')";

    if ($conn->query($sql) === TRUE) {
        echo "New reservation created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();

