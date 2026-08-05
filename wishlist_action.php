<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'connection.php';
global $connect;

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please log in to save artworks to your wishlist.',
        'require_login' => true
    ]);
    exit;
}

$userId = intval($_SESSION['user_id']);

// Retrieve product_id from POST or JSON payload
$productId = 0;
if (isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);
} else {
    $inputData = json_decode(file_get_contents('php://input'), true);
    if (isset($inputData['product_id'])) {
        $productId = intval($inputData['product_id']);
    }
}

if ($productId <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid Product ID.'
    ]);
    exit;
}

// 1. Check if product already exists in user's wishlist
$checkQuery = "SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?";
$stmt = mysqli_prepare($connect, $checkQuery);
mysqli_stmt_bind_param($stmt, "ii", $userId, $productId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$exists = ($result && mysqli_num_rows($result) > 0);
mysqli_stmt_close($stmt);

if ($exists) {
    // 2. Remove from wishlist
    $deleteQuery = "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?";
    $delStmt = mysqli_prepare($connect, $deleteQuery);
    mysqli_stmt_bind_param($delStmt, "ii", $userId, $productId);
    $success = mysqli_stmt_execute($delStmt);
    mysqli_stmt_close($delStmt);

    if ($success) {
        // Calculate updated count
        $countQuery = "SELECT COUNT(id) as total FROM wishlist WHERE user_id = ?";
        $cStmt = mysqli_prepare($connect, $countQuery);
        mysqli_stmt_bind_param($cStmt, "i", $userId);
        mysqli_stmt_execute($cStmt);
        $cRes = mysqli_stmt_get_result($cStmt);
        $cRow = mysqli_fetch_assoc($cRes);
        $totalWishlist = intval($cRow['total'] ?? 0);
        mysqli_stmt_close($cStmt);

        echo json_encode([
            'success' => true,
            'action' => 'removed',
            'wishlist_count' => $totalWishlist,
            'message' => 'Item removed from your Saved Artworks.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to remove artwork from wishlist.'
        ]);
    }
} else {
    // 3. Add to wishlist
    $insertQuery = "INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)";
    $insStmt = mysqli_prepare($connect, $insertQuery);
    mysqli_stmt_bind_param($insStmt, "ii", $userId, $productId);
    $success = mysqli_stmt_execute($insStmt);
    mysqli_stmt_close($insStmt);

    if ($success) {
        // Calculate updated count
        $countQuery = "SELECT COUNT(id) as total FROM wishlist WHERE user_id = ?";
        $cStmt = mysqli_prepare($connect, $countQuery);
        mysqli_stmt_bind_param($cStmt, "i", $userId);
        mysqli_stmt_execute($cStmt);
        $cRes = mysqli_stmt_get_result($cStmt);
        $cRow = mysqli_fetch_assoc($cRes);
        $totalWishlist = intval($cRow['total'] ?? 0);
        mysqli_stmt_close($cStmt);

        echo json_encode([
            'success' => true,
            'action' => 'added',
            'wishlist_count' => $totalWishlist,
            'message' => 'Artwork saved to your Wishlist!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to save artwork to wishlist.'
        ]);
    }
}
exit;
