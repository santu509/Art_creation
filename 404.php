<?php
$currentPage = '404.php';
include_once('nav.php');
?>

<!-- ==========================================
     CUSTOM 404 ERROR PAGE - SIDDHA ART CREATION
========================================== -->
<style>
    .error-404-section {
        background: linear-gradient(180deg, #FAF8F5 0%, #F4EFE6 100%);
        font-family: 'Outfit', sans-serif;
        color: #1A1612;
        min-height: 85vh;
        position: relative;
        overflow: hidden;
        padding-top: 0px;
        /* Full edge-to-edge hero banner under navbar */
    }

    /* Ambient Gold Glows */
    .bg-glow-404-1 {
        position: absolute;
        width: 650px;
        height: 650px;
        background: radial-gradient(circle, rgba(223, 186, 90, 0.18) 0%, rgba(250, 248, 245, 0) 70%);
        top: 300px;
        left: 50%;
        transform: translateX(-50%);
        pointer-events: none;
        z-index: 0;
    }

    .bg-glow-404-2 {
        position: absolute;
        width: 450px;
        height: 450px;
        background: radial-gradient(circle, rgba(197, 155, 39, 0.14) 0%, rgba(250, 248, 245, 0) 70%);
        bottom: -80px;
        right: 5%;
        pointer-events: none;
        z-index: 0;
    }

    /* 100% Full-Width Edge-to-Edge Panoramic 404 Hero Banner */
    .banner-404-hero {
        position: relative;
        z-index: 1;
        width: 100%;
        /* Shudhu 100% width, kono hack lagbe na */
        margin-bottom: 2.5rem;
    }

    .banner-404-illustration-wrapper {
        width: 100%;
        margin: 0;
        padding: 0;
        border-radius: 0px;
        overflow: hidden;
        border: none;
        background: #1A1612;
        /* Ekta dark background jate load howar somoy bhalo lage */
    }

    .banner-404-img {
        width: 100%;
        height: 35vh;
        /* Viewport height er 35% nebe, jate responsive thake */
        min-height: 280px;
        /* Jotai choto hok, 280px er kom hobe na */
        max-height: 450px;
        /* Beshi boro hoye screen jure nebe na */
        object-fit: cover;
        /* Image jate chyapta na hoy */
        object-position: center;
        /* Ekdom center theke image ta show korbe, nicher dik ar katbe na */
        display: block;
        border-radius: 0px;
    }

    @media (max-width: 768px) {
        .banner-404-img {
            min-height: 220px;
            height: 30vh;
            /* Mobile-er jonno ektu choto height */
        }
    }

    .error-404-card {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1.5px solid rgba(197, 155, 39, 0.28);
        border-radius: 28px;
        box-shadow: 0 20px 50px rgba(26, 22, 18, 0.07);
        padding: 3rem 2.2rem;
        max-width: 720px;
        width: 100%;
        position: relative;
        z-index: 1;
        transition: transform 0.4s ease, box-shadow 0.4s ease;
    }

    .error-404-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 25px 60px rgba(197, 155, 39, 0.16);
    }

    .error-badge-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(197, 155, 39, 0.12);
        color: #9B781E;
        border: 1px solid rgba(197, 155, 39, 0.3);
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 1.8px;
        text-transform: uppercase;
        margin-bottom: 0.8rem;
    }

    /* Massive 404 Gold Gradient Number */
    .error-code-massive {
        font-family: 'Playfair Display', serif;
        font-size: clamp(4.5rem, 12vw, 7.5rem);
        font-weight: 800;
        line-height: 0.9;
        letter-spacing: -2px;
        background: linear-gradient(135deg, #FFF0BD 0%, #DFBA5A 35%, #C59B27 70%, #9B781E 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 10px 24px rgba(197, 155, 39, 0.25));
        margin-bottom: 0.6rem;
        user-select: none;
    }

    .error-subtitle {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.5rem, 3.2vw, 2.1rem);
        font-weight: 700;
        color: #1A1612;
        margin-bottom: 0.8rem;
    }

    .error-description {
        font-size: 1rem;
        color: #5C5449;
        line-height: 1.6;
        max-width: 540px;
        margin-bottom: 2rem;
    }

    /* Buttons Styling */
    .btn-explore-404 {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 50%, #9B781E 100%);
        color: #1A1612 !important;
        font-weight: 700;
        font-size: 0.92rem;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        padding: 0.95rem 2.2rem;
        border-radius: 50px;
        text-decoration: none;
        box-shadow: 0 10px 28px rgba(197, 155, 39, 0.35);
        transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: none;
    }

    .btn-explore-404:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 15px 35px rgba(197, 155, 39, 0.5);
        color: #1A1612 !important;
    }

    .btn-home-404 {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        color: #1A1612 !important;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.9rem 1.8rem;
        border-radius: 50px;
        text-decoration: none;
        border: 1.5px solid rgba(26, 22, 18, 0.25);
        transition: all 0.35s ease;
    }

    .btn-home-404:hover {
        background: rgba(26, 22, 18, 0.06);
        border-color: #1A1612;
        transform: translateY(-2px);
    }
</style>

<main class="error-404-section d-flex flex-column align-items-center justify-content-center">
    <div class="bg-glow-404-1"></div>
    <div class="bg-glow-404-2"></div>

    <!-- 100% Full-Width Edge-to-Edge Panoramic 404 Hero Banner -->
    <div class="banner-404-hero w-100">
        <div class="banner-404-illustration-wrapper">
            <img src="asset/image/artisan_404_hd_banner.png" alt="404 Artwork Not Found Banner" class="banner-404-img">
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
                <a href="collection.php" class="btn-explore-404">
                    <i class="fa-solid fa-compass me-2"></i> Explore Collections
                </a>
                <a href="index.php" class="btn-home-404">
                    <i class="fa-solid fa-house me-2"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</main>

<?php include_once('footer.php'); ?>