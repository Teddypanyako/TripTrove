<?php
require_once('dbconfig.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Check if the user is an admin
    $isAdmin = strpos($username, '@') !== false && strpos($password, '@') !== false;

    // Check if the user is a client
    $isClient = strpos($username, '@') === false && strpos($password, '@') === false;

    if (!$isAdmin && !$isClient) {
        echo json_encode(["status" => "error", "message" => 'Both username and password must contain "@" for admin login, or neither for client login.']);
        exit();
    }

    // SQL query to find the user
    $sql = "SELECT * FROM login WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            echo json_encode(["status" => "success", "message" => 'Login successful!', "role" => $user['role']]);
        } else {
            echo json_encode(["status" => "error", "message" => 'Invalid password.']);
        }
    } else {
        echo json_encode(["status" => "error", "message" => 'No user found with this username.']);
    }

    $stmt->close();
}
$conn->close();

?>