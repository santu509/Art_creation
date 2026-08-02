<?php
include_once('nav.php');
include_once('connection.php');
global $connect;

// ---------------------------------------------------------
// 1. BACKEND FETCH & DATA PREPARATION
// ---------------------------------------------------------
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;

if ($productId > 0) {
    $query = "SELECT p.*, c.name as category_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE p.id = $productId AND p.status = 1 
              LIMIT 1";
    $result = mysqli_query($connect, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    }
}

// Handle product not found condition gracefully
if (!$product) {
?>
    <div class="container py-5 text-center my-5">
        <div class="p-5 bg-white rounded-4 shadow-sm border border-gold-subtle mx-auto" style="max-width: 600px;">
            <div class="mb-3 text-warning fs-1">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <h2 class="font-serif fw-bold text-dark mb-3" style="font-family: 'Playfair Display', serif;">Artwork Not Found</h2>
            <p class="text-muted mb-4">The handcrafted creation you are looking for is either unavailable or has been moved.</p>
            <a href="collection.php" class="btn btn-gold-primary rounded-pill px-4 py-2 text-decoration-none">
                <i class="fa-solid fa-arrow-left me-2"></i> Back to Art Collection
            </a>
        </div>
    </div>
<?php
    include_once('footer.php');
    exit();
}

// Price and Discount Calculation
$price = floatval($product['price']);
$discount = floatval($product['discount_percentage'] ?? 0);
$finalPrice = ($discount > 0) ? $price - ($price * ($discount / 100)) : $price;

// Gallery Images Decoding & Parsing
$galleryImages = [];
$mainImage = !empty($product['image']) ? "uploads/" . htmlspecialchars($product['image']) : "asset/image/default-image.jpg";
$galleryImages[] = $mainImage; // Always start with main image

if (!empty($product['gallery_image'])) {
    $decodedGallery = json_decode($product['gallery_image'], true);
    if (is_array($decodedGallery)) {
        foreach ($decodedGallery as $gImg) {
            $imgPath = "uploads/" . htmlspecialchars(trim($gImg));
            if (!in_array($imgPath, $galleryImages)) {
                $galleryImages[] = $imgPath;
            }
        }
    } elseif (is_string($product['gallery_image'])) {
        // Fallback for comma separated strings
        $splitImgs = explode(',', $product['gallery_image']);
        foreach ($splitImgs as $gImg) {
            $imgPath = "uploads/" . htmlspecialchars(trim($gImg));
            if (!in_array($imgPath, $galleryImages)) {
                $galleryImages[] = $imgPath;
            }
        }
    }
}

// External Purchase Links Decoding & Parsing
function parseProductLinks($rawJson)
{
    $links = [];
    if (empty($rawJson)) return $links;

    $decoded = json_decode($rawJson, true);
    if (is_array($decoded)) {
        foreach ($decoded as $key => $val) {
            if (is_array($val)) {
                $platform = $val['platform'] ?? $val['name'] ?? (is_numeric($key) ? 'Buy Online' : $key);
                $url = $val['url'] ?? $val['link'] ?? '';
            } else {
                $platform = is_numeric($key) ? 'Buy Online' : $key;
                $url = $val;
            }
            if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
                $links[] = [
                    'platform' => ucfirst(trim($platform)),
                    'url' => trim($url)
                ];
            }
        }
    } elseif (is_string($rawJson) && filter_var(trim($rawJson), FILTER_VALIDATE_URL)) {
        $links[] = [
            'platform' => 'Buy Online',
            'url' => trim($rawJson)
        ];
    }
    return $links;
}

$externalLinks = parseProductLinks($product['product_link'] ?? '');

// Fetch Related Products from same category
$catId = intval($product['category_id']);
$relatedQuery = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.category_id = $catId AND p.id != $productId AND p.status = 1 
                ORDER BY p.id DESC LIMIT 4";
$relatedResult = mysqli_query($connect, $relatedQuery);
?>

