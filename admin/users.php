<?php
session_start();
require_once(__DIR__ . "/../connection.php");
/** @var mysqli $connect */
require_once 'includes/pagination.php';

// Check admin authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('HTTP/1.1 401 Unauthorized');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    header("Location: index.php");
    exit;
}

$pageTitle = "Customers Management";
$currentPage = "users.php";

// Get Filter & Pagination Parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'newest';
$month = isset($_GET['month']) ? trim($_GET['month']) : '';
$startDate = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
$endDate = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = isset($_GET['per_page']) ? max(1, (int)$_GET['per_page']) : 5;

// Build SQL WHERE Conditions
$whereClause = [];

if (!empty($search)) {
    $safeSearch = mysqli_real_escape_string($connect, $search);
    $whereClause[] = "(name LIKE '%$safeSearch%' OR email LIKE '%$safeSearch%' OR phone LIKE '%$safeSearch%')";
}

if (!empty($month)) {
    $safeMonth = mysqli_real_escape_string($connect, $month);
    $whereClause[] = "DATE_FORMAT(created_at, '%Y-%m') = '$safeMonth'";
}

if (!empty($startDate)) {
    $safeStart = mysqli_real_escape_string($connect, $startDate);
    $whereClause[] = "DATE(created_at) >= '$safeStart'";
}

if (!empty($endDate)) {
    $safeEnd = mysqli_real_escape_string($connect, $endDate);
    $whereClause[] = "DATE(created_at) <= '$safeEnd'";
}

$whereSql = "";
if (count($whereClause) > 0) {
    $whereSql = "WHERE " . implode(" AND ", $whereClause);
}

// Build SQL ORDER BY Condition
$orderSql = "ORDER BY created_at DESC";
switch ($sort) {
    case 'oldest':
        $orderSql = "ORDER BY created_at ASC";
        break;
    case 'name_asc':
        $orderSql = "ORDER BY name ASC";
        break;
    case 'name_desc':
        $orderSql = "ORDER BY name DESC";
        break;
    case 'newest':
    default:
        $orderSql = "ORDER BY created_at DESC";
        break;
}

// Total Count Query for Pagination
$countQuery = "SELECT COUNT(*) as total FROM users $whereSql";
$countResult = mysqli_query($connect, $countQuery);
$totalRecords = 0;
if ($countResult) {
    $countRow = mysqli_fetch_assoc($countResult);
    $totalRecords = (int)$countRow['total'];
}

$totalPages = ceil($totalRecords / $perPage);
if ($page > $totalPages && $totalPages > 0) {
    $page = $totalPages;
}
$offset = ($page - 1) * $perPage;

// Fetch Customers Data Query
$dataQuery = "SELECT * FROM users $whereSql $orderSql LIMIT $offset, $perPage";
$usersResult = mysqli_query($connect, $dataQuery);

// Handle AJAX Request (Partial rendering for live keyup search & filters)
$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || isset($_GET['ajax']);

if ($isAjax) {
    ob_start();
    includeTableContent($usersResult, $page, $perPage, $totalRecords, $totalPages, $_GET);
    $tableHtml = ob_get_clean();
    echo json_encode([
        'status' => 'success',
        'html' => $tableHtml,
        'totalRecords' => $totalRecords
    ]);
    exit;
}

// Dynamic Dashboard Stat Calculations
$statTotal = 0;
$statMonth = 0;
$statWithPhone = 0;
$statToday = 0;

$qTotal = mysqli_query($connect, "SELECT COUNT(*) as total FROM users");
if ($qTotal) {
    $statTotal = mysqli_fetch_assoc($qTotal)['total'];
}

