<?php
include_once('includes/nav.php');
include_once('includes/connection.php');
global $connect;

// ---------------------------------------------------------
// 1. BACKEND FETCH & DATA PREPARATION
// ---------------------------------------------------------
$productId = isset($_GET['id']) ? decodeId($_GET['id']) : (isset($_POST['id']) ? decodeId($_POST['id']) : 0);
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
    include_once('includes/footer.php');
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
                <li class="breadcrumb-item"><a href="collection.php?category=<?= encodeId($product['category_id']) ?>" class="text-decoration-none text-muted"><?= htmlspecialchars($product['category_name']) ?></a></li>
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

                    <!-- Top-Right Action Buttons Overlay -->
                    <div class="image-top-actions">
                        <button type="button" class="btn-image-action btn-wishlist" title="Add to Wishlist" data-product-id="<?= $product['id'] ?>" onclick="toggleWishlist(<?= $product['id'] ?>, this)">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                    </div>

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
            <div class="d-flex align-items-center gap-3 mb-4">
                <?php if (!empty($externalLinks)): ?>
                    <?php if ($isLoggedIn): ?>
                        <!-- Single Large Premium Gold CTA Button for Logged-In User -->
                        <button type="button" class="btn-main-order-gold shadow-lg flex-grow-1" data-bs-toggle="modal" data-bs-target="#buyOptionsModal">
                            <i class="fa-solid fa-shield-halved fs-5 me-2"></i> Order Now
                        </button>
                    <?php else: ?>
                        <!-- Login Check Guard for Guest User -->
                        <button type="button" class="btn-main-order-gold shadow-lg flex-grow-1" data-bs-toggle="modal" data-bs-target="#loginModal" onclick="if(typeof showToast==='function'){showToast('Login Required: Please log in to your account to place an order.', 'info');}">
                            <i class="fa-solid fa-shield-halved fs-5 me-2"></i> Order Now
                        </button>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if ($isLoggedIn): ?>
                        <!-- Fallback Direct Order Button if no external links -->
                        <a href="contact.php?inquire=<?= urlencode($product['name']) ?>" class="btn-main-order-gold shadow-lg flex-grow-1 text-decoration-none">
                            <i class="fa-solid fa-paper-plane fs-5 me-2"></i> Inquire &amp; Order Direct
                        </a>
                    <?php else: ?>
                        <button type="button" class="btn-main-order-gold shadow-lg flex-grow-1" data-bs-toggle="modal" data-bs-target="#loginModal" onclick="if(typeof showToast==='function'){showToast('Login Required: Please log in to your account to place an order.', 'info');}">
                            <i class="fa-solid fa-paper-plane fs-5 me-2"></i> Inquire &amp; Order Direct
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
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
            <a href="collection.php?category=<?= encodeId($catId) ?>" class="btn btn-gold-outline rounded-pill px-3 py-2 text-decoration-none text-nowrap flex-shrink-0" style="font-size: 0.85rem;">
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
                                        <div class="action-btn btn-wishlist" title="Add to Wishlist" data-product-id="<?= $rel['id'] ?>" onclick="toggleWishlist(<?= $rel['id'] ?>, this)"><i class="fa-regular fa-heart"></i></div>
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
                            <a href="product_details.php?id=<?= encodeId($rel['id']) ?>" class="add-to-cart-btn mt-3 mt-auto text-decoration-none">
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
                            <?php if ($isLoggedIn): ?>
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
                            <?php else: ?>
                                <a href="#" onclick="event.preventDefault(); handleGuestOrderClick();" class="partner-card text-decoration-none">
                                    <div class="partner-icon-box" style="background: <?= $iconBg ?>; color: <?= $iconColor ?>;">
                                        <i class="<?= $iconClass ?>"></i>
                                    </div>
                                    <div class="partner-info ms-3 flex-grow-1">
                                        <div class="partner-name">Order via <?= $platform ?> <span class="badge bg-warning text-dark ms-2" style="font-size: 0.65rem;">Login Required</span></div>
                                        <div class="partner-subtext"><i class="fa-solid fa-shield-cat me-1" style="color: #DFBA5A;"></i> Authorized Delivery Partner</div>
                                    </div>
                                    <div class="partner-arrow">
                                        <i class="fa-solid fa-lock text-warning"></i>
                                    </div>
                                </a>
                            <?php endif; ?>
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

<script>
    function handleGuestOrderClick() {
        const buyModalEl = document.getElementById('buyOptionsModal');
        if (buyModalEl) {
            const buyModal = bootstrap.Modal.getInstance(buyModalEl);
            if (buyModal) buyModal.hide();
        }
        const loginModalEl = document.getElementById('loginModal');
        if (loginModalEl) {
            const loginModal = bootstrap.Modal.getOrCreateInstance(loginModalEl);
            loginModal.show();
        }
        if (typeof showToast === 'function') {
            showToast('Login Required: Please log in to your account to place an order.', 'info');
        }
    }
</script><?php echo "\n"; ?>

<?php include_once('includes/footer.php'); ?>