<!-- Custom CSS for Single Product Details Page -->
<style>
    body {
        background-color: #FAF8F5;
        font-family: 'Outfit', sans-serif;
        color: #2A241D;
    }

    /* Sticky Gallery Column */
    .product-gallery-sticky {
        position: sticky;
        top: 105px;
        background: #FFFFFF;
        padding: 20px;
        border-radius: 24px;
        border: 1px solid rgba(212, 175, 55, 0.22);
        box-shadow: 0 12px 35px rgba(42, 36, 29, 0.04);
    }

    .main-image-viewport {
        position: relative;
        width: 100%;
        max-width: 380px;
        aspect-ratio: 1 / 1;
        background: #FDFBF7;
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid #EAE6DF;
        box-shadow: inset 0 0 15px rgba(0, 0, 0, 0.02);
        margin: 0 auto 16px auto;
    }

    .main-image-viewport img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease-out, opacity 0.3s ease;
    }


    .badge-available-details {
        position: absolute;
        top: 16px;
        left: 16px;
        z-index: 2;
        background: #DEF7EC;
        border: 1px solid #B3E3CE;
        color: #03543F;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .badge-available-details::before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #0E9F6E;
        border-radius: 50%;
    }

    .badge-discount-details {
        position: absolute;
        bottom: 16px;
        right: 16px;
        z-index: 2;
        background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%);
        color: #1A1612;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 6px 16px;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(197, 155, 39, 0.35);
        display: inline-flex;
        align-items: center;
    }

    /* Thumbnail Selector Styles */
    .thumbnails-flex-row {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        padding-bottom: 8px;
        scrollbar-width: none;
    }

    .thumbnails-flex-row::-webkit-scrollbar {
        display: none;
    }

    .thumb-item {
        width: 75px;
        height: 75px;
        flex-shrink: 0;
        border-radius: 14px;
        overflow: hidden;
        background: #FFFFFF;
        border: 2px solid #EAE6DF;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .thumb-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .thumb-item:hover {
        border-color: #DFBA5A;
        transform: translateY(-1px);
    }

    .thumb-item.active {
        border-color: #C59B27;
        box-shadow: 0 0 12px rgba(197, 155, 39, 0.4);
        transform: scale(1.02);
    }

    /* Product Info Section */
    .product-category-tag {
        font-size: 0.82rem;
        color: #9B8A74;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 700;
    }

    .product-details-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.8rem, 3.5vw, 2.5rem);
        font-weight: 700;
        color: #1A1612;
        line-height: 1.25;
    }

    .price-container-details {
        display: flex;
        flex-direction: column;
        gap: 8px;
        background: linear-gradient(135deg, #1C1814 0%, #2A231C 100%);
        padding: 20px 24px;
        border-radius: 22px;
        border: 1px solid rgba(212, 175, 55, 0.4);
        box-shadow: 0 10px 30px rgba(26, 22, 18, 0.15);
        color: #FFFFFF;
    }

    .price-container-details .text-muted {
        color: #C2BBB0 !important;
    }

    .price-final-lg {
        font-size: 2.3rem;
        font-weight: 700;
        background: linear-gradient(135deg, #FFF0BD 0%, #DFBA5A 50%, #C59B27 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-family: 'Outfit', sans-serif;
    }

    .price-original-lg {
        font-size: 1.15rem;
        color: #9C9488;
        text-decoration: line-through;
        font-family: 'Outfit', sans-serif;
    }

    .badge-save-percent {
        background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%);
        color: #1A1612;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 6px 16px;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(197, 155, 39, 0.35);
    }

    .description-box {
        background: #FFFFFF;
        border-radius: 20px;
        padding: 22px 24px;
        border: 1px solid #EAE6DF;
        border-left: 5px solid #DFBA5A;
        box-shadow: 0 6px 22px rgba(42, 36, 29, 0.03);
    }

    .description-box p {
        color: #4A4237;
        line-height: 1.75;
        font-size: 0.98rem;
        margin-bottom: 0;
    }

    /* Platform External Buy Buttons */
    .buy-buttons-wrapper {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    /* -----------------------------------------
       UNIFIED PREMIUM BUY BUTTON & MODAL STYLES
    ----------------------------------------- */
    .btn-main-order-gold {
        width: 100%;
        padding: 16px 28px;
        border-radius: 50px;
        background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%);
        color: #1A1612;
        border: none;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.08rem;
        letter-spacing: 0.3px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        box-shadow: 0 8px 25px rgba(197, 155, 39, 0.3);
    }

    .btn-main-order-gold:hover {
        background: linear-gradient(135deg, #FFF0BD 0%, #DFBA5A 100%);
        color: #1A1612;
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(197, 155, 39, 0.42);
    }

    /* Small Compact Cart & Wishlist Action Buttons for Details Page */
    .btn-action-icon-details {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #FFFFFF;
        color: #2A241D;
        border: 1.5px solid #EAE6DF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        flex-shrink: 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        text-decoration: none;
    }

    .btn-action-icon-details:hover {
        background: #DFBA5A;
        color: #FFFFFF;
        border-color: #DFBA5A;
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 20px rgba(197, 155, 39, 0.3);
    }

    .btn-main-inquire-outline {
        width: 100%;
        padding: 13px 24px;
        border-radius: 50px;
        background: #FFFFFF;
        color: #2A241D;
        border: 1.5px solid #EAE6DF;
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-main-inquire-outline:hover {
        border-color: #DFBA5A;
        color: #B8860B;
        background: #FFFDF8;
        transform: translateY(-2px);
    }

    /* Partner Selection Modal Cards */
    .partner-card {
        display: flex;
        align-items: center;
        background: #FFFFFF;
        border: 1.5px solid #EAE6DF;
        border-radius: 18px;
        padding: 16px 20px;
        transition: all 0.35s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    }

    .partner-card:hover {
        border-color: #DFBA5A;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(197, 155, 39, 0.15);
        background: #FFFFFF;
    }

    .partner-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .partner-name {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 1.02rem;
        color: #2A241D;
        margin-bottom: 2px;
        transition: color 0.3s ease;
    }

    .partner-card:hover .partner-name {
        color: #B8860B;
    }

    .partner-subtext {
        font-family: 'Outfit', sans-serif;
        font-size: 0.78rem;
        color: #8C857E;
        font-weight: 500;
    }

    .partner-arrow {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #FAF8F5;
        color: #B8860B;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .partner-card:hover .partner-arrow {
        background: #DFBA5A;
        color: #FFFFFF;
        transform: translateX(4px);
    }

    .btn-platform-inquire:hover {
        background: #C59B27;
        color: #FFFFFF;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(197, 155, 39, 0.25);
    }

    /* Trust Badges Grid */
    .trust-badges-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-top: 25px;
    }

    @media (min-width: 576px) {
        .trust-badges-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .trust-badge-item {
        background: #FFFFFF;
        border: 1px solid #EAE6DF;
        border-radius: 14px;
        padding: 14px 10px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .trust-badge-item:hover {
        border-color: #DFBA5A;
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(197, 155, 39, 0.12);
    }

    .trust-badge-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: rgba(197, 155, 39, 0.1);
        color: #C59B27;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px auto;
        font-size: 1rem;
    }

    /* -----------------------------------------
       MODERN PRODUCT CARD STYLES FOR RELATED PRODUCTS
    ----------------------------------------- */
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

    .badge-available {
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 2;
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

    .badge-available::before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #0E9F6E;
        border-radius: 50%;
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
        cursor: pointer;
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
        font-size: 1.2rem;
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
        margin-bottom: 12px;
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

    /* Horizontal Scroll Container & Custom Scrollbar for Related Products */
    .scroll-container::-webkit-scrollbar {
        height: 6px;
    }

    .scroll-container::-webkit-scrollbar-track {
        background: #F5F2ED;
        border-radius: 10px;
    }

    .scroll-container::-webkit-scrollbar-thumb {
        background: linear-gradient(90deg, #DFBA5A 0%, #C59B27 100%);
        border-radius: 10px;
    }

    .scroll-container::-webkit-scrollbar-thumb:hover {
        background: #B8860B;
    }

    .related-section-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.3rem, 2.8vw, 1.8rem);
        font-weight: 700;
        color: #1A1612;
    }

    /* -----------------------------------------
       FULL-WIDTH HERO TOP BANNER SECTION
    ----------------------------------------- */
    .details-top-hero-section {
        position: relative;
        width: 100%;
        min-height: 240px;
        margin-top: 0 !important;
        padding-top: 100px !important;
        padding-bottom: 30px !important;
        background: url('asset/image/artisan_craft_banner.png') no-repeat center center / cover !important;
        overflow: hidden;
        border-bottom: 2px solid rgba(212, 175, 55, 0.35);
        display: flex;
        align-items: center;
    }

    .details-top-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg,
                rgba(26, 22, 18, 0.88) 0%,
                rgba(26, 22, 18, 0.72) 50%,
                rgba(18, 15, 12, 0.94) 100%) !important;
        z-index: 1;
    }

    .details-top-hero-content {
        padding-top: 0;
        padding-bottom: 0;
    }

    .details-top-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.3rem, 2.5vw, 1.85rem);
        font-weight: 700;
        margin-bottom: 0.35rem;
    }

    .details-top-desc {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(0.82rem, 1.2vw, 0.92rem);
        line-height: 1.5;
        max-width: 660px;
        color: #E2DDD5 !important;
    }

    .trust-badge-text {
        font-size: 0.76rem;
    }

    @media (max-width: 767.98px) {
        .details-top-hero-section {
            min-height: 200px;
            padding-top: 80px !important;
            padding-bottom: 20px !important;
        }

        .details-top-title {
            font-size: 1.18rem !important;
        }

        .details-top-desc {
            font-size: 0.78rem !important;
            margin-bottom: 0.35rem !important;
        }
    }
