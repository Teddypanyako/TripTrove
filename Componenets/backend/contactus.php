<?php
require_once '../backend/dbconfig/dbconfig.php';
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'])) {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        echo "Thank you for contacting us! We will get back to you shortly.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle admin reply
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply_message'])) {
    // Get reply data
    $contact_id = $_POST['contact_id'];
    $reply_message = $_POST['reply_message'];

    // Insert reply into the database
    $stmt = $conn->prepare("INSERT INTO replies (contact_id, reply_message) VALUES (?, ?)");
    $stmt->bind_param("is", $contact_id, $reply_message);

    if ($stmt->execute()) {
        echo "Reply sent successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch all contacts and their replies
$sql = "SELECT contacts.id, contacts.name, contacts.email, contacts.message, contacts.created_at, replies.reply_message, replies.replied_at
        FROM contacts
        LEFT JOIN replies ON contacts.id = replies.contact_id";
$result = $conn->query($sql);
