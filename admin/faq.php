<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit();
}
include_once('includes/sidebar.php');
?>
<?php
$pageTitle = "FAQ Management";
$currentPage = "faq_admin.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> | Siddha Art Creation Admin</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../asset/bootstrap-5.3.7-dist/css/bootstrap.min.css">

    <!-- Premium Fonts: Playfair Display (Serif headings) & Jost (Elegant Sans-serif) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-page: #FAF8F5;
            /* Warm elegant off-white canvas */
            --bg-card: #FFFFFF;
            /* Pure card white */
            --gold: #C39B62;
            /* Siddha Art signature luxury gold */
            --gold-light: rgba(195, 155, 98, 0.08);
            --gold-hover: #A67E48;
            /* Deep warm gold */
            --text-dark: #1E1B18;
            /* Rich dark charcoal */
            --text-muted: #6E6A64;
            /* Soft olive-grey body text */
            --border-color: #E6E2DC;
            /* Soft ivory-grey borders */
            --transition-smooth: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            --shadow-premium: 0 10px 30px rgba(30, 27, 24, 0.03);
            --shadow-hover: 0 15px 35px rgba(195, 155, 98, 0.12);
        }

        /* Typography & Custom Accents */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .serif-font {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
        }

        .accent-line {
            width: 40px;
            height: 3px;
            background: var(--gold);
            margin-top: 8px;
            border-radius: 20px;
        }

        /* Premium Stat Card Styling */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--shadow-premium);
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--gold);
            opacity: 0;
            transition: var(--transition-smooth);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: rgba(195, 155, 98, 0.3);
        }

        .stat-card:hover::after {
            opacity: 1;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            background: var(--gold-light);
            color: var(--gold);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            transition: var(--transition-smooth);
        }

        .stat-card:hover .stat-icon {
            background: var(--gold);
            color: #fff;
            transform: scale(1.05);
        }

        /* Custom UI Buttons */
        .btn-gold {
            background: linear-gradient(135deg, #C39B62 0%, #A67E48 100%);
            color: #FFFFFF !important;
            border: none;
            padding: 10px 22px;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-size: 0.82rem;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(195, 155, 98, 0.25);
            transition: var(--transition-smooth);
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(195, 155, 98, 0.4);
            background: linear-gradient(135deg, #D5AE74 0%, #B88F57 100%);
        }

        .btn-outline-gold {
            border: 1px solid var(--gold);
            background: transparent;
            color: var(--gold);
            padding: 10px 22px;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-size: 0.82rem;
            border-radius: 50px;
            transition: var(--transition-smooth);
        }

        .btn-outline-gold:hover {
            background: var(--gold-light);
            color: var(--gold-hover);
            border-color: var(--gold-hover);
        }

        /* Filter Controls Styling */
        .filter-section {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
            box-shadow: var(--shadow-premium);
            margin-bottom: 25px;
        }

        .form-select,
        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.92rem;
            color: var(--text-dark);
            background-color: var(--bg-page);
            transition: var(--transition-smooth);
        }

        .form-select:focus,
        .form-control:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(195, 155, 98, 0.15);
            background-color: #fff;
        }

        /* Table Card & Elements Styling */
        .table-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--shadow-premium);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-header-custom {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-custom {
            margin-bottom: 0;
        }

        .table-custom th {
            background-color: var(--bg-page);
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 16px 20px;
            border-bottom: 1.5px solid var(--border-color);
        }

        .table-custom td {
            padding: 18px 20px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
            font-size: 0.95rem;
            color: var(--text-dark);
            transition: var(--transition-smooth);
        }

        .table-custom tbody tr {
            transition: var(--transition-smooth);
        }

        .table-custom tbody tr:hover td {
            background-color: rgba(195, 155, 98, 0.02);
        }

        /* Action Buttons */
        .btn-action {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-color);
            background: #fff;
            color: var(--text-muted);
            transition: var(--transition-smooth);
            text-decoration: none;
            margin: 0 2px;
        }

        .btn-action-edit:hover {
            color: var(--gold);
            border-color: var(--gold);
            background-color: var(--gold-light);
            transform: translateY(-2px);
        }

        .btn-action-delete:hover {
            color: #dc3545;
            border-color: rgba(220, 53, 69, 0.2);
            background-color: rgba(220, 53, 69, 0.05);
            transform: translateY(-2px);
        }

        /* Custom Pagination */
        .pagination-container {
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--border-color);
            background: var(--bg-page);
        }

        .pagination-custom {
            margin-bottom: 0;
            display: flex;
            gap: 6px;
            list-style: none;
            padding: 0;
        }

        .pagination-item {
            display: inline;
        }

        .pagination-link {
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background-color: #fff;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.88rem;
            transition: var(--transition-smooth);
            display: inline-block;
        }

        .pagination-link:hover {
            border-color: var(--gold);
            color: var(--gold);
            background: var(--gold-light);
        }

        .pagination-item.active .pagination-link {
            background: var(--gold);
            color: #fff;
            border-color: var(--gold);
        }

        .pagination-item.disabled .pagination-link {
            pointer-events: none;
            opacity: 0.5;
            background-color: var(--bg-page);
        }

        /* Premium Modal Styling */
        .modal-content-premium {
            background-color: #fff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(30, 27, 24, 0.15);
            overflow: hidden;
        }

        .modal-header-premium {
            background: #181613;
            color: #fff;
            padding: 20px 24px;
            border-bottom: 2px solid var(--gold);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header-premium .modal-title {
            font-family: 'Playfair Display', serif;
            color: var(--gold);
        }

        .modal-header-premium .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .modal-body-premium {
            padding: 28px 24px;
        }

        .modal-footer-premium {
            padding: 16px 24px;
            border-top: 1px solid var(--border-color);
            background-color: var(--bg-page);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .table-custom {
            width: 100%;
            table-layout: fixed;
        }

        .table-custom th,
        .table-custom td {
            vertical-align: middle;
            word-break: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }

        .table-custom th:nth-child(1),
        .table-custom td:nth-child(1) {
            width: 70px;
            text-align: center;
        }

        .table-custom th:nth-child(2),
        .table-custom td:nth-child(2) {
            width: 35%;
        }

        .table-custom th:nth-child(3),
        .table-custom td:nth-child(3) {
            width: 50%;
        }

        .table-custom th:nth-child(4),
        .table-custom td:nth-child(4) {
            width: 120px;
            text-align: center;
        }

        /* Keyframe Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Styles Optimization */
        @media (max-width: 991.98px) {
            .table-header-custom {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .pagination-container {
                flex-direction: column;
                gap: 15px;
                text-align: center;
                align-items: center;
            }
        }

        @media (max-width: 767.98px) {
            .stat-card {
                padding: 16px 20px;
            }

            .stat-icon {
                width: 44px;
                height: 44px;
                font-size: 1.15rem;
            }

            .table-custom th {
                padding: 12px 14px;
                font-size: 0.75rem;
                letter-spacing: 0.05em;
            }

            .table-custom td {
                padding: 14px 14px;
                font-size: 0.88rem;
            }

            .btn-gold,
            .btn-outline-gold {
                padding: 8px 18px;
                font-size: 0.78rem;
            }

            .btn-action {
                width: 30px;
                height: 30px;
                border-radius: 6px;
            }

            .filter-section {
                padding: 15px;
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="admin-layout-wrapper">
        <!-- Main Content Area -->
        <main class="admin-main-content">
            <!-- Topbar Inclusion -->
            <?php include_once 'includes/topbar.php'; ?>

            <!-- Main Page Content -->
            <div class="container-fluid p-4 dashboard-container">

                <!-- Page Header Section -->
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#addFAQModal">
                        <i class="fa fa-plus me-2"></i>Add FAQ
                    </button>
                </div>

                <!-- Dashboard Stat Overview Cards -->
                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <div class="stat-card">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted fw-medium d-block mb-1" style="font-size: 0.85rem; letter-spacing: 0.05em; text-transform: uppercase;">Total FAQs</span>
                                    <h2 class="m-0 fw-bold" id="stat-total" style="font-size: 1.85rem; font-family: 'Jost', sans-serif;">0</h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="fa-solid fa-question-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Search & Filter Controls -->
                <div class="filter-section">
                    <div class="row g-3 align-items-end">
                        <div class="col-12">
                            <label class="form-label text-muted fw-semibold mb-2" style="font-size: 0.8rem; text-transform: uppercase;">Search FAQ</label>
                            <div class="position-relative">
                                <input type="text" id="search-input" class="form-control ps-4" placeholder="Search by keywords, questions or answers...">
                                <i class="fa fa-search text-muted position-absolute" style="top: 12px; right: 15px;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ List Table Card -->
                <div class="table-card">
                    <div class="table-header-custom">
                        <h5 class="m-0 serif-font" style="color:#d29706">FAQ Registry</h5>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted text-nowrap" style="font-size: 0.85rem;">Select Rows:</span>
                            <select id="rows-per-page" class="form-select form-select-sm w-100">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="text-center" style="color:#B8860B">Sl No.</th>
                                    <th style="color:#B8860B;">Question</th>
                                    <th style="color:#B8860B;">Answer</th>
                                    <th class="text-center" style="color:#B8860B;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="faq-table-body">
                                <?php
                                require_once "../includes/connection.php";
                                global $connect;

                                // take the faq from db
                                function getAllFAQ()
                                {
                                    global $connect;
                                    $sql = "SELECT * FROM faq ORDER BY id DESC";
                                    $result = mysqli_query($connect, $sql);

                                    if (!$result) {
                                        return [];
                                    }

                                    if (mysqli_num_rows($result) > 0) {
                                        return mysqli_fetch_all($result, MYSQLI_ASSOC);
                                    }
                                    return [];
                                }

                                $data = getAllFAQ();
                                $sl = 1;

                                if (!empty($data)) {
                                    foreach ($data as $faq) {
                                ?>
                                        <tr class="faq-row">
                                            <td class="text-center"><?php echo $sl++; ?></td>
                                            <td class="faq-question">
                                                <?php echo htmlspecialchars($faq['question']); ?>
                                            </td>
                                            <td class="faq-answer">
                                                <?php echo htmlspecialchars($faq['answer']); ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                    <button class="btn-action btn-action-edit"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editFAQModal"
                                                        data-id="<?php echo $faq['id']; ?>"
                                                        data-question="<?php echo htmlspecialchars($faq['question']); ?>"
                                                        data-answer="<?php echo htmlspecialchars($faq['answer']); ?>">
                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                    </button>

                                                    <button type="button"
                                                        class="btn-action btn-action-delete"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteFAQModal"
                                                        data-id="<?php echo $faq['id']; ?>">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr class="no-data-row">
                                        <td colspan="4" class="text-center">No FAQ Found in Database</td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination View Container -->
                    <div class="pagination-container">
                        <div class="text-muted" id="pagination-info" style="font-size: 0.88rem;">
                            <!-- Dynamically populated via JS -->
                        </div>
                        <nav>
                            <ul class="pagination-custom" id="pagination-list">
                                <!-- Rendered dynamically via JS -->
                            </ul>
                        </nav>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- ========================================== -->
    <!-- ADD NEW FAQ MODAL -->
    <!-- ========================================== -->
    <div class="modal fade" id="addFAQModal" tabindex="-1" aria-labelledby="addFAQModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content modal-content-premium">
                <div class="modal-header-premium">
                    <h5 class="modal-title m-0" id="addFAQModalLabel">
                        <i class="fa-regular fa-square-plus me-2"></i>Add New FAQ
                    </h5>
                    <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>
                <form id="add-faq-form" action="faq_action.php" method="POST">
                    <div class="modal-body modal-body-premium">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.85rem;">THE QUESTION</label>
                                <input type="text" name="question" class="form-control" placeholder="e.g., Do you accept custom canvas sizes?" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.85rem;">THE ANSWER</label>
                                <textarea name="answer" class="form-control" rows="5" placeholder="Provide a detailed, clear answer here..." required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer modal-footer-premium">
                        <button type="button" class="btn btn-outline-gold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-gold">Publish FAQ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- EDIT FAQ MODAL -->
    <!-- ========================================== -->
    <div class="modal fade" id="editFAQModal" tabindex="-1" aria-labelledby="editFAQModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content modal-content-premium">
                <div class="modal-header-premium">
                    <h5 class="modal-title m-0" id="editFAQModalLabel"><i class="fa-regular fa-pen-to-square me-2"></i>Modify FAQ</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="faq_action.php" method="POST">
                    <input type="hidden" name="id" id="edit-faq-id">
                    <div class="modal-body modal-body-premium">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.85rem;">THE QUESTION</label>
                                <textarea name="question" id="edit-question" class="form-control" required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted fw-semibold mb-1" style="font-size: 0.85rem;">THE ANSWER</label>
                                <textarea name="answer" id="edit-answer" class="form-control" rows="5" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer modal-footer-premium">
                        <button type="button" class="btn btn-outline-gold" data-bs-dismiss="modal">Cancel Changes</button>
                        <button type="submit" name="update" class="btn btn-gold">Update FAQ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- DELETE CONFIRMATION MODAL -->
    <!-- ========================================== -->
    <div class="modal fade" id="deleteFAQModal" tabindex="-1" aria-labelledby="deleteFAQModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content modal-content-premium">
                <div class="modal-header-premium bg-danger border-0">
                    <h5 class="modal-title m-0 text-white" id="deleteFAQModalLabel"><i class="fa-solid fa-triangle-exclamation me-2"></i>Delete FAQ</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-premium text-center">
                    <p class="text-dark fw-bold mb-2">Are you absolutely sure?</p>
                    <p class="text-muted small">This action cannot be undone. This FAQ entry will be permanently deleted.</p>
                </div>
                <div class="modal-footer modal-footer-premium border-0">
                    <button type="button" class="btn btn-outline-gold py-1 px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirm-delete-btn" class="btn btn-danger py-1 px-3 rounded-pill shadow-sm">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS with Popper -->
    <script src="../asset/bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Edit Modal Data Population
        const editModal = document.getElementById('editFAQModal');
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('edit-faq-id').value = button.getAttribute('data-id');
            document.getElementById('edit-question').value = button.getAttribute('data-question');
            document.getElementById('edit-answer').value = button.getAttribute('data-answer');
        });

        // Delete Modal Data
        let deleteId = null;
        const deleteModal = document.getElementById('deleteFAQModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            deleteId = button.getAttribute('data-id');
        });
        document.getElementById('confirm-delete-btn').addEventListener('click', function() {
            if (deleteId) {
                window.location.href = "faq_action.php?delete=" + deleteId;
            }
        });

        // ==========================================
        // DYNAMIC SEARCH & PAGINATION SCRIPT
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const rowsPerPageSelect = document.getElementById('rows-per-page');
            const tableBody = document.getElementById('faq-table-body');
            const paginationList = document.getElementById('pagination-list');
            const paginationInfo = document.getElementById('pagination-info');
            const totalStat = document.getElementById('stat-total');

            // Gather all data rows
            let allRows = Array.from(tableBody.querySelectorAll('tr.faq-row'));
            const noDataRow = tableBody.querySelector('.no-data-row'); // PHP generated if DB is empty

            // Create a dynamic "No matches found" row for the search functionality
            let searchNoMatchRow = document.createElement('tr');
            searchNoMatchRow.id = 'search-no-match';
            searchNoMatchRow.style.display = 'none'; // Hidden by default
            searchNoMatchRow.innerHTML = `
                <td colspan="4" class="text-center py-4 text-muted">
                    <i class="fa-solid fa-magnifying-glass mb-2" style="font-size: 1.5rem; opacity: 0.5;"></i>
                    <p class="mb-0 fw-medium">No matching FAQ found.</p>
                </td>
            `;
            tableBody.appendChild(searchNoMatchRow);

            // Update Total Stat card 
            if (totalStat) totalStat.innerText = allRows.length;

            let filteredRows = [...allRows];
            let currentPage = 1;
            let rowsPerPage = parseInt(rowsPerPageSelect.value);

            function renderTable() {
                // Hide all rows initially
                allRows.forEach(row => row.style.display = 'none');
                searchNoMatchRow.style.display = 'none';

                // Check if any data exists at all
                if (allRows.length === 0) {
                    if (noDataRow) noDataRow.style.display = ''; 
                    paginationInfo.innerHTML = 'Showing 0 to 0 of 0 entries';
                    paginationList.innerHTML = '';
                    return;
                }

                // Check if search filtered out everything
                if (filteredRows.length === 0) {
                    searchNoMatchRow.style.display = ''; // Show custom "not found" message
                    paginationInfo.innerHTML = 'Showing 0 to 0 of 0 entries';
                    paginationList.innerHTML = '';
                    return;
                }

                // Calculate pagination ranges
                const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
                if (currentPage > totalPages) currentPage = totalPages;
                if (currentPage < 1) currentPage = 1;

                const startIndex = (currentPage - 1) * rowsPerPage;
                const endIndex = Math.min(startIndex + rowsPerPage, filteredRows.length);

                // Show only items for current page and dynamically update the serial numbers
                for (let i = startIndex; i < endIndex; i++) {
                    filteredRows[i].style.display = '';
                    filteredRows[i].querySelector('td:first-child').innerText = i + 1; // Update Sl No.
                }

                renderPagination(totalPages, startIndex, endIndex, filteredRows.length);
            }

            function renderPagination(totalPages, start, end, totalItems) {
                // Update descriptive text
                paginationInfo.innerHTML = `Showing ${start + 1} to ${end} of ${totalItems} entries`;

                let html = '';

                // Previous Button
                html += `
                <li class="pagination-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a href="#" class="pagination-link" data-page="${currentPage - 1}">Prev</a>
                </li>`;

                // Page Numbers
                for (let i = 1; i <= totalPages; i++) {
                    html += `
                    <li class="pagination-item ${currentPage === i ? 'active' : ''}">
                        <a href="#" class="pagination-link" data-page="${i}">${i}</a>
                    </li>`;
                }

                // Next Button
                html += `
                <li class="pagination-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a href="#" class="pagination-link" data-page="${currentPage + 1}">Next</a>
                </li>`;

                paginationList.innerHTML = html;
            }

            // --- Event Listeners ---

            // Search Filter
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase().trim();
                filteredRows = allRows.filter(row => {
                    const question = row.querySelector('.faq-question').innerText.toLowerCase();
                    const answer = row.querySelector('.faq-answer').innerText.toLowerCase();
                    return question.includes(query) || answer.includes(query);
                });
                currentPage = 1; // Reset to page 1
                renderTable();
            });

            // Rows Per Page Dropdown
            rowsPerPageSelect.addEventListener('change', function(e) {
                rowsPerPage = parseInt(e.target.value);
                currentPage = 1; // Reset to page 1
                renderTable();
            });

            // Pagination Buttons
            paginationList.addEventListener('click', function(e) {
                e.preventDefault();
                if (e.target.classList.contains('pagination-link')) {
                    const parentLi = e.target.closest('.pagination-item');
                    if (parentLi.classList.contains('disabled') || parentLi.classList.contains('active')) return;

                    const newPage = parseInt(e.target.getAttribute('data-page'));
                    if (!isNaN(newPage)) {
                        currentPage = newPage;
                        renderTable();
                    }
                }
            });

            // Initialize on load
            renderTable();
        });
    </script>

    <!-- for php custom alert(success,failed,error) -->
    <?php if (isset($_GET['success']) || isset($_GET['updated']) || isset($_GET['deleted']) || isset($_GET['error'])): ?>
        <?php
        $message = "";
        $type = "success";

        if (isset($_GET['success'])) {
            $message = "FAQ added successfully!";
        } elseif (isset($_GET['updated'])) {
            $message = "FAQ updated successfully!";
        } elseif (isset($_GET['deleted'])) {
            $message = "FAQ deleted successfully!";
        } elseif (isset($_GET['error'])) {
            $message = "Something went wrong!";
            $type = "danger";
        }
        ?>

        <div class="position-fixed top-0 end-0 p-3" style="z-index:9999">
            <div id="liveToast" class="toast text-bg-<?php echo $type; ?> border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php if ($type == "success") { ?>
                            <i class="fa-solid fa-circle-check me-2"></i>
                        <?php } else { ?>
                            <i class="fa-solid fa-circle-xmark me-2"></i>
                        <?php } ?>
                        <?php echo $message; ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const toast = new bootstrap.Toast(document.getElementById('liveToast'), {
                    delay: 2000
                });
                toast.show();

                // URL clean
                window.history.replaceState({}, document.title, "faq_admin.php");
            });
        </script>
    <?php endif; ?>
</body>

</html>