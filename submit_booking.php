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
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$dob = $_POST['dob'];
$email = $_POST['email'];
$area_code = $_POST['area_code'];
$phone_number = $_POST['phone_number'];
$city = $_POST['city'];
$address = $_POST['address'];
$postal_code = $_POST['postal_code'];
$departure_date = $_POST['departure_date'];
$departure_time = $_POST['departure_time'];
$return_date = $_POST['return_date'];
$return_time = $_POST['return_time'];
$departing_from_country = $_POST['departing_from_country'];
$departing_from_city = $_POST['departing_from_city'];
$destination_country = $_POST['destination_country'];
$destination_city = $_POST['destination_city'];
$number_of_seats = $_POST['number_of_seats'];
$trip_route = $_POST['trip_route'];

// Prepare an SQL statement to insert the form data into the database
$sql = "INSERT INTO flight_bookings (
    first_name, last_name, dob, email, area_code, phone_number, city, address, postal_code,
    departure_date, departure_time, return_date, return_time,
    departing_from_country, departing_from_city, destination_country, destination_city,
    number_of_seats, trip_route
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssssssssssssss",
    $first_name,
    $last_name,
    $dob,
    $email,
    $area_code,
    $phone_number,
    $city,
    $address,
    $postal_code,
    $departure_date,
    $departure_time,
    $return_date,
    $return_time,
    $departing_from_country,
    $departing_from_city,
    $destination_country,
    $destination_city,
    $number_of_seats,
    $trip_route
);

// Execute the statement
if ($stmt->execute()) {
    echo "New flight added successfully";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
