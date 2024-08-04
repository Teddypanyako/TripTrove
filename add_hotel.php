<?php
// Database connection
$host = 'localhost';
$db = 'project';
$user = 'root';
$pass = ''; // Default password, change as needed
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $location = htmlspecialchars($_POST['location']);
    $services = htmlspecialchars($_POST['services']);
    $description = htmlspecialchars($_POST['description']);

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);

        // Validate file type (e.g., allow only JPEG and PNG)
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png'];
        if (!in_array($file_type, $allowed_types)) {
            die("Error: Only JPG, JPEG, and PNG files are allowed.");
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Insert into database
            $sql = "INSERT INTO hotels (name, location, services, description, image) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssss', $name, $location, $services, $description, $image);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Hotel added successfully.";
            } else {
                echo "Error adding hotel.";
            }

            $stmt->close();
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "No file uploaded or upload error.";
    }
}

$conn->close();
