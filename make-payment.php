<?php
require_once '../backend/dbconfig/dbconfig.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $cardNumber = $_POST['cardNumber'];
    $expDate = $_POST['expDate'];
    $cvv = $_POST['cvv'];
    $amount = $_POST['amount'];

    // Basic validation
    if (strlen($cardNumber) !== 16 || !is_numeric($cardNumber)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid card number. It should be 16 digits.']);
        exit;
    }

    if (strlen($cvv) !== 3 || !is_numeric($cvv)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid CVV. It should be 3 digits.']);
        exit;
    }

    // Insert data into database
    $stmt = $conn->prepare("INSERT INTO make-payments (fullName, email, cardNumber, expDate, cvv, amount) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssd", $fullName, $email, $cardNumber, $expDate, $cvv, $amount);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Payment submitted successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error processing payment. Please try again later.']);
    }

    $stmt->close();
    $conn->close();
}