</style>

<!-- Full-Width Hero Top Banner Section -->
<section class="details-top-hero-section" style="background: url('asset/image/artisan_craft_banner.png') no-repeat center center / cover;">
    <div class="details-top-hero-overlay"></div>
    <div class="container position-relative z-2 details-top-hero-content text-center">
        <div class="row justify-content-center w-100 mx-0">
            <div class="col-12 col-lg-9">
                <span class="badge rounded-pill px-3 py-1 fw-semibold text-uppercase mb-2" style="background: rgba(212, 175, 55, 0.2); color: #DFBA5A; border: 1px solid rgba(212, 175, 55, 0.4); backdrop-filter: blur(8px); letter-spacing: 1px; font-size: 0.72rem;">
                    <i class="fa-solid fa-hands-sparkles me-1"></i> 100% Sacred Organic Craftsmanship
                </span>
                <h1 class="details-top-title text-white mb-2">
                    Every Creation Tells a Story of <span style="background: linear-gradient(135deg, #FFF0BD 0%, #DFBA5A 50%, #C59B27 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-style: italic;">Pure Devotion</span> &amp; Heritage
                </h1>
                <div class="mx-auto mb-2" style="width: 55px; height: 2px; background: linear-gradient(90deg, #DFBA5A 0%, #C59B27 100%);"></div>
                <p class="details-top-desc lead text-light mb-3 mx-auto">
                    Hand-sculpted with eco-friendly Ganges river clay and painted with natural organic mineral pigments by master Indian sculptors.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Main Details Container -->
