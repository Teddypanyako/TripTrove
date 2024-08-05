<?php
require_once '../backend/dbconfig/dbconfig.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $location = $_POST['location'];
    $services = $_POST['services'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $image_path = "";
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["image"]["size"] > 5000000) { // 5MB limit
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Insert data into the database
    if ($uploadOk == 1) {
        $stmt = $conn->prepare("INSERT INTO hotels (name, location, price, services, description, image_path, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssss", $name, $location, $price, $services, $description, $image_path);

        if ($stmt->execute()) {
            echo "New hotel record created successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Fetch hotel data
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql = "SELECT name, location, price, services, description, image_path FROM hotels";
    $result = $conn->query($sql);

    $hotels = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $hotels[] = $row;
        }
    }

    echo json_encode($hotels);
}

$conn->close();
