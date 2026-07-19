<?php
session_start();
require_once 'connection.php';
/** @var mysqli $connect */


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';

// SMTP Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'priyabratabera67@gmail.com'); //owner gmail
define('SMTP_PASS', 'qpip ylxz wxwt awlk'); // owner app password
define('SMTP_SECURE', 'tls');
define('SMTP_FROM_EMAIL', 'priyabratabera67@gmail.com');
define('SMTP_FROM_NAME', 'Siddha Art Creation');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // 1. Receive and Sanitize Data
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $subject = trim($_POST['subject'] ?? 'General Inquiry');
  $message = trim($_POST['message'] ?? '');

  // Validation
  if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(['status' => 'error', 'message' => 'Name, Email, and Message are required fields.']);
    exit;
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
    exit;
  }

  // Escape data for security
  $safeName = mysqli_real_escape_string($connect, $name);
  $safeEmail = mysqli_real_escape_string($connect, $email);
  $safePhone = mysqli_real_escape_string($connect, $phone);
  $safeSubject = mysqli_real_escape_string($connect, $subject);
  $safeMessage = mysqli_real_escape_string($connect, $message);

  // 2. Insert into Database
  $sqlInsert = "INSERT INTO contact_messages (name, email, phone, subject, message, created_at) 
                  VALUES ('$safeName', '$safeEmail', '$safePhone', '$safeSubject', '$safeMessage', NOW())";

  if (mysqli_query($connect, $sqlInsert)) {

    // 3. Send Auto-Reply Email to the User
    $mail = new PHPMailer(true);

    try {
      $mail->isSMTP();
      $mail->Host       = SMTP_HOST;
      $mail->SMTPAuth   = true;
      $mail->Username   = SMTP_USER;
      $mail->Password   = SMTP_PASS;
      $mail->SMTPSecure = SMTP_SECURE;
      $mail->Port       = SMTP_PORT;

      // Sender and Recipient
      $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
      $mail->addAddress($email, $name); // User er mail e jabe

      // Email Content
      $mail->isHTML(true);
      $mail->Subject = 'We have received your message! - Siddha Art Creation';
      $mail->Body    = "
                <div style='font-family: Arial, sans-serif; background-color: #F5F2ED; padding: 30px; border-radius: 10px; max-width: 600px; margin: 0 auto; color: #3A3530;'>
                    <h2 style='color: #B8860B; border-bottom: 2px solid #E5E1DB; padding-bottom: 10px;'>Thank You for Reaching Out!</h2>
                    <p>Dear <strong>{$name}</strong>,</p>
                    <p>We have successfully received your inquiry regarding <strong>'{$subject}'</strong>. Our artisan team is currently reviewing your message and will get back to you within 24 hours.</p>
                    <div style='background-color: #FFFFFF; padding: 15px; border-radius: 8px; border: 1px solid #E5E1DB; margin: 20px 0;'>
                        <p style='margin: 0; font-size: 14px; color: #7D756C;'><strong>Your Message:</strong><br><br>" . nl2br(htmlspecialchars($message)) . "</p>
                    </div>
                    <p>If you have any urgent queries regarding an order, feel free to call us at +91 12345 67890.</p>
                    <br>
                    <p>Warm Regards,<br><strong>Siddhartha</strong><br>Siddha Art Creation Team</p>
                </div>
            ";

      $mail->send();

      // Success response back to frontend
      echo json_encode([
        'status' => 'success',
        'message' => 'Thank you! Your message has been sent successfully. Please check your email.'
      ]);
    } catch (Exception $e) {
      // Data database e save hoyeche, kintu mail jete issue hole
      echo json_encode([
        'status' => 'success',
        'message' => 'Message saved, but failed to send confirmation email.',
        'error' => $mail->ErrorInfo
      ]);
    }
  } else {
    // Database Error
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . mysqli_error($connect)]);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Invalid Request Method.']);
}
