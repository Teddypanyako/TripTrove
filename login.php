<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username_email = trim($_POST['username-email']);
    $password = trim($_POST['password']);

    if (empty($username_email) || empty($password)) {
        echo "Username and Password are required.";
        exit;
    }

    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "project";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, password, role FROM signup WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username_email, $username_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashedPassword, $role);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username_email;
            $_SESSION['role'] = $role;

            if ($role === 'admin') {
                header("Location: admin.php");
            } else {
                echo "Login successful! Redirecting to user page...";
                header("refresh:3; url=index.html");
            }
            exit;
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "No user found with that username or email.";
    }

    $stmt->close();
    $conn->close();
}
