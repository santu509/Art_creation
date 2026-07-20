<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determine current active page dynamically
$currentPage = isset($currentPage) ? $currentPage : basename($_SERVER['PHP_SELF']);

if (!function_exists('isTabActive')) {
    function isTabActive($pageName, $currentPage)
    {
        return ($currentPage === $pageName) ? 'active' : '';
    }
}
?>

<!-- Sidebar Backdrop for Mobile -->
<div class="admin-sidebar-backdrop" id="sidebarBackdrop" onclick="toggleAdminSidebar()"></div>

<!-- Admin Sidebar -->
<aside class="admin-sidebar" id="adminSidebar">

    <!-- Sidebar Brand Header -->
    <div class="sidebar-brand">
        <div class="brand-logo-box">
            <img src="../asset/image/logo.png" alt="Siddha Art" class="brand-logo-img">
        </div>
        <div class="brand-text-box">
            <h2 class="brand-name">Siddha Art</h2>
            <span class="brand-badge"><i class="fa-solid fa-shield-halved me-1"></i>Admin Panel</span>
        </div>
        <button type="button" class="btn-sidebar-close d-lg-none" onclick="toggleAdminSidebar()" title="Close Sidebar">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- Sidebar Navigation Menu -->
    <nav class="sidebar-menu">
        <div class="menu-label">MAIN</div>
        <a href="dashboard.php" class="menu-item <?php echo isTabActive('dashboard.php', $currentPage); ?>">
            <i class="fa-solid fa-chart-line menu-icon"></i>
            <span class="menu-title">Dashboard</span>
        </a>

        <div class="menu-label">MANAGEMENT</div>
        <a href="artworks.php" class="menu-item <?php echo isTabActive('artworks.php', $currentPage); ?>">
            <i class="fa-solid fa-palette menu-icon"></i>
            <span class="menu-title">Artworks</span>
        </a>
        <a href="categories.php" class="menu-item <?php echo isTabActive('categories.php', $currentPage); ?>">
            <i class="fa-solid fa-layer-group menu-icon"></i>
            <span class="menu-title">Categories</span>
        </a>
        <a href="orders.php" class="menu-item <?php echo isTabActive('orders.php', $currentPage); ?>">
            <i class="fa-solid fa-box-archive menu-icon"></i>
            <span class="menu-title">Orders</span>
        </a>
        <a href="users.php" class="menu-item <?php echo isTabActive('users.php', $currentPage); ?>">
            <i class="fa-solid fa-users menu-icon"></i>
            <span class="menu-title">Customers</span>
        </a>

        <div class="menu-label">COMMUNICATION</div>
        <a href="messages.php" class="menu-item <?php echo isTabActive('messages.php', $currentPage); ?>">
            <i class="fa-solid fa-envelope-open-text menu-icon"></i>
            <span class="menu-title">Messages</span>
        </a>

        <div class="menu-label">SYSTEM</div>
        <a href="settings.php" class="menu-item <?php echo isTabActive('settings.php', $currentPage); ?>">
            <i class="fa-solid fa-sliders menu-icon"></i>
            <span class="menu-title">Settings</span>
        </a>
    </nav>

    <!-- Sidebar User Footer -->
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                <?php
                $adminName = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'Admin';
                echo strtoupper(substr($adminName, 0, 1));
                ?>
            </div>
            <div class="user-details">
                <span class="user-name"><?php echo htmlspecialchars($adminName); ?></span>
                <span class="user-role">Administrator</span>
            </div>
        </div>
        <a href="login_action.php?action=logout" class="btn-logout" title="Logout">
            <i class="fa-solid fa-right-from-bracket"></i>
        </a>
    </div>

</aside>

