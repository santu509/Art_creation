<?php
session_start();
require_once(__DIR__ . "/../connection.php");
/** @var mysqli $connect */

// Check admin authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$pageTitle = "Category Management";
$currentPage = "category.php";

/**
 * Simple function to calculate products associated with a category ID
 */
function getCategoryProductCount($connect, $categoryId)
{
    $categoryId = (int)$categoryId;
    if ($categoryId <= 0) return 0;

    $query = mysqli_query($connect, "SELECT COUNT(*) as cnt FROM products WHERE category_id = $categoryId");
    if ($query) {
        $row = mysqli_fetch_assoc($query);
        return (int)$row['cnt'];
    }
    return 0;
}

// Handle Category Edit Fetching
$editId = isset($_GET['edit_id']) ? (int)$_GET['edit_id'] : 0;
$editCategory = null;

if ($editId > 0) {
    $editRes = mysqli_query($connect, "SELECT * FROM categories WHERE id = $editId LIMIT 1");
    if ($editRes && mysqli_num_rows($editRes) > 0) {
        $editCategory = mysqli_fetch_assoc($editRes);
    }
}

// Fetch Search & Sort Parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'newest';

$whereClause = "";
if (!empty($search)) {
    $safeSearch = mysqli_real_escape_string($connect, $search);
    $whereClause = "WHERE name LIKE '%$safeSearch%' OR description LIKE '%$safeSearch%'";
}

$query = "SELECT * FROM categories $whereClause";
$categoriesResult = mysqli_query($connect, $query);

$categoriesList = [];
if ($categoriesResult && mysqli_num_rows($categoriesResult) > 0) {
    while ($row = mysqli_fetch_assoc($categoriesResult)) {
        $row['product_count'] = getCategoryProductCount($connect, $row['id']);
        $categoriesList[] = $row;
    }
}

// Calculate Top Dynamic Stats
$totalCategories = count($categoriesList);
$activeCategories = 0;
$unusedCategories = 0;

foreach ($categoriesList as $cat) {
    if ((int)$cat['status'] === 1) {
        $activeCategories++;
    }
    if ((int)$cat['product_count'] === 0) {
        $unusedCategories++;
    }
}

// Apply Sorting to Categories List
switch ($sort) {
    case 'name_asc':
        usort($categoriesList, function ($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });
        break;
    case 'name_desc':
        usort($categoriesList, function ($a, $b) {
            return strcasecmp($b['name'], $a['name']);
        });
        break;
    case 'products_desc':
        usort($categoriesList, function ($a, $b) {
            if ($a['product_count'] == $b['product_count']) return $b['id'] - $a['id'];
            return $b['product_count'] - $a['product_count'];
        });
        break;
    case 'products_asc':
        usort($categoriesList, function ($a, $b) {
            if ($a['product_count'] == $b['product_count']) return $a['id'] - $b['id'];
            return $a['product_count'] - $b['product_count'];
        });
        break;
    case 'newest':
    default:
        usort($categoriesList, function ($a, $b) {
            return $b['id'] - $a['id'];
        });
        break;
}

