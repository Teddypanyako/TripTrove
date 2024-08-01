<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$fullName = $_POST['fullName'];
$email = $_POST['email'];
$cardNumber = $_POST['cardNumber'];
$expDate = $_POST['expDate'];
$cvv = $_POST['cvv'];
$amount = $_POST['amount'];

// Validate form data (basic example)
if (empty($fullName) || empty($email) || empty($cardNumber) || empty($expDate) || empty($cvv) || empty($amount)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit();
}

// Insert payment details into database (assuming there's a payments table)
$sql = "INSERT INTO payments (full_name, email, card_number, exp_date, cvv, amount) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $fullName, $email, $cardNumber, $expDate, $cvv, $amount);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Payment processed successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error processing payment.']);
}

$stmt->close();
$conn->close();