<div class="container py-4 my-3" style="max-width: 1180px;">

    <!-- Breadcrumb Navigation -->
    <nav class="mb-4" aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent p-0 mb-0 small" style="font-family: 'Outfit', sans-serif;">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted"><i class="fa-solid fa-house me-1"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="collection.php" class="text-decoration-none text-muted">Collections</a></li>
            <?php if (!empty($product['category_name'])): ?>
                <li class="breadcrumb-item"><a href="collection.php?category=<?= intval($product['category_id']) ?>" class="text-decoration-none text-muted"><?= htmlspecialchars($product['category_name']) ?></a></li>
            <?php endif; ?>
            <li class="breadcrumb-item active text-gold fw-semibold" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
        </ol>
    </nav>

    <!-- 2-Column Product Layout -->
    <div class="row g-4 g-lg-5">

        <!-- LEFT COLUMN: Sticky Image Gallery & Viewer -->
        <div class="col-12 col-lg-5">
            <div class="product-gallery-sticky">
                <!-- Large Viewport Image -->
                <div class="main-image-viewport">
                    <span class="badge-available-details">Available</span>
                    <img id="mainProductImage" src="<?= $galleryImages[0] ?>" alt="<?= htmlspecialchars($product['name']) ?>" onerror="this.src='asset/image/default-image.jpg';">
                    <?php if ($discount > 0): ?>
                        <span class="badge-discount-details">
                            <i class="fa-solid fa-tag me-1"></i><?= intval($discount) ?>% OFF
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Thumbnails Row -->
                <?php if (count($galleryImages) > 1): ?>
                    <div class="thumbnails-flex-row justify-content-center" id="productThumbnailsContainer">
                        <?php foreach ($galleryImages as $index => $imgUrl): ?>
                            <div class="thumb-item <?= ($index === 0) ? 'active' : '' ?>" onclick="switchMainProductImage(this, '<?= $imgUrl ?>')">
                                <img src="<?= $imgUrl ?>" alt="Thumbnail <?= $index + 1 ?>" onerror="this.src='asset/image/default-image.jpg';">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- RIGHT COLUMN: Product Details & Purchase Actions -->
        <div class="col-12 col-lg-7 d-flex flex-column ps-lg-4">

            <!-- Category & Authenticity Badges -->
            <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                <span class="badge rounded-pill px-3 py-2 fw-semibold text-uppercase" style="background: rgba(197, 155, 39, 0.12); color: #B8860B; border: 1px solid rgba(197, 155, 39, 0.3); letter-spacing: 1.2px; font-size: 0.75rem;">
                    <i class="fa-solid fa-gem me-1" style="color: #DFBA5A;"></i> <?= htmlspecialchars($product['category_name'] ?? 'Handcrafted Creation') ?>
                </span>
                <span class="badge rounded-pill px-3 py-2 fw-semibold text-uppercase" style="background: rgba(14, 159, 110, 0.1); color: #0E9F6E; border: 1px solid rgba(14, 159, 110, 0.25); font-size: 0.72rem; letter-spacing: 1px;">
                    <i class="fa-solid fa-shield-cat me-1"></i> Certified Masterpiece
                </span>
            </div>

            <!-- Product Title with Gold Accent Line -->
            <div class="mb-3 position-relative pb-2">
                <h1 class="product-details-title mb-2">
                    <?= htmlspecialchars($product['name']) ?>
                </h1>
                <div style="width: 60px; height: 3px; background: linear-gradient(90deg, #DFBA5A 0%, #C59B27 100%); border-radius: 50px;"></div>
            </div>

            <!-- Rating & Artisan Quality Rating -->
            <div class="d-flex align-items-center gap-2 mb-4 p-2 rounded-3" style="background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(234, 230, 223, 0.8);">
                <div class="d-flex align-items-center text-gold small">
                    <i class="fa-solid fa-star me-1" style="color: #DFBA5A;"></i>
                    <i class="fa-solid fa-star me-1" style="color: #DFBA5A;"></i>
                    <i class="fa-solid fa-star me-1" style="color: #DFBA5A;"></i>
                    <i class="fa-solid fa-star me-1" style="color: #DFBA5A;"></i>
                    <i class="fa-solid fa-star me-1" style="color: #DFBA5A;"></i>
                    <span class="fw-bold text-dark ms-1">4.9 / 5.0</span>
                </div>
                <span class="small text-muted border-start ps-2 fw-medium" style="font-size: 0.82rem; color: #7A7267 !important;">
                    100% Authentic Hand-Sculpted Indian Creation
                </span>
            </div>

            <!-- Floating Luminous Luxury Price Box -->
            <div class="price-container-details mb-4">
                <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-2">
                    <div>
                        <div class="small text-uppercase text-muted fw-semibold mb-1" style="letter-spacing: 1px; font-size: 0.72rem;">Artisan Direct Price</div>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="price-final-lg">₹<?= number_format($finalPrice, 2) ?></span>
                            <?php if ($discount > 0): ?>
                                <span class="price-original-lg">₹<?= number_format($price, 2) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($discount > 0): ?>
                        <span class="badge-save-percent">
                            <i class="fa-solid fa-bolt me-1"></i> Save <?= intval($discount) ?>% TODAY
                        </span>
                    <?php endif; ?>
                </div>

                <div class="w-100 mt-2 pt-2 border-top border-secondary border-opacity-25 d-flex align-items-center justify-content-between small text-muted" style="font-size: 0.78rem;">
                    <span><i class="fa-solid fa-truck-fast me-1" style="color: #DFBA5A;"></i> Free Insured Express Shipping</span>
                    <span><i class="fa-solid fa-rotate-left me-1" style="color: #DFBA5A;"></i> Safe Transit Guarantee</span>
                </div>
            </div>

            <!-- Description Block (Studio Exhibition Card) -->
            <div class="description-box mb-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="fw-bold text-dark mb-0" style="font-family: 'Playfair Display', serif; font-size: 1.1rem; color: #1A1612;">
                        <i class="fa-solid fa-scroll me-2" style="color: #C59B27;"></i> About This Artwork
                    </h6>
                    <span class="badge rounded-pill bg-light text-dark border px-2 py-1" style="font-size: 0.7rem;">Hand-Sculpted</span>
                </div>
                <p>
                    <?= nl2br(htmlspecialchars($product['description'] ?: 'Exquisitely handcrafted piece created with authentic traditional methods, using natural river clay, organic minerals, and meticulous hand-sculpting by renowned artisans.')) ?>
                </p>
            </div>

            <!-- Unified Main Action Buttons Row -->
            <div class="d-flex align-items-center gap-2 mb-4">
                <?php if (!empty($externalLinks)): ?>
                    <!-- Single Large Premium Gold CTA Button -->
                    <button type="button" class="btn-main-order-gold shadow-lg flex-grow-1" data-bs-toggle="modal" data-bs-target="#buyOptionsModal">
                        <i class="fa-solid fa-shield-halved fs-5 me-2"></i> Buy Original Piece
                    </button>
                <?php else: ?>
                    <!-- Fallback Direct Order Button if no external links -->
                    <a href="contact.php?inquire=<?= urlencode($product['name']) ?>" class="btn-main-order-gold shadow-lg flex-grow-1 text-decoration-none">
                        <i class="fa-solid fa-paper-plane fs-5 me-2"></i> Inquire &amp; Order Direct
                    </a>
                <?php endif; ?>

                <!-- Small Add to Cart Button -->
                <button type="button" class="btn-action-icon-details" title="Add to Cart">
                    <i class="fa-solid fa-cart-plus"></i>
                </button>

                <!-- Small Add to Wishlist Button -->
                <button type="button" class="btn-action-icon-details" title="Add to Wishlist">
                    <i class="fa-regular fa-heart"></i>
                </button>
            </div>

            <!-- Trust Badges Bar -->
            <div class="trust-badges-grid mt-auto">
                <div class="trust-badge-item">
                    <div class="trust-badge-icon"><i class="fa-solid fa-hand-sparkles"></i></div>
                    <div class="trust-badge-text">100% Handcrafted</div>
                </div>
                <div class="trust-badge-item">
                    <div class="trust-badge-icon"><i class="fa-solid fa-seedling"></i></div>
                    <div class="trust-badge-text">Eco River Clay</div>
                </div>
                <div class="trust-badge-item">
                    <div class="trust-badge-icon"><i class="fa-solid fa-box-open"></i></div>
                    <div class="trust-badge-text">Safe Packaging</div>
                </div>
                <div class="trust-badge-item">
                    <div class="trust-badge-icon"><i class="fa-solid fa-certificate"></i></div>
                    <div class="trust-badge-text">Artisan Certified</div>
                </div>
            </div>

        </div>
    </div>

