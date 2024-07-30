<?php
require_once '../backend/dbconfig/dbconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstName = $_POST['Fname'];
    $lastName = $_POST['Lname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate form data
    if (empty($firstName) || empty($lastName) || empty($email) || empty($username) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    // Check if email or username already exists
    $sql = "SELECT id FROM users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Email or username already exists.";
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert user into the database
    $sql = "INSERT INTO signup (first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $username, $hashedPassword);

    if ($stmt->execute()) {
        echo "User created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

