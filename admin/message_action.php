<?php
session_start();
require_once(__DIR__ . "/../connection.php");
/** @var mysqli $connect */

// Admin authentication check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Route incoming action parameter
$action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : '';

switch ($action) {
    case 'toggle_status':
        toggleMessageStatus($connect);
        break;

    case 'delete':
        deleteMessage($connect);
        break;

    default:
        header("Location: messages.php");
        exit;
}

/**
 * ACTION: Toggle Message Status (0 = Unread, 1 = Read)
 */
function toggleMessageStatus($connect)
{
    $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

    if ($id <= 0) {
        $_SESSION['error_message'] = "Invalid message ID.";
        header("Location: messages.php");
        exit;
    }

    // Fetch current status
    $query = mysqli_query($connect, "SELECT status FROM contact_messages WHERE id = $id LIMIT 1");

    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $currentStatus = (int)$row['status'];
        $newStatus = ($currentStatus === 1) ? 0 : 1;

        $update = mysqli_query($connect, "UPDATE contact_messages SET status = $newStatus WHERE id = $id");

        if ($update) {
            if ($newStatus === 1) {
                $_SESSION['success_message'] = "Message marked as Read.";
            } else {
                $_SESSION['success_message'] = "Message marked as Unread.";
            }
        } else {
            $_SESSION['error_message'] = "Failed to update message status.";
        }
    } else {
        $_SESSION['error_message'] = "Message not found.";
    }

    header("Location: messages.php");
    exit;
}

/**
 * ACTION: Delete Message by ID
 */
function deleteMessage($connect)
{
    $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

    if ($id <= 0) {
        $_SESSION['error_message'] = "Invalid message ID.";
        header("Location: messages.php");
        exit;
    }

    $delete = mysqli_query($connect, "DELETE FROM contact_messages WHERE id = $id");

    if ($delete) {
        $_SESSION['success_message'] = "Message deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to delete message.";
    }

    header("Location: messages.php");
    exit;
}