$qMonth = mysqli_query($connect, "SELECT COUNT(*) as total FROM users WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
if ($qMonth) {
    $statMonth = mysqli_fetch_assoc($qMonth)['total'];
}

$qPhone = mysqli_query($connect, "SELECT COUNT(*) as total FROM users WHERE phone IS NOT NULL AND phone != ''");
if ($qPhone) {
    $statWithPhone = mysqli_fetch_assoc($qPhone)['total'];
}

$qToday = mysqli_query($connect, "SELECT COUNT(*) as total FROM users WHERE DATE(created_at) = CURRENT_DATE()");
if ($qToday) {
    $statToday = mysqli_fetch_assoc($qToday)['total'];
}

// Function to render table content and pagination
function includeTableContent($result, $page, $perPage, $totalRecords, $totalPages, $queryParams)
{
?>
    <!-- Table Card Header Bar: Title + Page Size Selector -->
    <div class="table-card-header">
        <h5 class="table-card-title m-0">
            <i class="fa-solid fa-users me-2"></i>Customers List
        </h5>
        <!-- Top Page Size Selector Dropdown: Showing [ 5 ˅ ] Result -->
        <?php renderPageSizeSelector($perPage, [5, 10, 25, 50, 100]); ?>
    </div>

    <!-- Main Data Table -->
    <div class="table-responsive">
        <table class="table customer-table align-middle">
            <thead>
                <tr>
                    <th style="width: 60px;">SL #</th>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                    <th>Registered Date</th>
                    <th class="text-end" style="width: 140px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0):
                    $sl = (($page - 1) * $perPage) + 1;
                    while ($user = mysqli_fetch_assoc($result)):
                        $userImg = !empty($user['image']) ? '../' . htmlspecialchars($user['image']) : '../asset/image/default-image.jpg';
                        if (!file_exists($userImg) && !empty($user['image'])) {
                            $userImg = '../asset/image/default-image.jpg';
                        }
                        $phone = !empty($user['phone']) ? htmlspecialchars($user['phone']) : 'N/A';
                        $createdDate = !empty($user['created_at']) ? date('d M Y, h:i A', strtotime($user['created_at'])) : 'N/A';
                ?>
                        <tr>
                            <td>
                                <span class="sl-number"><?php echo sprintf("%02d", $sl++); ?></span>
                            </td>
                            <td>
                                <div class="customer-info-box">
                                    <img src="<?php echo $userImg; ?>" alt="<?php echo htmlspecialchars($user['name']); ?>" class="customer-avatar" onerror="this.src='../asset/image/default-image.jpg'">
                                    <div class="customer-details">
                                        <span class="customer-name"><?php echo htmlspecialchars($user['name']); ?></span>
                                        <span class="customer-email"><i class="fa-regular fa-envelope me-1"></i><?php echo htmlspecialchars($user['email']); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($user['phone'])): ?>
                                    <span class="phone-badge"><i class="fa-solid fa-phone me-1"></i><?php echo $phone; ?></span>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted">No Phone</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="date-badge"><i class="fa-regular fa-calendar me-1"></i><?php echo $createdDate; ?></span>
                            </td>
                            <td class="text-end">
                                <div class="action-buttons-wrapper justify-content-end">
                                    <!-- Mobile Direct Call Button (Only visible on mobile screens) -->
                                    <?php if (!empty($user['phone'])): ?>
                                        <a href="tel:<?php echo $phone; ?>" class="btn-call-mobile d-md-none" title="Call Customer directly">
                                            <i class="fa-solid fa-phone"></i>
                                            <span>Call</span>
                                        </a>
                                    <?php endif; ?>

                                    <!-- Desktop Direct Call Button -->
                                    <?php if (!empty($user['phone'])): ?>
                                        <a href="tel:<?php echo $phone; ?>" class="btn-action-icon d-none d-md-inline-flex call-btn" title="Call Customer">
                                            <i class="fa-solid fa-phone"></i>
                                        </a>
                                    <?php endif; ?>

                                    <!-- View Details Modal Trigger -->
                                    <button type="button" class="btn-action-icon view-btn" title="View Customer Details"
                                        onclick="viewCustomerDetails(<?php echo htmlspecialchars(json_encode($user)); ?>)">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php
                    endwhile;
                else:
                    ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fa-solid fa-users-slash empty-icon"></i>
                                <h4>No Customers Found</h4>
                                <p class="text-muted">No customers matched your search query or filter criteria.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Reusable Bottom Pagination Component -->
    <?php renderPagination($totalPages, $page, $totalRecords, $perPage, $queryParams, 'customers'); ?>
<?php
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - Siddha Art Creation Admin</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../asset/image/logo.png">
    <!-- Bootstrap 5 CSS -->
    <link href="../asset/bootstrap-5.3.7-dist/css/bootstrap.min.css" rel="stylesheet" onerror="this.onerror=null;this.href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css';">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --gold-primary: #D4AF37;
            --gold-deep: #B8860B;
            --gold-light: #F3E5AB;
            --gold-gradient: linear-gradient(135deg, #DFBA5A 0%, #C59B27 50%, #9B781E 100%);
            --gold-border: rgba(212, 175, 55, 0.25);
            --text-dark: #2A241D;
            --text-muted: #7C7267;
        }

        /* Stat Cards */
        .stat-card {
            background-color: #FFFFFF;
            border: 1px solid var(--gold-border);
            border-radius: 16px;
            padding: 20px 24px;
            box-shadow: 0 4px 15px rgba(184, 134, 11, 0.05);
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(184, 134, 11, 0.12);
        }

        .stat-details {
            display: flex;
            flex-direction: column;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
            margin-top: 4px;
        }

        .stat-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(184, 134, 11, 0.08) 100%);
            color: var(--gold-deep);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        /* Compact Header Search & Filter Toolbar */
        .toolbar-header-card {
            background-color: #FFFFFF;
            border: 1px solid var(--gold-border);
            border-radius: 16px;
            padding: 14px 20px;
            margin-bottom: 24px;
            box-shadow: 0 4px 15px rgba(184, 134, 11, 0.04);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .search-container-main {
            flex: 1;
            max-width: 550px;
            position: relative;
        }

        .search-input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gold-deep);
            font-size: 15px;
            pointer-events: none;
        }

        .form-control-custom,
        .form-select-custom {
            width: 100%;
            padding: 11px 16px 11px 44px;
            background-color: #FAF8F5;
            border: 1.5px solid rgba(212, 175, 55, 0.25);
            border-radius: 12px;
            color: var(--text-dark);
            font-size: 14px;
            font-family: 'Outfit', sans-serif;
            transition: all 0.25s ease;
        }

        .form-select-custom {
            padding-left: 14px;
            cursor: pointer;
        }

        .form-control-custom:focus,
        .form-select-custom:focus {
            outline: none;
            border-color: var(--gold-primary);
            background-color: #FFFFFF;
            box-shadow: 0 0 0 3.5px rgba(212, 175, 55, 0.2);
        }

        /* Filter Canvas Trigger Button */
        .btn-filter-canvas {
            padding: 11px 20px;
            border-radius: 12px;
            background-color: #FAF8F5;
            border: 1.5px solid var(--gold-border);
            color: var(--gold-deep);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s ease;
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(184, 134, 11, 0.06);
            position: relative;
        }

        .btn-filter-canvas:hover {
            background-color: var(--gold-light);
            color: var(--text-dark);
            border-color: var(--gold-primary);
            transform: translateY(-1px);
        }

        .badge-filter-count {
            background: var(--gold-gradient);
            color: #1A1612;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 10px;
        }

        /* Filter Offcanvas Drawer Styling */
        .filter-offcanvas {
            width: 380px !important;
            border-left: 1px solid var(--gold-border);
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.1);
        }

        .filter-offcanvas .offcanvas-header {
            background: linear-gradient(180deg, #FAF6F0 0%, #FFFFFF 100%);
            border-bottom: 1px solid var(--gold-border);
            padding: 20px 24px;
        }

        .filter-offcanvas .offcanvas-title {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--gold-deep);
        }

        .filter-offcanvas .offcanvas-body {
            padding: 24px;
            background-color: #FFFFFF;
        }

        .filter-section-title {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 8px;
            margin-top: 18px;
        }

        .filter-section-title:first-child {
            margin-top: 0;
        }

        .btn-gold-fill {
            width: 100%;
            padding: 13px;
            background: var(--gold-gradient);
            border: none;
            border-radius: 12px;
            color: #1A1612;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 15px rgba(197, 155, 39, 0.3);
        }

        .btn-gold-fill:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(197, 155, 39, 0.45);
        }

        .btn-reset-outline {
            width: 100%;
            padding: 11px;
            background-color: #FAF8F5;
            border: 1.5px solid var(--gold-border);
            border-radius: 12px;
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-reset-outline:hover {
            background-color: #F5EFE6;
            color: var(--text-dark);
        }

        /* Customer Table Container Card */
        .table-card {
            background-color: #FFFFFF;
            border: 1px solid var(--gold-border);
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(184, 134, 11, 0.06);
            overflow: hidden;
        }

        .table-card-header {
            padding: 16px 24px;
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
        }

        .customer-table {
            margin-bottom: 0;
            width: 100%;
        }

        .customer-table thead th {
            background-color: #FAF8F5;
            color: var(--gold-deep);
            font-family: 'Playfair Display', serif;
            font-size: 14px;
            font-weight: 700;
            padding: 14px 20px;
            border-bottom: 1.5px solid var(--gold-border);
            white-space: nowrap;
        }

        .customer-table tbody td {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.12);
            color: var(--text-dark);
            font-size: 14px;
        }

        .customer-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .customer-table tbody tr:hover {
            background-color: rgba(212, 175, 55, 0.04);
        }

        .sl-number {
            font-weight: 700;
            color: var(--gold-deep);
            font-size: 13px;
        }

        .customer-info-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .customer-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 1.5px solid var(--gold-border);
            box-shadow: 0 2px 6px rgba(184, 134, 11, 0.15);
        }

        .customer-details {
            display: flex;
            flex-direction: column;
        }

        .customer-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 15px;
        }

        .customer-email {
            font-size: 12px;
            color: var(--text-muted);
        }

        .phone-badge,
        .date-badge {
            font-size: 13px;
            color: var(--text-dark);
            font-weight: 500;
        }

        .action-buttons-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Mobile Specific Call Button */
        .btn-call-mobile {
            padding: 6px 12px;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            color: #FFFFFF !important;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 3px 8px rgba(37, 211, 102, 0.3);
            transition: transform 0.2s ease;
        }

        .btn-call-mobile:active {
            transform: scale(0.96);
        }

        /* Action Buttons */
        .btn-action-icon {
            width: 34px;
            height: 34px;
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
        }

        .btn-action-icon.call-btn:hover {
            background-color: #25D366;
            border-color: #25D366;
            color: #FFFFFF;
        }

        .btn-action-icon.view-btn:hover {
            background-color: var(--gold-deep);
            border-color: var(--gold-deep);
            color: #FFFFFF;
        }

        .empty-state {
            padding: 20px;
        }

        .empty-icon {
            font-size: 48px;
            color: var(--gold-deep);
            opacity: 0.4;
            margin-bottom: 12px;
        }

        @media (max-width: 576px) {
            .toolbar-header-card {
                flex-direction: column;
                align-items: stretch;
            }

            .search-container-main {
                max-width: 100%;
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

                <!-- Dynamic Stat Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-details">
                                <span class="stat-value"><?php echo number_format($statTotal); ?></span>
                                <span class="stat-label">Total Customers</span>
                            </div>
                            <div class="stat-icon-box">
                                <i class="fa-solid fa-users"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-details">
                                <span class="stat-value"><?php echo number_format($statMonth); ?></span>
                                <span class="stat-label">New This Month</span>
                            </div>
                            <div class="stat-icon-box">
                                <i class="fa-solid fa-user-plus"></i>
                            </div>
                        </div>
                    </div>


                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-details">
                                <span class="stat-value"><?php echo number_format($statToday); ?></span>
                                <span class="stat-label">Registered Today</span>
                            </div>
                            <div class="stat-icon-box">
                                <i class="fa-solid fa-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Compact Toolbar: Search Bar + Filter Canvas Trigger Button -->
                <div class="toolbar-header-card">

                    <!-- Search Input (Keyup Triggered Live Search) -->
                    <div class="search-container-main">
                        <i class="fa-solid fa-magnifying-glass search-input-icon"></i>
                        <input type="text" id="customerSearchInput" class="form-control-custom"
                            placeholder="Search by name, email, or phone..."
                            value="<?php echo htmlspecialchars($search); ?>" autocomplete="off">
                    </div>

                    <!-- Filter Canvas Trigger Button -->
                    <button type="button" class="btn-filter-canvas" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                        <i class="fa-solid fa-sliders"></i>
                        <span>Filters</span>
                        <span id="activeFilterBadge" class="badge-filter-count d-none">0</span>
                    </button>
                </div>

                <!-- Customer Table Container -->
                <div class="table-card" id="customerTableContainer">
                    <?php includeTableContent($usersResult, $page, $perPage, $totalRecords, $totalPages, $_GET); ?>
                </div>

            </div>
        </main>
    </div>

    <!-- FILTER CANVAS (Offcanvas Drawer) -->
    <div class="offcanvas offcanvas-end filter-offcanvas" tabindex="-1" id="filterOffcanvas" aria-labelledby="filterOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="filterOffcanvasLabel">
                <i class="fa-solid fa-sliders me-2 text-warning"></i>Filter & Sort
            </h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <form id="filterForm">
                <input type="hidden" name="per_page" id="perPageInput" value="<?php echo $perPage; ?>">

                <!-- Sort Customer -->
                <div class="mb-4">
                    <label class="filter-section-title" for="sortFilter">SORT BY</label>
                    <select name="sort" id="sortFilter" class="form-select-custom">
                        <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Newest First</option>
                        <option value="oldest" <?php echo $sort == 'oldest' ? 'selected' : ''; ?>>Oldest First</option>
                        <option value="name_asc" <?php echo $sort == 'name_asc' ? 'selected' : ''; ?>>Sort Name (A - Z)</option>
                        <option value="name_desc" <?php echo $sort == 'name_desc' ? 'selected' : ''; ?>>Sort Name (Z - A)</option>
                    </select>
                </div>

                <!-- Month Filter -->
                <div class="mb-4">
                    <label class="filter-section-title" for="monthFilter">FILTER BY MONTH</label>
                    <input type="month" name="month" id="monthFilter" class="form-control-custom"
                        value="<?php echo htmlspecialchars($month); ?>">
                </div>

                <!-- Date Range Filters -->
                <div class="mb-4">
                    <label class="filter-section-title">REGISTRATION DATE RANGE</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">From</label>
                            <input type="date" name="start_date" id="startDateFilter" class="form-control-custom px-2"
                                value="<?php echo htmlspecialchars($startDate); ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1">To</label>
                            <input type="date" name="end_date" id="endDateFilter" class="form-control-custom px-2"
                                value="<?php echo htmlspecialchars($endDate); ?>">
                        </div>
                    </div>
                </div>

                <!-- Canvas Action Buttons -->
                <div class="mt-5">
                    <button type="button" class="btn-gold-fill mb-2" onclick="applyFiltersAndClose()">
                        <i class="fa-solid fa-check me-1"></i> Apply Filters
                    </button>
                    <button type="button" class="btn-reset-outline" onclick="resetFilters()">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset All Filters
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Customer Details Modal -->
    <div class="modal fade" id="customerDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 50%, #9B781E 100%);">
                    <h5 class="modal-title font-serif fw-bold" style="font-family: 'Playfair Display', serif;"><i class="fa-solid fa-user-circle me-2"></i>Customer Profile</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <img id="modalAvatar" src="../asset/image/default-image.jpg" class="rounded-circle mb-3 border border-2 border-warning" style="width: 90px; height: 90px; object-fit: cover;">
                    <h4 id="modalName" class="fw-bold mb-1" style="color: #2A241D;">Name</h4>
                    <p id="modalEmail" class="text-muted small mb-3">email@example.com</p>

                    <div class="p-3 bg-light rounded-3 text-start mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fa-solid fa-phone text-warning me-3"></i>
                            <div>
                                <span class="d-block text-muted small">Phone Number</span>
                                <strong id="modalPhone" class="text-dark">N/A</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa-regular fa-calendar text-warning me-3"></i>
                            <div>
                                <span class="d-block text-muted small">Registration Date</span>
                                <strong id="modalDate" class="text-dark">N/A</strong>
                            </div>
                        </div>
                    </div>

                    <div id="modalCallContainer"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="../asset/bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js" onerror="this.onerror=null;this.src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js';"></script>

    <!-- Keyup Dynamic Search & Filter Script -->
    <script>
        let searchTimer;

        // Key-up Event Listener on Main Search Input
        document.getElementById('customerSearchInput').addEventListener('keyup', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                triggerSearch(1);
            }, 300);
        });

        // Change Items Per Page Dynamic Handler
        function changePerPage(val) {
            document.getElementById('perPageInput').value = val;
            triggerSearch(1);
        }

        // Update active filter badge counter
        function updateActiveFilterBadge() {
            const sortVal = document.getElementById('sortFilter').value;
            const monthVal = document.getElementById('monthFilter').value;
            const startVal = document.getElementById('startDateFilter').value;
            const endVal = document.getElementById('endDateFilter').value;

            let count = 0;
            if (sortVal !== 'newest') count++;
            if (monthVal !== '') count++;
            if (startVal !== '') count++;
            if (endVal !== '') count++;

            const badge = document.getElementById('activeFilterBadge');
            if (count > 0) {
                badge.innerText = count;
                badge.classList.remove('d-none');
            } else {
                badge.classList.add('d-none');
            }
        }

        // Trigger AJAX Search & Filter
        function triggerSearch(page = 1) {
            updateActiveFilterBadge();

            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);

            // Append main search input
            const searchValue = document.getElementById('customerSearchInput').value.trim();
            params.set('search', searchValue);
            params.set('page', page);
            params.set('ajax', '1');

            // Show table loading opacity
            const container = document.getElementById('customerTableContainer');
            container.style.opacity = '0.6';

            fetch('users.php?' + params.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    container.style.opacity = '1';
                    if (data.status === 'success') {
                        container.innerHTML = data.html;
                    }
                })
                .catch(err => {
                    container.style.opacity = '1';
                    console.error("AJAX Search Error:", err);
                });
        }

        // Apply Filters and Close Canvas
        function applyFiltersAndClose() {
            triggerSearch(1);
            const canvasElem = document.getElementById('filterOffcanvas');
            const bsCanvas = bootstrap.Offcanvas.getInstance(canvasElem);
            if (bsCanvas) {
                bsCanvas.hide();
            }
        }

        // Reset Filters
        function resetFilters() {
            document.getElementById('customerSearchInput').value = '';
            document.getElementById('sortFilter').value = 'newest';
            document.getElementById('monthFilter').value = '';
            document.getElementById('startDateFilter').value = '';
            document.getElementById('endDateFilter').value = '';
            document.getElementById('perPageInput').value = '10';
            triggerSearch(1);

            const canvasElem = document.getElementById('filterOffcanvas');
            const bsCanvas = bootstrap.Offcanvas.getInstance(canvasElem);
            if (bsCanvas) {
                bsCanvas.hide();
            }
        }

        // View Customer Details Modal
        function viewCustomerDetails(user) {
            document.getElementById('modalName').innerText = user.name || 'N/A';
            document.getElementById('modalEmail').innerText = user.email || 'N/A';
            document.getElementById('modalPhone').innerText = user.phone || 'N/A';

            const avatar = user.image ? '../' + user.image : '../asset/image/default-image.jpg';
            const imgElem = document.getElementById('modalAvatar');
            imgElem.src = avatar;
            imgElem.onerror = function() {
                this.src = '../asset/image/default-image.jpg';
            };

            const regDate = user.created_at ? new Date(user.created_at).toLocaleString('en-US', {
                dateStyle: 'medium',
                timeStyle: 'short'
            }) : 'N/A';
            document.getElementById('modalDate').innerText = regDate;

            const callBox = document.getElementById('modalCallContainer');
            if (user.phone) {
                callBox.innerHTML = `
                    <a href="tel:${user.phone}" class="btn w-100 fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); border-radius: 12px;">
                        <i class="fa-solid fa-phone me-2"></i> Call Customer Now
                    </a>
                `;
            } else {
                callBox.innerHTML = '';
            }

            const modal = new bootstrap.Modal(document.getElementById('customerDetailsModal'));
            modal.show();
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateActiveFilterBadge();
        });
    </script>
</body>

</html>