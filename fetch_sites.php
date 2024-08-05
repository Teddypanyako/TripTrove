<?php
header('Content-Type: application/json');

$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "project";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, location, price, description, image FROM tourist_sites";
$result = $conn->query($sql);

$tourist_sites = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tourist_sites[] = $row;
    }
}

echo json_encode($tourist_sites);

$conn->close();
