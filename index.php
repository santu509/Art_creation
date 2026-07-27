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

    /* Badge Tagline Custom Colors */
    .hero-badge {
        background: rgba(212, 175, 55, 0.12);
        border: 1px solid rgba(212, 175, 55, 0.35);
        backdrop-filter: blur(8px);
        color: #DFBA5A;
        letter-spacing: 1px;
    }

    /* Main Heading Custom Fonts */
    .hero-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.4rem, 5vw, 4.2rem);
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
    .carousel-item.active .hero-badge {
        animation: fadeInUp 0.8s cubic-bezier(0.25, 1, 0.5, 1) both;
    }

    .carousel-item.active .hero-title {
        animation: fadeInUp 0.9s cubic-bezier(0.25, 1, 0.5, 1) 0.15s both;
    }

    .carousel-item.active .hero-description {
        animation: fadeInUp 0.9s cubic-bezier(0.25, 1, 0.5, 1) 0.3s both;
    }

    .carousel-item.active .hero-buttons-wrapper {
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

    /* Bottom Feature Highlights Bar */
    .hero-features-bar {
        background-color: #241F1A;
        border-color: rgba(212, 175, 55, 0.2) !important;
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

        .hero-badge {
            font-size: 0.75rem !important;
            padding: 5px 14px !important;
            margin-bottom: 0.75rem !important;
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

        .hero-features-bar {
            z-index: 2;
            bottom: 100px !important;
        }
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
                                <div class="hero-badge d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill fw-semibold text-uppercase mb-4 shadow-sm">
                                    Sacred Handcrafted Devotion
                                </div>
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
                                <div class="hero-badge d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill fw-semibold text-uppercase mb-4 shadow-sm">
                                    <i class="fa-solid fa-plant-wilt"></i> Earthy Terracotta Living
                                </div>
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
                                <div class="hero-badge d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill fw-semibold text-uppercase mb-4 shadow-sm">
                                    <i class="fa-solid fa-hands-holding-circle"></i> Heritage Master Artisans
                                </div>
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

    <!-- Bottom Feature Highlights Bar -->
    <section class="hero-features-bar position-relative border-top border-bottom py-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-md-4">
                    <div class="d-flex align-items-center gap-3 px-2">
                        <div class="feature-box-icon rounded-3 flex-shrink-0 fs-5 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-hands-clapping"></i>
                        </div>
                        <div>
                            <h4 class="feature-box-title fs-5 fw-bold mb-1">100% Eco-Friendly Clay</h4>
                            <p class="feature-box-desc small m-0">Hand-sculpted using pure river clay and natural organic pigments</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="d-flex align-items-center gap-3 px-2">
                        <div class="feature-box-icon rounded-3 flex-shrink-0 fs-5 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                        </div>
                        <div>
                            <h4 class="feature-box-title fs-5 fw-bold mb-1">Custom Clay Commissions</h4>
                            <p class="feature-box-desc small m-0">Personalized statues, custom dimensions, and traditional finishes</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="d-flex align-items-center gap-3 px-2">
                        <div class="feature-box-icon rounded-3 flex-shrink-0 fs-5 d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-shield-cat"></i>
                        </div>
                        <div>
                            <h4 class="feature-box-title fs-5 fw-bold mb-1">Safe Crate Packaging</h4>
                            <p class="feature-box-desc small m-0">Zero-damage delivery guarantee with reinforced protective crates</p>
                        </div>
                    </div>
                </div>
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