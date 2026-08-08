<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

include_once(__DIR__ . '/../includes/connection.php');
global $connect;
// 1. Delete Product
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // delete the image from server
    $result = $connect->query("SELECT image, gallery_image FROM products WHERE id = $id");
    if ($row = $result->fetch_assoc()) {
        if (!empty($row['image']) && file_exists('../uploads/' . $row['image'])) unlink('../uploads/' . $row['image']);
        if (!empty($row['gallery_image'])) {
            $gallery = json_decode($row['gallery_image'], true);
            if (is_array($gallery)) {
                foreach($gallery as $img) {
                    if (file_exists('../uploads/' . $img)) unlink('../uploads/' . $img);
                }
            }
        }
    }

    $connect->query("DELETE FROM products WHERE id = $id");
    $_SESSION['success_message'] = "Product deleted successfully.";
    header("Location: collection.php");
    exit;
}

// 2. Add or Edit Product
if (isset($_POST['save_product'])) {
    $name = $connect->real_escape_string($_POST['name']);
    $category_id = intval($_POST['category_id']);
    $price = floatval($_POST['price']);
    $discount_percentage = intval($_POST['discount_percentage']);
    $status = intval($_POST['status']);
    $description = $connect->real_escape_string($_POST['description']);
    
    // Product Links
    $product_link = json_encode([
        'flipkart' => $connect->real_escape_string($_POST['flipkart_link'] ?? ''),
        'meesho' => $connect->real_escape_string($_POST['meesho_link'] ?? '')
    ]);
    
    // Upload Directory Setup
    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $id = !empty($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    
    $existing_image = '';
    $existing_gallery = [];
    if ($id > 0) {
        $res = $connect->query("SELECT image, gallery_image FROM products WHERE id = $id");
        if ($row = $res->fetch_assoc()) {
            $existing_image = $row['image'];
            if (!empty($row['gallery_image'])) {
                $existing_gallery = json_decode($row['gallery_image'], true) ?: [];
            }
        }
    }

    // Main Image Upload Logic
    $image = $existing_image;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $new_image = time() . '_main_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $new_image;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Delete old image if exists
            if (!empty($existing_image) && file_exists($upload_dir . $existing_image)) {
                unlink($upload_dir . $existing_image);
            }
            $image = $new_image;
        }
    }

    // Gallery Images Logic
    // 1. Remove selected gallery images
    if (isset($_POST['remove_gallery']) && is_array($_POST['remove_gallery'])) {
        foreach ($_POST['remove_gallery'] as $rem_img) {
            if (($key = array_search($rem_img, $existing_gallery)) !== false) {
                unset($existing_gallery[$key]);
                if (file_exists($upload_dir . $rem_img)) {
                    unlink($upload_dir . $rem_img);
                }
            }
        }
        $existing_gallery = array_values($existing_gallery); // Re-index array
    }

    // 2. Upload new gallery images
    if (isset($_FILES['gallery_image']) && !empty($_FILES['gallery_image']['name'][0])) {
        $total_count = count($_FILES['gallery_image']['name']);
        for ($i = 0; $i < $total_count; $i++) {
            if ($_FILES['gallery_image']['error'][$i] == 0) {
                $g_image = time() . '_gallery_' . $i . '_' . basename($_FILES['gallery_image']['name'][$i]);
                $g_target_file = $upload_dir . $g_image;
                if (move_uploaded_file($_FILES['gallery_image']['tmp_name'][$i], $g_target_file)) {
                    $existing_gallery[] = $g_image;
                }
            }
        }
    }
    
    // Server Side Validation for Gallery Count (Fallback)
    $total_final_gallery = count($existing_gallery);
    if ($total_final_gallery < 2 || $total_final_gallery > 4) {
        $_SESSION['error_message'] = "Please provide minimum 2 and maximum 4 gallery images.";
        header("Location: collection.php");
        exit;
    }
    
    $gallery_json = !empty($existing_gallery) ? $connect->real_escape_string(json_encode($existing_gallery)) : '';

    if ($id > 0) {
        // Edit logic in collection page
        $image_sql = $image ? "'$image'" : "NULL";
        $gallery_sql = $gallery_json ? "'$gallery_json'" : "NULL";
        
        $sql = "UPDATE products SET name='$name', category_id=$category_id, price=$price, discount_percentage=$discount_percentage, status=$status, description='$description', product_link='$product_link', image=$image_sql, gallery_image=$gallery_sql WHERE id=$id";
        if ($connect->query($sql)) {
            $_SESSION['success_message'] = "Product updated successfully.";
        } else {
            $_SESSION['error_message'] = "Error updating product: " . $connect->error;
        }
    } else {
        // Add logic
        $image_val = $image ? "'$image'" : "NULL";
        $gallery_json_val = $gallery_json ? "'$gallery_json'" : "NULL";
        
        $sql = "INSERT INTO products (name, category_id, price, discount_percentage, status, description, product_link, image, gallery_image) 
                VALUES ('$name', $category_id, $price, $discount_percentage, $status, '$description', '$product_link', $image_val, $gallery_json_val)";
        if ($connect->query($sql)) {
            $_SESSION['success_message'] = "Product added successfully.";
        } else {
            $_SESSION['error_message'] = "Error adding product: " . $connect->error;
        }
    }
    
    header("Location: collection.php");
    exit;
}
?>
