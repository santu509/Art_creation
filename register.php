<?php
session_start();
require_once 'connection.php';
/** @var mysqli $connect */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'priyabratabera67@gmail.com');
define('SMTP_PASS', 'qpip ylxz wxwt awlk');
define('SMTP_SECURE', 'tls');
define('SMTP_FROM_EMAIL', 'priyabratabera67@gmail.com');
define('SMTP_FROM_NAME', 'Sidda Art Creation');

header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'send_otp':
        handleSendOtp($connect);
        break;
    case 'verify_otp':
        handleVerifyOtp();
        break;
    case 'register':
        handleRegister($connect);
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}

function handleSendOtp($connect)
{
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    if (empty($name) || empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter Full Name and Email.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
        exit;
    }

    // Enforce 5-minute resend limit on backend
    if (isset($_SESSION['otp_sent_time'])) {
        $elapsed = time() - $_SESSION['otp_sent_time'];
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

    // Check if email already exists
    $stmt = mysqli_prepare($connect, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email is already registered! Please sign in.']);
        mysqli_stmt_close($stmt);
        exit;
    }
    mysqli_stmt_close($stmt);

    // Generate 6-digit OTP
    $otp = rand(100000, 999999);

    // Save state in Session
    $_SESSION['verify_otp'] = $otp;
    $_SESSION['verify_email'] = $email;
    $_SESSION['verify_name'] = $name;
    $_SESSION['email_verified'] = false;
    $_SESSION['otp_sent_time'] = time();

    // Initialize PHPMailer
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
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email - Sidda Art Creation';
        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; background-color: #F5F2ED; padding: 40px; color: #3A3530; max-width: 600px; margin: 0 auto; border-radius: 12px;'>
                <h2 style='color: #B8860B; border-bottom: 2px solid #C5A880; padding-bottom: 15px; font-weight: normal;'>Verify Your Email Address</h2>
                <p>Hello <strong>{$name}</strong>,</p>
                <p>Thank you for initiating your registration with <strong>Sidda Art Creation</strong>. To continue, please verify your email address using the 6-digit verification code below:</p>
                <div style='background-color: #FFFFFF; border: 1px solid #E5E1DB; border-radius: 8px; padding: 20px; text-align: center; margin: 25px 0;'>
                    <span style='font-size: 2.2rem; font-weight: bold; letter-spacing: 5px; color: #3A3530;'>{$otp}</span>
                </div>
                <p style='font-size: 0.85rem; color: #8C857E;'>This code is valid for 5 minutes. If you did not make this request, please disregard this email.</p>
                <hr style='border: none; border-top: 1px solid #E5E1DB; margin: 30px 0;'>
                <p style='font-size: 0.8rem; color: #A59E96; text-align: center;'>© 2026 Sidda Art Creation. All rights reserved.</p>
            </div>
        ";

        $mail->send();
        echo json_encode(['status' => 'success', 'message' => 'A 6-digit OTP code has been sent to your email!']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => "Mailer Error: {$mail->ErrorInfo}"]);
    }
}

function handleVerifyOtp()
{
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $otp = isset($_POST['otp']) ? trim($_POST['otp']) : '';

    if (empty($email) || empty($otp)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter the OTP verification code.']);
        exit;
    }

    if (
        isset($_SESSION['verify_otp']) && $_SESSION['verify_otp'] == $otp &&
        isset($_SESSION['verify_email']) && strcasecmp($_SESSION['verify_email'], $email) == 0
    ) {
        $_SESSION['email_verified'] = true;
        echo json_encode(['status' => 'success', 'message' => 'Email address verified successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid OTP code. Please enter the correct verification code.']);
    }
}

function handleRegister($connect)
{
    if (!isset($_SESSION['email_verified']) || $_SESSION['email_verified'] !== true) {
        echo json_encode(['status' => 'error', 'message' => 'Please verify your email address first.']);
        exit;
    }

    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

    if (strcasecmp($_SESSION['verify_email'], $email) !== 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email address mismatch. Please verify again.']);
        exit;
    }

    if (empty($name) || empty($email) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
        exit;
    }

    // Default image path
    $imagePath = "asset/image/default-image.jpg";

    // Handle Profile Image Upload (if any)
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_image']['tmp_name'];
        $fileName = $_FILES['profile_image']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedExtensions = ['jpg', 'gif', 'png', 'jpeg', 'webp'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $uploadFileDir = 'uploads/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $imagePath = $dest_path;
            }
        }
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = mysqli_prepare($connect, "INSERT INTO users (name, email, phone, password, image, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
    mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $phone, $hashedPassword, $imagePath);

    if (mysqli_stmt_execute($stmt)) {
        unset($_SESSION['verify_otp']);
        unset($_SESSION['verify_email']);
        unset($_SESSION['verify_name']);
        unset($_SESSION['email_verified']);
        unset($_SESSION['otp_sent_time']);

        $_SESSION['is_logged_in'] = true;
        $_SESSION['user_id'] = mysqli_insert_id($connect);
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_image'] = $imagePath;

        echo json_encode(['status' => 'success', 'message' => "Welcome to Siddha Art, {$name}!"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . mysqli_error($connect)]);
    }
    mysqli_stmt_close($stmt);
}
