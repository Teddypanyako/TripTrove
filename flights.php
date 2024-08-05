<?php
require_once '../backend/dbconfig/dbconfig.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $travel_date = $_POST['travel_date'];
    $travel_time = $_POST['travel_time'];
    $departure_from = $_POST['departure_from'];
    $destination = $_POST['destination'];
    $class = $_POST['class'];
    $business_class_price = $_POST['business_class_price'];
    $economy_class_price = $_POST['economy_class_price'];

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
        $stmt = $conn->prepare("INSERT INTO flights (travel_date, departure_from, destination, class, business_class_price, economy_class_price, image_path, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssdds", $travel_date, $travel_time, $departure_from, $destination, $class, $business_class_price, $economy_class_price, $image_path);

        if ($stmt->execute()) {
            echo "New flight record created successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Fetch flight data
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql = "SELECT travel_date, departure_from, destination, class, business_class_price, economy_class_price, image_path FROM flights";
    $result = $conn->query($sql);

    $flights = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $flights[] = $row;
        }
    } 

    echo json_encode($flights);
}

$conn->close();
?>
