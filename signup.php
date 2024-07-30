<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $Fname = trim($_POST['Fname']);
    $Lname = trim($_POST['Lname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($Fname) || empty($Lname) || empty($email) || empty($username) || empty($password)) {
        echo "All fields are required.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "project";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id FROM signup WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Email or Username already exists.";
        $stmt->close();
        $conn->close();
        exit;
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO signup (fname, lname, email, username, password, role) VALUES (?, ?, ?, ?, ?, 'client')");
    $stmt->bind_param("sssss", $Fname, $Lname, $email, $username, $hashedPassword);

    if ($stmt->execute()) {
        echo "Sign up successful!";
        header("refresh:3; url=signup.html");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
sleep(3);
