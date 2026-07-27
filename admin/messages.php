<?php
session_start();
require_once(__DIR__ . "/../connection.php");
/** @var mysqli $connect */
require_once 'includes/pagination.php';

// Check admin authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$pageTitle = "Contact Messages";
$currentPage = "messages.php";

// Calculate Top Dynamic Stats
$totalMessages = 0;
$unreadMessages = 0;
$readMessages = 0;

$statTotalRes = mysqli_query($connect, "SELECT COUNT(*) as cnt FROM contact_messages");
if ($statTotalRes) {
    $row = mysqli_fetch_assoc($statTotalRes);
    $totalMessages = (int)$row['cnt'];
}

$statUnreadRes = mysqli_query($connect, "SELECT COUNT(*) as cnt FROM contact_messages WHERE status = 0");
if ($statUnreadRes) {
    $row = mysqli_fetch_assoc($statUnreadRes);
    $unreadMessages = (int)$row['cnt'];
}

$statReadRes = mysqli_query($connect, "SELECT COUNT(*) as cnt FROM contact_messages WHERE status = 1");
if ($statReadRes) {
    $row = mysqli_fetch_assoc($statReadRes);
    $readMessages = (int)$row['cnt'];
}

// Handle Search, Filter & Pagination Parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter = isset($_GET['filter']) ? trim($_GET['filter']) : 'all';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = isset($_GET['per_page']) ? max(1, (int)$_GET['per_page']) : 10;

$whereClauses = [];

if (!empty($search)) {
    $safeSearch = mysqli_real_escape_string($connect, $search);
    $whereClauses[] = "(name LIKE '%$safeSearch%' OR email LIKE '%$safeSearch%' OR phone LIKE '%$safeSearch%' OR subject LIKE '%$safeSearch%' OR message LIKE '%$safeSearch%')";
}

if ($filter === 'unread') {
    $whereClauses[] = "status = 0";
} elseif ($filter === 'read') {
    $whereClauses[] = "status = 1";
}

$whereSQL = "";
if (count($whereClauses) > 0) {
    $whereSQL = "WHERE " . implode(" AND ", $whereClauses);
}

// Count Total Filtered Records for Pagination
$countQuery = "SELECT COUNT(*) as cnt FROM contact_messages $whereSQL";
$countRes = mysqli_query($connect, $countQuery);
$totalRecords = 0;
if ($countRes) {
    $row = mysqli_fetch_assoc($countRes);
    $totalRecords = (int)$row['cnt'];
}

$totalPages = ceil($totalRecords / $perPage);
if ($page > $totalPages && $totalPages > 0) {
    $page = $totalPages;
}
$offset = ($page - 1) * $perPage;

// Fetch Paginated Messages
$query = "SELECT * FROM contact_messages $whereSQL ORDER BY created_at DESC LIMIT $offset, $perPage";
$messagesResult = mysqli_query($connect, $query);

$messagesList = [];
if ($messagesResult && mysqli_num_rows($messagesResult) > 0) {
    while ($row = mysqli_fetch_assoc($messagesResult)) {
        $messagesList[] = $row;
    }
}

// Prepare URL query parameters array for pagination component
$queryParams = [
    'search' => $search,
    'filter' => $filter,
    'per_page' => $perPage
];

// Handle AJAX Request (Partial rendering for live search & pagination without page reload)
$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || isset($_GET['ajax']);

if ($isAjax) {
    ob_start();
    renderMessagesTableCard($messagesList, $page, $perPage, $totalRecords, $totalPages, $queryParams, $search, $filter, $totalMessages, $unreadMessages, $readMessages, $offset);
    $tableHtml = ob_get_clean();
    echo json_encode([
        'status' => 'success',
        'html' => $tableHtml,
        'totalRecords' => $totalRecords
    ]);
    exit;
}

