<?php
require_once '../backend/dbconfig/dbconfig.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $conn->real_escape_string($_POST['fullName']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $checkInDate = $conn->real_escape_string($_POST['checkInDate']);
    $checkOutDate = $conn->real_escape_string($_POST['checkOutDate']);
    $guests = $conn->real_escape_string($_POST['guests']);

    // Insert form data into database
    $sql = "INSERT INTO sitebooking (full_name, email, phone, check_in_date, check_out_date, guests) 
            VALUES ('$fullName', '$email', '$phone', '$checkInDate', '$checkOutDate', '$guests')";

    if ($conn->query($sql) === TRUE) {
        echo "Reservation submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();

