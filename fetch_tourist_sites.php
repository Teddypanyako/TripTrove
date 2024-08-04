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

// Fetch tourist sites data
$sql = "SELECT * FROM tourist_sites";
$result = $conn->query($sql);

$sites = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $sites[] = $row;
    }
}

echo json_encode($sites);

$conn->close();
?>
