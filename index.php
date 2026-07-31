<!-- Navbar Inclusion -->
<?php include_once('nav.php'); ?>

<style>
    /* -----------------------------------------
       Hero Banner Carousel Custom Core Styles 
       (Only animations, gradients, and specific sizes are kept here)
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

    /* Slide Keyframe Animations (Only active on the first slide) */
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

    .feature-box-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(184, 134, 11, 0.08) 100%);
        border: 1px solid rgba(212, 175, 55, 0.3);
        color: #DFBA5A;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .feature-box-title {
        font-family: 'Playfair Display', serif;
        color: #F5F2ED;
    }

    .feature-box-desc {
        color: #A59E96;
    }

    .hero-content-container {
        padding-top: 105px;
        padding-bottom: 95px;
    }

    .hero-indicators-wrapper {
        bottom: 13px;
    }

    @media (max-width: 768px) {
        .hero-carousel .carousel-item {
            height: auto !important;
            min-height: 640px;
            padding-top: 95px !important;
            padding-bottom: 65px !important;
        }

        .hero-content-container {
            padding-top: 10px !important;
            padding-bottom: 0 !important;
        }

        .hero-title {
            font-size: clamp(1.65rem, 5.5vw, 2.2rem) !important;
            line-height: 1.22 !important;
            margin-bottom: 0.75rem !important;
        }

        .hero-description {
            font-size: 0.88rem !important;
            line-height: 1.5 !important;
            margin-bottom: 1.25rem !important;
        }

        .hero-buttons-wrapper {
            gap: 10px !important;
            margin-bottom: 2.2rem !important;
        }

        .hero-btn-primary,
        .hero-btn-secondary {
            padding: 10px 20px !important;
            font-size: 0.8rem !important;
        }

        .hero-control-prev {
            left: 8px;
        }

        .hero-control-next {
            right: 8px;
        }

        .hero-carousel-control {
            width: 38px;
            height: 38px;
            font-size: 0.85rem;
        }

        .hero-indicators-wrapper {
            bottom: 115px !important;
        }

        .hero-indicator-item {
            padding: 4px 10px !important;
        }

        .hero-indicator-item span.num {
            font-size: 0.7rem !important;
        }

        .hero-indicator-item span.label {
            font-size: 0.72rem !important;
        }
    }

    /* -----------------------------------------
       About Us & Why Choose Us Section Styles 
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
        box-shadow: 0 4px 12px rgba(197, 155, 39, 0.08);
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

    /* Trust Stats Bar Responsive Styling */
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
            padding-left: 0 !important;
            padding-right: 0 !important;
            width: 100%;
        }

        .trust-stats-bar .stat-item {
            gap: 4px !important;
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
        letter-spacing: 0.5px;
        padding: 13px 30px;
        border: none;
        border-radius: 50px;
        transition: all 0.35s cubic-bezier(0.25, 1, 0.5, 1);
        box-shadow: 0 8px 22px rgba(197, 155, 39, 0.28);
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

    .btn-gold-primary i {
        transition: transform 0.3s ease;
    }

    .btn-gold-primary:hover i {
        transform: translateX(5px);
    }

    .feature-card {
        position: relative;
        background: #FFFFFF;
        padding: 32px 26px;
        border-radius: 20px;
        border: 1px solid rgba(212, 175, 55, 0.2);
        box-shadow: 0 10px 30px rgba(26, 22, 18, 0.04);
        height: 100%;
        transition: all 0.35s cubic-bezier(0.25, 1, 0.5, 1);
        overflow: hidden;
        z-index: 1;
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
        box-shadow: 0 8px 20px rgba(197, 155, 39, 0.28);
        transition: all 0.35s ease;
    }

    .feature-card:hover .icon-box {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 12px 25px rgba(197, 155, 39, 0.42);
    }

    .feature-card h4 {
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1A1612;
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
       Shop by Category Section Styles 
    ----------------------------------------- */
    .shop-by-category {
        background-color: #FFFFFF;
        position: relative;
    }

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

    /* Light Modern Category Card (Warm Luxury UI) */
    .category-card {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        width: 100%;
        min-height: 270px;
        aspect-ratio: 4 / 4.6;
        border-radius: 22px;
        overflow: hidden;
        text-decoration: none;
        padding: 30px 24px;
        background: linear-gradient(145deg, #FFFFFF 0%, #F9F5EE 100%);
        border: 1px solid rgba(212, 175, 55, 0.22);
        box-shadow: 0 10px 30px rgba(42, 36, 29, 0.05);
        transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        z-index: 1;
    }

    /* Top Accent Line */
    .category-card::before {
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

    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 18px 40px rgba(184, 134, 11, 0.15);
        border-color: rgba(212, 175, 55, 0.55);
        background: linear-gradient(145deg, #FFFFFF 0%, #F5EFE4 100%);
    }

    .category-card:hover::before {
        opacity: 1;
    }

    /* Large Monogram Watermark (Light Theme) */
    .category-watermark {
        position: absolute;
        top: 10px;
        right: 18px;
        font-family: 'Playfair Display', serif;
        font-size: 6.8rem;
        font-weight: 800;
        color: rgba(184, 134, 11, 0.07);
        line-height: 1;
        pointer-events: none;
        user-select: none;
        transition: color 0.4s ease, transform 0.4s ease;
        z-index: 0;
    }

    .category-card:hover .category-watermark {
        color: rgba(184, 134, 11, 0.14);
        transform: scale(1.1) translateX(-4px);
    }

    /* Card Top Header */
    .category-card-top {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .category-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.14) 0%, rgba(184, 134, 11, 0.06) 100%);
        border: 1px solid rgba(212, 175, 55, 0.28);
        color: #B8860B;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        transition: all 0.35s ease;
        box-shadow: 0 4px 12px rgba(184, 134, 11, 0.06);
    }

    .category-card:hover .category-icon-box {
        background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%);
        color: #1A1612;
        border-color: transparent;
        box-shadow: 0 8px 20px rgba(197, 155, 39, 0.35);
    }

    .category-tag {
        font-family: 'Outfit', sans-serif;
        font-size: 0.73rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #B8860B;
        background: rgba(184, 134, 11, 0.08);
        padding: 5px 12px;
        border-radius: 20px;
        border: 1px solid rgba(184, 134, 11, 0.18);
    }

    /* Card Bottom Content */
    .category-card-bottom {
        position: relative;
        z-index: 2;
    }

    .category-title {
        font-family: 'Playfair Display', serif;
        color: #1A1612;
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 6px;
        line-height: 1.3;
        transition: color 0.3s ease, transform 0.35s ease;
    }

    .category-card:hover .category-title {
        color: #B8860B;
        transform: translateY(-2px);
    }

    .category-subtext {
        font-family: 'Outfit', sans-serif;
        font-size: 0.86rem;
        color: #7C7267;
        margin-bottom: 16px;
    }

    .category-cta {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #B8860B;
        font-family: 'Outfit', sans-serif;
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.35s ease;
    }

    .category-cta .cta-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(184, 134, 11, 0.1);
        border: 1px solid rgba(184, 134, 11, 0.22);
        color: #B8860B;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        transition: all 0.35s ease;
    }

    .category-card:hover .category-cta {
        color: #1A1612;
    }

    .category-card:hover .category-cta .cta-icon {
        transform: translateX(6px);
        background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%);
        color: #1A1612;
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(197, 155, 39, 0.35);
    }

    /* Optional Image Overrides if image IS present */
    .category-card.has-image-card {
        padding: 0;
        display: block;
    }

    .category-card.has-image-card .category-img-wrapper {
        position: absolute;
        inset: 0;
        overflow: hidden;
    }

    .category-card.has-image-card .category-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .category-card.has-image-card:hover .category-img {
        transform: scale(1.12);
    }

    .category-card.has-image-card .category-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(26, 22, 18, 0) 25%, rgba(26, 22, 18, 0.45) 60%, rgba(26, 22, 18, 0.92) 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 26px 22px;
        z-index: 2;
    }

    .btn-gold-outline {
        border: 2px solid #D4AF37;
        color: #B8860B;
        font-weight: 700;
        transition: all 0.3s ease;
        background-color: transparent;
        text-decoration: none;
        letter-spacing: 0.5px;
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
                <!-- Slide 1: Divine Clay Idols -->
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
                                    <a href="collections.php?category=divine-idols" class="hero-btn-primary d-inline-flex align-items-center justify-content-center gap-2 px-4 py-3 rounded-pill text-uppercase fw-bold text-decoration-none border-0">
                                        <span>Explore Sacred Idols</span>
                                        <i class="fa-solid fa-arrow-right-long"></i>
                                    </a>
                                    <a href="gallery.php" class="hero-btn-secondary d-inline-flex align-items-center justify-content-center gap-2 px-4 py-3 rounded-pill fw-semibold text-decoration-none">
                                        <span>View Divine Gallery</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2: Terracotta Home Decor -->
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
                                    <a href="collections.php?category=terracotta-decor" class="hero-btn-primary d-inline-flex align-items-center justify-content-center gap-2 px-4 py-3 rounded-pill text-uppercase fw-bold text-decoration-none border-0">
                                        <span>Shop Terracotta Decor</span>
                                        <i class="fa-solid fa-arrow-right-long"></i>
                                    </a>
                                    <a href="about.php#styling-guide" class="hero-btn-secondary d-inline-flex align-items-center justify-content-center gap-2 px-4 py-3 rounded-pill fw-semibold text-decoration-none">
                                        <span>Decor Styling Guide</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3: Custom Sculptures & Artisans -->
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
                                    <a href="about.php#artisan-story" class="hero-btn-secondary d-inline-flex align-items-center justify-content-center gap-2 px-4 py-3 rounded-pill fw-semibold text-decoration-none">
                                        <span>Watch Artisan Process <i class="fa-solid fa-circle-play ms-1"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Custom Previous & Next Controls -->
            <button class="hero-carousel-control hero-control-prev rounded-circle d-flex align-items-center justify-content-center position-absolute top-50 translate-middle-y z-3 text-white border-0" type="button" data-bs-target="#siddhaHeroCarousel" data-bs-slide="prev" aria-label="Previous Slide">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button class="hero-carousel-control hero-control-next rounded-circle d-flex align-items-center justify-content-center position-absolute top-50 translate-middle-y z-3 text-white border-0" type="button" data-bs-target="#siddhaHeroCarousel" data-bs-slide="next" aria-label="Next Slide">
                <i class="fa-solid fa-chevron-right"></i>
            </button>

            <!-- Custom Indicators Bar -->
            <div class="hero-indicators-wrapper position-absolute start-0 w-100 z-3 d-flex align-items-center justify-content-center gap-3 list-unstyled">
                <div class="hero-indicator-item active d-flex align-items-center gap-2 px-3 py-2 rounded-pill" data-bs-target="#siddhaHeroCarousel" data-bs-slide-to="0">
                    <span class="num fw-bold">01</span>
                    <span class="label fw-semibold">Divine Idols</span>
                    <div class="hero-indicator-bar rounded-1"></div>
                </div>
                <div class="hero-indicator-item d-flex align-items-center gap-2 px-3 py-2 rounded-pill" data-bs-target="#siddhaHeroCarousel" data-bs-slide-to="1">
                    <span class="num fw-bold">02</span>
                    <span class="label fw-semibold">Terracotta Decor</span>
                    <div class="hero-indicator-bar rounded-1"></div>
                </div>
                <div class="hero-indicator-item d-flex align-items-center gap-2 px-3 py-2 rounded-pill" data-bs-target="#siddhaHeroCarousel" data-bs-slide-to="2">
                    <span class="num fw-bold">03</span>
                    <span class="label fw-semibold">Custom Statues</span>
                    <div class="hero-indicator-bar rounded-1"></div>
                </div>
            </div>
        </div>
    </section>


    <!-- About Us / Why Choose Us Section -->
    <section class="about-local-shop py-5">
        <!-- Ambient background glows -->
        <div class="bg-ambient-glow"></div>
        <div class="bg-ambient-glow-2"></div>

        <div class="container py-4 position-relative z-1">
            <div class="row align-items-center g-5">

                <!-- Left Side: About Our Shop -->
                <div class="col-12 col-lg-5 text-center text-lg-start">
                    <div class="badge-custom mb-3">
                        <i class="fa-solid fa-gem me-1"></i> Our Local Story
                    </div>
                    <h2 class="about-title mb-4">
                        Handmade with Love, <span class="text-gold-gradient">Direct from Artisans</span>.
                    </h2>
                    <p class="about-text mb-4">
                        Siddha Art Creation is a celebration of our local craftsmanship. From beautifully detailed clay idols to handmade bags and terracotta decor, every single piece is crafted by the skilled hands of our local artists. We bring you the authentic touch of traditional art, directly from our workshop to your home.
                    </p>

                    <!-- Trust Stats Bar -->
                    <div class="trust-stats-bar d-flex align-items-center justify-content-between justify-content-lg-start my-4 py-3 px-1 px-sm-3 border-top border-bottom border-gold-subtle">
                        <div class="stat-item d-flex align-items-center gap-1 gap-sm-2">
                            <h3 class="stat-num fw-bold mb-0 text-gold-gradient">50+</h3>
                            <span class="stat-label small text-muted lh-sm">Master<br>Artisans</span>
                        </div>
                        <div class="stat-divider vr opacity-25"></div>
                        <div class="stat-item d-flex align-items-center gap-1 gap-sm-2">
                            <h3 class="stat-num fw-bold mb-0 text-gold-gradient">100%</h3>
                            <span class="stat-label small text-muted lh-sm">Pure River<br>Clay</span>
                        </div>
                        <div class="stat-divider vr opacity-25"></div>
                        <div class="stat-item d-flex align-items-center gap-1 gap-sm-2">
                            <h3 class="stat-num fw-bold mb-0 text-gold-gradient">10k+</h3>
                            <span class="stat-label small text-muted lh-sm">Sacred Idols<br>Crafted</span>
                        </div>
                    </div>

                    <a href="aboutus.php" class="btn-gold-primary text-uppercase mt-2">
                        <span>Read Our Story</span>
                        <i class="fa-solid fa-arrow-right-long ms-2"></i>
                    </a>
                </div>

                <!-- Right Side: Why Choose Us (4 Cards Staggered) -->
                <div class="col-12 col-lg-7">
                    <div class="row g-4">

                        <!-- Feature 1 -->
                        <div class="col-12 col-sm-6">
                            <div class="feature-card">
                                <div class="icon-box"><i class="fa-solid fa-hand-sparkles"></i></div>
                                <h4>100% Handmade</h4>
                                <p>No machines used. Just pure devotion and skilled hands shaping every detail of our idols and bags.</p>
                            </div>
                        </div>

                        <!-- Feature 2 (Staggered offset) -->
                        <div class="col-12 col-sm-6 mt-sm-5">
                            <div class="feature-card">
                                <div class="icon-box"><i class="fa-solid fa-leaf"></i></div>
                                <h4>Eco-Friendly Clay</h4>
                                <p>We strictly use natural river clay and organic colors that are completely safe for nature & rivers.</p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="col-12 col-sm-6">
                            <div class="feature-card">
                                <div class="icon-box"><i class="fa-solid fa-users"></i></div>
                                <h4>Support Local Artists</h4>
                                <p>Your purchase directly empowers our hardworking local artisans and supports their traditional families.</p>
                            </div>
                        </div>

                        <!-- Feature 4 (Staggered offset) -->
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

    <!-- Modern Shop by Category Section -->
    <section class="shop-by-category py-5">
        <div class="container py-4">

            <!-- Section Header -->
            <div class="row justify-content-center text-center mb-5">
                <div class="col-12 col-md-8 col-lg-6">
                    <h2 class="section-title mb-3">Shop by Category</h2>
                    <p class="section-subtitle">
                        Explore our handcrafted collections of divine idols, terracotta decor, and artisan creations.
                    </p>
                    <div class="title-divider mx-auto"></div>
                </div>
            </div>

            <!-- Category Grid (Simple Procedural MySQLi Query Loop) -->
            <div class="row g-4">
                <?php
                include_once('connection.php');
                global $connect;

                // Fetch up to 4 active categories
                $cat_query = "SELECT * FROM categories WHERE status = 1 ORDER BY id DESC LIMIT 4";
                $cat_result = mysqli_query($connect, $cat_query);

                // Decorative icons for the cards
                $icons = ['fa-solid fa-shapes', 'fa-solid fa-hands-holding-circle', 'fa-solid fa-spray-can-sparkles', 'fa-solid fa-om', 'fa-solid fa-gem', 'fa-solid fa-fan'];
                $icon_index = 0;

                if ($cat_result && mysqli_num_rows($cat_result) > 0) {
                    while ($row = mysqli_fetch_assoc($cat_result)) {
                        $category_name = htmlspecialchars($row['name']);
                        $category_id = $row['id'];
                        $first_letter = mb_strtoupper(mb_substr($category_name, 0, 1, 'UTF-8'));

                        // Use database description if available, limit to 40 characters
                        $category_desc = !empty($row['description']) ? htmlspecialchars(mb_substr($row['description'], 0, 40)) . '...' : 'Explore Handcrafted Collection';

                        $current_icon = $icons[$icon_index % count($icons)];
                        $icon_index++;
                ?>
                        <!-- Category Card (Text & Icon Based) -->
                        <div class="col-12 col-sm-6 col-lg-3">
                            <a href="collections.php?category=<?php echo $category_id; ?>" class="category-card">
                                <div class="category-watermark"><?php echo $first_letter; ?></div>
                                <div class="category-card-top">
                                    <div class="category-icon-box">
                                        <i class="<?php echo $current_icon; ?>"></i>
                                    </div>
                                    <span class="category-tag"><i class="fa-solid fa-sparkles me-1"></i> Artisan</span>
                                </div>
                                <div class="category-card-bottom">
                                    <h3 class="category-title"><?php echo $category_name; ?></h3>
                                    <p class="category-subtext mb-3"><?php echo $category_desc; ?></p>
                                    <div class="category-cta">
                                        <span>Explore Now</span>
                                        <div class="cta-icon"><i class="fa-solid fa-arrow-right"></i></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php
                    }
                } else {
                    // Fallback categories if database table has no active records yet
                    $fallback_cats = [
                        ['name' => 'Divine Clay Idols', 'icon' => 'fa-solid fa-om', 'id' => 1],
                        ['name' => 'Terracotta Home Decor', 'icon' => 'fa-solid fa-shapes', 'id' => 2],
                        ['name' => 'Custom Sculptures', 'icon' => 'fa-solid fa-hands-holding-circle', 'id' => 3],
                        ['name' => 'Handcrafted Accessories', 'icon' => 'fa-solid fa-gem', 'id' => 4],
                    ];
                    foreach ($fallback_cats as $cat) {
                        $first_letter = mb_strtoupper(mb_substr($cat['name'], 0, 1, 'UTF-8'));
                    ?>
                        <!-- Fallback Category Card -->
                        <div class="col-12 col-sm-6 col-lg-3">
                            <a href="collections.php?category=<?php echo $cat['id']; ?>" class="category-card">
                                <div class="category-watermark"><?php echo $first_letter; ?></div>
                                <div class="category-card-top">
                                    <div class="category-icon-box">
                                        <i class="<?php echo $cat['icon']; ?>"></i>
                                    </div>
                                    <span class="category-tag"><i class="fa-solid fa-sparkles me-1"></i> Artisan</span>
                                </div>
                                <div class="category-card-bottom">
                                    <h3 class="category-title"><?php echo htmlspecialchars($cat['name']); ?></h3>
                                    <p class="category-subtext mb-3">Explore Handcrafted Collection</p>
                                    <div class="category-cta">
                                        <span>Explore Now</span>
                                        <div class="cta-icon"><i class="fa-solid fa-arrow-right"></i></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                <?php
                    }
                }
                ?>
            </div>

            <!-- View All Categories Button -->
            <div class="text-center mt-5">
                <a href="collections.php" class="btn btn-gold-outline rounded-pill px-5 py-3 text-uppercase" style="font-size: 0.9rem; letter-spacing: 0.8px;">
                    View All Categories
                </a>
            </div>

        </div>
    </section>

    <!-- Footer Inclusion -->
    <?php include_once('footer.php'); ?>

    <!-- Sync Custom Indicator States -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
</body>

</html>