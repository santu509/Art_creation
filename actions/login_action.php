<?php
session_start();
require_once __DIR__ . '/../includes/connection.php';
global $connect;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../vendor/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/PHPMailer/src/SMTP.php';

if (!defined('SMTP_HOST')) define('SMTP_HOST', 'smtp.gmail.com');
if (!defined('SMTP_PORT')) define('SMTP_PORT', 587);
if (!defined('SMTP_USER')) define('SMTP_USER', 'siddhaartcreation@gmail.com');
if (!defined('SMTP_PASS')) define('SMTP_PASS', 'ejvs frll todh hcif');
if (!defined('SMTP_SECURE')) define('SMTP_SECURE', 'tls');
if (!defined('SMTP_FROM_EMAIL')) define('SMTP_FROM_EMAIL', 'siddhaartcreation@gmail.com');
if (!defined('SMTP_FROM_NAME')) define('SMTP_FROM_NAME', 'Sidda Art Creation');

header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'logout') {
    session_destroy();
    echo json_encode(['status' => 'success', 'message' => 'Logged out successfully.']);
    exit;
}

// Action: Send Reset OTP
if ($action === 'send_reset_otp' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    if (empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter your email address.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
        exit;
    }

    // Rate limiting: 5 minutes resend limit
    if (isset($_SESSION['reset_otp_sent_time']) && isset($_SESSION['reset_email']) && strcasecmp($_SESSION['reset_email'], $email) === 0) {
        $elapsed = time() - $_SESSION['reset_otp_sent_time'];
        if ($elapsed < 300) {
            $remaining = 300 - $elapsed;
            $minutes = floor($remaining / 60);
            $seconds = $remaining % 60;
            echo json_encode([
                'status' => 'error',
                'message' => sprintf("Please wait %02d:%02d before requesting a new OTP.", $minutes, $seconds)
            ]);
            exit;
        }
    }

    // Check if user email exists in database
    $stmt = mysqli_prepare($connect, "SELECT name FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($res)) {
        $userName = $row['name'];
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No account found with this email address.']);
        mysqli_stmt_close($stmt);
        exit;
    }
    mysqli_stmt_close($stmt);

    // Generate 6-digit OTP
    $otp = rand(100000, 999999);

    // Save in session
    $_SESSION['reset_otp'] = $otp;
    $_SESSION['reset_email'] = $email;
    $_SESSION['reset_otp_sent_time'] = time();

    // Send email via PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = SMTP_SECURE === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($email, $userName);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Verification Code - Sidda Art Creation';
        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; background-color: #F5F2ED; padding: 40px; color: #3A3530; max-width: 600px; margin: 0 auto; border-radius: 12px;'>
                <h2 style='color: #B8860B; border-bottom: 2px solid #C5A880; padding-bottom: 15px; font-weight: normal;'>Password Reset Request</h2>
                <p>Hello <strong>{$userName}</strong>,</p>
                <p>We received a request to reset your password for your <strong>Sidda Art Creation</strong> account. Please use the 6-digit verification code below to complete your password reset:</p>
                <div style='background-color: #FFFFFF; border: 1px solid #E5E1DB; border-radius: 8px; padding: 20px; text-align: center; margin: 25px 0;'>
                    <span style='font-size: 2.2rem; font-weight: bold; letter-spacing: 5px; color: #3A3530;'>{$otp}</span>
                </div>
                <p style='font-size: 0.85rem; color: #8C857E;'>This OTP is valid for 5 minutes. If you did not request a password reset, please ignore this email.</p>
                <hr style='border: none; border-top: 1px solid #E5E1DB; margin: 30px 0;'>
                <p style='font-size: 0.8rem; color: #A59E96; text-align: center;'>© 2026 Sidda Art Creation. All rights reserved.</p>
            </div>
        ";

        $mail->send();
        echo json_encode(['status' => 'success', 'message' => 'A 6-digit OTP code has been sent to your email address!']);
    } catch (\Throwable $e) {
        $errMsg = $mail->ErrorInfo ?: $e->getMessage();
        echo json_encode(['status' => 'error', 'message' => "Mailer Error: {$errMsg}"]);
    }
    exit;
}

// Action: Reset Password with OTP Verification
if ($action === 'reset_password' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $otp = isset($_POST['otp']) ? trim($_POST['otp']) : '';
    $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if (empty($email) || empty($otp) || empty($newPassword) || empty($confirmPassword)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields including the OTP.']);
        exit;
    }

    if (!isset($_SESSION['reset_otp']) || !isset($_SESSION['reset_email'])) {
        echo json_encode(['status' => 'error', 'message' => 'No active OTP session. Please request a new OTP.']);
        exit;
    }

    if (strcasecmp($_SESSION['reset_email'], $email) !== 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email mismatch. Please request a new OTP.']);
        exit;
    }

    if (isset($_SESSION['reset_otp_sent_time']) && (time() - $_SESSION['reset_otp_sent_time'] > 300)) {
        echo json_encode(['status' => 'error', 'message' => 'OTP has expired. Please request a new OTP code.']);
        exit;
    }

    if ($_SESSION['reset_otp'] != $otp) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid OTP code. Please enter the correct verification code.']);
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
        unset($_SESSION['reset_otp']);
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_otp_sent_time']);
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
