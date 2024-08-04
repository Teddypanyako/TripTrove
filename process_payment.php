<?php
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve form data
    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $cardNumber = trim($_POST['cardNumber']);
    $expDate = trim($_POST['expDate']);
    $cvv = trim($_POST['cvv']);
    $amount = trim($_POST['amount']);

    // Validate form data
    if (empty($fullName) || empty($email) || empty($cardNumber) || empty($expDate) || empty($cvv) || empty($amount)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit;
    }

    // Here you would integrate with a payment gateway to process the payment
    // For demonstration, we will just return a success message

    echo json_encode(['status' => 'success', 'message' => 'Payment processed successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
