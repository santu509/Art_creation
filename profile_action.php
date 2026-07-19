<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** @var mysqli $connect */

header('Content-Type: application/json');

// Check user login session
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access. Please login first.']);
    exit;
}

require_once('connection.php');

$userId = $_SESSION['user_id'];
$action = $_REQUEST['action'] ?? '';

// Route action request to the corresponding function
switch ($action) {
    case 'get_details':
        getUserDetails($connect, $userId);
        break;

    case 'update_profile':
        updateProfileDetails($connect, $userId, $_POST, $_FILES);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action request.']);
        exit;
}

// =========================================================================
// FUNCTIONS
// =========================================================================

/**
 * Function 1: Fetch user profile details
 */
function getUserDetails($connect, $userId)
{
    // ID ke secure kora holo
    $safeUserId = mysqli_real_escape_string($connect, $userId);

    // Simple Query
    $sql = "SELECT name, email, phone, image, created_at FROM users WHERE id = '$safeUserId'";
    $result = mysqli_query($connect, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $user['image'] = !empty($user['image']) ? $user['image'] : 'asset/image/default-image.jpg';
        $user['created_at_formatted'] = !empty($user['created_at']) ? date("F j, Y", strtotime($user['created_at'])) : 'N/A';

        echo json_encode(['status' => 'success', 'data' => $user]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User details not found.']);
    }
}

/**
 * Function 2: Update user profile details
 */
function updateProfileDetails($connect, $userId, $postData, $filesData)
{
    $name = trim($postData['name'] ?? '');
    $phone = trim($postData['phone'] ?? '');

    if (empty($name)) {
        echo json_encode(['status' => 'error', 'message' => 'Full Name cannot be empty.']);
        return;
    }

    $safeUserId = mysqli_real_escape_string($connect, $userId);

    // 1. Purono data database theke tule ana hocche
    $sqlSelect = "SELECT name, phone, image FROM users WHERE id = '$safeUserId'";
    $resSelect = mysqli_query($connect, $sqlSelect);
    $currentUser = mysqli_fetch_assoc($resSelect);

    $currentName = $currentUser['name'] ?? '';
    $currentPhone = $currentUser['phone'] ?? '';

    // Purono image er path ta save kore rakhlam delete korar jonno
    $oldImagePath = $currentUser['image'] ?? 'asset/image/default-image.jpg';
    $imagePath = $oldImagePath; // By default notun path hobe purono tai

    // 2. Check kora hocche notun kono chobi file esheche kina
    $isNewImageUploaded = (isset($filesData['image']) && $filesData['image']['error'] === UPLOAD_ERR_OK);
    $newImageSaved = false; // Flag to track if new image is successfully saved

    // 3. CHANGE DETECTION
    if ($name === $currentName && $phone === $currentPhone && !$isNewImageUploaded) {
        echo json_encode(['status' => 'info', 'message' => 'No changes were made to your profile.']);
        return;
    }

    $safeName = mysqli_real_escape_string($connect, $name);
    $safePhone = mysqli_real_escape_string($connect, $phone);

    // 4. Handle Profile Picture Upload
    if ($isNewImageUploaded) {
        $fileTmpPath = $filesData['image']['tmp_name'];
        $fileName = $filesData['image']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $uploadFileDir = 'uploads/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $destPath = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $imagePath = $destPath; // Database e save korar jonno notun path
                $newImageSaved = true; // Flag true korlam
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload profile image.']);
                return;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid file format. Allowed: JPG, JPEG, PNG, WEBP, GIF.']);
            return;
        }
    }

    $safeImagePath = mysqli_real_escape_string($connect, $imagePath);

    // 5. Update query with simple mysqli_query
    $sqlUpdate = "UPDATE users SET name = '$safeName', phone = '$safePhone', image = '$safeImagePath', updated_at = NOW() WHERE id = '$safeUserId'";

    if (mysqli_query($connect, $sqlUpdate)) {
        // DATABASE UPDATE SUCCESS!

        // 6. DELETE OLD IMAGE (Jodi notun chobi upload hoy ar puronota default na hoy)
        if ($newImageSaved && $oldImagePath !== 'asset/image/default-image.jpg') {
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $_SESSION['user_name'] = $name;
        $_SESSION['user_image'] = $imagePath;

        echo json_encode([
            'status' => 'success',
            'message' => 'Profile updated successfully!',
            'name' => $name,
            'image' => $imagePath
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . mysqli_error($connect)]);
    }
}
