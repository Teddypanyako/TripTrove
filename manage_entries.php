<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$type = $_POST['type'];
$action = $_POST['action'];
$id = $_POST['id'];

if ($action === 'delete') {
    if ($type === 'flight') {
        $sql = "DELETE FROM flights WHERE id = $id";
    } elseif ($type === 'hotel') {
        $sql = "DELETE FROM hotels WHERE id = $id";
    } elseif ($type === 'tourist_site') {
        $sql = "DELETE FROM tourist_sites WHERE id = $id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>
