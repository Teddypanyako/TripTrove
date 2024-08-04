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

// Get hotel ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM hotels WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $hotel = $result->fetch_assoc();
} else {
    die("Hotel ID not specified.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $services = $_POST['services'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);

    if (!empty($image)) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $sql = "UPDATE hotels SET name = ?, location = ?, services = ?, description = ?, image = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssssi', $name, $location, $services, $description, $image, $id);
        } else {
            echo "Error uploading image.";
            $stmt->close();
            $conn->close();
            exit();
        }
    } else {
        $sql = "UPDATE hotels SET name = ?, location = ?, services = ?, description = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssi', $name, $location, $services, $description, $id);
    }

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Hotel updated successfully.";
    } else {
        echo "Error updating hotel.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Hotel</title>
</head>
<style>
    form {
        display: flex;
        flex-direction: column;
        width: 50%;
        margin: 0 auto;
    }

    input,
    textarea {
        margin-bottom: 10px;
        padding: 5px;
    }

    button {
        padding: 5px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
    }
</style>

<body>
    <h1>Edit Hotel</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" value="<?= htmlspecialchars($hotel['name']) ?>" required placeholder="Hotel Name" />
        <input type="text" name="location" value="<?= htmlspecialchars($hotel['location']) ?>" required placeholder="Location" />
        <textarea name="services" required placeholder="Services"><?= htmlspecialchars($hotel['services']) ?></textarea>
        <textarea name="description" required placeholder="Description"><?= htmlspecialchars($hotel['description']) ?></textarea>
        <input type="file" name="image" />
        <button type="submit">Update Hotel</button>
    </form>
</body>

</html>