</div>
</div>

<!-- Related Artworks Section -->
<?php if ($relatedResult && mysqli_num_rows($relatedResult) > 0): ?>
    <section class="mt-5 pt-4 border-top container">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-end gap-3 mb-4">
            <div>
                <h3 class="related-section-title mb-1">More From This Collection</h3>
                <p class="text-muted small mb-0">Explore other handcrafted creations you might love.</p>
            </div>
            <a href="collection.php?category=<?= $catId ?>" class="btn btn-gold-outline rounded-pill px-3 py-2 text-decoration-none text-nowrap flex-shrink-0" style="font-size: 0.85rem;">
                View Category <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>

        <!-- Horizontal Scrollable Track for Both Desktop & Mobile -->
        <div class="scroll-container overflow-auto pb-3 pt-2" style="scrollbar-width: thin; scrollbar-color: #DFBA5A #F5F2ED;">
            <div class="d-flex flex-nowrap gap-4">
                <?php while ($rel = mysqli_fetch_assoc($relatedResult)):
                    $relImg = !empty($rel['image']) ? "uploads/" . htmlspecialchars($rel['image']) : "asset/image/default-image.jpg";
                    $relPrice = floatval($rel['price']);
                    $relDiscount = floatval($rel['discount_percentage'] ?? 0);
                    $relFinal = ($relDiscount > 0) ? $relPrice - ($relPrice * ($relDiscount / 100)) : $relPrice;
                ?>
                    <div class="scroll-item" style="min-width: 270px; max-width: 270px; flex-shrink: 0;">
                        <div class="modern-product-card">
                            <div class="d-flex flex-column flex-grow-1">
                                <div class="img-container">
                                    <span class="badge-available">Available</span>
                                    <img src="<?= $relImg ?>" alt="<?= htmlspecialchars($rel['name']) ?>" loading="lazy" onerror="this.src='asset/image/default-image.jpg';">
                                    <?php if ($relDiscount > 0): ?>
                                        <span class="badge-discount-corner">
                                            <i class="fa-solid fa-tag me-1"></i><?= intval($relDiscount) ?>% OFF
                                        </span>
                                    <?php endif; ?>
                                    <div class="action-buttons">
                                        <div class="action-btn" title="Add to Cart"><i class="fa-solid fa-cart-plus"></i></div>
                                        <div class="action-btn" title="Add to Wishlist"><i class="fa-regular fa-heart"></i></div>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="cat-name mb-0"><?= htmlspecialchars($rel['category_name'] ?? 'Handcrafted Art') ?></span>
                                    </div>
                                    <h4 class="prod-name" title="<?= htmlspecialchars($rel['name']) ?>"><?= htmlspecialchars($rel['name']) ?></h4>
                                    <div class="price-box">
                                        <span class="price-current">₹<?= number_format($relFinal, 2) ?></span>
                                        <?php if ($relDiscount > 0): ?>
                                            <span class="price-old ms-2">₹<?= number_format($relPrice, 2) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <a href="product_details.php?id=<?= $rel['id'] ?>" class="add-to-cart-btn mt-3 mt-auto text-decoration-none">
                                Explore Product <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

