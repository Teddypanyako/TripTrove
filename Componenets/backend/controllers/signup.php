<?php
require_once 'dbconfig.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to sanitize user input
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = sanitize_input($_POST["fname"]);
    $lname = sanitize_input($_POST["lname"]);
    $email = sanitize_input($_POST["email"]);
    $username = sanitize_input($_POST["username"]);
    $password = sanitize_input($_POST["password"]);

    // Check if all details are entered
    if (empty($fname) || empty($lname) || empty($email) || empty($username) || empty($password)) {
        echo json_encode(['error' => 'Missing Details']);
        exit;
    }

    // Check if both username and password contain '@' for admin sign-up, or neither for client sign-up
    $is_admin = strpos($username, '@') !== false && strpos($password, '@') !== false;
    $is_client = strpos($username, '@') === false && strpos($password, '@') === false;

    if (!$is_admin && !$is_client) {
        echo json_encode(['error' => 'Both username and password must contain "@" for admin sign-up, or neither for client sign-up.']);
        exit;
    }

    // Check if the username already exists
    $sql = "SELECT id FROM signup WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['error' => 'Username already exists']);
        $stmt->close();
        $conn->close();
        exit;
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user into the database
    $sql = "INSERT INTO signup (fname, lname, email, username, password, role) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $role = $is_admin ? 'admin' : 'client';
    $stmt->bind_param("ssssss", $fname, $lname, $email, $username, $hashed_password, $role);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'User registered successfully!']);
        header("refresh:1;url=signup.html");
    } else {
        echo json_encode(['error' => 'Error registering user.']);
    }

    $stmt->close();
    $conn->close();
}

