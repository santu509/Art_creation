<?php
include_once('includes/nav.php');
include_once('includes/connection.php');
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

<body>
    <!-- Modern Banner Carousel Section -->
    <section class="position-relative overflow-hidden mt-0" style="background-color: #1A1612;">
        <div id="siddhaHeroCarousel" class="carousel slide carousel-fade hero-carousel" data-bs-ride="carousel" data-bs-interval="6000">
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active position-relative" style="background-image: url('asset/image/banner-1.png');">
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
                                        <span>Explore Collection</span>
                                        <i class="fa-solid fa-arrow-right-long"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item position-relative" style="background-image: url('asset/image/banner-2.png');">
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
                                        <span>Explore Collection</span>
                                        <i class="fa-solid fa-arrow-right-long"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item position-relative" style="background-image: url('asset/image/banner-3.png');">
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
                                        <span>Explore Collection</span>
                                        <i class="fa-solid fa-arrow-right-long"></i>
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
                <!-- Right Side (Animated Asymmetric 4-Card Bento Grid) -->
                <div class="col-12 col-lg-7 mt-5 mt-lg-0">
                    <div class="about-card-grid position-relative">
                        <div class="row g-3 g-md-4 align-items-stretch">
                            <!-- Card 01: Craftsmanship & Local Support (LARGE CARD) -->
                            <div class="col-12 col-md-7">
                                <div class="about-interactive-card about-card-lg float-card-1">
                                    <div class="shimmer-light"></div>
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="about-card-icon-box">
                                                <i class="fa-solid fa-hands-holding-circle"></i>
                                            </div>
                                        </div>
                                        <h3 class="about-card-title">100% Authentic Handcrafted</h3>
                                        <p class="about-card-desc">
                                            Every piece is meticulously hand-sculpted by our local artisans. Your purchase directly supports traditional craftspeople and preserves our rich cultural heritage.
                                        </p>
                                    </div>
                                    <div>
                                        <span class="about-card-pill"><i class="fa-solid fa-crown me-1"></i> Empowering Artisans</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Card 02: Material & Quality (SMALL CARD - Hidden on mobile) -->
                            <div class="col-12 col-md-5 d-none d-md-block">
                                <div class="about-interactive-card about-card-sm float-card-2">
                                    <div class="shimmer-light"></div>
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="about-card-icon-box">
                                                <i class="fa-solid fa-seedling"></i>
                                            </div>
                                        </div>
                                        <h3 class="about-card-title">Eco-Friendly & Pure</h3>
                                        <p class="about-card-desc">
                                            Crafted using premium, natural river clay and organic colors. Completely free from toxic additives, making it safe for your home and the environment.
                                        </p>
                                    </div>
                                    <div>
                                        <span class="about-card-pill"><i class="fa-solid fa-leaf me-1"></i> Organic Materials</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Card 03: Product Range & Customization (SMALL CARD - Hidden on mobile) -->
                            <div class="col-12 col-md-5 d-none d-md-block">
                                <div class="about-interactive-card about-card-sm float-card-3">
                                    <div class="shimmer-light"></div>
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="about-card-icon-box">
                                                <i class="fa-solid fa-gem"></i>
                                            </div>
                                        </div>
                                        <h3 class="about-card-title">Exclusive Collections</h3>
                                        <p class="about-card-desc">
                                            Shop our unique, ready-to-ship terracotta decor and divine idols, or request bespoke, custom-sculpted pieces tailored exactly to your vision.
                                        </p>
                                    </div>
                                    <div>
                                        <span class="about-card-pill"><i class="fa-solid fa-wand-magic-sparkles me-1"></i> Custom Made</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Card 04: Shipping & Trust (LARGE CARD) -->
                            <div class="col-12 col-md-7">
                                <div class="about-interactive-card about-card-lg float-card-4">
                                    <div class="shimmer-light"></div>
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="about-card-icon-box">
                                                <i class="fa-solid fa-shield-heart"></i>
                                            </div>
                                        </div>
                                        <h3 class="about-card-title">Trusted & Secure Delivery</h3>
                                        <p class="about-card-desc">
                                            Enjoy peace of mind with multi-layer protective packaging. We ensure safe, fast, and hassle-free doorstep delivery across India through our trusted partners.
                                        </p>
                                    </div>
                                    <div>
                                        <span class="about-card-pill"><i class="fa-solid fa-truck-fast me-1"></i> Pan-India Shipping</span>
                                    </div>
                                </div>
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
                        $countText = sprintf("%02d", $cat['product_count']) . ' ' . ($cat['product_count'] == 1 ? 'Item Available' : 'Items Available');
                ?>
                        <div class="category-scroll-item">
                            <a href="collection.php?category=<?php echo encodeId($cat['id']); ?>" class="category-btn-card">
                                <div class="cat-btn-content">
                                    <span class="cat-btn-title"><?php echo htmlspecialchars($cat['name']); ?></span>
                                    <span class="cat-btn-count"><?php echo $countText; ?></span>
                                </div>
                                <div class="cat-btn-arrow">
                                    <i class="fa-solid fa-chevron-right"></i>
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
                                <a href="product_details.php?id=<?php echo encodeId($prod['id']); ?>" class="text-decoration-none d-block flex-grow-1">
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
                                <a href="product_details.php?id=<?php echo encodeId($prod['id']); ?>" class="add-to-cart-btn mt-auto">
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

    <!-- Testimonials Section -->
    <section class="testimonials-section py-2" style="background-color: #faf9f9;">
        <div class="container py-4">
            <div class="text-center mb-5">
                <div class="badge-custom mb-3"><i class="fa-solid fa-star me-1" style="color: #DFBA5A;"></i> Authentic Reviews</div>
                <h2 class="section-title mb-2">What Our <span style="color: #CBA232;">Clients Say</span></h2>
                <div class="title-divider mx-auto mt-3"></div>
            </div>

            <div class="testi-scroll-slider" id="testiScrollSlider">
                <?php
                $testi_query = "SELECT f.rating, f.review, u.name, u.image FROM feedback f JOIN users u ON f.customers_id = u.id WHERE f.rating >= 3 AND f.rating <= 5 ORDER BY f.id DESC LIMIT 7";
                $testi_result = mysqli_query($connect, $testi_query);
                if ($testi_result && mysqli_num_rows($testi_result) > 0) {
                    while ($testi = mysqli_fetch_assoc($testi_result)) {
                        $profile_img = 'asset/image/default-image.jpg';
                        if (!empty($testi['image']) && $testi['image'] !== 'default.png') {
                            $imgPath = (strpos($testi['image'], 'uploads/') === 0) ? $testi['image'] : 'uploads/' . $testi['image'];
                            if (file_exists($imgPath)) {
                                $profile_img = $imgPath;
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

    <section class="contact-new-section py-3">
        <div class="bg-ambient-glow-contact"></div>
        <div class="bg-ambient-glow-contact-2"></div>
        <div class="container py-4 position-relative z-1">
            <div class="text-center mb-2">
                <div class="section-subtitle"><i class="fa-solid fa-paper-plane me-1"></i> Get in touch</div>
                <h2 class="section-title">Contact <span style="color: #CBA232;">&</span> Feedback</h2>
            </div>

            <div class="row g-4 align-items-stretch mt-2">
                <!-- Left Side: Contact Information & Location -->
                <div class="col-lg-4">
                    <div class="contact-card">
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="contact-info-text">
                                <h6>Address</h6>
                                <p>Uttar Nischinta, Analberia, West Bengal, 721444</p>
                            </div>
                        </div>
                        <a href="tel:+912583691235" class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div class="contact-info-text">
                                <h6>Call Us</h6>
                                <p>+91 6297657671</p>
                            </div>
                        </a>
                        <a href="https://wa.me/913265489526" target="_blank" class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fa-brands fa-whatsapp"></i>
                            </div>
                            <div class="contact-info-text">
                                <h6>Whatsapp Us</h6>
                                <p>+91 9775085649</p>
                            </div>
                        </a>
                        <a href="mailto:santusau@gmail.com" class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div class="contact-info-text">
                                <h6>Email Us</h6>
                                <p>siddhaartcreation@gmail.com</p>
                            </div>
                        </a>

                        <div class="map-container">
                            <span class="map-badge-overlay"><i class="fa-solid fa-map-pin me-1"></i> Workshop Location</span>
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4380.527850166815!2d87.70801892531237!3d21.926086417003113!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a02d90060397891%3A0x89e04d62228802ea!2sSiddha%20art%20creation!5e0!3m2!1sen!2sin!4v1786337047288!5m2!1sen!2sin" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Interactive Review & Rating Card -->
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

                            <!-- Feeling Rating Pill Badge (Matches user reference) -->
                            <div class="rating-feeling-wrapper mb-3 mt-2">
                                <div class="rating-feeling-pill" id="ratingFeelingPill">
                                    <span class="rating-emoji" id="ratingEmoji">✨</span>
                                    <span class="rating-feeling-text" id="ratingFeelingText"><?php echo $user_has_review ? 'Update your rating' : 'Select Rating'; ?></span>
                                </div>
                            </div>

                            <div class="textarea-wrapper mb-2">
                                <textarea name="message" id="reviewMessageInput" class="form-control" rows="10" placeholder="Write your review..." minlength="50" maxlength="180" required><?php echo htmlspecialchars($existing_review); ?></textarea>
                                <div class="char-count-tag" id="charCountTag"><span id="currentCharCount">0</span>/180</div>
                            </div>

                            <button type="submit" class="btn-submit-review">
                                <span><?php echo $user_has_review ? 'Update Review' : 'Submit Review'; ?></span>
                                <i class="fa-solid fa-paper-plane ms-2"></i>
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


            const ratingFeelings = {
                1: {
                    emoji: '😡',
                    label: 'Disappointing',
                    color: '#DC2626',
                    bg: '#FEE2E2',
                    border: '#FCA5A5'
                },
                2: {
                    emoji: '😳',
                    label: 'Average',
                    color: '#D97706',
                    bg: '#FEF3C7',
                    border: '#FDE68A'
                },
                3: {
                    emoji: '🙂',
                    label: 'Good Experience',
                    color: '#059669',
                    bg: '#D1FAE5',
                    border: '#A7F3D0'
                },
                4: {
                    emoji: '😊',
                    label: 'Very Satisfied',
                    color: '#047857',
                    bg: '#ECFDF5',
                    border: '#6EE7B7'
                },
                5: {
                    emoji: '🤩',
                    label: 'Outstanding!',
                    color: '#B45309',
                    bg: '#FEF3C7',
                    border: '#FCD34D'
                }
            };

            const ratingEmoji = document.getElementById('ratingEmoji');
            const ratingFeelingText = document.getElementById('ratingFeelingText');
            const ratingFeelingPill = document.getElementById('ratingFeelingPill');
            const reviewInput = document.getElementById('reviewMessageInput');
            const currentCharCount = document.getElementById('currentCharCount');

            if (reviewInput && currentCharCount) {
                currentCharCount.textContent = reviewInput.value.length;
                reviewInput.addEventListener('input', function() {
                    currentCharCount.textContent = this.value.length;
                });
            }

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
                    const starVal = star.getAttribute('data-rating');
                    if (starVal <= rating && rating > 0) {
                        star.style.color = '#FFB800';
                        star.classList.add('active');
                    } else {
                        star.style.color = '#E2DDD5';
                        star.classList.remove('active');
                    }
                });

                if (ratingFeelings[rating]) {
                    const feel = ratingFeelings[rating];
                    if (ratingEmoji) ratingEmoji.textContent = feel.emoji;
                    if (ratingFeelingText) {
                        ratingFeelingText.textContent = feel.label;
                        ratingFeelingText.style.color = feel.color;
                    }
                    if (ratingFeelingPill) {
                        ratingFeelingPill.style.background = feel.bg;
                        ratingFeelingPill.style.borderColor = feel.border;
                        ratingFeelingPill.classList.add('bump');
                        setTimeout(() => ratingFeelingPill.classList.remove('bump'), 300);
                    }
                } else {
                    if (ratingEmoji) ratingEmoji.textContent = '✨';
                    if (ratingFeelingText) {
                        ratingFeelingText.textContent = '<?php echo $user_has_review ? "Update your rating" : "Select Rating"; ?>';
                        ratingFeelingText.style.color = '#1A1612';
                    }
                    if (ratingFeelingPill) {
                        ratingFeelingPill.style.background = 'linear-gradient(145deg, #FAF7F2 0%, #F5EFE6 100%)';
                        ratingFeelingPill.style.borderColor = 'rgba(197, 155, 39, 0.35)';
                    }
                }
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
                    if (message.length < 50 || message.length > 180) {
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

                    fetch('actions/feedback_action.php', {
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
    <?php include_once('includes/footer.php'); ?>

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

                function getCatScrollStep() {
                    if (catItems && catItems.length > 0) {
                        const itemWidth = catItems[0].offsetWidth;
                        const gap = parseInt(window.getComputedStyle(catSlider).gap) || 16;
                        return itemWidth + gap;
                    }
                    return 240;
                }

                if (catPrev) {
                    catPrev.addEventListener('click', function() {
                        catSlider.scrollBy({
                            left: -getCatScrollStep(),
                            behavior: 'smooth'
                        });
                    });
                }
                if (catNext) {
                    catNext.addEventListener('click', function() {
                        catSlider.scrollBy({
                            left: getCatScrollStep(),
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