// Helper Function to Render Messages Table Card Content
function renderMessagesTableCard($messagesList, $page, $perPage, $totalRecords, $totalPages, $queryParams, $search, $filter, $totalMessages, $unreadMessages, $readMessages, $offset)
{
?>
    <div class="table-card-header">
        <h3 class="table-card-title">
            <i class="fa-solid fa-comments me-2"></i>Contact Messages
        </h3>

        <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
            <!-- Select Rows Dropdown -->
            <?php renderPageSizeSelector($perPage, [5, 10, 25, 50, 100], 'changePerPage(this.value)'); ?>

            <!-- Filter Pills -->
            <div class="d-flex align-items-center gap-1">
                <button type="button" onclick="filterMessages('all')" class="btn-filter-pill <?php echo ($filter === 'all') ? 'active' : ''; ?>">
                    All (<?php echo $totalMessages; ?>)
                </button>
                <button type="button" onclick="filterMessages('unread')" class="btn-filter-pill <?php echo ($filter === 'unread') ? 'active' : ''; ?>">
                    Unread (<?php echo $unreadMessages; ?>)
                </button>
                <button type="button" onclick="filterMessages('read')" class="btn-filter-pill <?php echo ($filter === 'read') ? 'active' : ''; ?>">
                    Read (<?php echo $readMessages; ?>)
                </button>
            </div>

            <!-- Search Input -->
            <div class="search-input-box m-0">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="messageSearchInput" class="form-control" placeholder="Search sender, subject..." value="<?php echo htmlspecialchars($search); ?>" onkeyup="handleSearchInput(this.value)">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table messages-table align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">SL</th>
                    <th>Sender Details</th>
                    <th class="text-start">Subject</th>
                    <th class="text-start">Date</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" style="width: 180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($messagesList)): ?>
                    <?php
                    $sl = $offset + 1;
                    foreach ($messagesList as $msg):
                        $msgId = (int)$msg['id'];
                        $status = (int)$msg['status'];
                        $isUnread = ($status === 0);
                        $formattedDate = date('M d, Y - h:i A', strtotime($msg['created_at']));
                    ?>
                        <tr class="<?php echo $isUnread ? 'unread-row' : ''; ?>">
                            <td>
                                <span class="sl-number"><?php echo $sl++; ?></span>
                            </td>
                            <td>
                                <div class="sender-name"><?php echo htmlspecialchars($msg['name']); ?></div>
                                <div class="sender-meta">
                                    <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" title="Email Sender">
                                        <i class="fa-regular fa-envelope me-1"></i><?php echo htmlspecialchars($msg['email']); ?>
                                    </a>
                                    <?php if (!empty($msg['phone'])): ?>
                                        <span>•</span>
                                        <a href="tel:<?php echo htmlspecialchars($msg['phone']); ?>" title="Call Phone">
                                            <i class="fa-solid fa-phone me-1"></i><?php echo htmlspecialchars($msg['phone']); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="subject-text" title="<?php echo htmlspecialchars($msg['subject']); ?>">
                                    <?php echo !empty($msg['subject']) ? htmlspecialchars($msg['subject']) : '(No Subject)'; ?>
                                </div>
                            </td>
                            <td>
                                <span class="date-text"><?php echo $formattedDate; ?></span>
                            </td>
                            <td class="text-center">
                                <?php if ($isUnread): ?>
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 rounded-pill">
                                        <i class="fa-solid fa-envelope me-1" style="font-size: 9px;"></i>Unread
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill">
                                        <i class="fa-solid fa-circle-check me-1" style="font-size: 9px;"></i>Read
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex align-items-center justify-content-end">
                                    <!-- View Modal Trigger -->
                                    <button type="button"
                                        class="btn-action-icon view-btn"
                                        title="View Details"
                                        onclick="openViewModal(this)"
                                        data-id="<?php echo $msgId; ?>"
                                        data-name="<?php echo htmlspecialchars($msg['name']); ?>"
                                        data-email="<?php echo htmlspecialchars($msg['email']); ?>"
                                        data-phone="<?php echo htmlspecialchars($msg['phone']); ?>"
                                        data-subject="<?php echo htmlspecialchars($msg['subject']); ?>"
                                        data-date="<?php echo $formattedDate; ?>"
                                        data-message="<?php echo htmlspecialchars($msg['message']); ?>"
                                        data-status="<?php echo $status; ?>">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    <!-- Reply via Mailto -->
                                    <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>?subject=<?php echo rawurlencode('Re: ' . ($msg['subject'] ?? '')); ?>"
                                        class="btn-action-icon reply-btn"
                                        title="Reply via Email">
                                        <i class="fa-solid fa-reply"></i>
                                    </a>

                                    <!-- Toggle Status -->
                                    <?php if ($isUnread): ?>
                                        <a href="message_action.php?action=toggle_status&id=<?php echo $msgId; ?>"
                                            class="btn-action-icon toggle-btn-active"
                                            title="Mark as Read">
                                            <i class="fa-solid fa-envelope-circle-check"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="message_action.php?action=toggle_status&id=<?php echo $msgId; ?>"
                                            class="btn-action-icon toggle-btn-disabled"
                                            title="Mark as Unread">
                                            <img src="../asset/image/unread-message.png" width="20" alt="">
                                        </a>
                                    <?php endif; ?>

                                    <!-- Delete Button -->
                                    <button type="button"
                                        class="btn-action-icon delete-btn"
                                        title="Delete Message"
                                        onclick="confirmDelete(<?php echo $msgId; ?>)">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-regular fa-folder-open mb-3 text-secondary" style="font-size: 38px; display: block;"></i>
                            <span style="font-size: 15px; font-weight: 500;">No contact messages found.</span>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Reusable Bottom Pagination Component -->
    <?php renderPagination($totalPages, $page, $totalRecords, $perPage, $queryParams, 'messages'); ?>
