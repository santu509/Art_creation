<?php
session_start();
require_once __DIR__ . '/../includes/connection.php';
global $connect;
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'You must be logged in to submit a review.']);
        exit;
    }

    $customer_id = $_SESSION['user_id'];
    
    // 2. Receive and Sanitize Data
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $review = isset($_POST['message']) ? trim($_POST['message']) : '';

    if ($rating < 1 || $rating > 5) {
        echo json_encode(['status' => 'error', 'message' => 'Please select a valid rating between 1 and 5.']);
        exit;
    }

    if (empty($review)) {
        echo json_encode(['status' => 'error', 'message' => 'Please write a review message.']);
        exit;
    }

    $safeReview = mysqli_real_escape_string($connect, $review);

    // 3. Check if review already exists for this customer
    $checkSql = "SELECT id FROM feedback WHERE customers_id = ?";
    $checkStmt = mysqli_prepare($connect, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "i", $customer_id);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);

    if (mysqli_num_rows($checkResult) > 0) {
        // UPDATE existing review
        $updateSql = "UPDATE feedback SET rating = ?, review = ? WHERE customers_id = ?";
        $updateStmt = mysqli_prepare($connect, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "isi", $rating, $safeReview, $customer_id);
        
        if (mysqli_stmt_execute($updateStmt)) {
            echo json_encode(['status' => 'success', 'message' => 'Your review has been successfully updated!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update review. Please try again later.']);
        }
        mysqli_stmt_close($updateStmt);
    } else {
        // INSERT new review
        $insertSql = "INSERT INTO feedback (customers_id, rating, review) VALUES (?, ?, ?)";
        $insertStmt = mysqli_prepare($connect, $insertSql);
        mysqli_stmt_bind_param($insertStmt, "iis", $customer_id, $rating, $safeReview);
        
        if (mysqli_stmt_execute($insertStmt)) {
            echo json_encode(['status' => 'success', 'message' => 'Thank you! Your review has been successfully submitted.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to submit review. Please try again later.']);
        }
        mysqli_stmt_close($insertStmt);
    }
    
    mysqli_stmt_close($checkStmt);
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request Method.']);
}
?>
