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

// Fetch flights data
$sql = "SELECT * FROM flights";
$result = $conn->query($sql);

$flights = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $flights[] = $row;
    }
}

echo json_encode($flights);

$conn->close();
?>
