<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

include_once(__DIR__ . '/../connection.php');
global $connect;


// Fetch all active categories for the dropdown
$categoriesResult = $connect->query("SELECT id, name FROM categories ORDER BY name ASC");
$categoriesList = [];
if ($categoriesResult && $categoriesResult->num_rows > 0) {
    while ($row = $categoriesResult->fetch_assoc()) {
        $categoriesList[] = $row;
    }
}

// 3. Search and Pagination Logic
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
if (!in_array($limit, [5, 10, 25, 50, 100])) $limit = 10;

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? $connect->real_escape_string(trim($_GET['search'])) : '';
$whereClause = "WHERE 1=1";
if (!empty($search)) {
    $whereClause .= " AND (p.name LIKE '%$search%' OR c.name LIKE '%$search%')";
}

$total_result = $connect->query("SELECT COUNT(*) AS total FROM products p LEFT JOIN categories c ON p.category_id = c.id $whereClause");
$total_row = $total_result->fetch_assoc();
$total_pages = ceil($total_row['total'] / $limit);

$products = $connect->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id $whereClause ORDER BY p.id DESC LIMIT $limit OFFSET $offset");

// Stats for Top Cards
$totalProductsRes = $connect->query("SELECT COUNT(*) as total, SUM(IF(status=1,1,0)) as active, SUM(IF(status=0,1,0)) as inactive FROM products");
$productStats = $totalProductsRes->fetch_assoc();

$pageTitle = "Artwork Collection";
$currentPage = "collection.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - Siddha Art Admin</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../asset/image/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../asset/bootstrap-5.3.7-dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-page: #FAF8F5;
            --bg-card: #FFFFFF;
            --gold-primary: #D4AF37;
            --gold-deep: #B8860B;
            --gold-accent: #C59B27;
            --gold-light: #F3E5AB;
            --gold-border: rgba(212, 175, 55, 0.3);
            --text-dark: #2A241D;
            --text-muted: #7C7267;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-page);
            color: var(--text-dark);
        }

        .admin-layout-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .admin-main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            overflow-x: hidden;
        }

        /* Stat Cards */
        .stat-card {
            background-color: #FFFFFF;
            border: 1px solid var(--gold-border);
            border-radius: 16px;
            padding: 22px 24px;
            box-shadow: 0 4px 15px rgba(184, 134, 11, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(184, 134, 11, 0.12);
        }

        .stat-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1;
        }

        .stat-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(184, 134, 11, 0.08) 100%);
            color: var(--gold-deep);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        /* Form Controls */
        .form-label-custom {
            font-size: 13px;
            font-weight: 600;
            color: #4a4036;
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }

        .form-control-custom,
        .form-select-custom {
            background-color: #fbfaf8;
            border: 1px solid #e2d7c1;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            color: var(--text-dark);
            transition: all 0.3s ease;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.02);
            width: 100%;
        }

        .form-control-custom:focus,
        .form-select-custom:focus {
            background-color: #FFFFFF;
            border-color: var(--gold-primary);
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.15);
            outline: none;
        }

        .btn-gold-submit {
            background: linear-gradient(135deg, #fbb802 20%, #f8b203 80%);
            color: #FFFFFF;
            font-weight: 600;
            font-size: 14px;
            border-radius: 10px;
            padding: 12px 20px;
            border: none;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.25);
            transition: all 0.25s ease;
        }

        .btn-gold-submit:hover {
            background: linear-gradient(135deg, #ffb618 0%, #ffd53ef4 100%);
            color: #FFFFFF;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(212, 175, 55, 0.35);
        }

        /* Table Styles */
        .table-card {
            background-color: #FFFFFF;
            border: 1px solid var(--gold-border);
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(184, 134, 11, 0.06);
            overflow: hidden;
        }

        .table-card-header {
            padding: 20px 24px;
            background-color: #FAF6F0;
            border-bottom: 1.5px solid var(--gold-border);
        }

        .table-card-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--gold-deep);
        }

        .custom-table {
            margin: 0;
            width: 100%;
        }

        .custom-table th {
            background-color: #FAF6F0;
            color: var(--gold-deep);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 16px 18px;
            border-bottom: 1px solid var(--gold-border);
        }

        .custom-table td {
            padding: 18px 18px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.15);
            vertical-align: middle;
            font-size: 14px;
        }

        /* Action Buttons */
        .btn-action-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid var(--gold-border);
            background-color: #FAF8F5;
            color: var(--gold-deep);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-left: 4px;
        }

        .btn-action-icon.edit-btn:hover {
            background-color: var(--gold-deep);
            border-color: var(--gold-deep);
            color: #FFFFFF;
        }

        .btn-action-icon.delete-btn {
            color: #D93848;
            border-color: rgba(217, 56, 72, 0.3);
        }

        .btn-action-icon.delete-btn:hover {
            background-color: #D93848;
            border-color: #D93848;
            color: #FFFFFF;
        }

        /* Modal styling to match category.php vibe */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-bottom: 1.5px solid var(--gold-border);
            background-color: #FAF6F0;
            border-radius: 20px 20px 0 0;
            padding: 20px 24px;
        }

        .modal-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--gold-deep);
            font-size: 22px;
        }

        .modal-footer {
            border-top: 1.5px solid var(--gold-border);
            padding: 16px 24px;
            background-color: #FAF8F5;
            border-radius: 0 0 20px 20px;
        }

        .btn-modal-cancel {
            background-color: #FFFFFF;
            border: 1px solid #E0E0E0 !important;
            color: #2A241D;
            font-weight: 600;
            border-radius: 10px;
            padding: 12px 24px;
            transition: all 0.2s ease;
            border-radius: 14px;
            padding: 10px 22px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .btn-modal-cancel:hover {
            background: linear-gradient(135deg, #ff1a1a, #d60000);
            color: #fff;
            transform: translateY(-3px) scale(1.04);
            box-shadow: 0 12px 28px rgba(255, 0, 0, 0.30), 0 4px 10px rgba(0, 0, 0, 0.12);
            border-color: transparent;
        }


        .product-img-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--gold-border);
        }

        /* Checkbox styling for gallery remove */
        .gallery-item-wrapper {
            position: relative;
            display: inline-block;
            transition: all 0.2s;
        }

        .gallery-item-wrapper img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--gold-border);
            transition: all 0.2s;
        }

        .remove-img-lbl {
            position: absolute;
            top: -6px;
            right: -6px;
            background-color: #D93848;
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.2s;
        }

        .remove-img-lbl:hover {
            transform: scale(1.1);
        }

        .link-icon-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            margin-right: 5px;
            color: #555;
            text-decoration: none;
            transition: all 0.2s;
        }

        .link-icon-badge:hover {
            background: #e9ecef;
            color: var(--gold-deep);
            border-color: var(--gold-deep);
        }
    </style>
