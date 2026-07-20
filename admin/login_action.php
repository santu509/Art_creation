<?php
session_start();
require_once(__DIR__ . "/../connection.php");
/** @var mysqli $connect */

// Handle Logout via GET request
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Set header for JSON response
header('Content-Type: application/json');

// Ensure request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$action = isset($_POST['action']) ? trim($_POST['action']) : '';

// Route the action
if ($action === 'login') {
    handleLogin($connect);
} elseif ($action === 'reset_password') {
    handleResetPassword($connect);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Unknown action requested.']);
}

exit;


// ==========================================
// FUNCTION: Handle Admin Login
// ==========================================
function handleLogin($connect)
{
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter both Username and Password.']);
        return;
    }

    // Input Security (Prevent SQL Injection using mysqli_real_escape_string)
    $safeUsername = mysqli_real_escape_string($connect, $username);

    // Query admin table by username
    $sql = "SELECT id, username, password FROM admin WHERE username = '$safeUsername' LIMIT 1";
    $result = mysqli_query($connect, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);

        // Verify Password
        if (password_verify($password, $admin['password'])) {
            // Login Successful - Set Sessions
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];

            echo json_encode([
                'status' => 'success',
                'message' => 'Login successful! Redirecting...',
                'redirect' => file_exists('dashboard.php') ? 'dashboard.php' : 'index.php'
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid password. Please try again.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Admin username not found.']);
    }
}


// ==========================================
// FUNCTION: Handle Admin Password Reset
// ==========================================
function handleResetPassword($connect)
{
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $new_password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Validation Checks
    if (empty($username) || empty($new_password) || empty($confirm_password)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required to reset password.']);
        return;
    }

    if ($new_password !== $confirm_password) {
        echo json_encode(['status' => 'error', 'message' => 'New Password and Confirm Password do not match.']);
        return;
    }

    if (strlen($new_password) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 6 characters long.']);
        return;
    }

    // Input Security
    $safeUsername = mysqli_real_escape_string($connect, $username);

    // Step 1: Check if admin exists in admin table
    $sqlCheck = "SELECT id FROM admin WHERE username = '$safeUsername' LIMIT 1";
    $resultCheck = mysqli_query($connect, $sqlCheck);

    if ($resultCheck && mysqli_num_rows($resultCheck) > 0) {
        // Admin found, update password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $safePassword = mysqli_real_escape_string($connect, $hashed_password);

        $sqlUpdate = "UPDATE admin SET password = '$safePassword' WHERE username = '$safeUsername'";

        if (mysqli_query($connect, $sqlUpdate)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Password updated successfully! You can now log in with your new password.'
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update password. Database error.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No admin account found with that username.']);
    }
}
