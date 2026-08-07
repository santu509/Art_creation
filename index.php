<!-- Navbar Inclusion -->
<?php
include_once('nav.php');
include_once('connection.php');
global $connect;

$user_has_review = false;
$existing_rating = 0;
$existing_review = '';

if (isset($_SESSION['user_id'])) {
    $customer_id = $_SESSION['user_id'];
    $stmt = mysqli_prepare($connect, "SELECT rating, review FROM feedback WHERE customers_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $customer_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $user_has_review = true;
        $existing_rating = (int)$row['rating'];
        $existing_review = $row['review'];
    }
    mysqli_stmt_close($stmt);
}
?>

<style>
    /* -----------------------------------------
       Hero Banner Carousel Custom Core Styles 
    ----------------------------------------- */
    .hero-carousel .carousel-item {
        height: 82vh;
        min-height: 560px;
        max-height: 850px;
        background-size: cover;
        background-position: center center;
        transition: transform 1.2s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.8s ease-in-out;
    }

    /* Subtle Zoom Effect */
    .hero-carousel .carousel-item.active {
        animation: heroZoom 10s ease-out infinite alternate;
    }

    @keyframes heroZoom {
        0% {
            transform: scale(1);
        }

        100% {
            transform: scale(1.05);
        }
    }

    /* Rich Gradient Overlay */
    .hero-overlay {
        background: linear-gradient(135deg, rgba(26, 22, 18, 0.9) 0%, rgba(26, 22, 18, 0.65) 45%, rgba(0, 0, 0, 0.25) 100%);
    }

    /* Main Heading Custom Fonts */
    .hero-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 5vw, 3.2rem);
        letter-spacing: -0.5px;
        line-height: 1.18;
    }

    /* Autotyping Cursor Animation */
    .typing-cursor {
        display: inline-block;
        color: #DFBA5A;
        font-weight: 300;
        margin-left: 3px;
        animation: blinkCursor 0.75s ease-in-out infinite;
        user-select: none;
        -webkit-text-fill-color: #DFBA5A !important;
    }

    @keyframes blinkCursor {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }
    }

    .text-gold-accent {
        background: linear-gradient(135deg, #FFF0BD 0%, #DFBA5A 50%, #C59B27 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-style: italic;
    }

    /* Subtitle Description */
    .hero-description {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(1rem, 1.8vw, 1.2rem);
        color: #E2DDD5;
        line-height: 1.65;
        max-width: 620px;
    }

    /* Hero Action Buttons */
    .hero-btn-primary {
        background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 50%, #9B781E 100%);
        color: #1A1612;
        letter-spacing: 0.5px;
        transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        box-shadow: 0 8px 25px rgba(197, 155, 39, 0.35);
    }

    .hero-btn-primary:hover {
        color: #1A1612;
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(197, 155, 39, 0.5);
    }

    .hero-btn-secondary {
        background: rgba(255, 255, 255, 0.06);
        color: #F5F2ED;
        letter-spacing: 0.5px;
        border: 1px solid rgba(245, 242, 237, 0.25);
        backdrop-filter: blur(6px);
        transition: all 0.3s ease;
    }

    .hero-btn-secondary:hover {
        color: #DFBA5A;
        border-color: #DFBA5A;
        background: rgba(212, 175, 55, 0.12);
        transform: translateY(-3px);
    }

    /* Slide Keyframe Animations */
    .carousel-item:first-child.active {
        animation: fadeInUp 0.8s cubic-bezier(0.25, 1, 0.5, 1) both;
    }

    .carousel-item:first-child.active .hero-title {
        animation: fadeInUp 0.9s cubic-bezier(0.25, 1, 0.5, 1) 0.15s both;
    }

    .carousel-item:first-child.active .hero-description {
        animation: fadeInUp 0.9s cubic-bezier(0.25, 1, 0.5, 1) 0.3s both;
    }

    .carousel-item:first-child.active .hero-buttons-wrapper {
        animation: fadeInUp 0.9s cubic-bezier(0.25, 1, 0.5, 1) 0.45s both;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Glassmorphism Navigation Controls */
    .hero-carousel-control {
        width: 52px;
        height: 52px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        opacity: 0.85;
        transition: all 0.3s ease;
    }

    .hero-carousel-control:hover {
        background: rgba(212, 175, 55, 0.9);
        border-color: #DFBA5A;
        color: #1A1612 !important;
        opacity: 1;
        transform: translateY(-50%) scale(1.08);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
    }

    .hero-control-prev {
        left: 30px;
    }

    .hero-control-next {
        right: 30px;
    }

    /* Numbered Carousel Indicators */
    .hero-indicator-item {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .hero-indicator-item span.num {
        font-size: 0.75rem;
        color: #A59E96;
    }

    .hero-indicator-item span.label {
        font-size: 0.8rem;
        color: #E2DDD5;
        display: none;
    }

    .hero-indicator-bar {
        width: 24px;
        height: 3px;
        background: rgba(255, 255, 255, 0.3);
        transition: all 0.4s ease;
    }

    .hero-indicator-item.active {
        background: rgba(212, 175, 55, 0.2);
        border-color: rgba(212, 175, 55, 0.6);
    }

    .hero-indicator-item.active span.num {
        color: #DFBA5A;
    }

    .hero-indicator-item.active span.label {
        display: inline-block;
        color: #FFFFFF;
    }

    .hero-indicator-item.active .hero-indicator-bar {
        width: 45px;
        background: linear-gradient(90deg, #DFBA5A 0%, #C59B27 100%);
    }

    .hero-content-container {
        padding-top: 105px;
        padding-bottom: 95px;
    }

    .hero-indicators-wrapper {
        bottom: 60px;
    }

    @media (max-width: 768px) {
        .hero-carousel .carousel-item {
            height: auto !important;
            min-height: 520px;
            padding-top: 85px !important;
            padding-bottom: 50px !important;
        }

        .hero-content-container {
            padding-top: 10px !important;
            padding-bottom: 0 !important;
        }

        .hero-title {
            font-size: clamp(1.5rem, 5vw, 2rem) !important;
            margin-bottom: 0.65rem !important;
        }

        .hero-description {
            font-size: 0.84rem !important;
            margin-bottom: 1rem !important;
            line-height: 1.5 !important;
        }

        .hero-buttons-wrapper {
            gap: 8px !important;
            margin-bottom: 1.5rem !important;
        }

        /* Sleek Mobile Sizing for ALL Buttons */
        .hero-btn-primary,
        .hero-btn-secondary {
            padding: 8px 16px !important;
            font-size: 0.78rem !important;
            letter-spacing: 0.2px !important;
            border-radius: 50px !important;
        }

        .btn-gold-primary,
        .btn-gold-outline {
            padding: 8px 18px !important;
            font-size: 0.82rem !important;
        }

        .add-to-cart-btn {
            padding: 8px 12px !important;
            font-size: 0.85rem !important;
        }

        .hero-control-prev {
            left: 8px;
        }

        .hero-control-next {
            right: 8px;
        }

        .hero-carousel-control {
            width: 36px;
            height: 36px;
        }

        .hero-indicators-wrapper {
            bottom: 75px !important;
        }
    }

    @media (max-width: 576px) {

        .hero-btn-primary,
        .hero-btn-secondary {
            padding: 7px 13px !important;
            font-size: 0.73rem !important;
        }
    }

    /* -----------------------------------------
       About Us Section Styles 
    ----------------------------------------- */
    .about-local-shop {
        position: relative;
        background: linear-gradient(180deg, #FAF8F5 0%, #F5F0E8 100%);
        overflow: hidden;
    }

    .about-local-shop .bg-ambient-glow {
        position: absolute;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(223, 186, 90, 0.14) 0%, rgba(250, 248, 245, 0) 70%);
        top: -120px;
        left: -120px;
        pointer-events: none;
        z-index: 0;
    }

    .about-local-shop .bg-ambient-glow-2 {
        position: absolute;
        width: 450px;
        height: 450px;
        background: radial-gradient(circle, rgba(197, 155, 39, 0.12) 0%, rgba(250, 248, 245, 0) 70%);
        bottom: -100px;
        right: -80px;
        pointer-events: none;
        z-index: 0;
    }

    .about-local-shop .badge-custom {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        background: rgba(197, 155, 39, 0.12);
        color: #9B781E;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.8rem;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        border: 1px solid rgba(197, 155, 39, 0.28);
    }

    .about-local-shop .about-title {
        font-family: 'Playfair Display', serif;
        color: #1A1612;
        font-size: clamp(2.1rem, 4vw, 2.75rem);
        font-weight: 700;
        line-height: 1.22;
        letter-spacing: -0.5px;
    }

    .about-local-shop .text-gold-gradient {
        background: linear-gradient(135deg, #C59B27 0%, #9B781E 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-style: italic;
    }

    .about-local-shop .about-text {
        color: #5C5449;
        font-size: 1.05rem;
        line-height: 1.75;
        font-family: 'Outfit', sans-serif;
    }

    .border-gold-subtle {
        border-color: rgba(197, 155, 39, 0.22) !important;
    }

    .trust-stats-bar {
        gap: 1.5rem;
    }

    .trust-stats-bar .stat-num {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.15rem, 3.8vw, 1.6rem);
    }

    .trust-stats-bar .stat-label {
        font-size: clamp(0.66rem, 2.2vw, 0.78rem);
    }

    .trust-stats-bar .stat-divider {
        height: 32px;
        color: #C59B27;
    }

    @media (max-width: 576px) {
        .trust-stats-bar {
            gap: 0.35rem !important;
            width: 100%;
        }

        .trust-stats-bar .stat-divider {
            height: 22px;
            margin: 0 1px;
        }
    }

    .btn-gold-primary {
        background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 50%, #9B781E 100%);
        color: #1A1612;
        font-weight: 700;
        padding: 13px 30px;
        border-radius: 50px;
        transition: all 0.35s cubic-bezier(0.25, 1, 0.5, 1);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        font-size: 0.88rem;
    }

    .btn-gold-primary:hover {
        color: #1A1612;
        transform: translateY(-3px);
        box-shadow: 0 14px 30px rgba(197, 155, 39, 0.45);
    }

    .feature-card {
        background: #FFFFFF;
        padding: 32px 26px;
        border-radius: 20px;
        border: 1px solid rgba(212, 175, 55, 0.2);
        box-shadow: 0 10px 30px rgba(26, 22, 18, 0.04);
        transition: all 0.35s cubic-bezier(0.25, 1, 0.5, 1);
        position: relative;
        overflow: hidden;
    }

    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #DFBA5A 0%, #C59B27 100%);
        opacity: 0;
        transition: opacity 0.35s ease;
    }

    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 18px 40px rgba(197, 155, 39, 0.16);
        border-color: rgba(212, 175, 55, 0.5);
    }

    .feature-card:hover::before {
        opacity: 1;
    }

    .feature-card .icon-box {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%);
        color: #1A1612;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.45rem;
        margin-bottom: 20px;
        transition: all 0.35s ease;
    }

    .feature-card:hover .icon-box {
        transform: scale(1.1) rotate(5deg);
    }

    .feature-card h4 {
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .feature-card p {
        font-family: 'Outfit', sans-serif;
        font-size: 0.92rem;
        color: #655D53;
        margin: 0;
        line-height: 1.6;
    }

    /* -----------------------------------------
       Section Utility Styles
    ----------------------------------------- */
    .section-title {
        font-family: 'Playfair Display', serif;
        color: #2A241D;
        font-size: clamp(2rem, 4vw, 2.6rem);
        font-weight: 700;
    }



    .section-subtitle {
        color: #6C757D;
        font-size: 1rem;
        margin-bottom: 15px;
        font-family: 'Outfit', sans-serif;
    }

    .title-divider {
        width: 65px;
        height: 3px;
        background: linear-gradient(90deg, #DFBA5A 0%, #C59B27 100%);
        border-radius: 2px;
    }

    /* =========================================
       ULTRA MODERN CATEGORY CARDS SHOWCASE
    ========================================= */
    .premium-category-section {
        background-color: #FAF8F5;
        position: relative;
    }

    .modern-cat-card {
        position: relative;
        background: #FFFFFF;
        border: 1px solid #EAE6DF;
        border-radius: 22px;
        padding: 26px 22px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 180px;
        height: 100%;
        text-decoration: none;
        transition: all 0.35s cubic-bezier(0.25, 1, 0.5, 1);
        overflow: hidden;
        z-index: 1;
        box-shadow: 0 4px 18px rgba(42, 36, 29, 0.03);
    }

    .modern-cat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #DFBA5A 0%, #C59B27 100%);
        opacity: 0;
        transition: opacity 0.35s ease;
    }

    .modern-cat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 36px rgba(197, 155, 39, 0.14);
        border-color: rgba(212, 175, 55, 0.4);
    }

    .modern-cat-card:hover::before {
        opacity: 1;
    }

    .cat-icon-wrapper {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(197, 155, 39, 0.08) 100%);
        border: 1px solid rgba(212, 175, 55, 0.25);
        color: #C59B27;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        transition: all 0.35s ease;
        margin-bottom: 16px;
    }

    .modern-cat-card:hover .cat-icon-wrapper {
        background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%);
        color: #1A1612;
        border-color: transparent;
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 8px 20px rgba(197, 155, 39, 0.3);
    }

    .cat-card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1A1612;
        margin-bottom: 6px;
        transition: color 0.3s ease;
        line-height: 1.3;
    }

    .modern-cat-card:hover .cat-card-title {
        color: #C59B27;
    }

    .cat-card-count {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-family: 'Outfit', sans-serif;
        font-size: 0.78rem;
        font-weight: 600;
        color: #7C7267;
        background: #F8F5F0;
        padding: 4px 12px;
        border-radius: 50px;
        border: 1px solid #EAE6DF;
        transition: all 0.3s ease;
    }

    .modern-cat-card:hover .cat-card-count {
        background: rgba(212, 175, 55, 0.12);
        color: #9B781E;
        border-color: rgba(212, 175, 55, 0.3);
    }

    .cat-card-action {
        font-family: 'Outfit', sans-serif;
        font-size: 0.82rem;
        font-weight: 700;
        color: #C59B27;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        margin-top: 14px;
        transition: transform 0.3s ease;
    }

    .modern-cat-card:hover .cat-card-action {
        transform: translateX(4px);
    }

    /* Horizontal Scroll Slider for Modern Category Cards */
    .category-scroll-slider {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        padding-top: 10px;
        padding-bottom: 20px;
        padding-left: 5px;
        padding-right: 5px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        /* Hide scrollbar Firefox */
        -ms-overflow-style: none;
        /* Hide scrollbar IE/Edge */
    }

    .category-scroll-slider::-webkit-scrollbar {
        display: none;
        /* Hide scrollbar Chrome/Safari */
    }

    .category-scroll-item {
        flex: 0 0 280px;
        min-width: 270px;
        scroll-snap-align: start;
    }

    @media (max-width: 767.98px) {
        .category-scroll-item {
            flex: 0 0 100%;
            min-width: 100%;
        }
    }

    /* Modern Custom Dot Indicator Scrollbar Styles */
    .cat-dot-control-wrapper,
    .prod-dot-control-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        margin-top: 25px;
    }

    .cat-dots-container,
    .prod-dots-container {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cat-dot-item,
    .prod-dot-item {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #E2DDD5;
        cursor: pointer;
        transition: all 0.35s cubic-bezier(0.25, 1, 0.5, 1);
    }

    .cat-dot-item:hover,
    .prod-dot-item:hover {
        background: #C59B27;
        transform: scale(1.25);
    }

    .cat-dot-item.active,
    .prod-dot-item.active {
        width: 32px;
        height: 10px;
        border-radius: 20px;
        background: linear-gradient(90deg, #DFBA5A 0%, #C59B27 100%);
        box-shadow: 0 4px 12px rgba(197, 155, 39, 0.4);
    }

    .cat-scroll-nav-btn,
    .prod-scroll-nav-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #FFFFFF;
        border: 1px solid #E2DDD5;
        color: #1A1612;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        flex-shrink: 0;
        font-size: 0.85rem;
    }

    .cat-scroll-nav-btn:hover,
    .prod-scroll-nav-btn:hover {
        background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%);
        color: #FFFFFF;
        border-color: transparent;
        transform: scale(1.08);
        box-shadow: 0 6px 16px rgba(197, 155, 39, 0.3);
    }

    /* =========================================
       Horizontal Scroll Product Gallery 
    ========================================= */
    .product-gallery {
        background-color: #FFFFFF;
    }

    .horizontal-scroll-container {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;


        padding-top: 20px;
        padding-bottom: 30px;
        padding-left: 5px;
        padding-right: 5px;

        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .horizontal-scroll-container::-webkit-scrollbar {
        display: none;
    }

    .scroll-item {
        flex: 0 0 280px;
        scroll-snap-align: start;
    }

    @media (max-width: 768px) {
        .scroll-item {
            flex: 0 0 100%;
        }
    }

    /* Ultra Modern Product Card Styles */
    .modern-product-card {
        background: #fff;
        border-radius: 20px;
        padding: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid #F0ECE4;
        position: relative;
    }

    .modern-product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(197, 155, 39, 0.12);
        border-color: rgba(212, 175, 55, 0.4);
    }

    .modern-product-card .img-container {
        position: relative;
        border-radius: 14px;
        overflow: hidden;
        aspect-ratio: 1/1;
        background: #F9F7F3;
        margin-bottom: 15px;
    }

    .modern-product-card .img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .modern-product-card:hover .img-container img {
        transform: scale(1.08);
    }

    .badge-available,
    .badge-discount-inline {
        background: #DEF7EC;
        border: 1px solid #B3E3CE;
        color: #03543F;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .badge-available {
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 2;
    }

    .badge-discount-corner {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%);
        color: #1A1612;
        font-size: 0.76rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 50px;
        box-shadow: 0 4px 12px rgba(197, 155, 39, 0.3);
        z-index: 2;
        display: inline-flex;
        align-items: center;
    }

    .badge-available::before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #0E9F6E;
        border-radius: 50%;
    }

    .action-buttons {
        position: absolute;
        top: 10px;
        right: -50px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        transition: all 0.4s ease;
        z-index: 2;
    }

    .modern-product-card:hover .action-buttons {
        right: 10px;
    }

    .action-btn {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.95);
        color: #1A1612;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .action-btn:hover {
        background: #DFBA5A;
        color: #fff;
        transform: scale(1.1);
    }

    .card-info {
        padding: 0 5px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .cat-name {
        font-size: 0.75rem;
        color: #9B8A74;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 5px;
        font-weight: 600;
        font-family: 'Outfit', sans-serif;
    }

    .prod-name {
        /* font-family: 'Playfair Display', serif; */
        font-size: 1rem;
        font-weight: 700;
        color: #2C2620;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.3s ease;
    }

    .modern-product-card:hover .prod-name {
        color: #5a3301;
    }

    .price-box {
        margin-top: auto;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
    }

    .price-current {
        font-size: 1.2rem;
        font-weight: 700;
        color: #C59B27;
        font-family: 'Outfit', sans-serif;
    }

    .price-old {
        font-size: 0.85rem;
        color: #A59E96;
        text-decoration: line-through;
        font-family: 'Outfit', sans-serif;
    }

    .add-to-cart-btn {
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        background: #FDFBF7;
        color: #4A4036;
        border: 1px solid #EBE5D9;
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.4s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        cursor: pointer;
    }

    .modern-product-card:hover .add-to-cart-btn {
        background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%);
        color: #FFFFFF;
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(197, 155, 39, 0.25);
    }

    .btn-gold-outline {
        border: 2px solid #D4AF37;
        color: #B8860B;
        font-weight: 700;
        background-color: transparent;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-gold-outline:hover {
        background-color: #B8860B;
        color: #FFFFFF;
        border-color: #B8860B;
        box-shadow: 0 8px 22px rgba(184, 134, 11, 0.35);
        transform: translateY(-2px);
    }

    /* Premium Our Local Story Cards */
    .premium-image-card {
        transition: transform 0.4s ease, box-shadow 0.4s ease;
    }

    .premium-image-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15) !important;
    }

    .hover-zoom {
        transition: transform 0.8s ease;
    }

    .premium-image-card:hover .hover-zoom {
        transform: scale(1.08);
    }

    .premium-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0) 60%);
    }

    .font-outfit {
        font-family: 'Outfit', sans-serif;
    }

    .premium-text-card {
        transition: all 0.3s ease;
    }

    .premium-text-card:hover {
        background: #FDFBF7 !important;
        border-color: rgba(212, 175, 55, 0.5) !important;
        transform: translateY(-3px);
    }

    /* Ultra-Premium Minimalist Category Button */
    .category-btn-card {
        background: transparent;
        border: 1px solid rgba(197, 155, 39, 0.3);
        border-radius: 50px;
        padding: 20px 30px;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.25, 1, 0.5, 1);
        min-height: 120px;
    }

    .category-btn-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #1A1612 0%, #2A241D 100%);
        transform: translateY(100%);
        transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1);
        z-index: 0;
    }

    .category-btn-card:hover {
        border-color: #1A1612;
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(26, 22, 18, 0.15);
    }

    .category-btn-card:hover::before {
        transform: translateY(0);
    }

    .cat-btn-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.35rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        color: #1A1612;
        position: relative;
        z-index: 1;
        transition: color 0.5s ease;
        margin-bottom: 8px;
    }

    .category-btn-card:hover .cat-btn-title {
        color: #DFBA5A;
    }

    .cat-btn-count {
        font-family: 'Outfit', sans-serif;
        font-size: 0.75rem;
        font-weight: 400;
        letter-spacing: 2px;
        color: #8C8276;
        text-transform: uppercase;
        position: relative;
        z-index: 1;
        transition: color 0.5s ease;
    }

    .category-btn-card:hover .cat-btn-count {
        color: #E2DDD5;
    }

    /* Testimonial Section */
    .testimonial-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 30px 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border: none;
        height: 100%;
        transition: transform 0.3s ease;
        position: relative;
    }

    .testimonial-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    .quote-icon-custom {
        position: absolute;
        top: 25px;
        right: 25px;
        color: #F0E3D3;
        font-size: 3.2rem;
        line-height: 1;
        opacity: 0.8;
    }

    .testimonial-img-wrapper {
        width: 60px;
        height: 60px;
        flex-shrink: 0;
        border-radius: 50%;
        border: 2px solid #C49A45;
        padding: 2px;
        background: #fff;
    }

    .testimonial-img-wrapper img,
    .testimonial-img-wrapper div {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .testimonial-star {
        color: #FFB800;
        font-size: 1rem;
        margin-right: 2px;
    }

    /* Contact Section */
    .contact-form-control {
        border: 1px solid #EAE6DF;
        border-radius: 12px;
        padding: 12px 20px;
        font-family: 'Outfit', sans-serif;
        background: #FDFBF7;
    }

    .contact-form-control:focus {
        border-color: #DFBA5A;
        box-shadow: 0 0 0 3px rgba(223, 186, 90, 0.15);
        background: #FFFFFF;
    }
</style>

<body>
    <!-- Modern Banner Carousel Section -->
    <section class="position-relative overflow-hidden mt-0" style="background-color: #1A1612;">
        <div id="siddhaHeroCarousel" class="carousel slide carousel-fade hero-carousel" data-bs-ride="carousel" data-bs-interval="6000">
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active position-relative" style="background-image: url('asset/image/hero_banner_1.png');">
                    <div class="position-absolute top-0 start-0 w-100 h-100 z-1 hero-overlay"></div>
                    <div class="container hero-content-container position-relative z-2 h-100 d-flex align-items-center">
                        <div class="row align-items-center w-100">
                            <div class="col-12 col-lg-8 col-xl-7 px-4">
                                <h1 class="hero-title fw-bold text-white mb-4 autotype-title" data-phrases='[
                                    "Sacred Clay Idols &amp; <span class=\"text-gold-accent\">Divine Blessing</span> Creations",
                                    "Handcrafted River Clay &amp; <span class=\"text-gold-accent\">Pure Sacred</span> Artistry",
                                    "Eco-Friendly Sculptures &amp; <span class=\"text-gold-accent\">Artisan Divine</span> Masterpieces"
                                ]'>
                                    Sacred Clay Idols &amp; <span class="text-gold-accent">Divine Blessing</span> Creations
                                </h1>
                                <p class="hero-description fw-light mb-5">
                                    Elevate your home altar and festival celebrations with 100% eco-friendly, hand-sculpted raw clay idols. Crafted with pure river clay, sacred mantras, and intricate artisan detail.
                                </p>
                                <div class="hero-buttons-wrapper d-flex align-items-center gap-3 flex-wrap">
                                    <a href="collection.php" class="hero-btn-primary d-inline-flex align-items-center justify-content-center gap-2 px-4 py-3 rounded-pill text-uppercase fw-bold text-decoration-none border-0">
                                        <span>Explore Sacred Idols</span>
                                        <i class="fa-solid fa-arrow-right-long"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item position-relative" style="background-image: url('asset/image/hero_banner_2.png');">
                    <div class="position-absolute top-0 start-0 w-100 h-100 z-1 hero-overlay"></div>
                    <div class="container hero-content-container position-relative z-2 h-100 d-flex align-items-center">
                        <div class="row align-items-center w-100">
                            <div class="col-12 col-lg-8 col-xl-7 px-4">
                                <h1 class="hero-title fw-bold text-white mb-4 autotype-title" data-phrases='[
                                    "Earthy Terracotta &amp; <span class=\"text-gold-accent\">Rustic Home Decor</span> Artistry",
                                    "Hand-Molded Vases &amp; <span class=\"text-gold-accent\">Traditional Clay</span> Murals",
                                    "Warm Earthen Artifacts &amp; <span class=\"text-gold-accent\">Soulful Modern</span> Interiors"
                                ]'>
                                    Earthy Terracotta &amp; <span class="text-gold-accent">Rustic Home Decor</span> Artistry
                                </h1>
                                <p class="hero-description fw-light mb-5">
                                    Transform your living spaces with eco-friendly terracotta vases, clay wall murals, and traditional earthenware artifacts that bring warmth and soul to modern interiors.
                                </p>
                                <div class="hero-buttons-wrapper d-flex align-items-center gap-3 flex-wrap">
                                    <a href="collection.php" class="hero-btn-secondary d-inline-flex align-items-center justify-content-center gap-2 px-4 py-3 rounded-pill fw-semibold text-decoration-none">
                                        <span>Shop Terracotta Decor</span>
                                        <i class="fa-solid fa-arrow-right-long"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item position-relative" style="background-image: url('asset/image/hero_banner_3.png');">
                    <div class="position-absolute top-0 start-0 w-100 h-100 z-1 hero-overlay"></div>
                    <div class="container hero-content-container position-relative z-2 h-100 d-flex align-items-center">
                        <div class="row align-items-center w-100">
                            <div class="col-12 col-lg-8 col-xl-7 px-4">
                                <h1 class="hero-title fw-bold text-white mb-4 autotype-title" data-phrases='[
                                    "Bespoke Sculptures &amp; <span class=\"text-gold-accent\">Custom Hand-Molded</span> Statues",
                                    "Personalized Figures &amp; <span class=\"text-gold-accent\">Spiritual Vision</span> Creations",
                                    "Handcrafted Art by <span class=\"text-gold-accent\">Renowned Master</span> Sculptors"
                                ]'>
                                    Bespoke Sculptures &amp; <span class="text-gold-accent">Custom Hand-Molded</span> Statues
                                </h1>
                                <p class="hero-description fw-light mb-5">
                                    Turn your cherished memories and spiritual visions into tangible art. Commission personalized clay figures and custom statues handcrafted by renowned Indian master sculptors.
                                </p>
                                <div class="hero-buttons-wrapper d-flex align-items-center gap-3 flex-wrap">
                                    <a href="contact.php?type=custom-statue" class="hero-btn-primary d-inline-flex align-items-center justify-content-center gap-2 px-4 py-3 rounded-pill text-uppercase fw-bold text-decoration-none border-0">
                                        <span>Commission Custom Statue</span>
                                        <i class="fa-solid fa-arrow-right-long"></i>
                                    </a>
                                    <a href="aboutus.php" class="hero-btn-secondary d-inline-flex align-items-center justify-content-center gap-2 px-4 py-3 rounded-pill fw-semibold text-decoration-none">
                                        <span>Read Artisan Process</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Custom Controls & Indicators -->
            <button class="hero-carousel-control hero-control-prev rounded-circle d-flex align-items-center justify-content-center position-absolute top-50 translate-middle-y z-3 text-white border-0" type="button" data-bs-target="#siddhaHeroCarousel" data-bs-slide="prev">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button class="hero-carousel-control hero-control-next rounded-circle d-flex align-items-center justify-content-center position-absolute top-50 translate-middle-y z-3 text-white border-0" type="button" data-bs-target="#siddhaHeroCarousel" data-bs-slide="next">
                <i class="fa-solid fa-chevron-right"></i>
            </button>

            <div class="hero-indicators-wrapper position-absolute start-0 w-100 z-3 d-flex align-items-center justify-content-center gap-3 list-unstyled">
                <div class="hero-indicator-item active d-flex align-items-center gap-2 px-3 py-2 rounded-pill" data-bs-target="#siddhaHeroCarousel" data-bs-slide-to="0">
                    <span class="num fw-bold">01</span><span class="label fw-semibold">Divine Idols</span>
                    <div class="hero-indicator-bar rounded-1"></div>
                </div>
                <div class="hero-indicator-item d-flex align-items-center gap-2 px-3 py-2 rounded-pill" data-bs-target="#siddhaHeroCarousel" data-bs-slide-to="1">
                    <span class="num fw-bold">02</span><span class="label fw-semibold">Terracotta Decor</span>
                    <div class="hero-indicator-bar rounded-1"></div>
                </div>
                <div class="hero-indicator-item d-flex align-items-center gap-2 px-3 py-2 rounded-pill" data-bs-target="#siddhaHeroCarousel" data-bs-slide-to="2">
                    <span class="num fw-bold">03</span><span class="label fw-semibold">Custom Statues</span>
                    <div class="hero-indicator-bar rounded-1"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section class="about-local-shop py-5">
        <div class="bg-ambient-glow"></div>
        <div class="bg-ambient-glow-2"></div>
        <div class="container py-4 position-relative z-1">
            <div class="row align-items-center g-5">
                <!-- Left Side -->
                <div class="col-12 col-lg-5 text-center text-lg-start">
                    <div class="badge-custom mb-3"><i class="fa-solid fa-gem me-1"></i> Our Local Story</div>
                    <h2 class="about-title mb-4">Handmade with Love, <span class="text-gold-gradient">Direct from Artisans</span>.</h2>
                    <p class="about-text mb-4">
                        Siddha Art Creation is a celebration of our local craftsmanship. From beautifully detailed clay idols to handmade bags and terracotta decor, every single piece is crafted by the skilled hands of our local artists. We bring you the authentic touch of traditional art, directly from our workshop to your home.
                    </p>
                    <!-- Trust Feature Badges Bar (No Static Numbers) -->
                    <div class="trust-stats-bar d-flex align-items-center justify-content-between justify-content-lg-start gap-2 gap-sm-4 my-4 py-3 px-1 px-sm-3 border-top border-bottom border-gold-subtle">
                        <div class="stat-item d-flex align-items-center gap-2">
                            <i class="fa-solid fa-certificate fs-4 text-gold-gradient"></i>
                            <span class="stat-label small fw-semibold text-dark lh-sm">Authentic<br><span class="text-muted fw-normal">Craftsmanship</span></span>
                        </div>
                        <div class="stat-divider vr opacity-25"></div>
                        <div class="stat-item d-flex align-items-center gap-2">
                            <i class="fa-solid fa-seedling fs-4 text-gold-gradient"></i>
                            <span class="stat-label small fw-semibold text-dark lh-sm">Organic<br><span class="text-muted fw-normal">River Clay</span></span>
                        </div>
                        <div class="stat-divider vr opacity-25"></div>
                        <div class="stat-item d-flex align-items-center gap-2">
                            <i class="fa-solid fa-shield-heart fs-4 text-gold-gradient"></i>
                            <span class="stat-label small fw-semibold text-dark lh-sm">Safe & Care<br><span class="text-muted fw-normal">Packaging</span></span>
                        </div>
                    </div>
                    <a href="aboutus.php" class="btn-gold-primary text-uppercase mt-2">
                        <span>Read Our Story</span><i class="fa-solid fa-arrow-right-long ms-2"></i>
                    </a>
                </div>
                <!-- Right Side (Updated Layout) -->
                <div class="col-12 col-lg-7 d-flex justify-content-center align-items-center mt-5 mt-lg-0">
                    <div class="story-image-wrapper position-relative" style="width: 100%; max-width: 500px; aspect-ratio: 1/1;">
                        <!-- Gold Outline -->
                        <div class="story-outline position-absolute border rounded-4 border-2" style="border-color: #C59B27 !important; top: -15px; right: 15px; width: 90%; height: 90%; z-index: 0;"></div>

                        <!-- Main Back Image -->
                        <div class="story-img-main position-absolute rounded-4 overflow-hidden shadow-lg" style="top: 0; left: 0; width: 80%; height: 80%; border: 8px solid #FFFFFF; z-index: 1;">
                            <img src="asset/image/artisan_story.jpg" alt="Artisan Sculpting" class="img-fluid w-100 h-100 object-fit-cover">
                        </div>

                        <!-- EST Badge -->
                        <div class="story-badge position-absolute rounded-circle d-flex flex-column justify-content-center align-items-center shadow" style="top: -20px; left: -20px; width: 90px; height: 90px; background-color: #3b2a22; z-index: 2; border: 4px solid #FAF8F5;">
                            <div class="w-100 h-100 rounded-circle position-absolute" style="border: 1px dashed rgba(223, 186, 90, 0.6); top: 0; left: 0; transform: scale(0.85);"></div>
                            <span style="color: #DFBA5A; font-size: 0.7rem; font-family: 'Outfit', sans-serif; letter-spacing: 1px; margin-bottom: -2px;">EST.</span>
                            <span style="color: #FFFFFF; font-size: 1.1rem; font-family: 'Playfair Display', serif; font-weight: 700;">2026</span>
                        </div>

                        <!-- Small Front Image -->
                        <div class="story-img-small position-absolute rounded-4 overflow-hidden shadow-lg" style="bottom: 0; right: 0; width: 55%; height: 55%; border: 8px solid #FFFFFF; z-index: 3;">
                            <img src="asset/image/clay_artwork.jpg" alt="Clay Artwork" class="img-fluid w-100 h-100 object-fit-cover">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shop by Category Section (Modern Attractive Showcase) -->
    <section class="premium-category-section py-5">
        <div class="container py-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
                <div class="text-center text-md-start mb-3 mb-md-0">
                    <h2 class="section-title mb-2">Shop by <span style="color: #CBA232;">Category</span> </h2>
                    <p class="section-subtitle mb-0">
                        Discover our wide range of handcrafted categories tailored for your aesthetic taste.
                    </p>

                    <div class="title-divider mx-auto mx-md-0 mt-3"></div>
                </div>
                <div class="text-center text-md-end">
                    <a href="collection.php" class="btn btn-gold-outline rounded-pill px-4 py-2">
                        View All Categories <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

            <!-- Modern Category Horizontal Scroll Showcase -->
            <div class="category-scroll-slider mt-2" id="categoryScrollSlider">
                <?php
                // Array of aesthetic icons for category cards
                $categoryIcons = [
                    'fa-solid fa-hands-holding-circle',
                    'fa-solid fa-fire-burner',
                    'fa-solid fa-palette',
                    'fa-solid fa-gem',
                    'fa-solid fa-layer-group',
                    'fa-solid fa-crown',
                    'fa-solid fa-shapes'
                ];

                // Fetch categories and product count
                $cat_query = "SELECT c.id, c.name, (SELECT COUNT(id) FROM products p WHERE p.category_id = c.id AND p.status = 1) as product_count FROM categories c WHERE c.status = 1 ORDER BY c.id ASC";
                $cat_result = mysqli_query($connect, $cat_query);
                if ($cat_result && mysqli_num_rows($cat_result) > 0) {
                    $iconIdx = 0;
                    while ($cat = mysqli_fetch_assoc($cat_result)) {
                        $currentIcon = $categoryIcons[$iconIdx % count($categoryIcons)];
                        $iconIdx++;
                        $countText = sprintf("%02d", $cat['product_count']) . ' ' . ($cat['product_count'] == 1 ? 'Item' : 'Items');
                ?>
                        <div class="category-scroll-item">
                            <a href="collection.php?category=<?php echo $cat['id']; ?>" class="category-btn-card">
                                <span class="cat-btn-title"><?php echo htmlspecialchars($cat['name']); ?></span>
                                <span class="cat-btn-count"><?php echo $countText; ?></span>
                            </a>
                        </div>
                <?php
                    }
                }
                ?>
            </div>

            <!-- Dot Scrollbar Navigation Controls -->
            <div class="cat-dot-control-wrapper">
                <button class="cat-scroll-nav-btn" id="catScrollPrev" title="Scroll Left">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <div class="cat-dots-container" id="catDotsContainer">
                    <!-- Dynamic Dots populated via JS -->
                </div>
                <button class="cat-scroll-nav-btn" id="catScrollNext" title="Scroll Right">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Latest Masterpieces (Horizontal Scroll) -->
    <section class="product-gallery py-5">
        <div class="container py-4">
            <!-- Section Header -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
                <div class="text-center text-md-start mb-3 mb-md-0">
                    <h2 class="section-title mb-2">Our Latest <span style="color: #CBA232;">Masterpieces</span></h2>
                    <p class="section-subtitle mb-0">
                        Explore our newest exquisite handcrafted creations.
                    </p>
                    <div class="title-divider mx-auto mx-md-0 mt-3"></div>
                </div>
                <div class="text-center text-md-end">
                    <a href="collection.php" class="btn btn-gold-outline rounded-pill px-4 py-2">
                        View All Collections <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

            <?php
            // Fetch latest active product from each category
            $products_query = "
                SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id IN (
                    SELECT MAX(id) 
                    FROM products 
                    WHERE status = 1 
                    GROUP BY category_id
                )
                ORDER BY p.id DESC 
                LIMIT 8
            ";
            $products_result = mysqli_query($connect, $products_query);
            ?>

            <div class="horizontal-scroll-container mt-4" id="productScrollSlider">
                <?php
                if ($products_result && mysqli_num_rows($products_result) > 0):
                    while ($prod = mysqli_fetch_assoc($products_result)):
                        $img_src = !empty($prod['image']) ? "uploads/" . htmlspecialchars($prod['image']) : "asset/image/default-image.jpg";
                        $prod_cat_name = !empty($prod['category_name']) ? $prod['category_name'] : "Handcrafted";
                        $prod_price = floatval($prod['price']);
                        $prod_discount = floatval($prod['discount_percentage'] ?? 0);
                ?>
                        <div class="scroll-item">
                            <div class="modern-product-card">
                                <a href="product_details.php?id=<?php echo $prod['id']; ?>" class="text-decoration-none d-block flex-grow-1">
                                    <?php if (isset($prod['status']) && $prod['status'] == 1): ?>
                                        <span class="badge-available">Available</span>
                                    <?php endif; ?>

                                    <div class="img-container">
                                        <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($prod['name']); ?>" onerror="this.src='asset/image/default-image.jpg';">
                                        <?php if ($prod_discount > 0): ?>
                                            <span class="badge-discount-corner">
                                                <i class="fa-solid fa-tag me-1"></i><?php echo intval($prod_discount); ?>% OFF
                                            </span>
                                        <?php endif; ?>
                                        <div class="action-buttons">
                                            <div class="action-btn btn-wishlist" title="Add to Wishlist" data-product-id="<?php echo $prod['id']; ?>" onclick="event.preventDefault(); toggleWishlist(<?php echo $prod['id']; ?>, this);"><i class="fa-regular fa-heart"></i></div>
                                        </div>
                                    </div>
                                    <div class="card-info">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="cat-name mb-0"><?php echo htmlspecialchars($prod_cat_name); ?></span>
                                        </div>
                                        <h4 class="prod-name"><?php echo htmlspecialchars($prod['name']); ?></h4>
                                        <div class="price-box">
                                            <?php if ($prod_discount > 0):
                                                $final_price = $prod_price - ($prod_price * ($prod_discount / 100));
                                            ?>
                                                <span class="price-current">₹<?php echo number_format($final_price, 2); ?></span>
                                                <span class="price-old ms-2">₹<?php echo number_format($prod_price, 2); ?></span>
                                            <?php else: ?>
                                                <span class="price-current">₹<?php echo number_format($prod_price, 2); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                                <a href="product_details.php?id=<?php echo $prod['id']; ?>" class="add-to-cart-btn mt-auto">
                                    Explore Product <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    <?php
                    endwhile;
                else:
                    ?>
                    <div class="w-100 text-center text-muted py-5">
                        <i class="fa-solid fa-box-open fs-1 mb-3 text-light-gray"></i>
                        <p class="fs-5">More exquisite products coming soon!</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Dot Scrollbar Navigation Controls for Products -->
            <div class="prod-dot-control-wrapper">
                <button class="prod-scroll-nav-btn" id="prodScrollPrev" title="Scroll Left">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <div class="prod-dots-container" id="prodDotsContainer">
                    <!-- Dynamic Dots populated via JS -->
                </div>
                <button class="prod-scroll-nav-btn" id="prodScrollNext" title="Scroll Right">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <style>
        .testi-scroll-slider {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 1.5rem;
            -ms-overflow-style: none;
            /* IE/Edge */
            scrollbar-width: none;
            /* Firefox */
            scroll-behavior: smooth;
            padding-bottom: 15px;
        }

        .testi-scroll-slider::-webkit-scrollbar {
            display: none;
            /* Chrome/Safari */
        }

        .testi-scroll-item {
            flex: 0 0 calc(33.333% - 1rem);
            scroll-snap-align: start;
        }

        @media (max-width: 991.98px) {
            .testi-scroll-item {
                flex: 0 0 calc(50% - 0.75rem);
            }
        }

        @media (max-width: 767.98px) {
            .testi-scroll-item {
                flex: 0 0 100%;
            }
        }
    </style>

    <!-- Testimonials Section -->
    <section class="testimonials-section py-5" style="background-color: #FAF8F5;">
        <div class="container py-4">
            <div class="text-center mb-5">
                <div class="badge-custom mb-3"><i class="fa-solid fa-star me-1" style="color: #DFBA5A;"></i> Authentic Reviews</div>
                <h2 class="section-title mb-2">What Our <span style="color: #CBA232;">Clients Say</span></h2>
                <div class="title-divider mx-auto mt-3"></div>
            </div>

            <div class="testi-scroll-slider" id="testiScrollSlider">
                <?php
                $testi_query = "SELECT f.rating, f.review, u.name, u.image FROM feedback f JOIN users u ON f.customers_id = u.id ORDER BY f.id DESC";
                $testi_result = mysqli_query($connect, $testi_query);
                if ($testi_result && mysqli_num_rows($testi_result) > 0) {
                    while ($testi = mysqli_fetch_assoc($testi_result)) {
                        $profile_img = 'asset/image/default-image.jpg';
                        if (!empty($testi['image']) && $testi['image'] !== 'default.png') {
                            if (strpos($testi['image'], 'uploads/') === 0) {
                                $profile_img = $testi['image'];
                            } else {
                                $profile_img = 'uploads/' . $testi['image'];
                            }
                        }

                        // Extract initials as fallback
                        $words = explode(" ", trim($testi['name']));
                        $initials = "";
                        foreach ($words as $w) {
                            if (!empty($w)) $initials .= strtoupper($w[0]);
                        }
                        if (strlen($initials) > 2) $initials = substr($initials, 0, 2);
                        if (empty($initials)) $initials = "U";
                ?>
                        <div class="testi-scroll-item">
                            <div class="testimonial-card h-100 d-flex flex-column">
                                <i class="fa-solid fa-quote-right quote-icon-custom"></i>
                                <p class="font-outfit text-muted mb-4 flex-grow-1" style="line-height: 1.6; font-style: italic; font-size: 0.95rem; padding-right: 40px;"><?php echo htmlspecialchars('"' . $testi['review'] . '"'); ?></p>
                                <div class="d-flex align-items-center gap-3 mt-auto">
                                    <div class="testimonial-img-wrapper">
                                        <img src="<?php echo htmlspecialchars($profile_img); ?>" onerror="this.onerror=null; this.outerHTML='<div class=\'bg-dark text-white d-flex align-items-center justify-content-center fw-bold\' style=\'font-size: 1rem;\'><?php echo htmlspecialchars($initials); ?></div>';" alt="User">
                                    </div>

                                    <div>
                                        <h5 class="mb-1 fw-bold" style="font-family: 'Outfit', sans-serif; font-size: 1.1rem; color: #3E2B1F;"><?php echo htmlspecialchars($testi['name']); ?></h5>
                                        <div>
                                            <?php
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $testi['rating']) echo '<i class="fa-solid fa-star testimonial-star"></i>';
                                                else echo '<i class="fa-solid fa-star" style="color: #F0E3D3; font-size: 1rem; margin-right: 2px;"></i>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<p class="text-center text-muted w-100">No reviews yet.</p>';
                }
                ?>
            </div>

            <?php if ($testi_result && mysqli_num_rows($testi_result) > 0) { ?>
                <!-- Dot Scrollbar Navigation Controls -->
                <div class="cat-dot-control-wrapper mt-4">
                    <button class="cat-scroll-nav-btn" id="testiScrollPrev" title="Scroll Left">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <div class="cat-dots-container" id="testiDotsContainer">
                        <!-- Dynamic Dots populated via JS -->
                    </div>
                    <button class="cat-scroll-nav-btn" id="testiScrollNext" title="Scroll Right">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            <?php } ?>
        </div>
    </section>

    <!-- Contact & Feedback Section -->
    <style>
        .contact-new-section {
            background-color: #FDFBF7;
            font-family: 'Outfit', sans-serif;
        }

        .contact-new-section .section-subtitle {
            color: #C49A45;
            font-size: 0.85rem;
            letter-spacing: 2px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .contact-new-section .section-title {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: 2.5rem;
            color: #2D2D2D;
            position: relative;
            display: inline-block;
            margin-bottom: 3.5rem;
        }

        .contact-new-section .section-title::after {
            content: '';
            position: absolute;
            width: 60px;
            height: 1.5px;
            background-color: #C49A45;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
        }

        .contact-card,
        .feedback-card {
            background-color: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.04);
            padding: 2.5rem;
            height: 100%;
        }

        .contact-info-item {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            margin-bottom: 1.2rem;
        }

        .contact-info-icon {
            width: 48px;
            height: 48px;
            background-color: #FDF1D5;
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 1.2rem;
        }

        .contact-info-text h6 {
            margin-bottom: 0.1rem;
            font-weight: 500;
            color: #2D2D2D;
            font-size: 1.05rem;
        }

        .contact-info-text p {
            margin-bottom: 0;
            color: #717171;
            font-size: 0.9rem;
        }

        .map-container {
            border-radius: 12px;
            overflow: hidden;
            height: 200px;
            margin-top: 1.5rem;
            border: 1px solid #f0f0f0;
        }

        .star-rating {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin: 1.5rem 0;
            color: #e0e0e0;
            font-size: 2rem;
        }

        .star-rating i:hover,
        .star-rating i.active {
            color: #F8C31C;
            cursor: pointer;
        }

        .feedback-card textarea {
            border-radius: 8px;
            border: 1px solid #e8e8e8;
            padding: 1rem;
            resize: none;
            box-shadow: none;
            font-size: 0.95rem;
        }

        .feedback-card textarea:focus {
            border-color: #F8C31C;
            box-shadow: 0 0 0 0.2rem rgba(248, 195, 28, 0.15);
            outline: none;
        }

        .btn-submit-review {
            background-color: #F4C41B;
            color: #111;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 0.9rem 2rem;
            width: 100%;
            transition: all 0.3s;
            margin-top: 1.5rem;
        }

        .btn-submit-review:hover {
            background-color: #dfb215;
            color: #111;
        }

        .rate-title {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            color: #2D2D2D;
            font-size: 1.8rem;
        }

        .rate-title i {
            color: #F8C31C;
            margin-right: 0.5rem;
        }

        .select-rating-text {
            color: #555;
            font-weight: 500;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }

        /* Custom Guest Login Modal */
        .guest-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1050;
            display: none;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .guest-modal-overlay.show {
            display: flex;
            opacity: 1;
        }

        .guest-modal {
            background: #fff;
            border-radius: 16px;
            padding: 3rem 2.5rem;
            max-width: 420px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        .guest-modal-overlay.show .guest-modal {
            transform: translateY(0);
        }

        .guest-modal-icon {
            width: 70px;
            height: 70px;
            background: #FDF1D5;
            color: #C49A45;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1.5rem auto;
        }

        .guest-modal h4 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: #2D2D2D;
            margin-bottom: 1rem;
        }

        .guest-modal p {
            color: #666;
            margin-bottom: 2rem;
            font-family: 'Outfit', sans-serif;
        }

        .guest-modal .btn-login {
            background-color: #F4C41B;
            color: #111;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 0.8rem 2rem;
            width: 100%;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .guest-modal .btn-login:hover {
            background-color: #dfb215;
            color: #111;
        }

        .guest-modal .btn-close-modal {
            background: transparent;
            border: none;
            color: #aaa;
            font-size: 0.9rem;
            margin-top: 1rem;
            text-decoration: underline;
            transition: color 0.2s;
        }

        .guest-modal .btn-close-modal:hover {
            color: #666;
        }
    </style>

    <section class="contact-new-section py-5">
        <div class="container py-4">
            <div class="text-center mb-2">
                <div class="section-subtitle">Get in touch</div>
                <h2 class="section-title">Contact & Feedback</h2>
            </div>

            <div class="row g-4 align-items-stretch mt-3">
                <div class="col-lg-4">
                    <div class="contact-card">
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="contact-info-text">
                                <h6>Address</h6>
                                <p>CCLMS, Contai, India</p>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div class="contact-info-text">
                                <h6>Call Us</h6>
                                <p>+91 2583691235</p>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fa-brands fa-whatsapp"></i>
                            </div>
                            <div class="contact-info-text">
                                <h6>Whatsapp Us</h6>
                                <p>+91 3265489526</p>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div class="contact-info-text">
                                <h6>Email Us</h6>
                                <p>santusau@gmail.com</p>
                            </div>
                        </div>

                        <div class="map-container">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d117926.24135547926!2d87.69741544335937!3d21.776657900000005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a03264669f9d789%3A0xe985d7da0019672f!2sContai%2C%20West%20Bengal!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="feedback-card text-center">
                        <h3 class="rate-title"><i class="fa-solid fa-star"></i> Rate Our Service</h3>

                        <form id="feedbackForm">
                            <div class="star-rating" id="starRating">
                                <i class="fa-solid fa-star" data-rating="1"></i>
                                <i class="fa-solid fa-star" data-rating="2"></i>
                                <i class="fa-solid fa-star" data-rating="3"></i>
                                <i class="fa-solid fa-star" data-rating="4"></i>
                                <i class="fa-solid fa-star" data-rating="5"></i>
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" value="<?php echo $existing_rating; ?>">

                            <div class="select-rating-text">
                                <?php echo $user_has_review ? 'Update your rating' : 'Select Rating'; ?>
                            </div>

                            <textarea name="message" class="form-control" rows="6" placeholder="Write your review..." minlength="30" maxlength="120" required><?php echo htmlspecialchars($existing_review); ?></textarea>

                            <button type="submit" class="btn-submit-review">
                                <?php echo $user_has_review ? 'Update Review' : 'Submit Review'; ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Custom Guest Modal -->
    <div class="guest-modal-overlay" id="guestModal">
        <div class="guest-modal">
            <div class="guest-modal-icon">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h4>Login Required</h4>
            <p>You need to be logged in to share your valuable feedback with us.</p>
            <a href="login.php" class="btn-login">Go to Login</a>
            <button class="btn-close-modal" id="closeGuestModal">Maybe Later</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('#starRating i');
            const ratingInput = document.getElementById('ratingInput');
            const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
            const guestModal = document.getElementById('guestModal');
            const closeGuestModal = document.getElementById('closeGuestModal');
            const feedbackForm = document.getElementById('feedbackForm');


            // Initialize stars if existing rating
            highlightStars(ratingInput.value);

            stars.forEach(star => {
                star.addEventListener('mouseover', function() {
                    const rating = this.getAttribute('data-rating');
                    highlightStars(rating);
                });

                star.addEventListener('mouseout', function() {
                    const rating = ratingInput.value;
                    highlightStars(rating);
                });

                star.addEventListener('click', function() {
                    const rating = this.getAttribute('data-rating');
                    ratingInput.value = rating;
                    highlightStars(rating);
                });
            });

            function highlightStars(rating) {
                stars.forEach(star => {
                    if (star.getAttribute('data-rating') <= rating && rating > 0) {
                        star.style.color = '#F8C31C';
                    } else {
                        star.style.color = '#e0e0e0';
                    }
                });
            }

            // Guest Modal Logic
            if (closeGuestModal) {
                closeGuestModal.addEventListener('click', (e) => {
                    e.preventDefault();
                    guestModal.classList.remove('show');
                    setTimeout(() => guestModal.style.display = 'none', 300);
                });
            }

            // Form Submit Logic
            if (feedbackForm) {
                feedbackForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (!isLoggedIn) {
                        guestModal.style.display = 'flex';
                        // small delay for transition
                        setTimeout(() => guestModal.classList.add('show'), 10);
                        return;
                    }

                    if (ratingInput.value == 0) {
                        showToast('Please select a rating before submitting.', 'error');
                        return;
                    }

                    const message = feedbackForm.querySelector('textarea[name="message"]').value.trim();
                    if (message.length < 30 || message.length > 120) {
                        showToast('Your review must be between 20 and 50 characters.', 'error');
                        return;
                    }

                    // Basic spam/gibberish validation
                    // 1. Check for repeated characters (e.g., 'aaaaaa')
                    if (/(.)\1{4,}/.test(message)) {
                        showToast('Please provide relevant feedback, avoid repeating characters.', 'error');
                        return;
                    }
                    // 2. Check if the message is one long continuous word without spaces
                    if (message.indexOf(' ') === -1 && message.length > 15) {
                        showToast('Please provide a proper sentence with spaces.', 'error');
                        return;
                    }

                    const formData = new FormData(feedbackForm);
                    const submitBtn = feedbackForm.querySelector('.btn-submit-review');
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
                    submitBtn.disabled = true;

                    fetch('feedback_action.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                showToast(data.message, 'success');
                                submitBtn.innerHTML = 'Update Review';
                            } else {
                                showToast(data.message, 'error');
                                submitBtn.innerHTML = originalBtnText;
                            }
                        })
                        .catch(error => {
                            showToast('An error occurred. Please try again.', 'error');
                            submitBtn.innerHTML = originalBtnText;
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                        });
                });
            }
        });
    </script>

    <!-- Footer Inclusion -->
    <?php include_once('footer.php'); ?>

    <!-- Sync Custom Indicator States & Category Scrollbar -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hero Carousel Sync
            const heroCarousel = document.getElementById('siddhaHeroCarousel');
            const indicators = document.querySelectorAll('.hero-indicator-item');

            if (heroCarousel && indicators.length > 0) {
                heroCarousel.addEventListener('slide.bs.carousel', function(e) {
                    indicators.forEach((indicator, index) => {
                        if (index === e.to) {
                            indicator.classList.add('active');
                        } else {
                            indicator.classList.remove('active');
                        }
                    });
                });
            }

            // Custom Category Dot Scrollbar Logic
            const catSlider = document.getElementById('categoryScrollSlider');
            const catDotsContainer = document.getElementById('catDotsContainer');
            const catPrev = document.getElementById('catScrollPrev');
            const catNext = document.getElementById('catScrollNext');

            if (catSlider && catDotsContainer) {
                const catItems = catSlider.querySelectorAll('.category-scroll-item');
                const itemCount = catItems.length;

                // Create Dots matching item count
                catDotsContainer.innerHTML = '';
                for (let i = 0; i < itemCount; i++) {
                    const dot = document.createElement('div');
                    dot.className = 'cat-dot-item' + (i === 0 ? ' active' : '');
                    dot.setAttribute('title', `Go to category ${i + 1}`);
                    dot.addEventListener('click', function() {
                        if (catItems[i]) {
                            catItems[i].scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest',
                                inline: 'start'
                            });
                        }
                    });
                    catDotsContainer.appendChild(dot);
                }

                const dots = catDotsContainer.querySelectorAll('.cat-dot-item');

                function updateActiveDot() {
                    const sliderRect = catSlider.getBoundingClientRect();
                    let activeIndex = 0;
                    let minDiff = Infinity;

                    catItems.forEach((item, index) => {
                        const itemRect = item.getBoundingClientRect();
                        const diff = Math.abs(itemRect.left - sliderRect.left);
                        if (diff < minDiff) {
                            minDiff = diff;
                            activeIndex = index;
                        }
                    });

                    dots.forEach((dot, index) => {
                        if (index === activeIndex) {
                            dot.classList.add('active');
                        } else {
                            dot.classList.remove('active');
                        }
                    });
                }

                catSlider.addEventListener('scroll', updateActiveDot);
                window.addEventListener('resize', updateActiveDot);

                if (catPrev) {
                    catPrev.addEventListener('click', function() {
                        catSlider.scrollBy({
                            left: -290,
                            behavior: 'smooth'
                        });
                    });
                }
                if (catNext) {
                    catNext.addEventListener('click', function() {
                        catSlider.scrollBy({
                            left: 290,
                            behavior: 'smooth'
                        });
                    });
                }
            }

            // Custom Testimonial Dot Scrollbar Logic
            const testiSlider = document.getElementById('testiScrollSlider');
            const testiDotsContainer = document.getElementById('testiDotsContainer');
            const testiPrev = document.getElementById('testiScrollPrev');
            const testiNext = document.getElementById('testiScrollNext');

            if (testiSlider && testiDotsContainer) {
                const testiItems = testiSlider.querySelectorAll('.testi-scroll-item');
                const itemCount = testiItems.length;

                // Create Dots matching item count
                testiDotsContainer.innerHTML = '';
                for (let i = 0; i < itemCount; i++) {
                    const dot = document.createElement('div');
                    dot.className = 'cat-dot-item' + (i === 0 ? ' active' : '');
                    dot.setAttribute('title', `Go to testimonial ${i + 1}`);
                    dot.addEventListener('click', function() {
                        if (testiItems[i]) {
                            testiItems[i].scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest',
                                inline: 'start'
                            });
                        }
                    });
                    testiDotsContainer.appendChild(dot);
                }

                const dots = testiDotsContainer.querySelectorAll('.cat-dot-item');

                function updateActiveDotTesti() {
                    const sliderRect = testiSlider.getBoundingClientRect();
                    let activeIndex = 0;
                    let minDiff = Infinity;

                    testiItems.forEach((item, index) => {
                        const itemRect = item.getBoundingClientRect();
                        const diff = Math.abs(itemRect.left - sliderRect.left);
                        if (diff < minDiff) {
                            minDiff = diff;
                            activeIndex = index;
                        }
                    });

                    dots.forEach((dot, index) => {
                        if (index === activeIndex) {
                            dot.classList.add('active');
                        } else {
                            dot.classList.remove('active');
                        }
                    });
                }

                testiSlider.addEventListener('scroll', updateActiveDotTesti);
                window.addEventListener('resize', updateActiveDotTesti);

                if (testiPrev) {
                    testiPrev.addEventListener('click', function() {
                        testiSlider.scrollBy({
                            left: -320,
                            behavior: 'smooth'
                        });
                    });
                }
                if (testiNext) {
                    testiNext.addEventListener('click', function() {
                        testiSlider.scrollBy({
                            left: 320,
                            behavior: 'smooth'
                        });
                    });
                }
            }

            // Custom Product Dot Scrollbar Logic
            const prodSlider = document.getElementById('productScrollSlider');
            const prodDotsContainer = document.getElementById('prodDotsContainer');
            const prodPrev = document.getElementById('prodScrollPrev');
            const prodNext = document.getElementById('prodScrollNext');

            if (prodSlider && prodDotsContainer) {
                const prodItems = prodSlider.querySelectorAll('.scroll-item');
                const itemCount = prodItems.length;

                // Create Dots matching item count
                prodDotsContainer.innerHTML = '';
                for (let i = 0; i < itemCount; i++) {
                    const dot = document.createElement('div');
                    dot.className = 'prod-dot-item' + (i === 0 ? ' active' : '');
                    dot.setAttribute('title', `Go to product ${i + 1}`);
                    dot.addEventListener('click', function() {
                        if (prodItems[i]) {
                            prodItems[i].scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest',
                                inline: 'start'
                            });
                        }
                    });
                    prodDotsContainer.appendChild(dot);
                }

                const dots = prodDotsContainer.querySelectorAll('.prod-dot-item');

                function updateActiveProductDot() {
                    const sliderRect = prodSlider.getBoundingClientRect();
                    let activeIndex = 0;
                    let minDiff = Infinity;

                    prodItems.forEach((item, index) => {
                        const itemRect = item.getBoundingClientRect();
                        const diff = Math.abs(itemRect.left - sliderRect.left);
                        if (diff < minDiff) {
                            minDiff = diff;
                            activeIndex = index;
                        }
                    });

                    dots.forEach((dot, index) => {
                        if (index === activeIndex) {
                            dot.classList.add('active');
                        } else {
                            dot.classList.remove('active');
                        }
                    });
                }

                prodSlider.addEventListener('scroll', updateActiveProductDot);
                window.addEventListener('resize', updateActiveProductDot);

                if (prodPrev) {
                    prodPrev.addEventListener('click', function() {
                        prodSlider.scrollBy({
                            left: -300,
                            behavior: 'smooth'
                        });
                    });
                }
                if (prodNext) {
                    prodNext.addEventListener('click', function() {
                        prodSlider.scrollBy({
                            left: 300,
                            behavior: 'smooth'
                        });
                    });
                }
            }

            // ================================================
            // Auto-Typing Text Animation Engine with Cursor
            // ================================================
            class TypewriterEngine {
                constructor(element, phrases, options = {}) {
                    this.element = element;
                    this.phrases = phrases;

                    // ==========================================================
                    // ⚙️ SPEED CONTROLS
                    // ==========================================================
                    this.typeSpeed = options.typeSpeed || 120; // Type korar speed
                    this.deleteSpeed = options.deleteSpeed || 40; //deletespeed
                    this.holdTime = options.holdTime || 3000; // Hold time
                    this.pauseTime = options.pauseTime || 500; // pause time to start new line
                    // ==========================================================

                    this.phraseIndex = 0;
                    this.tokenIndex = 0;
                    this.isDeleting = false;
                    this.timer = null;

                    this.element.innerHTML = '<span class="autotype-content"></span><span class="typing-cursor">|</span>';
                    this.contentSpan = this.element.querySelector('.autotype-content');

                    this.start();
                }

                tokenize(html) {
                    const tokens = [];
                    let i = 0;
                    while (i < html.length) {
                        if (html[i] === '<') {
                            let tag = '';
                            while (i < html.length && html[i] !== '>') {
                                tag += html[i];
                                i++;
                            }
                            if (i < html.length) {
                                tag += html[i];
                                i++;
                            }
                            tokens.push({
                                type: 'tag',
                                value: tag
                            });
                        } else if (html[i] === '&') {
                            let semicolonIndex = html.indexOf(';', i);
                            if (semicolonIndex !== -1 && semicolonIndex - i <= 10) {
                                let entity = html.substring(i, semicolonIndex + 1);
                                tokens.push({
                                    type: 'entity',
                                    value: entity
                                });
                                i = semicolonIndex + 1;
                            } else {
                                tokens.push({
                                    type: 'char',
                                    value: html[i]
                                });
                                i++;
                            }
                        } else {
                            tokens.push({
                                type: 'char',
                                value: html[i]
                            });
                            i++;
                        }
                    }
                    return tokens;
                }

                render(tokens, maxIndex) {
                    let html = '';
                    const openTags = [];

                    for (let i = 0; i < maxIndex; i++) {
                        const token = tokens[i];
                        if (token.type === 'tag') {
                            html += token.value;
                            if (token.value.startsWith('</')) {
                                openTags.pop();
                            } else if (!token.value.endsWith('/>')) {
                                const match = token.value.match(/<([a-zA-Z0-9]+)/);
                                if (match) {
                                    openTags.push(match[1]);
                                }
                            }
                        } else {
                            html += token.value;
                        }
                    }

                    for (let i = openTags.length - 1; i >= 0; i--) {
                        html += `</${openTags[i]}>`;
                    }

                    this.contentSpan.innerHTML = html;
                }

                tick() {
                    const currentPhrase = this.phrases[this.phraseIndex];
                    const tokens = this.tokenize(currentPhrase);

                    if (!this.isDeleting) {
                        // Type forward
                        while (this.tokenIndex < tokens.length && tokens[this.tokenIndex].type === 'tag') {
                            this.tokenIndex++;
                        }

                        this.render(tokens, this.tokenIndex);

                        if (this.tokenIndex < tokens.length) {
                            this.tokenIndex++;
                            this.timer = setTimeout(() => this.tick(), this.typeSpeed);
                        } else {
                            // Reached the end: pause with blinking cursor then delete backward
                            this.isDeleting = true;
                            this.timer = setTimeout(() => this.tick(), this.holdTime);
                        }
                    } else {
                        // Delete backward
                        while (this.tokenIndex > 0 && tokens[this.tokenIndex - 1].type === 'tag') {
                            this.tokenIndex--;
                        }

                        this.render(tokens, this.tokenIndex);

                        if (this.tokenIndex > 0) {
                            this.tokenIndex--;
                            this.timer = setTimeout(() => this.tick(), this.deleteSpeed);
                        } else {
                            // Finished deleting backward: move to next phrase
                            this.isDeleting = false;
                            this.phraseIndex = (this.phraseIndex + 1) % this.phrases.length;
                            this.timer = setTimeout(() => this.tick(), this.pauseTime);
                        }
                    }
                }

                start() {
                    this.tick();
                }
            }

            // Initialize all autotype titles
            document.querySelectorAll('.autotype-title').forEach(el => {
                let phrases = [];
                try {
                    const dataAttr = el.getAttribute('data-phrases');
                    if (dataAttr) {
                        phrases = JSON.parse(dataAttr);
                    }
                } catch (e) {
                    console.error('Error parsing autotype phrases:', e);
                }
                if (!phrases || phrases.length === 0) {
                    phrases = [el.innerHTML.trim()];
                }
                new TypewriterEngine(el, phrases);
            });
        });
    </script>
</body>

</html>