<?php
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> | Siddha Art Creation Admin</title>

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../asset/bootstrap-5.3.7-dist/css/bootstrap.min.css">

    <!-- Premium Fonts: Playfair Display & Jost / Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-page: #FAF8F5;
            --bg-card: #FFFFFF;
            --gold-primary: #D4AF37;
            --gold-deep: #B8860B;
            --gold-light: rgba(212, 175, 55, 0.1);
            --gold-border: rgba(212, 175, 55, 0.25);
            --text-dark: #2A241D;
            --text-muted: #6C757D;
            --sidebar-width: 270px;
            --topbar-height: 70px;
        }

        body {
            background-color: var(--bg-page);
            font-family: 'Jost', sans-serif;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        .admin-layout-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .admin-main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            min-width: 0;
        }

        @media (max-width: 991.98px) {
            .admin-main-content {
                margin-left: 0;
            }
        }

        /* Top Stat Cards */
        .stat-card {
            background-color: #FFFFFF;
            border: 1px solid var(--gold-border);
            border-radius: 16px;
            padding: 20px 24px;
            box-shadow: 0 6px 20px rgba(184, 134, 11, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(184, 134, 11, 0.1);
        }

        .stat-title {
            font-size: 12px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1;
        }

        .stat-icon-box {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(184, 134, 11, 0.08) 100%);
            color: var(--gold-deep);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        /* Data Table Card */
        .table-card {
            background-color: #FFFFFF;
            border: 1px solid var(--gold-border);
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(184, 134, 11, 0.06);
            overflow: visible;
        }

        .table-card-header {
            padding: 18px 24px;
            background-color: #FAF6F0;
            border-bottom: 1.5px solid var(--gold-border);
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .table-card-title {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--gold-deep);
            margin: 0;
        }

        .search-input-box {
            position: relative;
            min-width: 240px;
        }

        .search-input-box input {
            padding-left: 36px;
            height: 38px;
            font-size: 13px;
            border-radius: 10px;
            background-color: #FAF8F5;
            border: 1.5px solid rgba(212, 175, 55, 0.25);
        }

        .search-input-box input:focus {
            background-color: #FFFFFF;
            border-color: var(--gold-primary);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
            outline: none;
        }

        .search-input-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gold-deep);
            font-size: 13px;
        }

        /* Filter Pills */
        .btn-filter-pill {
            font-size: 13px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            border: 1px solid var(--gold-border);
            background-color: #FAF8F5;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-filter-pill:hover,
        .btn-filter-pill.active {
            background-color: var(--gold-deep);
            color: #FFFFFF;
            border-color: var(--gold-deep);
        }

        .messages-table {
            margin: 0;
            width: 100%;
        }

        .messages-table th {
            background-color: #FAF6F0;
            color: var(--gold-deep);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 14px 18px;
            border-bottom: 1px solid var(--gold-border);
            white-space: nowrap;
        }

        .messages-table td {
            padding: 16px 18px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.15);
            vertical-align: middle;
            font-size: 14px;
        }

        /* Visual Cue for Unread Message Rows */
        .messages-table tr.unread-row {
            background-color: #FFFDF4 !important;
            border-left: 4px solid var(--gold-primary);
        }

        .messages-table tr.unread-row td {
            font-weight: 500;
        }

        .sl-number {
            font-weight: 600;
            color: var(--text-muted);
            font-size: 13px;
        }

        /* Sender Details Stacked Format */
        .sender-name {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 15px;
            margin-bottom: 2px;
        }

        .sender-meta {
            font-size: 12px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 2px;
        }

        .sender-meta a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .sender-meta a:hover {
            color: var(--gold-deep);
        }

        .subject-text {
            font-weight: 600;
            color: var(--text-dark);
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .date-text {
            font-size: 13px;
            color: var(--text-muted);
            white-space: nowrap;
        }

        /* Action Buttons */
        .btn-action-icon {
            width: 35px;
            height: 35px;
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
            margin-left: 3px;
        }

        .btn-action-icon.view-btn:hover {
            background-color: var(--gold-deep);
            border-color: var(--gold-deep);
            color: #FFFFFF;
        }

        .btn-action-icon.reply-btn {
            color: #0D6EFD;
            border-color: rgba(13, 110, 253, 0.25);
        }

        .btn-action-icon.reply-btn:hover {
            background-color: #0D6EFD;
            border-color: #0D6EFD;
            color: #FFFFFF;
        }

        .btn-action-icon.toggle-btn-active {
            color: #198754;
            border-color: rgba(25, 135, 84, 0.3);
        }

        .btn-action-icon.toggle-btn-active:hover {
            background-color: #198754;
            border-color: #198754;
            color: #FFFFFF;
        }

        .btn-action-icon.toggle-btn-disabled {
            color: #6C757D;
            border-color: rgba(108, 117, 125, 0.3);
        }

        .btn-action-icon.toggle-btn-disabled:hover {
            background-color: #a6b6c3;
            border-color: #6C757D;
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

        /* View Modal Custom Styling */
        .modal-gold-header {
            background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            color: #FFFFFF;
            border-bottom: none;
            padding: 20px 26px;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
        }

        .modal-gold-header .modal-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 20px;
        }

        .modal-content-custom {
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .info-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 700;
            color: var(--gold-deep);
            margin-bottom: 3px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .message-body-box {
            background-color: #FAF8F5;
            border: 1.5px solid var(--gold-border);
            border-radius: 14px;
            padding: 16px 20px;
            font-size: 14px;
            line-height: 1.6;
            color: var(--text-dark);
            white-space: pre-wrap;
            word-break: break-word;
            min-height: 100px;
            max-height: 280px;
            overflow-y: auto;
        }

        /* Delete Confirmation Modal Styling */
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

        @media (max-width: 768px) {
            .container-fluid {
                padding: 14px !important;
            }

            .table-card-header {
                flex-direction: column;
                align-items: stretch !important;
            }

            .search-input-box {
                width: 100%;
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
                        echo htmlspecialchars($_SESSION['success_message']);
                        unset($_SESSION['success_message']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                        <?php
                        echo htmlspecialchars($_SESSION['error_message']);
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
                                <div class="stat-title">TOTAL MESSAGES</div>
                                <div class="stat-value"><?php echo $totalMessages; ?></div>
                            </div>
                            <div class="stat-icon-box">
                                <i class="fa-solid fa-inbox"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="stat-card">
                            <div>
                                <div class="stat-title">UNREAD MESSAGES</div>
                                <div class="stat-value text-warning"><?php echo $unreadMessages; ?></div>
                            </div>
                            <div class="stat-icon-box" style="background: linear-gradient(135deg, rgba(255, 193, 7, 0.2) 0%, rgba(255, 193, 7, 0.08) 100%); color: #D4AF37;">
                              <img src="../asset/image/unread-message.png" width="30" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="stat-card">
                            <div>
                                <div class="stat-title">READ MESSAGES</div>
                                <div class="stat-value text-success"><?php echo $readMessages; ?></div>
                            </div>
                            <div class="stat-icon-box" style="background: linear-gradient(135deg, rgba(25, 135, 84, 0.15) 0%, rgba(25, 135, 84, 0.08) 100%); color: #198754;">
                                <i class="fa-solid fa-envelope-circle-check"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Messages Data Table Card Container -->
                <div class="table-card" id="messagesTableContainer" style="transition: opacity 0.2s ease;">
                    <?php renderMessagesTableCard($messagesList, $page, $perPage, $totalRecords, $totalPages, $queryParams, $search, $filter, $totalMessages, $unreadMessages, $readMessages, $offset); ?>
                </div>

            </div>
        </main>
    </div>

    <!-- View Message Details Modal -->
    <div class="modal fade" id="viewMessageModal" tabindex="-1" aria-labelledby="viewMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-gold-header">
                    <h5 class="modal-title" id="viewMessageModalLabel">
                        <i class="fa-solid fa-envelope-open-text me-2"></i>Message Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <div class="info-label">Sender Name</div>
                            <div class="info-value" id="modalSenderName">-</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="info-label">Received Date</div>
                            <div class="info-value" id="modalDate">-</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="info-label">Email Address</div>
                            <div class="info-value">
                                <a id="modalEmailLink" href="#" class="text-decoration-none text-dark hover-gold">
                                    <i class="fa-regular fa-envelope me-1 text-gold"></i><span id="modalEmail">-</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value">
                                <a id="modalPhoneLink" href="#" class="text-decoration-none text-dark hover-gold">
                                    <i class="fa-solid fa-phone me-1 text-gold"></i><span id="modalPhone">-</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-label">Subject</div>
                            <div class="info-value text-gold-deep" id="modalSubject" style="font-size: 16px;">-</div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <div class="info-label mb-2">Message Content</div>
                        <div class="message-body-box" id="modalMessageContent">
                            -
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 px-4 py-3 justify-content-between">
                    <div>
                        <a id="modalToggleStatusBtn" href="#" class="btn btn-outline-secondary btn-sm rounded-pill px-3 me-2">
                            <i class="fa-solid fa-envelope-circle-check me-1"></i>Toggle Read Status
                        </a>
                    </div>
                    <div class="d-flex gap-2">
                        <a id="modalReplyBtn" href="#" class="btn btn-primary btn-sm rounded-pill px-3" style="background-color: var(--gold-deep); border: none;">
                            <i class="fa-solid fa-reply me-1"></i>Reply via Email
                        </a>
                        <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Confirmation Modal for Delete -->
    <div class="modal fade" id="modernAlertModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content modal-alert-content">
                <div class="modal-body p-0">
                    <div class="modal-alert-icon-circle" id="modalIconCircle">
                        <i id="modalIcon" class="fa-solid fa-trash-can"></i>
                    </div>

                    <h4 id="modalTitle" class="modal-alert-title">Delete Message?</h4>

                    <p id="modalMessage" class="modal-alert-message">
                        Are you sure you want to delete this message? This process cannot be undone.
                    </p>

                    <div id="modalButtonsContainer" class="d-flex align-items-center justify-content-center gap-2">
                        <button type="button" class="btn btn-modal-cancel w-50" data-bs-dismiss="modal">Cancel</button>
                        <a id="modalConfirmActionBtn" href="#" class="btn btn-danger btn-modal-action w-50" style="background-color: #E63946; border: none;">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="../asset/bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alert messages after 4 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 3000);

        let modernModalInstance = null;
        let viewModalInstance = null;

        document.addEventListener('DOMContentLoaded', function() {
            const deleteModalEl = document.getElementById('modernAlertModal');
            if (deleteModalEl) {
                modernModalInstance = new bootstrap.Modal(deleteModalEl);
            }

            const viewModalEl = document.getElementById('viewMessageModal');
            if (viewModalEl) {
                viewModalInstance = new bootstrap.Modal(viewModalEl);
            }
        });

        // Open View Message Modal & Populate Data
        function openViewModal(btn) {
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name') || 'N/A';
            const email = btn.getAttribute('data-email') || 'N/A';
            const phone = btn.getAttribute('data-phone') || 'N/A';
            const subject = btn.getAttribute('data-subject') || '(No Subject)';
            const date = btn.getAttribute('data-date') || 'N/A';
            const message = btn.getAttribute('data-message') || '';
            const status = parseInt(btn.getAttribute('data-status') || '0');

            document.getElementById('modalSenderName').innerText = name;
            document.getElementById('modalDate').innerText = date;
            document.getElementById('modalEmail').innerText = email;
            document.getElementById('modalEmailLink').href = 'mailto:' + email;

            document.getElementById('modalPhone').innerText = phone;
            if (phone !== 'N/A' && phone.trim() !== '') {
                document.getElementById('modalPhoneLink').href = 'tel:' + phone;
            } else {
                document.getElementById('modalPhoneLink').removeAttribute('href');
            }

            document.getElementById('modalSubject').innerText = subject;
            document.getElementById('modalMessageContent').innerText = message;

            // Update Reply Button Href
            document.getElementById('modalReplyBtn').href = 'mailto:' + email + '?subject=' + encodeURIComponent('Re: ' + subject);

            // Update Toggle Status Button Href & Text
            const toggleBtn = document.getElementById('modalToggleStatusBtn');
            toggleBtn.href = 'message_action.php?action=toggle_status&id=' + id;
            if (status === 0) {
                toggleBtn.innerHTML = '<i class="fa-solid fa-envelope-circle-check me-1"></i>Mark as Read';
                toggleBtn.className = 'btn btn-outline-success btn-sm rounded-pill px-3 me-2';
            } else {
                toggleBtn.innerHTML = '<i class="fa-solid fa-envelope me-1"></i>Mark as Unread';
                toggleBtn.className = 'btn btn-outline-secondary btn-sm rounded-pill px-3 me-2';
            }

            if (viewModalInstance) {
                viewModalInstance.show();
            }
        }

        let currentSearch = '<?php echo htmlspecialchars($search, ENT_QUOTES); ?>';
        let currentFilter = '<?php echo htmlspecialchars($filter, ENT_QUOTES); ?>';
        let currentPerPage = <?php echo (int)$perPage; ?>;
        let currentPage = <?php echo (int)$page; ?>;
        let searchTimer = null;

        // Trigger AJAX Search, Filter & Pagination without page reload
        function triggerSearch(page = 1) {
            currentPage = page;
            const searchInput = document.getElementById('messageSearchInput');
            const isSearchFocused = (document.activeElement === searchInput);
            if (searchInput) {
                currentSearch = searchInput.value.trim();
            }

            const params = new URLSearchParams({
                search: currentSearch,
                filter: currentFilter,
                per_page: currentPerPage,
                page: currentPage,
                ajax: '1'
            });

            const container = document.getElementById('messagesTableContainer');
            if (container) {
                container.style.opacity = '0.5';
            }

            fetch('messages.php?' + params.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (container) {
                        container.style.opacity = '1';
                        if (data.status === 'success') {
                            container.innerHTML = data.html;
                            const newSearchInput = document.getElementById('messageSearchInput');
                            if (newSearchInput && isSearchFocused) {
                                newSearchInput.focus();
                                newSearchInput.setSelectionRange(newSearchInput.value.length, newSearchInput.value.length);
                            }
                        }
                    }
                })
                .catch(err => {
                    if (container) container.style.opacity = '1';
                    console.error('AJAX Search Error:', err);
                });
        }

        function filterMessages(filterType) {
            currentFilter = filterType;
            triggerSearch(1);
        }

        function changePerPage(perPageVal) {
            currentPerPage = parseInt(perPageVal);
            triggerSearch(1);
        }

        function handleSearchInput(val) {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                currentSearch = val.trim();
                triggerSearch(1);
            }, 300);
        }

        // Show Modern Confirmation Modal for Delete
        function confirmDelete(id) {
            document.getElementById('modalTitle').innerText = 'Delete Message?';
            document.getElementById('modalMessage').innerText = 'Are you sure you want to delete this contact message? This process cannot be undone.';

            const confirmBtn = document.getElementById('modalConfirmActionBtn');
            confirmBtn.href = 'message_action.php?action=delete&id=' + id;

            if (modernModalInstance) {
                modernModalInstance.show();
            }
        }
    </script>
</body>

</html>