</div>

<!-- Vanilla JS Script for Main Image Switcher -->
<script>
    function switchMainProductImage(thumbElement, newImgSrc) {
        const mainImg = document.getElementById('mainProductImage');
        if (!mainImg) return;

        // Smooth opacity transition
        mainImg.style.opacity = '0.3';
        setTimeout(() => {
            mainImg.src = newImgSrc;
            mainImg.style.opacity = '1';
        }, 150);

        // Update active class on thumbnails
        const thumbs = document.querySelectorAll('.thumb-item');
        thumbs.forEach(t => t.classList.remove('active'));
        if (thumbElement) {
            thumbElement.classList.add('active');
        }
    }
</script>

<!-- Partner Selection Modal (#buyOptionsModal) -->
<div class="modal fade" id="buyOptionsModal" tabindex="-1" aria-labelledby="buyOptionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden" style="background: #FAF8F5;">

            <!-- Modal Header -->
            <div class="modal-header border-0 pb-0 pt-4 px-4 position-relative">
                <div class="pe-4">
                    <span class="badge rounded-pill px-3 py-1 fw-semibold text-uppercase mb-2" style="background: rgba(184, 134, 11, 0.12); color: #B8860B; border: 1px solid rgba(184, 134, 11, 0.25); font-size: 0.7rem; letter-spacing: 1px;">
                        <i class="fa-solid fa-lock me-1"></i> Authorized Partners
                    </span>
                    <h4 class="modal-title fw-bold text-dark mb-1" id="buyOptionsModalLabel" style="font-family: 'Playfair Display', serif; font-size: 1.45rem;">
                        Secure Checkout Partners
                    </h4>
                    <p class="text-muted small mb-0" style="font-family: 'Outfit', sans-serif; font-size: 0.88rem; color: #8C857E !important;">
                        Please select your preferred authorized partner for secure payment and fast delivery.
                    </p>
                </div>
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body (Partner Cards Loop) -->
            <div class="modal-body p-4">
                <div class="d-flex flex-column gap-3">
                    <?php if (!empty($externalLinks)): ?>
                        <?php foreach ($externalLinks as $link):
                            $platform = htmlspecialchars($link['platform']);
                            $url = htmlspecialchars($link['url']);
                            $platformLower = strtolower($link['platform']);

                            // Determine Platform Brand Icon & Colors for subtle badge
                            $iconBg = 'rgba(184, 134, 11, 0.1)';
                            $iconColor = '#B8860B';
                            $iconClass = 'fa-solid fa-store';

                            if (strpos($platformLower, 'flipkart') !== false) {
                                $iconBg = 'rgba(40, 116, 240, 0.08)';
                                $iconColor = '#2874F0';
                                $iconClass = 'fa-solid fa-bag-shopping';
                            } elseif (strpos($platformLower, 'meesho') !== false) {
                                $iconBg = 'rgba(224, 53, 112, 0.08)';
                                $iconColor = '#E03570';
                                $iconClass = 'fa-solid fa-shop';
                            } elseif (strpos($platformLower, 'amazon') !== false) {
                                $iconBg = 'rgba(255, 153, 0, 0.1)';
                                $iconColor = '#D47E00';
                                $iconClass = 'fa-solid fa-cart-shopping';
                            }
                        ?>
                            <a href="<?= $url ?>" target="_blank" rel="noopener noreferrer" class="partner-card text-decoration-none">
                                <div class="partner-icon-box" style="background: <?= $iconBg ?>; color: <?= $iconColor ?>;">
                                    <i class="<?= $iconClass ?>"></i>
                                </div>
                                <div class="partner-info ms-3 flex-grow-1">
                                    <div class="partner-name">Order via <?= $platform ?></div>
                                    <div class="partner-subtext"><i class="fa-solid fa-shield-cat me-1" style="color: #DFBA5A;"></i> Authorized Delivery Partner</div>
                                </div>
                                <div class="partner-arrow">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-0 pt-0 pb-4 px-4 justify-content-center">
                <span class="small text-muted d-flex align-items-center gap-1" style="font-size: 0.78rem; color: #8C857E !important;">
                    <i class="fa-solid fa-circle-check text-success me-1"></i> 100% Genuine Handcrafted Guarantee by Siddha Art Creation
                </span>
            </div>

        </div>
    </div>
</div>

<?php include_once('footer.php'); ?>