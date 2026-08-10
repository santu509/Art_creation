<?php
session_start();
require_once __DIR__ . '/../includes/connection.php';
global $connect;
header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'logout') {
    session_destroy();
    echo json_encode(['status' => 'success', 'message' => 'Logged out successfully.']);
    exit;
}

// Reset Password Action (No OTP, Direct Email & Password Update)
if ($action === 'reset_password' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if (empty($email) || empty($newPassword) || empty($confirmPassword)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields.']);
        exit;
    }

    if ($newPassword !== $confirmPassword) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match. Please re-enter.']);
        exit;
    }

    if (strlen($newPassword) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 6 characters long.']);
        exit;
    }

    // Check if user email exists
    $stmt = mysqli_prepare($connect, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($res) === 0) {
        echo json_encode(['status' => 'error', 'message' => 'No account found with this email address.']);
        mysqli_stmt_close($stmt);
        exit;
    }
    mysqli_stmt_close($stmt);

    // Update Hashed Password
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    $upStmt = mysqli_prepare($connect, "UPDATE users SET password = ? WHERE email = ?");
    mysqli_stmt_bind_param($upStmt, "ss", $hashedPassword, $email);

    if (mysqli_stmt_execute($upStmt)) {
        echo json_encode(['status' => 'success', 'message' => 'Password reset successfully! You can now sign in with your new password.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update password. Please try again.']);
    }
    mysqli_stmt_close($upStmt);
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
            $imgPath = (!empty($row['image']) && file_exists(__DIR__ . '/../' . $row['image'])) ? $row['image'] : 'asset/image/default-image.jpg';
            $_SESSION['user_image'] = $imgPath;

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
