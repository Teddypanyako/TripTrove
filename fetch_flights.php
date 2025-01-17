<?php
header('Content-Type: application/json');

$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "project";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$sql = "SELECT id, flight_name, origin, destination, class, price, travel_date, image FROM flights";
$result = $conn->query($sql);

$flights = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $flights[] = $row;
    }
} else {
    echo json_encode(["error" => "No flights found"]);
    exit;
}

$conn->close();
echo json_encode($flights);
