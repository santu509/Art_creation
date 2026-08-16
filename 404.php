<?php

http_response_code(404);


$base_path = dirname($_SERVER['SCRIPT_NAME']);
$base_path = str_replace('\\', '/', $base_path); 
if ($base_path === '/') {
    $base_path = ''; // 
}

$currentPage = '404.php';


include_once(__DIR__ . '/includes/nav.php');
?>

<!-- ==========================================
     CUSTOM 404 ERROR PAGE - SIDDHA ART CREATION
========================================== -->
<main class="error-404-section d-flex flex-column align-items-center justify-content-center">
    <div class="bg-glow-404-1"></div>
    <div class="bg-glow-404-2"></div>

    <!-- 100% Full-Width Edge-to-Edge Panoramic 404 Hero Banner -->
    <div class="banner-404-hero w-100">
        <div class="banner-404-illustration-wrapper">
           
            <img src="<?= $base_path ?>/asset/image/artisan_404_hd_banner.png" alt="404 Artwork Not Found Banner" class="banner-404-img">
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="container px-3 text-center mb-5">
        <div class="error-404-card mx-auto">
            <div class="error-badge-tag">
                <i class="fa-solid fa-palette"></i> Masterpiece Unreachable
            </div>

            <div class="error-code-massive">404</div>

            <h2 class="error-subtitle">Oops! Art Not Found</h2>

            <p class="error-description mx-auto">
                It seems the handcrafted masterpiece or page you are looking for has been moved or doesn't exist in our gallery.
            </p>

            <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap">
               
                <a href="<?= $base_path ?>/index.php" class="btn-explore-404">
                    <i class="fa-solid fa-house me-2"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</main>

<?php 

include_once(__DIR__ . '/includes/footer.php'); 
?>