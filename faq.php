<!-- ========================================== -->
<!-- NAVBAR PLACEHOLDER (nav.php will load here)-->
<!-- ========================================== -->
<?php include_once('nav.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Frequently Asked Questions for Siddha Art Creation. Explore our philosophy, custom commissions, and shipping details.">
    <title>FAQ | Siddha Art Creation</title>
    
    <!-- Google Fonts: Playfair Display (Serif) & Jost (Sans-Serif) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="asset/bootstrap-5.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        /* Color Palette & Custom Properties */
        :root {
            --bg-page: #FAF8F5;          /* Warm, elegant off-white background */
            --bg-card: #FFFFFF;          /* Pure white for containers */
            --gold: #C39B62;             /* Premium, luxury gold */
            --gold-light: rgba(195, 155, 98, 0.1);
            --gold-dark: #A67E48;
            --text-dark: #1E1B18;        /* Deep charcoal/brown-black */
            --text-muted: #6E6A64;       /* Soft grey-brown for body copy */
            --border-color: #E6E2DC;     /* Soft ivory-grey borders */
            --transition-smooth: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Global Reset & Typography */
        body {
            background-color: var(--bg-page);
            color: var(--text-dark);
            font-family: 'Jost', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
            letter-spacing: 0.02em;
        }

        h1, h2, h3, h4, h5, h6, .serif {
            font-family: 'Playfair Display', serif;
        }

        /* =======================================
           NEW INTERACTIVE BANNER STYLES 
           ======================================= */
        .hero-banner-wrapper {
            position: relative;
            width: 100%;
            height: 450px;
            overflow: hidden;
            background-color: #1E1B18;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Animated Background Image */
        .hero-bg-image {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: url('https://images.unsplash.com/photo-1513364776144-60967b0f800f?q=80&w=2071&auto=format&fit=crop') no-repeat center center;
            background-size: cover;
            animation: subtleZoom 25s infinite alternate linear;
            z-index: 1;
        }

        @keyframes subtleZoom {
            0% { transform: scale(1); }
            100% { transform: scale(1.15); }
        }

        /* Elegant Dark Overlay */
        .hero-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(24, 22, 19, 0.9) 0%, rgba(42, 38, 33, 0.75) 100%);
            z-index: 2;
        }

        /* Banner Content Container */
        .hero-content {
            position: relative;
            z-index: 3;
            text-align: center;
            padding: 0 20px;
            transform: translateY(-20px);
        }

        /* Staggered Text Animations */
        .animate-up {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 0.8s ease forwards;
        }
        
        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }

        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }

        .section-tagline {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.3em;
            color: var(--gold);
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
            padding: 6px 15px;
            border: 1px solid rgba(195, 155, 98, 0.4);
            border-radius: 30px;
            background: rgba(195, 155, 98, 0.1);
            backdrop-filter: blur(5px);
        }

        .banner-title {
            text-shadow: 2px 4px 8px rgba(0,0,0,0.4);
            letter-spacing: 1px;
            font-size: clamp(2.5rem, 5vw, 4rem);
        }

        /* SVG Shape Divider (Wave) */
        .custom-shape-divider-bottom {
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            z-index: 4;
        }

        .custom-shape-divider-bottom svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 70px;
        }

        .custom-shape-divider-bottom .shape-fill {
            fill: var(--bg-page);
        }

        /* Interactive Glassmorphism Search Box */
        .search-container {
            max-width: 600px;
            margin: -40px auto 50px;
            position: relative;
            z-index: 10;
        }

        .search-wrapper {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 50px;
            padding: 8px 8px 8px 28px;
            box-shadow: 0 20px 40px rgba(30, 27, 24, 0.15), 
                        inset 0 0 0 1px rgba(255, 255, 255, 0.5);
            display: flex;
            align-items: center;
            transition: var(--transition-smooth);
        }

        .search-wrapper:focus-within {
            background: #FFFFFF;
            box-shadow: 0 25px 50px rgba(195, 155, 98, 0.2), 
                        inset 0 0 0 2px var(--gold);
            transform: translateY(-3px);
        }

        .search-input {
            border: none;
            outline: none;
            background: transparent;
            width: 100%;
            font-size: 1.1rem;
            color: var(--text-dark);
            font-weight: 500;
        }

        .search-input::placeholder {
            color: #999;
            font-weight: 400;
        }

        .search-btn {
            background: var(--gold);
            border: none;
            color: white;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-smooth);
            font-size: 1.3rem;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(195, 155, 98, 0.4);
        }

        .search-btn:hover {
            background: var(--gold-dark);
            transform: scale(1.08) rotate(5deg);
            box-shadow: 0 6px 20px rgba(195, 155, 98, 0.6);
        }
        /* ======================================= */

        /* FAQ Accordion Styling */
        .faq-list {
            max-width: 800px;
            margin: 0 auto;
        }

        .faq-item {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px !important;
            margin-bottom: 16px;
            overflow: hidden;
            transition: var(--transition-smooth);
            box-shadow: 0 4px 12px rgba(30, 27, 24, 0.02);
        }

        .faq-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(195, 155, 98, 0.08);
            border-color: rgba(195, 155, 98, 0.4);
        }

        .faq-item.active {
            border-color: var(--gold);
            box-shadow: 0 12px 30px rgba(195, 155, 98, 0.12);
        }

        .faq-trigger {
            width: 100%;
            padding: 24px;
            background: transparent;
            border: none;
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-dark);
            font-weight: 500;
            font-size: 1.15rem;
            transition: var(--transition-smooth);
            outline: none;
        }

        .faq-trigger span {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            padding-right: 15px;
        }

        .faq-icon-box {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--bg-page);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold);
            font-size: 0.8rem;
            transition: var(--transition-smooth);
            flex-shrink: 0;
        }

        .faq-item.active .faq-icon-box {
            background: var(--gold);
            color: white;
            transform: rotate(180deg);
        }

        .faq-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .faq-body {
            padding: 0 24px 24px 24px;
            color: var(--text-muted);
            font-size: 1rem;
            line-height: 1.7;
            font-weight: 300;
        }

        .faq-item.hidden-element {
            display: none !important;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            display: none;
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--gold);
            margin-bottom: 15px;
        }

        /* Elegant Footer Details */
        .contact-cta {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 50px 40px;
            text-align: center;
            max-width: 800px;
            margin: 60px auto 40px;
            box-shadow: 0 10px 40px rgba(30, 27, 24, 0.04);
            position: relative;
            overflow: hidden;
        }

        .contact-cta::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
        }

        .btn-custom-gold {
            background-color: var(--gold);
            color: white;
            border: 1px solid var(--gold);
            padding: 14px 40px;
            border-radius: 30px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            font-size: 0.85rem;
            transition: var(--transition-smooth);
            display: inline-block;
            text-decoration: none;
        }

        .btn-custom-gold:hover {
            background-color: transparent;
            color: var(--gold);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(195, 155, 98, 0.15);
        }
    </style>