<!-- Common Sidebar & Layout CSS -->
<style>
    :root {
        --sidebar-width: 270px;
        --topbar-height: 70px;
        --bg-body: #FAF8F5;
        --bg-white: #FFFFFF;
        --gold-primary: #D4AF37;
        --gold-deep: #B8860B;
        --gold-light: #F3E5AB;
        --gold-gradient: linear-gradient(135deg, #DFBA5A 0%, #C59B27 50%, #9B781E 100%);
        --gold-border: rgba(212, 175, 55, 0.25);
        --text-dark: #2A241D;
        --text-muted: #7C7267;
    }

    /* Base Layout */
    body {
        font-family: 'Outfit', sans-serif;
        background-color: var(--bg-body);
        color: var(--text-dark);
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }

    .admin-layout-wrapper {
        display: flex;
        min-height: 100vh;
    }

    .admin-main-content {
        flex: 1;
        margin-left: var(--sidebar-width);
        min-width: 0;
        transition: margin-left 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    /* Admin Sidebar Styling */
    .admin-sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background-color: var(--bg-white);
        border-right: 1px solid var(--gold-border);
        box-shadow: 4px 0 20px rgba(184, 134, 11, 0.05);
        display: flex;
        flex-direction: column;
        z-index: 1040;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow-y: auto;
    }

    /* Brand Header */
    .sidebar-brand {
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid var(--gold-border);
        background: linear-gradient(180deg, #FAF6F0 0%, #FFFFFF 100%);
        position: relative;
    }

    .brand-logo-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #F5EFE6;
        border: 1px solid var(--gold-border);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(212, 175, 55, 0.15);
    }

    .brand-logo-img {
        max-width: 75%;
        max-height: 75%;
        object-fit: contain;
    }

    .brand-text-box {
        display: flex;
        flex-direction: column;
    }

    .brand-name {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        font-weight: 700;
        color: var(--gold-deep);
        margin: 0;
        line-height: 1.2;
    }

    .brand-badge {
        font-size: 11px;
        color: var(--text-muted);
        font-weight: 500;
    }

    .btn-sidebar-close {
        position: absolute;
        right: 14px;
        top: 20px;
        background: none;
        border: none;
        font-size: 20px;
        color: var(--text-muted);
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .btn-sidebar-close:hover {
        color: var(--gold-deep);
    }

    /* Navigation Menu */
    .sidebar-menu {
        padding: 16px 14px;
        flex: 1;
    }

    .menu-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        letter-spacing: 0.8px;
        margin: 18px 10px 8px 10px;
        text-transform: uppercase;
    }

    .menu-label:first-child {
        margin-top: 4px;
    }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 12px;
        color: var(--text-dark);
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 4px;
        transition: all 0.25s ease;
        position: relative;
    }

    .menu-icon {
        font-size: 16px;
        width: 22px;
        text-align: center;
        color: var(--text-muted);
        transition: color 0.25s ease;
    }

    .menu-title {
        flex: 1;
    }

    /* Hover & Active States */
    .menu-item:hover {
        background-color: rgba(212, 175, 55, 0.08);
        color: var(--gold-deep);
    }

    .menu-item:hover .menu-icon {
        color: var(--gold-deep);
    }

    .menu-item.active {
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(184, 134, 11, 0.08) 100%);
        color: var(--gold-deep);
        font-weight: 600;
        border-left: 4px solid var(--gold-deep);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.12);
    }

    .menu-item.active .menu-icon {
        color: var(--gold-deep);
    }

    /* Sidebar Footer */
    .sidebar-footer {
        padding: 16px;
        border-top: 1px solid var(--gold-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #FAF8F5;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
        overflow: hidden;
    }

    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: var(--gold-gradient);
        color: #1A1612;
        font-weight: 700;
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(184, 134, 11, 0.25);
    }

    .user-details {
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .user-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-role {
        font-size: 11px;
        color: var(--text-muted);
    }

    .btn-logout {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background-color: #FFFFFF;
        border: 1px solid var(--gold-border);
        color: #C53030;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .btn-logout:hover {
        background-color: #FFF5F5;
        color: #E53E3E;
        transform: translateY(-1px);
    }

    /* Backdrop for Mobile Sidebar */
    .admin-sidebar-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(3px);
        z-index: 1030;
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .admin-sidebar-backdrop.show {
        display: block;
        opacity: 1;
    }

    /* Mobile Responsive Adjustments */
    @media (max-width: 991px) {
        .admin-main-content {
            margin-left: 0;
        }

        .admin-sidebar {
            transform: translateX(-100%);
        }

        .admin-sidebar.show {
            transform: translateX(0);
        }
    }
</style>

<!-- Toggle Sidebar JS Script -->
<script>
    function toggleAdminSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        if (sidebar && backdrop) {
            sidebar.classList.toggle('show');
            backdrop.classList.toggle('show');
        }
    }
</script>