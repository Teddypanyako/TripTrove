<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Database connection
$host = 'localhost';
$db = 'project';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
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
    $upload_ok = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is a valid image
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check === false) {
        echo "File is not an image.";
        $upload_ok = 0;
    }

    // Check file size
    if ($_FILES['image']['size'] > 5000000) {
        echo "<p style='text-align: center; font-weight: bold; color: red;'>Sorry, your file is too large.</p>";
        $upload_ok = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "<p style='text-align: center; font-weight: bold; color: red;'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
        $upload_ok = 0;
    }

    // Check if $upload_ok is set to 0 by an error
    if ($upload_ok == 0) {
        echo "<p style='text-align: center; font-weight: bold; color: red;'>Sorry, your file was not uploaded.</p>";
    } else {
        // Check for upload errors
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            echo "Error uploading file: " . $_FILES['image']['error'];
        } else {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Insert into database
                $sql = "INSERT INTO flights (flight_name, origin, destination, price, travel_date, class, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sssssss', $flight_name, $origin, $destination, $price, $travel_date, $class, $image);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo "<p style='text-align: center; font-weight: bold; color: green;'>Flight added successfully.</p>";
                    header("refresh:2; url=admin.php");
                } else {
                    echo "<p style='text-align: center; font-weight: bold; color: red;'>Error adding flight.</p>";
                }

                $stmt->close();
            } else {
                echo "<p style='text-align: center; font-weight: bold; color: red;'>Sorry, there was an error uploading your file.</p>";
            }
        }
    }
}

$conn->close();