// Render Table Rows Function for reuse and AJAX
function renderCategoryRows($categoriesList)
{
    if (!empty($categoriesList)):
        $sl = 1;
        foreach ($categoriesList as $cat):
            $catStatus = (int)$cat['status'];
            $prodCount = (int)$cat['product_count'];
            $catNameEsc = htmlspecialchars($cat['name']);
            $catNameSlash = htmlspecialchars(addslashes($cat['name']));
?>
            <tr>
                <td>
                    <span class="sl-number"><?php echo sprintf("%02d", $sl++); ?></span>
                </td>
                <td>
                    <div class="category-name-text"><?php echo $catNameEsc; ?></div>
                    <div class="category-desc-text text-truncate" style="max-width: 280px;">
                        <?php echo !empty($cat['description']) ? htmlspecialchars($cat['description']) : 'No description provided'; ?>
                    </div>
                </td>
                <td class="text-center">
                    <?php if ($catStatus === 1): ?>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                            <i class="fa-solid fa-circle me-1" style="font-size: 8px;"></i>Active
                        </span>
                    <?php else: ?>
                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 rounded-pill">
                            <i class="fa-solid fa-circle me-1" style="font-size: 8px;"></i>Disabled
                        </span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <span class="badge-product-count">
                        <i class="fa-solid fa-box me-1" style="color: var(--gold-deep);"></i><?php echo $prodCount; ?> Products
                    </span>
                </td>
                <td class="text-end">
                    <div class="d-inline-flex align-items-center justify-content-end">
                        <!-- Toggle Status Button -->
                        <?php if ($catStatus === 1): ?>
                            <?php if ($prodCount > 0): ?>
                                <button type="button"
                                    class="btn-action-icon toggle-btn-disabled disabled-blocked"
                                    title="Cannot disable: Category has attached products"
                                    onclick="showBlockedAlert('Cannot Disable Category', 'Cannot disable category \'<?php echo $catNameSlash; ?>\': It has <?php echo $prodCount; ?> attached product(s)! Please remove or reassign products first.');">
                                    <i class="fa-solid fa-ban"></i>
                                </button>
                            <?php else: ?>
                                <button type="button"
                                    class="btn-action-icon toggle-btn-disabled"
                                    title="Disable Category"
                                    onclick="showConfirmModal('Disable Category', 'Are you sure you want to disable category \'<?php echo $catNameSlash; ?>\'?', 'category_action.php?action=toggle_status&id=<?php echo $cat['id']; ?>&status=0', 'Disable', 'btn-warning');">
                                    <i class="fa-solid fa-power-off"></i>
                                </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <button type="button"
                                class="btn-action-icon toggle-btn-active"
                                title="Enable Category"
                                onclick="showConfirmModal('Enable Category', 'Are you sure you want to enable category \'<?php echo $catNameSlash; ?>\'?', 'category_action.php?action=toggle_status&id=<?php echo $cat['id']; ?>&status=1', 'Enable', 'btn-success');">
                                <i class="fa-solid fa-circle-check"></i>
                            </button>
                        <?php endif; ?>

                        <!-- Edit Button -->
                        <a href="category.php?edit_id=<?php echo $cat['id']; ?>"
                            class="btn-action-icon edit-btn"
                            title="Edit Category">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>

                        <!-- Delete Button -->
                        <?php if ($prodCount > 0): ?>
                            <button type="button"
                                class="btn-action-icon delete-btn disabled-blocked"
                                title="Cannot delete: Category has attached products"
                                onclick="showBlockedAlert('Cannot Delete Category', 'Cannot delete category \'<?php echo $catNameSlash; ?>\': It has <?php echo $prodCount; ?> attached product(s)! Please remove or reassign products first.');">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        <?php else: ?>
                            <button type="button"
                                class="btn-action-icon delete-btn"
                                title="Delete Category"
                                onclick="showConfirmModal('Are you sure?', 'Do you really want to delete this category? This process cannot be undone.', 'category_action.php?action=delete&id=<?php echo $cat['id']; ?>', 'Delete', 'btn-danger');">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" class="text-center py-5">
                <div class="text-muted">
                    <i class="fa-solid fa-folder-open fa-3x mb-3" style="color: var(--gold-border);"></i>
                    <p class="mb-0 fw-medium">No categories found matching your request.</p>
                </div>
            </td>
        </tr>
<?php endif;
}

// Handle AJAX Response for Live Search & Sorting
$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || isset($_GET['ajax']);

