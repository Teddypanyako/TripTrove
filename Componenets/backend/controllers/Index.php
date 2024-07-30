<?php
require_once '../backend/dbconfig/dbconfig.php';

// Include your database connection file
require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    $email = trim($_POST['email']);

    // Validate the email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit();
    }

    // Prepare the SQL statement to insert the email into the subscriptions table
    $sql = "INSERT INTO subscriptions (email, subscribed_at) VALUES (?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);

    if ($stmt->execute()) {
        echo "Subscription successful!";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Handle data fetching

    // Prepare the SQL statement to fetch subscribed emails
    $sql = "SELECT email, subscribed_at FROM subscriptions ORDER BY subscribed_at DESC";
    $result = $conn->query($sql);

    $subscriptions = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $subscriptions[] = $row;
        }
    }

    // Return the data as JSON
    header('Content-Type: application/json');
    echo json_encode($subscriptions);
}

$conn->close();