</head>

<body>
    <div class="admin-layout-wrapper">
        <!-- Dynamic Sidebar Inclusion -->
        <?php include_once 'includes/sidebar.php'; ?>

        <!-- Main Content Area -->
        <main class="admin-main-content">
            <!-- Topbar Inclusion -->
            <?php include_once 'includes/topbar.php'; ?>

            <!-- Main Page Content -->
            <div class="container-fluid p-4">

                <!-- Toast Notifications -->
                <div class="toast-container position-fixed top-0 end-0 p-4" style="z-index: 1060; margin-top: 60px;">
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="toast align-items-center text-white bg-success border-0 shadow-lg mb-3" role="alert" aria-live="assertive" aria-atomic="true" id="successToast">
                            <div class="d-flex p-2">
                                <div class="toast-body fs-6 fw-semibold">
                                    <i class="fa-solid fa-circle-check me-2" style="font-size:18px;"></i> <?= $_SESSION['success_message'] ?>
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    <?php unset($_SESSION['success_message']);
                    endif; ?>

                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="toast align-items-center text-white bg-danger border-0 shadow-lg mb-3" role="alert" aria-live="assertive" aria-atomic="true" id="errorToast">
                            <div class="d-flex p-2">
                                <div class="toast-body fs-6 fw-semibold">
                                    <i class="fa-solid fa-triangle-exclamation me-2" style="font-size:18px;"></i> <?= $_SESSION['error_message'] ?>
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    <?php unset($_SESSION['error_message']);
                    endif; ?>
                </div>

                <!-- Top Dynamic Stat Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-12 col-md-4">
                        <div class="stat-card">
                            <div>
                                <div class="stat-title">TOTAL PRODUCTS</div>
                                <div class="stat-value"><?= $productStats['total'] ?? 0; ?></div>
                            </div>
                            <div class="stat-icon-box">
                                <i class="fa-solid fa-box"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="stat-card">
                            <div>
                                <div class="stat-title">ACTIVE PRODUCTS</div>
                                <div class="stat-value"><?= $productStats['active'] ?? 0; ?></div>
                            </div>
                            <div class="stat-icon-box">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="stat-card">
                            <div>
                                <div class="stat-title">DISABLED PRODUCTS</div>
                                <div class="stat-value"><?= $productStats['inactive'] ?? 0; ?></div>
                            </div>
                            <div class="stat-icon-box" style="color: #D93848; background: rgba(217, 56, 72, 0.1);">
                                <i class="fa-solid fa-power-off"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Table -->
                <div class="table-card">
                    <div class="table-card-header d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-3">
                        <h3 class="table-card-title mb-0">Artwork Collection</h3>
                        <div class="d-flex flex-column flex-lg-row align-items-center gap-3 w-100 w-xl-auto" style="max-width: 800px;">
                            <form method="GET" action="collection.php" class="d-flex flex-wrap gap-2 mb-0 w-100 justify-content-end align-items-center">
                                <div class="position-relative" style="flex-grow: 1; min-width: 200px; max-width: 300px;">
                                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: var(--gold-deep); font-size:14px;"></i>
                                    <input type="text" name="search" class="form-control-custom ps-5" placeholder="Search category, product..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" style="height: 44px; width:100%;">
                                </div>
                                <select name="limit" class="form-select-custom w-auto" style="height: 44px;">
                                    <option value="5" <?= $limit == 5 ? 'selected' : '' ?>>5 Rows</option>
                                    <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10 Rows</option>
                                    <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25 Rows</option>
                                    <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50 Rows</option>
                                </select>
                                <button type="submit" class="btn btn-gold-submit d-flex align-items-center justify-content-center" style="height: 44px; padding: 0 16px;">
                                    Apply Filter
                                </button>
                                <a href="collection.php" class="btn btn-light d-flex align-items-center justify-content-center shadow-sm" style="height: 44px; padding: 0 16px; border: 1px solid #ddd; border-radius: 10px; color: var(--text-dark); font-weight: 600; text-decoration: none;">
                                    Reset
                                </a>
                            </form>
                            <button class="btn btn-gold-submit d-flex align-items-center justify-content-center flex-shrink-0 ms-xl-2" data-bs-toggle="modal" data-bs-target="#productModal" onclick="clearForm()" style="height: 44px;">
                                <i class="fas fa-plus me-2"></i> Add New
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="custom-table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Image</th>
                                    <th>Product Details</th>
                                    <th>Category</th>
                                    <th>Price & Discount</th>
                                    <th>Links</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($products->num_rows > 0): ?>
                                    <?php $sl = $offset + 1; ?>
                                    <?php while ($row = $products->fetch_assoc()): ?>
                                        <?php
                                        $links = [];
                                        if (!empty($row['product_link'])) {
                                            $links = json_decode($row['product_link'], true) ?: [];
                                        }
                                        ?>
                                        <tr>
                                            <td class="text-muted fw-bold"><?= sprintf("%02d", $sl++) ?></td>
                                            <td>
                                                <?php if (!empty($row['image'])): ?>
                                                    <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="Product" class="product-img-thumb shadow-sm">
                                                <?php else: ?>
                                                    <div class="product-img-thumb d-flex align-items-center justify-content-center bg-light text-muted border">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($row['name']) ?></div>
                                                <div class="text-muted mt-1" style="font-size: 13px; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    <?= htmlspecialchars($row['description']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border border-secondary-subtle rounded-pill px-3 py-2 fw-medium shadow-sm">
                                                    <?= htmlspecialchars($row['category_name'] ?? 'Unknown') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark fs-6">₹ <?= number_format($row['price'], 2) ?></div>
                                                <?php if ($row['discount_percentage'] > 0): ?>
                                                    <div class="text-success fw-semibold mt-1" style="font-size:12px;">
                                                        <i class="fas fa-tag"></i> <?= $row['discount_percentage'] ?>% OFF
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <?php if (!empty($links['flipkart'])): ?>
                                                        <a href="<?= htmlspecialchars($links['flipkart']) ?>" target="_blank" class="link-icon-badge" title="Flipkart Link">
                                                            <i class="fas fa-shopping-cart"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if (!empty($links['meesho'])): ?>
                                                        <a href="<?= htmlspecialchars($links['meesho']) ?>" target="_blank" class="link-icon-badge" title="Meesho Link">
                                                            <i class="fas fa-shopping-bag"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if (empty($links['flipkart']) && empty($links['meesho'])): ?>
                                                        <span class="text-muted" style="font-size:12px;">N/A</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($row['status'] == 1): ?>
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill shadow-sm">
                                                        <i class="fa-solid fa-circle me-1" style="font-size: 8px;"></i> Active
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 rounded-pill shadow-sm">
                                                        <i class="fa-solid fa-circle me-1" style="font-size: 8px;"></i> Disabled
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn-action-icon edit-btn"
                                                    data-id="<?= $row['id'] ?>"
                                                    data-name="<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>"
                                                    data-category="<?= $row['category_id'] ?>"
                                                    data-price="<?= $row['price'] ?>"
                                                    data-discount="<?= $row['discount_percentage'] ?>"
                                                    data-status="<?= $row['status'] ?>"
                                                    data-desc="<?= htmlspecialchars($row['description'], ENT_QUOTES) ?>"
                                                    data-image="<?= htmlspecialchars($row['image'], ENT_QUOTES) ?>"
                                                    data-gallery="<?= htmlspecialchars($row['gallery_image'], ENT_QUOTES) ?>"
                                                    data-flink="<?= htmlspecialchars($links['flipkart'] ?? '', ENT_QUOTES) ?>"
                                                    data-mlink="<?= htmlspecialchars($links['meesho'] ?? '', ENT_QUOTES) ?>"
                                                    title="Edit Product">
                                                    <i class="fas fa-pen-to-square"></i>
                                                </button>
                                                <button type="button" class="btn-action-icon delete-btn" onclick="confirmDelete(<?= $row['id'] ?>)" title="Delete Product">
                                                    <i class="fas fa-trash-can"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fa-solid fa-box-open fa-3x mb-3" style="color: var(--gold-border);"></i>
                                                <p class="mb-0 fw-medium fs-5">No products found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="px-4 py-3 border-top d-flex justify-content-center justify-content-md-end" style="border-color: var(--gold-border) !important; background-color: #FAF8F5;">
                            <nav>
                                <ul class="pagination mb-0 gap-1 align-items-center">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item me-2">
                                            <a class="page-link shadow-sm rounded fw-medium" href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>" style="color: var(--gold-deep); border-color: var(--gold-border); background-color: #fff; font-size: 13px;">
                                                <i class="fas fa-chevron-left me-1"></i> Prev
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                            <a class="page-link shadow-sm rounded" href="?page=<?= $i ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>" style="<?= ($page == $i) ? 'background-color: var(--gold-deep); border-color: var(--gold-deep); color: white; font-weight: bold;' : 'color: var(--gold-deep); border-color: var(--gold-border); background-color: #fff;' ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($page < $total_pages): ?>
                                        <li class="page-item ms-2">
                                            <a class="page-link shadow-sm rounded fw-medium" href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>" style="color: var(--gold-deep); border-color: var(--gold-border); background-color: #fff; font-size: 13px;">
                                                Next <i class="fas fa-chevron-right ms-1"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </main>
    </div>

    <!-- Product Modal (Add & Edit) -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="collection_action.php" method="POST" enctype="multipart/form-data" id="productForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel">Add New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 p-md-5">
                        <input type="hidden" name="product_id" id="product_id">

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label-custom">Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control-custom" name="name" id="name" required placeholder="Enter product name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Category <span class="text-danger">*</span></label>
                                <select class="form-select-custom" name="category_id" id="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categoriesList as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="form-label-custom">Price (₹) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control-custom" name="price" id="price" required placeholder="0.00">
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="form-label-custom">Discount (%)</label>
                                <input type="number" class="form-control-custom" name="discount_percentage" id="discount_percentage" value="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Status</label>
                                <select class="form-select-custom" name="status" id="status">
                                    <option value="1">Active</option>
                                    <option value="0">Disabled</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label-custom"><i class="fas fa-shopping-cart me-1 text-muted"></i> Flipkart Link<span class="text-danger"> *</span></label>
                                <input type="url" class="form-control-custom" name="flipkart_link" id="flipkart_link" required placeholder="https://flipkart.com/...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom"><i class="fas fa-shopping-bag me-1 text-muted"></i> Meesho Link<span class="text-danger"> *</span></label>
                                <input type="url" class="form-control-custom" name="meesho_link" id="meesho_link" required placeholder="https://meesho.com/...">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-custom">Description<span class="text-danger"> *</span></label>
                            <textarea class="form-control-custom" name="description" required id="description" rows="4" placeholder="Write beautiful product description here..."></textarea>
                        </div>

                        <!-- Image Upload Fields -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="p-3 border rounded-3" style="border-color: var(--gold-border) !important; background-color: #fbfaf8;">
                                    <label class="form-label-custom mb-1"><i class="fas fa-image me-1"></i> Main Image <span class="text-danger main-req-star">*</span></label>
                                    <small class="text-muted d-block mb-3" style="font-size:12px;">This image will appear on the table & store front.</small>
                                    <input type="file" class="form-control-custom p-2 bg-white" name="image" id="image" accept="image/*" required>
                                    <div id="image_preview" class="mt-3"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3" style="border-color: var(--gold-border) !important; background-color: #fbfaf8; height: 100%;">
                                    <label class="form-label-custom mb-1"><i class="fas fa-images me-1"></i> Gallery Images <span class="text-danger">*</span></label>
                                    <small class="text-muted d-block mb-3" style="font-size:12px;">Select minimum 2 and maximum 4 images.</small>
                                    <input type="file" class="form-control-custom p-2 bg-white" name="gallery_image[]" id="gallery_image" accept="image/*" multiple>
                                    <div id="new_gallery_preview" class="mt-3 d-flex flex-wrap gap-2"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div id="existing_gallery_preview" class="d-flex flex-wrap gap-3 mt-2"></div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-modal-cancel shadow-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="save_product" class="btn btn-gold-submit shadow-sm px-4">
                            <i class="fas fa-save me-1"></i> Save Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Custom Premium Delete Alert Modal -->
    <div class="modal fade" id="deleteAlertModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content text-center p-4 border-0 shadow-lg" style="background: #fff; border-radius: 24px;">
                <div class="mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: #FFF5F5; border: 4px solid #E63946; color: #E63946; border-radius: 50%; font-size: 36px; box-shadow: 0 10px 20px rgba(230, 57, 70, 0.15);">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <h4 class="fw-bold mb-2 text-dark" style="font-family: 'Playfair Display', serif;">Confirm Delete</h4>
                <p class="text-muted mb-4" style="font-size: 14px; line-height: 1.5;">Are you sure you want to delete this product? This action cannot be reverted.</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light px-4 fw-semibold shadow-sm" data-bs-dismiss="modal" style="border-radius: 12px; border: 1px solid #ddd; font-size:15px; width:45%;">Cancel</button>
                    <a href="#" id="confirmDeleteLink" class="btn btn-danger px-4 fw-semibold shadow-sm d-flex align-items-center justify-content-center" style="border-radius: 12px; background-color: #E63946; border:none; font-size:15px; width:55%;">Yes, Delete</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../asset/bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Auto-dismiss Toasts
            var toastElList = [].slice.call(document.querySelectorAll('.toast'));
            var toastList = toastElList.map(function(toastEl) {
                return new bootstrap.Toast(toastEl, {
                    delay: 2500
                });
            });
            toastList.forEach(toast => toast.show());

            // Live preview for Main Image
            $('#image').on('change', function(event) {
                var file = event.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image_preview').html(`
                            <div class="p-2 border rounded" style="border-color: var(--gold-border) !important; background: #fff;">
                                <p class="mb-2 fw-semibold" style="font-size:12px; color:var(--gold-deep);"><i class="fas fa-eye me-1"></i>Live Preview:</p>
                                <img src="${e.target.result}" class="product-img-thumb shadow-sm" style="width: 80px; height: 80px;">
                            </div>
                        `);
                    }
                    reader.readAsDataURL(file);
                } else {
                    $('#image_preview').html('');
                }
            });

            // Live preview for Gallery Images
            $('#gallery_image').on('change', function(event) {
                $('#new_gallery_preview').html('');
                var files = event.target.files;
                if (files.length > 0) {
                    $('#new_gallery_preview').append('<div class="w-100 fw-semibold mb-2" style="font-size:12px; color:var(--gold-deep);"><i class="fas fa-eye me-1"></i>Live Preview (' + files.length + ' images):</div>');
                    $.each(files, function(i, file) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('#new_gallery_preview').append(`
                                <img src="${e.target.result}" class="product-img-thumb shadow-sm" style="width: 60px; height: 60px;">
                            `);
                        }
                        reader.readAsDataURL(file);
                    });
                }
            });

            // Form Submit Validation for Gallery Image Count
            $('#productForm').on('submit', function(e) {
                let isEdit = $('#product_id').val() > 0;
                let newFilesCount = $('#gallery_image')[0].files.length;
                let existingCount = $('.gallery-item-wrapper').length;
                let removedCount = $('.remove-checkbox:checked').length;

                let totalGalleryCount = (isEdit ? (existingCount - removedCount) : 0) + newFilesCount;

                if (totalGalleryCount < 2 || totalGalleryCount > 4) {
                    e.preventDefault();
                    alert('Error: You must provide a minimum of 2 and a maximum of 4 gallery images.');
                    return false;
                }

                return true;
            });

            // Populate Edit Modal
            $('.edit-btn').on('click', function() {
                $('#productModalLabel').html('<i class="fas fa-pen-to-square me-2"></i>Edit Product');
                $('#product_id').val($(this).data('id'));
                $('#name').val($(this).data('name'));
                $('#category_id').val($(this).data('category'));
                $('#price').val($(this).data('price'));
                $('#discount_percentage').val($(this).data('discount'));
                $('#status').val($(this).data('status'));
                $('#description').val($(this).data('desc'));
                $('#flipkart_link').val($(this).data('flink'));
                $('#meesho_link').val($(this).data('mlink'));

                $('#image').prop('required', false);
                $('.main-req-star').hide();

                $('#image_preview').html('');
                $('#new_gallery_preview').html('');
                $('#existing_gallery_preview').html('');

                // Show Main Image Preview
                var mainImage = $(this).data('image');
                if (mainImage) {
                    $('#image_preview').html(`
                        <div class="p-2 border rounded" style="border-color: var(--gold-border) !important; background: #fff;">
                            <p class="mb-2 fw-semibold" style="font-size:12px; color:var(--gold-deep);">Current Main Image:</p>
                            <img src="../uploads/${mainImage}" class="product-img-thumb shadow-sm" style="width: 80px; height: 80px;">
                        </div>
                    `);
                }

                // Show Gallery Images Preview with Remove Checkbox
                var gallery = $(this).data('gallery');
                if (gallery) {
                    var galleryHtml = '<div class="w-100 mt-4 mb-3 pb-2 border-bottom fw-bold" style="font-size:15px; color:var(--text-dark);"><i class="fas fa-images me-1"></i> Current Gallery Images <span class="text-muted fw-normal" style="font-size:12px;">(Select the red (X) icon to remove)</span></div>';
                    if (typeof gallery === 'string') {
                        try {
                            gallery = JSON.parse(gallery);
                        } catch (e) {
                            gallery = [];
                        }
                    }
                    if (Array.isArray(gallery) && gallery.length > 0) {
                        gallery.forEach(function(img, index) {
                            galleryHtml += `
                            <div class="gallery-item-wrapper" id="gal_wrap_${index}">
                                <img src="../uploads/${img}" alt="Gallery" class="shadow-sm">
                                <label class="remove-img-lbl" title="Remove this image">
                                    <input type="checkbox" name="remove_gallery[]" value="${img}" class="d-none remove-checkbox" data-target="#gal_wrap_${index}">
                                    <i class="fas fa-times" style="font-size: 10px;"></i>
                                </label>
                            </div>`;
                        });
                        $('#existing_gallery_preview').html(galleryHtml);
                    }
                }

                $('#productModal').modal('show');
            });

            // Handle UI feedback for removing gallery images
            $(document).on('change', '.remove-checkbox', function() {
                let targetWrap = $(this).data('target');
                if (this.checked) {
                    $(targetWrap).find('img').css({
                        'opacity': '0.3',
                        'filter': 'grayscale(100%)',
                        'border-color': '#D93848'
                    });
                    $(this).closest('.remove-img-lbl').removeClass('bg-danger').addClass('bg-secondary');
                } else {
                    $(targetWrap).find('img').css({
                        'opacity': '1',
                        'filter': 'none',
                        'border-color': 'var(--gold-border)'
                    });
                    $(this).closest('.remove-img-lbl').removeClass('bg-secondary').addClass('bg-danger');
                }
            });
        });

        function confirmDelete(id) {
            $('#confirmDeleteLink').attr('href', 'collection_action.php?delete=' + id);
            $('#deleteAlertModal').modal('show');
        }

        function clearForm() {
            $('#productModalLabel').html('<i class="fas fa-plus-circle me-2"></i>Add New Product');
            $('#product_id').val('');
            $('#name').val('');
            $('#category_id').val('');
            $('#price').val('');
            $('#discount_percentage').val('0');
            $('#status').val('1');
            $('#description').val('');
            $('#flipkart_link').val('');
            $('#meesho_link').val('');

            $('#image').prop('required', true);
            $('.main-req-star').show();

            $('#image').val('');
            $('#gallery_image').val('');
            $('#image_preview').html('');
            $('#new_gallery_preview').html('');
            $('#existing_gallery_preview').html('');
        }
    </script>
</body>

</html>