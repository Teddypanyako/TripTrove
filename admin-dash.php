<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $flight_name = $_POST['flight_name'];
        $origin = $_POST['origin'];
        $destination = $_POST['destination'];
        $price = $_POST['price'];
        $travel_date = $_POST['travel_date'];
        $class = $_POST['class'];

        // Handle file upload
        $image = $_FILES['image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo "The file " . htmlspecialchars(basename($image)) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }


        // Insert into database
        $sql = "INSERT INTO flights (flight_name, origin, destination, price, travel_date, class, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $flight_name, $origin, $destination, $price, $travel_date, $class, $image);

        if ($stmt->execute()) {
            echo "New flight added successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
