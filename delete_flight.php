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

// Delete flight based on ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM flights WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<p style='display:flex;justify-content:center;align-items:center;color:green;font-size:3rem;font-weight:bold;'>Flight Deleted Successfully</p>";
        echo "header('refresh:2; url=admin.php')";
    } else {
        echo "<p style='display:flex;justify-content:center;align-items:center;color:red;font-size:3rem;font-weight:bold;'>Error Deleting Flight </p>";
    }

    $stmt->close();
}

// Delete hotel based on ID
if (isset($_GET['hotel_id'])) {
    $hotel_id = $_GET['hotel_id'];
    $sql = "DELETE FROM hotels WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $hotel_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<p style='display:flex;justify-content:center;align-items:center;color:green;font-size:3rem;font-weight:bold;'>Hotel Deleted Succesfully</p>";
        echo "header('refresh:2; url=admin.php')";
    } else {
        echo "<p style='display:flex;justify-content:center;align-items:center;color:red;font-size:3rem;font-weight:bold;'>Error Deleting Hotel</p>";
    }

    $stmt->close();
}

// Delete tourist site based on ID
if (isset($_GET['tourist_site_id'])) {
    $tourist_site_id = $_GET['tourist_site_id'];
    $sql = "DELETE FROM tourist_sites WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $tourist_site_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<p style='display:flex;justify-content:center;align-items:center;color:green;font-size:3rem;font-weight:bold;'>Tourist site deleted successfully.</p>";
        header("refresh:2; url=admin.php");
        exit;
    } else {
        echo "<p style='display:flex;justify-content:center;align-items:center;color:red;font-size:3rem;font-weight:bold;'>Error Deleting Tourist Site</p>";
    }

    $stmt->close();
}

$conn->close();
