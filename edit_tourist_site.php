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

// Get tourist site ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tourist_sites WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $site = $result->fetch_assoc();
} else {
    die("Tourist site ID not specified.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);

    if (!empty($image)) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $sql = "UPDATE tourist_sites SET name = ?, location = ?, description = ?, image = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssi', $name, $location, $description, $image, $id);
        } else {
            echo "Error uploading image.";
            $stmt->close();
            $conn->close();
            exit();
        }
    } else {
        $sql = "UPDATE tourist_sites SET name = ?, location = ?, description = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $name, $location, $description, $id);
    }

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Tourist site updated successfully.";
    } else {
        echo "Error updating tourist site.";
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
    <title>Edit Tourist Site</title>
    <style>
        form {
            display: flex;
            flex-direction: column;
            width: 50%;
            margin: 0 auto;
        }

        input,
        textarea {
            margin-bottom: 1rem;
            padding: 0.5rem;
            font-size: 1rem;
        }

        button {
            padding: 0.5rem 1rem;
            font-size: 1rem;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h1>Edit Tourist Site</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" value="<?= htmlspecialchars($site['name']) ?>" required placeholder="Site Name" />
        <input type="text" name="location" value="<?= htmlspecialchars($site['location']) ?>" required placeholder="Location" />
        <textarea name="description" required placeholder="Description"><?= htmlspecialchars($site['description']) ?></textarea>
        <input type="file" name="image" />
        <button type="submit">Update Tourist Site</button>
    </form>
</body>

</html>