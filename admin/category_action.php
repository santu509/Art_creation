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
  case 'insert':
    insertCategory($connect);
    break;
  case 'update':
    updateCategory($connect);
    break;
  case 'delete':
    deleteCategory($connect);
    break;
  case 'toggle_status':
    toggleStatus($connect);
    break;
  default:
    header("Location: category.php");
    exit;
}

// ==========================================
// ACTION: Insert Category
// ==========================================
function insertCategory($connect)
{
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);

  if (empty($name)) {
    $_SESSION['error_message'] = "Category Name is required.";
    header("Location: category.php");
    exit;
  }

  $name = mysqli_real_escape_string($connect, $name);
  $description = mysqli_real_escape_string($connect, $description);

  $check = mysqli_query($connect, "SELECT * FROM categories WHERE name='$name'");

  if (mysqli_num_rows($check) > 0) {
    $_SESSION['error_message'] = "Category already exists!";
  } else {

    $insert = mysqli_query($connect, "INSERT INTO categories(name,description) VALUES('$name','$description')");

    if ($insert) {
      $_SESSION['success_message'] = "Category created successfully!";
    } else {
      $_SESSION['error_message'] = "Failed to create category.";
    }
  }

  header("Location: category.php");
  exit;
}

// ==========================================
// ACTION: Update Category
// ==========================================
function updateCategory($connect)
{
  $id = $_POST['id'];
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);

  $name = mysqli_real_escape_string($connect, $name);
  $description = mysqli_real_escape_string($connect, $description);

  $check = mysqli_query($connect, "SELECT * FROM categories WHERE name='$name' AND id!='$id'");

  if (mysqli_num_rows($check) > 0) {
    $_SESSION['error_message'] = "Category already exists!";
  } else {
    $update = mysqli_query($connect, "UPDATE categories SET name='$name', description='$description' WHERE id='$id'");
    if ($update) {
      $_SESSION['success_message'] = "Category updated successfully!";
    } else {
      $_SESSION['error_message'] = "Failed to update category.";
    }
  }

  header("Location: category.php");
  exit;
}

// ==========================================
// ACTION: Delete Category
// ==========================================
function deleteCategory($connect)
{
  $id = $_GET['id'];

  $count = mysqli_query($connect, "SELECT COUNT(*) AS total FROM products WHERE category_id='$id'");

  $row = mysqli_fetch_assoc($count);

  if ($row['total'] > 0) {

    $_SESSION['error_message'] = "Cannot delete! Category contains {$row['total']} product(s).";
  } else {

    $delete = mysqli_query($connect, "DELETE FROM categories WHERE id='$id'");

    if ($delete) {
      $_SESSION['success_message'] = "Category deleted successfully!";
    } else {
      $_SESSION['error_message'] = "Failed to delete category.";
    }
  }

  header("Location: category.php");
  exit;
}

// ==========================================
// ACTION: Toggle Category Status
// ==========================================
function toggleStatus($connect)
{
  $id = $_GET['id'];
  $status = $_GET['status'];

  if ($status == 0) {

    $count = mysqli_query($connect, "SELECT COUNT(*) AS total FROM products WHERE category_id='$id'");

    $row = mysqli_fetch_assoc($count);

    if ($row['total'] > 0) {

      $_SESSION['error_message'] = "Cannot disable! Category contains {$row['total']} product(s).";

      header("Location: category.php");
      exit;
    }
  }

  $update = mysqli_query($connect, "UPDATE categories SET status='$status' WHERE id='$id'");

  if ($update) {

    if ($status == 1) {
      $_SESSION['success_message'] = "Category activated successfully!";
    } else {
      $_SESSION['success_message'] = "Category disabled successfully!";
    }
  } else {

    $_SESSION['error_message'] = "Failed to change status.";
  }

  header("Location: category.php");
  exit;
}
