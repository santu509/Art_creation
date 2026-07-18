<?php
session_start();
require_once 'connection.php';

header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'logout') {
    session_destroy();
    echo json_encode(['status' => 'success', 'message' => 'Logged out successfully.']);
    exit;
}

// Default Login action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter Email and Password.']);
        exit;
    }

    $stmt = mysqli_prepare($connect, "SELECT id, name, email, password, image FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            // Log in success
            $_SESSION['is_logged_in'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_image'] = !empty($row['image']) ? $row['image'] : 'asset/image/default-image.jpg';

            echo json_encode(['status' => 'success', 'message' => 'Access Granted. Welcome back!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid password. Please try again.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email address not found.']);
    }
    mysqli_stmt_close($stmt);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
?>
