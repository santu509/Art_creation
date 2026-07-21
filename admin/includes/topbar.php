<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle = isset($pageTitle) ? $pageTitle : 'Admin Panel';
$adminUsername = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'Admin';
?>

<!-- Topbar Navigation -->
<header class="admin-topbar">
    <div class="topbar-left">
        <button type="button" class="btn-topbar-toggle d-lg-none" onclick="toggleAdminSidebar()" title="Toggle Menu">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="topbar-title-box">
            <h1 class="topbar-page-title"><?php echo htmlspecialchars($pageTitle); ?></h1>
            <span class="topbar-breadcrumb d-none d-sm-inline">Siddha Art &bull; Admin</span>
        </div>
    </div>

    <div class="topbar-right">
       

        <!-- View Website Link -->
        <a href="../index.php" target="_blank" class="btn-topbar-action" title="View Website">
            <i class="fa-solid fa-globe"></i>
            <span class="d-none d-sm-inline ms-1">View Site</span>
        </a>

        <!-- Admin Profile -->
        <div class="topbar-user">
            <div class="user-avatar-sm">
                <?php echo strtoupper(substr($adminUsername, 0, 1)); ?>
            </div>
            <span class="user-name-sm d-none d-sm-inline"><?php echo htmlspecialchars($adminUsername); ?></span>
        </div>
    </div>
</header>

<style>
    .admin-topbar {
        height:85px;
        background-color: #FFFFFF;
        border-bottom: 1px solid rgba(212, 175, 55, 0.25);
        padding: 0 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 1020;
        box-shadow: 0 4px 15px rgba(184, 134, 11, 0.04);
    }

    .topbar-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .btn-topbar-toggle {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background-color: #FAF8F5;
        border: 1px solid rgba(212, 175, 55, 0.3);
        color: #B8860B;
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .btn-topbar-toggle:hover {
        background-color: #F3E5AB;
        color: #1A1612;
    }

    .topbar-page-title {
        font-family: 'Playfair Display', serif;
        font-size: 22px;
        font-weight: 700;
        color: #2A241D;
        margin: 0;
        line-height: 1.2;
    }

    .topbar-breadcrumb {
        font-size: 12px;
        color: #7C7267;
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .topbar-search {
        position: relative;
        align-items: center;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        color: #B8860B;
        font-size: 14px;
    }

    .search-input {
        padding: 8px 16px 8px 36px;
        background-color: #FAF8F5;
        border: 1px solid rgba(212, 175, 55, 0.25);
        border-radius: 20px;
        font-size: 13px;
        width: 200px;
        color: #2A241D;
        transition: all 0.25s ease;
    }

    .search-input:focus {
        outline: none;
        width: 260px;
        border-color: #D4AF37;
        background-color: #FFFFFF;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
    }

    .btn-topbar-action {
        padding: 8px 14px;
        border-radius: 10px;
        background-color: #FAF8F5;
        border: 1px solid rgba(212, 175, 55, 0.3);
        color: #B8860B;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
    }

    .btn-topbar-action:hover {
        background-color: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%);
        background-color: #F3E5AB;
        color: #1A1612;
    }

    .topbar-user {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-avatar-sm {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 50%, #9B781E 100%);
        color: #1A1612;
        font-weight: 700;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(184, 134, 11, 0.2);
    }

    .user-name-sm {
        font-size: 13px;
        font-weight: 600;
        color: #2A241D;
    }
</style>
