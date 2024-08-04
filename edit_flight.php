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

// Get flight ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM flights WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $flight = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $flight_name = $_POST['flight_name'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $price = $_POST['price'];
    $travel_date = $_POST['travel_date'];
    $class = $_POST['class'];
    $image = $_FILES['image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $sql = "UPDATE flights SET flight_name = ?, origin = ?, destination = ?, price = ?, travel_date = ?, class = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssssi', $flight_name, $origin, $destination, $price, $travel_date, $class, $image, $id);
        $stmt->execute();
    } else {
        echo "Error uploading image.";
    }

    if ($stmt->affected_rows > 0) {
        echo "Flight updated successfully.";
    } else {
        echo "Error updating flight.";
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
    <title>Edit Flight</title>
    <style>
        form {
            display: flex;
            flex-direction: column;
            width: 50%;
            margin: 0 auto;
        }

        input,
        select {
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
    <h1>Edit Flight</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="flight_name" value="<?= $flight['flight_name'] ?>" required />
        <input type="text" name="origin" value="<?= $flight['origin'] ?>" required />
        <input type="text" name="destination" value="<?= $flight['destination'] ?>" required />
        <input type="number" name="price" value="<?= $flight['price'] ?>" required />
        <input type="date" name="travel_date" value="<?= $flight['travel_date'] ?>" required />
        <select name="class" required>
            <option value="business" <?= $flight['class'] === 'business' ? 'selected' : '' ?>>Business</option>
            <option value="economy" <?= $flight['class'] === 'economy' ? 'selected' : '' ?>>Economy</option>
        </select>
        <input type="file" name="image" />
        <button type="submit">Update Flight</button>
    </form>
</body>

</html>