<?php
// Database connection
$host = 'localhost';
$db = 'project';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch hotels data
$sql = "SELECT * FROM hotels";
$result = $conn->query($sql);

$hotels = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $hotels[] = $row;
    }
}

echo json_encode($hotels);

$conn->close();
?>
