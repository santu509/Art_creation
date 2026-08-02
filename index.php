<!-- Navbar Inclusion -->
<?php
include_once('nav.php');
include_once('connection.php');
global $connect;
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

    /* -----------------------------------------
       FLOWING ANIMATED GOLD TEXT GRADIENT
    ----------------------------------------- */
    .animated-gold-text {
        font-family: 'Playfair Display', serif;
        /* Richer, deeper gold gradient without pure white so it stays readable */
        background: linear-gradient(to right,
                #B8860B 0%,
                #D4AF37 25%,
                #fce1b6 50%,
                #D4AF37 75%,
                #B8860B 100%);
        /* Set exactly to 200% for mathematical seamless looping */
        background-size: 200% auto;
        color: #B8860B;
        /* Fallback color */
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        /* Increased time slightly for a more elegant, smooth flow */
        animation: goldShineFlow 6s linear infinite;
        display: inline-block;
    }

    @keyframes goldShineFlow {
        0% {
            background-position: 0% center;
        }

        100% {
            /* Moving exactly to 200% matches the background size and creates a perfect loop */
            background-position: 200% center;
        }
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
            flex: 0 0 80%;
            min-width: 260px;
        }
    }

    /* Modern Custom Dot Indicator Scrollbar Styles */
    .cat-dot-control-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        margin-top: 25px;
    }

    .cat-dots-container {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cat-dot-item {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #E2DDD5;
        cursor: pointer;
        transition: all 0.35s cubic-bezier(0.25, 1, 0.5, 1);
    }

    .cat-dot-item:hover {
        background: #C59B27;
        transform: scale(1.25);
    }

    .cat-dot-item.active {
        width: 32px;
        height: 10px;
        border-radius: 20px;
        background: linear-gradient(90deg, #DFBA5A 0%, #C59B27 100%);
        box-shadow: 0 4px 12px rgba(197, 155, 39, 0.4);
    }

    .cat-scroll-nav-btn {
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

    .cat-scroll-nav-btn:hover {
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
            flex: 0 0 75%;
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
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
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
        color: #C59B27;
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
                                <h1 class="hero-title fw-bold text-white mb-4">
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
                                <h1 class="hero-title fw-bold text-white mb-4">
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
                                <h1 class="hero-title fw-bold text-white mb-4">
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
                <!-- Right Side -->
                <div class="col-12 col-lg-7">
                    <div class="row g-4">
                        <div class="col-12 col-sm-6">
                            <div class="feature-card feature-card-tall">
                                <div class="icon-box"><i class="fa-solid fa-hand-sparkles"></i></div>
                                <h4>100% Handmade</h4>
                                <p>No machines used. Just pure devotion and skilled hands shaping every detail of our idols and bags.</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 mt-sm-5">
                            <div class="feature-card">
                                <div class="icon-box"><i class="fa-solid fa-leaf"></i></div>
                                <h4>Eco-Friendly Clay</h4>
                                <p>We strictly use natural river clay and organic colors that are completely safe for nature & rivers.</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="feature-card feature-card-short">
                                <div class="icon-box"><i class="fa-solid fa-users"></i></div>
                                <h4>Support Local Artists</h4>
                                <p>Your purchase directly empowers our hardworking local artisans and supports their traditional families.</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 mt-sm-5">
                            <div class="feature-card">
                                <div class="icon-box"><i class="fa-solid fa-paintbrush"></i></div>
                                <h4>Custom Orders</h4>
                                <p>Looking for a specific idol or bag design? We take custom orders tailored to your exact choice.</p>
                            </div>
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
                    <h2 class="section-title animated-gold-text mb-2">Shop by Category</h2>
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
                            <a href="collection.php?category=<?php echo $cat['id']; ?>" class="modern-cat-card">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="cat-icon-wrapper">
                                            <i class="<?php echo $currentIcon; ?>"></i>
                                        </div>
                                        <span class="cat-card-count">
                                            <i class="fa-solid fa-box-open me-1"></i> <?php echo $countText; ?>
                                        </span>
                                    </div>
                                    <h3 class="cat-card-title"><?php echo htmlspecialchars($cat['name']); ?></h3>
                                </div>
                                <div class="cat-card-action">
                                    Explore Collection <i class="fa-solid fa-arrow-right ms-2"></i>
                                </div>
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
            // Fetch latest active products
            $products_query = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.status = 1 ORDER BY p.id DESC LIMIT 8";
            $products_result = mysqli_query($connect, $products_query);
            ?>

            <div class="horizontal-scroll-container mt-4">
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
                                <a href="product.php?id=<?php echo $prod['id']; ?>" class="text-decoration-none d-block flex-grow-1">
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
                                            <div class="action-btn" title="Add to Cart"><i class="fa-solid fa-cart-plus"></i></div>
                                            <div class="action-btn" title="Add to Wishlist"><i class="fa-regular fa-heart"></i></div>
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
                                <a href="product.php?id=<?php echo $prod['id']; ?>" class="add-to-cart-btn mt-auto">
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
        </div>
    </section>

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
        });
    </script>
</body>

</html>