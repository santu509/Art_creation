<?php
session_start();
require_once '../connection.php';

// Check admin authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$pageTitle = "Dashboard Overview";
$currentPage = "dashboard.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Siddha Art Creation Admin</title>
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
        .stat-card {
            background-color: #FFFFFF;
            border: 1px solid rgba(212, 175, 55, 0.25);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(184, 134, 11, 0.05);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(184, 134, 11, 0.12);
        }

        .stat-icon-box {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(184, 134, 11, 0.08) 100%);
            color: #B8860B;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 16px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #2A241D;
            line-height: 1.2;
        }

        .stat-label {
            font-size: 13px;
            color: #7C7267;
            font-weight: 500;
        }

        .welcome-card {
            background: linear-gradient(135deg, #FAF6F0 0%, #FFFFFF 100%);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 6px 20px rgba(184, 134, 11, 0.08);
        }

        .welcome-title {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            color: #B8860B;
            font-weight: 700;
            margin-bottom: 8px;
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

            <!-- Dashboard Inner Content -->
            <div class="container-fluid p-4">
                
                <!-- Welcome Card -->
                <div class="welcome-card">
                    <h2 class="welcome-title">Welcome Back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h2>
                    <p class="text-muted m-0">Here is a quick overview of your Siddha Art Creation management console.</p>
                </div>

                <!-- Stats Overview -->
                <div class="row g-4">
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-icon-box">
                                <i class="fa-solid fa-palette"></i>
                            </div>
                            <div class="stat-value">24</div>
                            <div class="stat-label">Total Artworks</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-icon-box">
                                <i class="fa-solid fa-box-archive"></i>
                            </div>
                            <div class="stat-value">12</div>
                            <div class="stat-label">Total Orders</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-icon-box">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <div class="stat-value">8</div>
                            <div class="stat-label">Registered Users</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="stat-card">
                            <div class="stat-icon-box">
                                <i class="fa-solid fa-envelope-open-text"></i>
                            </div>
                            <div class="stat-value">5</div>
                            <div class="stat-label">New Messages</div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

</body>
</html>