if ($isAjax) {
    ob_start();
    renderCategoryRows($categoriesList);
    $tableRowsHtml = ob_get_clean();
    echo json_encode([
        'status' => 'success',
        'html' => $tableRowsHtml,
        'count' => count($categoriesList)
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management - Siddha Art Admin</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../asset/image/logo.png">
    <!-- Bootstrap 5 CSS -->
    <link href="../asset/bootstrap-5.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Google Fonts -->
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

        /* Category Form Card */
        .form-card {
            background-color: #FFFFFF;
            border: 1px solid var(--gold-border);
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(184, 134, 11, 0.06);
            padding: 24px;
            height: 100%;
        }

        .card-header-custom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1.5px solid var(--gold-border);
        }

        .card-header-title {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--gold-deep);
            margin: 0;
        }

        .form-label-custom {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .form-control-custom {
            background-color: #FAF8F5;
            border: 1.5px solid rgba(212, 175, 55, 0.25);
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 14px;
            color: var(--text-dark);
            transition: all 0.2s ease;
        }

        .form-control-custom:focus {
            background-color: #FFFFFF;
            border-color: var(--gold-primary);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
            outline: none;
        }

        .btn-gold-submit {
            background: linear-gradient(135deg, #dab858 0%, #d1b05b 100%);
            color: #FFFFFF;
            font-weight: 600;
            font-size: 14px;
            border-radius: 12px;
            padding: 12px 20px;
            border: none;
            box-shadow: 0 4px 12px rgba(217, 56, 72, 0.25);
            transition: all 0.25s ease;
        }

        .btn-gold-submit:hover {
            background: linear-gradient(135deg, #ffb618 0%, #ffd53ef4 100%);
            color: #FFFFFF;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(217, 56, 72, 0.35);
        }

        /* Existing Categories Table Card */
        .table-card {
            background-color: #FFFFFF;
            border: 1px solid var(--gold-border);
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(184, 134, 11, 0.06);
            overflow: hidden;
        }

        .table-card-header {
            padding: 18px 24px;
            background-color: #FAF6F0;
            border-bottom: 1.5px solid var(--gold-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .table-card-title {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--gold-deep);
            margin: 0;
        }

        .category-table {
            margin: 0;
            width: 100%;
        }

        .category-table th {
            background-color: #FAF6F0;
            color: var(--gold-deep);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 14px 18px;
            border-bottom: 1px solid var(--gold-border);
        }

        .category-table td {
            padding: 16px 18px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.15);
            vertical-align: middle;
        }

        .sl-number {
            font-weight: 600;
            color: var(--text-muted);
            font-size: 14px;
        }

        .category-name-text {
            font-weight: 600;
            font-size: 15px;
            color: var(--text-dark);
            margin-bottom: 2px;
        }

        .category-desc-text {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.4;
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

        .btn-action-icon.delete-btn:hover:not(:disabled) {
            background-color: #D93848;
            border-color: #D93848;
            color: #FFFFFF;
        }

        .btn-action-icon.toggle-btn-active {
            color: #198754;
            border-color: rgba(25, 135, 84, 0.3);
        }

        .btn-action-icon.toggle-btn-active:hover:not(:disabled) {
            background-color: #198754;
            border-color: #198754;
            color: #FFFFFF;
        }

        .btn-action-icon.toggle-btn-disabled {
            color: #6c757d;
            border-color: rgba(108, 117, 125, 0.3);
        }

        .btn-action-icon.toggle-btn-disabled:hover:not(:disabled) {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #FFFFFF;
        }

        .btn-action-icon:disabled,
        .btn-action-icon.disabled-blocked {
            opacity: 0.45;
            cursor: not-allowed;
        }

        .badge-product-count {
            background-color: #FAF8F5;
            border: 1px solid var(--gold-border);
            color: var(--text-dark);
            font-size: 12px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 20px;
        }

        .search-input-box {
            position: relative;
            max-width: 220px;
        }

        .search-input-box input {
            padding-left: 36px;
            height: 38px;
            font-size: 13px;
        }

        .search-input-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gold-deep);
        }

        /* Filter Sort Button & Dropdown */
        .btn-filter-sort {
            height: 38px;
            width: 38px;
            border-radius: 10px;
            background-color: #FAF8F5;
            border: 1px solid var(--gold-border);
            color: var(--gold-deep);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-filter-sort:hover,
        .btn-filter-sort:focus {
            background-color: var(--gold-deep);
            color: #FFFFFF;
            border-color: var(--gold-deep);
        }

        .dropdown-menu-sort {
            border-radius: 14px;
            border: 1px solid var(--gold-border);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 8px;
            min-width: 200px;
        }

        .dropdown-item-sort {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-dark);
            border-radius: 8px;
            padding: 8px 12px;
            transition: all 0.15s ease;
        }

        .dropdown-item-sort:hover,
        .dropdown-item-sort.active {
            background-color: #FAF6F0;
            color: var(--gold-deep);
            font-weight: 600;
        }

        /* Modern Alert Modal Styling */
        .modal-alert-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            padding: 30px 24px 24px 24px;
            text-align: center;
        }

        .modal-alert-icon-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            border: 3px solid #E63946;
            background-color: #FFF5F5;
            color: #E63946;
            font-size: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .modal-alert-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 22px;
            color: #2A241D;
            margin-bottom: 8px;
        }

        .modal-alert-message {
            font-size: 14px;
            color: #6C757D;
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .btn-modal-cancel {
            background-color: #FFFFFF;
            border: 1px solid #E0E0E0 !important;
            color: #2A241D;
            font-weight: 600;
            border-radius: 10px;
            padding: 10px 20px;
            transition: all 0.2s ease;
        }

        .btn-modal-cancel:hover {
            background-color: #F8F9FA;
            color: #000000;
        }

        .btn-modal-action {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            border: none;
            transition: all 0.2s ease;
        }


        .table-card {
            overflow: visible !important;
        }

        /* ========================================== */
        /* Mobile Responsiveness Fixes for Category   */
        /* ========================================== */
        @media (max-width: 768px) {

            /* Container padding mobile e komano holo */
            .container-fluid {
                padding: 12px !important;
            }

            /* Form card ar Table card er padding mobile e adjust kora holo */
            .form-card,
            .table-card {
                padding: 16px !important;
                border-radius: 12px;
            }

            /* Table header toolbar (Search + Filter icon) stack kora holo */
            .table-card-header {
                flex-direction: column;
                align-items: stretch !important;
                gap: 12px;
                padding: 14px 16px;
            }

            .search-input-box {
                max-width: 100% !important;
                width: 100%;
            }

            .table-card-header .d-flex.align-items-center.gap-2 {
                width: 100%;
                justify-content: space-between;
            }

            .btn-filter-sort {
                height: 38px;
                width: 42px;
                flex-shrink: 0;
            }

            /* Table text padding ar font size mobile er jonno choto kora holo */
            .category-table th,
            .category-table td {
                padding: 12px 10px !important;
                font-size: 13px !important;
            }

            /* Total Products badge mobile e wrap howa rokher jonno */
            .badge-product-count {
                padding: 4px 8px !important;
                font-size: 11px !important;
                white-space: nowrap;
            }

            /* Action buttons gulo mobile e jeno pasapashi thake aroverflow na hoy */
            .category-table td .d-inline-flex {
                gap: 3px !important;
            }

            .btn-action-icon {
                width: 32px !important;
                height: 32px !important;
                font-size: 12px !important;
                margin-left: 2px !important;
                border-radius: 8px;
            }


            .dropdown-menu-sort {
                position: fixed !important;
                top: auto !important;
                bottom: 20px !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                width: 90% !important;
                max-width: 320px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3) !important;
                z-index: 9999 !important;
            }
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

                <!-- Alert Messages Container -->
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>
                        <?php
                        echo $_SESSION['success_message'];
                        unset($_SESSION['success_message']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                        <?php
                        echo $_SESSION['error_message'];
                        unset($_SESSION['error_message']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Top Dynamic Stat Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-12 col-md-4">
                        <div class="stat-card">
                            <div>
                                <div class="stat-title">TOTAL CATEGORIES</div>
                                <div class="stat-value"><?php echo $totalCategories; ?></div>
                            </div>
                            <div class="stat-icon-box">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="stat-card">
                            <div>
                                <div class="stat-title">ACTIVE CATEGORIES</div>
                                <div class="stat-value text-success"><?php echo $activeCategories; ?></div>
                            </div>
                            <div class="stat-icon-box">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="stat-card">
                            <div>
                                <div class="stat-title">UNUSED CATEGORIES</div>
                                <div class="stat-value text-secondary"><?php echo $unusedCategories; ?></div>
                            </div>
                            <div class="stat-icon-box">
                                <i class="fa-solid fa-folder-open"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid (Form Left + Existing Categories Table Right) -->
                <div class="row g-4">

                    <!-- Left Column: Add / Edit Form -->
                    <div class="col-12 col-lg-4">
                        <div class="form-card">
                            <div class="card-header-custom">
                                <h3 class="card-header-title">
                                    <?php if ($editCategory): ?>
                                        <i class="fa-solid fa-pen-to-square me-2"></i>Edit Category
                                    <?php else: ?>
                                        <i class="fa-solid fa-plus-circle me-2"></i>Add New Category
                                    <?php endif; ?>
                                </h3>
                                <?php if ($editCategory): ?>
                                    <a href="category.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3" title="Cancel Edit">
                                        <i class="fa-solid fa-xmark me-1"></i>Cancel
                                    </a>
                                <?php endif; ?>
                            </div>

                            <form action="category_action.php" method="POST" id="categoryForm">
                                <input type="hidden" name="action" value="<?php echo $editCategory ? 'update' : 'insert'; ?>">
                                <?php if ($editCategory): ?>
                                    <input type="hidden" name="id" value="<?php echo $editCategory['id']; ?>">
                                <?php endif; ?>

                                <!-- Category Name Input -->
                                <div class="mb-3">
                                    <label class="form-label-custom">Category Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                        name="name"
                                        class="form-control form-control-custom"
                                        placeholder="e.g. Smart Home / Paintings"
                                        value="<?php echo $editCategory ? htmlspecialchars($editCategory['name']) : ''; ?>"
                                        required>
                                </div>

                                <!-- Description Input -->
                                <div class="mb-4">
                                    <label class="form-label-custom">Description</label>
                                    <textarea name="description"
                                        class="form-control form-control-custom"
                                        rows="4"
                                        placeholder="Brief description of the category..."><?php echo $editCategory ? htmlspecialchars($editCategory['description']) : ''; ?></textarea>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-gold-submit w-100">
                                    <?php if ($editCategory): ?>
                                        <i class="fa-solid fa-arrows-rotate me-2"></i>Update Category
                                    <?php else: ?>
                                        <i class="fa-solid fa-floppy-disk me-2"></i>Save Category
                                    <?php endif; ?>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Right Column: Existing Categories Table -->
                    <div class="col-12 col-lg-8">
                        <div class="table-card">
                            <!-- Table Header Toolbar -->
                            <div class="table-card-header">
                                <h5 class="table-card-title">
                                    <i class="fa-solid fa-list me-2"></i>Existing Categories
                                </h5>

                                <div class="d-flex align-items-center gap-2">
                                    <!-- KeyUp Live Search Box -->
                                    <div class="search-input-box">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                        <input type="text"
                                            id="searchInput"
                                            name="search"
                                            class="form-control form-control-custom"
                                            placeholder="Search categories..."
                                            value="<?php echo htmlspecialchars($search); ?>"
                                            autocomplete="off">
                                    </div>

                                    <!-- Filter Sort Dropdown -->
                                    <div class="dropdown">
                                        <button class="btn btn-filter-sort"
                                            type="button"
                                            id="sortDropdownBtn"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            title="Sort Categories">
                                            <i class="fa-solid fa-filter"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-sort" aria-labelledby="sortDropdownBtn">
                                            <li class="dropdown-header text-muted small fw-bold px-3 py-1 text-uppercase">Sort Categories By</li>
                                            <li><a class="dropdown-item dropdown-item-sort sort-item <?php echo $sort === 'name_asc' ? 'active' : ''; ?>" href="#" data-sort="name_asc">Name (A to Z)</a></li>
                                            <li><a class="dropdown-item dropdown-item-sort sort-item <?php echo $sort === 'name_desc' ? 'active' : ''; ?>" href="#" data-sort="name_desc">Name (Z to A)</a></li>
                                            <li><a class="dropdown-item dropdown-item-sort sort-item <?php echo $sort === 'products_desc' ? 'active' : ''; ?>" href="#" data-sort="products_desc">Most Products</a></li>
                                            <li><a class="dropdown-item dropdown-item-sort sort-item <?php echo $sort === 'products_asc' ? 'active' : ''; ?>" href="#" data-sort="products_asc">Least Products</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Table Section -->
                            <div class="table-responsive">
                                <table class="table category-table align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width: 20px;">SL</th>
                                            <th>Category Name</th>
                                            <th class="text-center" style="width: 120px;">Status</th>
                                            <th class="text-center" style="width: 170px;">Total Products</th>
                                            <th class="text-center" style="width: 130px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="categoryTableBody">
                                        <?php renderCategoryRows($categoriesList); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    <!-- Custom Modern Confirmation & Alert Modal -->
    <div class="modal fade" id="modernAlertModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
            <div class="modal-content modal-alert-content">
                <div class="modal-body p-0">
                    <!-- Icon Circle -->
                    <div class="modal-alert-icon-circle" id="modalIconCircle">
                        <i id="modalIcon" class="fa-solid fa-xmark"></i>
                    </div>

                    <!-- Modal Title -->
                    <h4 id="modalTitle" class="modal-alert-title">Are you sure?</h4>

                    <!-- Modal Message -->
                    <p id="modalMessage" class="modal-alert-message">
                        Do you really want to delete this category? This process cannot be undone.
                    </p>

                    <!-- Modal Action Buttons -->
                    <div id="modalButtonsContainer" class="d-flex align-items-center justify-content-center gap-2">
                        <button type="button" class="btn btn-modal-cancel w-50" data-bs-dismiss="modal">Cancel</button>
                        <a id="modalConfirmActionBtn" href="#" class="btn btn-danger btn-modal-action w-50" style="background-color: #E63946;">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="../asset/bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alert messages after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 4000);

        // Modern Modal Logic & KeyUp Live Search & Sorting without Page Reload
        let modernModalInstance = null;
        let currentSort = '<?php echo htmlspecialchars($sort); ?>';
        let searchDebounceTimer = null;

        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.getElementById('modernAlertModal');
            if (modalElement) {
                modernModalInstance = new bootstrap.Modal(modalElement);
            }

            // KeyUp Live Search Listener
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    clearTimeout(searchDebounceTimer);
                    searchDebounceTimer = setTimeout(function() {
                        fetchCategoriesNoReload();
                    }, 250);
                });
            }

            // Sort Dropdown Option Listener
            const sortItems = document.querySelectorAll('.sort-item');
            sortItems.forEach(function(item) {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    sortItems.forEach(el => el.classList.remove('active'));
                    this.classList.add('active');
                    currentSort = this.getAttribute('data-sort');
                    fetchCategoriesNoReload();
                });
            });
        });

        // AJAX Fetch Categories with no page reload
        function fetchCategoriesNoReload() {
            const searchInput = document.getElementById('searchInput');
            const searchVal = searchInput ? searchInput.value.trim() : '';
            const tbody = document.getElementById('categoryTableBody');

            if (!tbody) return;
            tbody.style.opacity = '0.5';

            const fetchUrl = `category.php?ajax=1&search=${encodeURIComponent(searchVal)}&sort=${encodeURIComponent(currentSort)}`;

            fetch(fetchUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    tbody.style.opacity = '1';
                    if (data.status === 'success') {
                        tbody.innerHTML = data.html;
                    }
                })
                .catch(err => {
                    tbody.style.opacity = '1';
                    console.error('Failed to fetch categories:', err);
                });
        }

        // Show Modern Confirmation Modal
        function showConfirmModal(title, message, confirmUrl, confirmBtnText, btnClass) {
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalMessage').innerText = message;

            const iconCircle = document.getElementById('modalIconCircle');
            const modalIcon = document.getElementById('modalIcon');
            const buttonsContainer = document.getElementById('modalButtonsContainer');

            iconCircle.style.borderColor = '#E63946';
            iconCircle.style.backgroundColor = '#FFF5F5';
            iconCircle.style.color = '#E63946';
            modalIcon.className = 'fa-solid fa-xmark';

            const bgBtnColor = (btnClass === 'btn-warning') ? '#f0ad4e' : ((btnClass === 'btn-success') ? '#198754' : '#E63946');

            buttonsContainer.innerHTML = `
                <button type="button" class="btn btn-modal-cancel w-50" data-bs-dismiss="modal">Cancel</button>
                <a href="${confirmUrl}" class="btn ${btnClass} btn-modal-action w-50" style="background-color: ${bgBtnColor}; border: none;">${confirmBtnText}</a>
            `;

            if (modernModalInstance) {
                modernModalInstance.show();
            }
        }

        // Show Modern Alert Modal for Blocked Actions
        function showBlockedAlias(title, message) {
            // alias for blocked alert
        }

        function showBlockedAlert(title, message) {
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalMessage').innerText = message;

            const iconCircle = document.getElementById('modalIconCircle');
            const modalIcon = document.getElementById('modalIcon');
            const buttonsContainer = document.getElementById('modalButtonsContainer');

            iconCircle.style.borderColor = '#E63946';
            iconCircle.style.backgroundColor = '#FFF5F5';
            iconCircle.style.color = '#E63946';
            modalIcon.className = 'fa-solid fa-exclamation';

            buttonsContainer.innerHTML = `
                <button type="button" class="btn btn-danger btn-modal-action w-100" data-bs-dismiss="modal" style="background-color: #E63946; border: none;">Got It</button>
            `;

            if (modernModalInstance) {
                modernModalInstance.show();
            }
        }
    </script>
</body>

</html>