</head>
<body>

    <!-- Redesigned Interactive Hero Banner -->
    <header class="hero-banner-wrapper">
        <div class="hero-bg-image"></div>
        <div class="hero-overlay"></div>
        
        <div class="container hero-content text-light">
            <span class="section-tagline animate-up delay-1"><i class="bi bi-stars me-2"></i>Siddha Art Creation</span>
            <h1 class="display-4 fw-bold mb-3 text-gold banner-title animate-up delay-2">Frequently Asked Questions</h1>
            <p class="lead text-light-gray fs-5 opacity-75 animate-up delay-3 mx-auto" style="max-width: 600px;">
                Everything you need to know about the creation, uniqueness, and care of your masterpiece.
            </p>
        </div>

        <!-- SVG Bottom Curve -->
        <div class="custom-shape-divider-bottom">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
    </header>

    <!-- Glassmorphism Search Bar -->
    <div class="container search-container animate-up delay-3">
        <div class="search-wrapper">
            <i class="bi bi-search text-gold fs-5 me-3"></i>
            <input type="text" id="faqSearch" class="search-input" placeholder="Search questions, mediums, or details..." autocomplete="off">
            <button class="search-btn" aria-label="Search">
                <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </div>

    <main class="container mb-5 mt-4">
        
        <!-- FAQ Accordion List -->
        <div class="faq-list">
            
            <!-- Question 1 -->
            <div class="faq-item animate-up delay-1">
                <button class="faq-trigger" aria-expanded="false">
                    <span>What is Siddha Art?</span>
                    <div class="faq-icon-box">
                        <i class="bi bi-chevron-down"></i>
                    </div>
                </button>
                <div class="faq-content">
                    <div class="faq-body">
                        Siddha Art is a spiritual, meditative, and mystical approach to creation, drawing inspiration from ancient traditions and spiritual pathways. Each artwork is created intuitively, intended to elevate frequencies and bring beauty, balance, and positive energy to your physical space.
                    </div>
                </div>
            </div>

            <!-- Question 2 -->
            <div class="faq-item animate-up delay-2">
                <button class="faq-trigger" aria-expanded="false">
                    <span>Do you offer custom art commissions?</span>
                    <div class="faq-icon-box">
                        <i class="bi bi-chevron-down"></i>
                    </div>
                </button>
                <div class="faq-content">
                    <div class="faq-body">
                        Yes, we gladly work with collectors for personalized custom art pieces. The journey starts with a one-on-one consultation to understand your spatial design, colors, and the general energetic intention you want to capture in the canvas.
                    </div>
                </div>
            </div>

            <!-- Question 3 -->
            <div class="faq-item animate-up delay-3">
                <button class="faq-trigger" aria-expanded="false">
                    <span>How long does it take to complete a custom piece?</span>
                    <div class="faq-icon-box">
                        <i class="bi bi-chevron-down"></i>
                    </div>
                </button>
                <div class="faq-content">
                    <div class="faq-body">
                        A typical commission takes between 4 to 8 weeks. This accommodates the intentional layer-by-layer drying times and detailed alignments required to craft a unique, museum-grade masterpiece.
                    </div>
                </div>
            </div>

            <!-- Question 4 -->
            <div class="faq-item">
                <button class="faq-trigger" aria-expanded="false">
                    <span>What mediums do you use in your creations?</span>
                    <div class="faq-icon-box">
                        <i class="bi bi-chevron-down"></i>
                    </div>
                </button>
                <div class="faq-content">
                    <div class="faq-body">
                        Our medium list is diverse and premium: high-grade heavy body acrylics, organic oils, 24k gold leaf details, crushed crystals (like amethyst or quartz), and unique natural earth pigments to guarantee depth and textural beauty.
                    </div>
                </div>
            </div>

            <!-- Question 5 -->
            <div class="faq-item">
                <button class="faq-trigger" aria-expanded="false">
                    <span>Do you ship internationally?</span>
                    <div class="faq-icon-box">
                        <i class="bi bi-chevron-down"></i>
                    </div>
                </button>
                <div class="faq-content">
                    <div class="faq-body">
                        Absolutely. We ship internationally using custom-built wood crating and verified fine-art couriers. Every single shipment is fully insured, and you will receive regular tracking information from our door to yours.
                    </div>
                </div>
            </div>

            <!-- Question 6 -->
            <div class="faq-item">
                <button class="faq-trigger" aria-expanded="false">
                    <span>How should I care for my Siddha Art?</span>
                    <div class="faq-icon-box">
                        <i class="bi bi-chevron-down"></i>
                    </div>
                </button>
                <div class="faq-content">
                    <div class="faq-body">
                        To maintain color richness, avoid hanging your piece in places with extreme relative humidity or continuous harsh direct sunlight. Light dust can be brushed away safely using a soft, clean microfibre feather duster. Avoid pressing down on gold foil or raised textured surfaces.
                    </div>
                </div>
            </div>

            <!-- Empty State for Search -->
            <div class="empty-state" id="emptyState">
                <i class="bi bi-search-heart"></i>
                <h4 class="serif fs-3 mt-2 text-dark">No Questions Found</h4>
                <p class="text-muted">Try searching for other terms like 'ship', 'gold', or 'time'.</p>
            </div>

        </div>

        <!-- Contact Section -->
        <section class="contact-cta">
            <h3 class="serif fs-2 text-dark mb-3">Have a Unique Question?</h3>
            <p class="text-muted mb-4 max-width-md mx-auto fs-6">If you are interested in a specific layout, sized commission, or want to discuss the philosophy behind a piece, feel free to reach out to us directly.</p>
            <a href="contact.php" class="btn btn-custom-gold">Contact With Us</a>
        </section>

    </main>

    <!-- ========================================== -->
    <!-- FOOTER PLACEHOLDER (Update with footer.php if needed)-->
    <!-- ========================================== -->
    <?php include_once('footer.php'); ?>
    


    <!-- Interactive Javascript Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const faqItems = document.querySelectorAll('.faq-item');
            const searchInput = document.getElementById('faqSearch');
            const emptyState = document.getElementById('emptyState');

            // 1. Accordion Toggle Logic
            faqItems.forEach(item => {
                const trigger = item.querySelector('.faq-trigger');
                const content = item.querySelector('.faq-content');

                trigger.addEventListener('click', () => {
                    const isOpen = item.classList.contains('active');

                    // Close all active items first for clean accordion look
                    faqItems.forEach(otherItem => {
                        if (otherItem !== item && otherItem.classList.contains('active')) {
                            otherItem.classList.remove('active');
                            otherItem.querySelector('.faq-trigger').setAttribute('aria-expanded', 'false');
                            otherItem.querySelector('.faq-content').style.maxHeight = '0';
                        }
                    });

                    // Toggle current item
                    if (isOpen) {
                        item.classList.remove('active');
                        trigger.setAttribute('aria-expanded', 'false');
                        content.style.maxHeight = '0';
                    } else {
                        item.classList.add('active');
                        trigger.setAttribute('aria-expanded', 'true');
                        content.style.maxHeight = content.scrollHeight + 'px';
                    }
                });
            });

            // 2. Dynamic Search logic
            function searchFAQ() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;

                faqItems.forEach(item => {
                    const question = item.querySelector('.faq-trigger span').textContent.toLowerCase();
                    const answer = item.querySelector('.faq-body').textContent.toLowerCase();

                    if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                        item.classList.remove('hidden-element');
                        visibleCount++;
                    } else {
                        item.classList.add('hidden-element');
                        
                        // Reset accordion state on search hide
                        item.classList.remove('active');
                        item.querySelector('.faq-trigger').setAttribute('aria-expanded', 'false');
                        item.querySelector('.faq-content').style.maxHeight = '0';
                    }
                });

                // Show/hide empty state message
                if (visibleCount === 0) {
                    emptyState.style.display = 'block';
                } else {
                    emptyState.style.display = 'none';
                }
            }

            // Live Search Input Event
            searchInput.addEventListener('input', searchFAQ);
        });
    </script>
</body>
</html>