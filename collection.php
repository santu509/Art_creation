<?php
// =========================================================
// COLLECTION PAGE WITH AJAX FILTERING (NO PAGE RELOAD)
// =========================================================

include_once('connection.php');
global $connect;

// Helper function to query products dynamically
function fetchProductsData($connect, $category = 0, $search = '', $sort = 'newest')
{
  $whereClauses = ["p.status = 1"];

  if (!empty($search)) {
    $safeSearch = mysqli_real_escape_string($connect, trim($search));
    $whereClauses[] = "(p.name LIKE '%$safeSearch%' OR p.description LIKE '%$safeSearch%')";
  }

  if ($category > 0) {
    $whereClauses[] = "p.category_id = " . intval($category);
  }

  $whereSQL = implode(" AND ", $whereClauses);

  $orderBy = "p.id DESC";
  if ($sort === 'price_asc') {
    $orderBy = "p.price ASC";
  } elseif ($sort === 'price_desc') {
    $orderBy = "p.price DESC";
  } elseif ($sort === 'name_asc') {
    $orderBy = "p.name ASC";
  }

  $query = "SELECT p.*, c.name as category_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE $whereSQL 
              ORDER BY $orderBy";

  return mysqli_query($connect, $query);
}

// Helper function to render Product Cards HTML & Modals
function renderProductCardsHTML($productsResult, $search = '')
{
  ob_start();
  if ($productsResult && mysqli_num_rows($productsResult) > 0) {
    while ($product = mysqli_fetch_assoc($productsResult)) {
      $imgSrc = !empty($product['image']) ? 'uploads/' . htmlspecialchars($product['image']) : 'asset/image/default-image.jpg';
      $price = floatval($product['price']);
      $discount = floatval($product['discount_percentage']);
      $finalPrice = ($discount > 0) ? $price - ($price * ($discount / 100)) : $price;
?>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="art-card">
          <div class="art-img-box">
            <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($product['name']) ?>" loading="lazy" onerror="this.src='asset/image/default-image.jpg';">
            <?php if ($discount > 0): ?>
              <span class="art-badge-discount">
                <i class="fa-solid fa-tag me-1"></i><?= intval($discount) ?>% OFF
              </span>
            <?php endif; ?>
            <?php if (!empty($product['category_name'])): ?>
              <span class="art-category-pill">
                <?= htmlspecialchars($product['category_name']) ?>
              </span>
            <?php endif; ?>
          </div>
          <div class="art-card-body">
            <h3 class="art-title" title="<?= htmlspecialchars($product['name']) ?>">
              <?= htmlspecialchars($product['name']) ?>
            </h3>
            <p class="art-desc">
              <?= htmlspecialchars($product['description'] ?: 'Exquisitely handcrafted piece created with authentic traditional methods.') ?>
            </p>
            <div class="art-card-footer">
              <div>
                <span class="art-price-main">₹<?= number_format($finalPrice, 2) ?></span>
                <?php if ($discount > 0): ?>
                  <span class="art-price-old">₹<?= number_format($price, 2) ?></span>
                <?php endif; ?>
              </div>
              <button type="button"
                class="btn btn-view-art"
                data-bs-toggle="modal"
                data-bs-target="#artModal<?= $product['id'] ?>">
                View <i class="fa-solid fa-arrow-right ms-1"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Product Details Modal -->
      <div class="modal fade" id="artModal<?= $product['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-body p-0">
              <div class="row g-0">
                <div class="col-md-6 bg-light d-flex align-items-center justify-content-center p-3" style="min-height: 320px;">
                  <img src="<?= $imgSrc ?>" class="img-fluid rounded-3 shadow-sm" style="max-height: 380px; object-fit: contain;" alt="<?= htmlspecialchars($product['name']) ?>">
                </div>
                <div class="col-md-6 p-4 d-flex flex-column justify-content-between">
                  <div>
                    <div class="d-flex justify-content-between align-items-start mb-2">
                      <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-1 fw-semibold small">
                        <?= htmlspecialchars($product['category_name'] ?? 'Handcrafted Art') ?>
                      </span>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <h3 class="font-serif fw-bold text-dark mb-3" style="font-family: 'Playfair Display', serif;">
                      <?= htmlspecialchars($product['name']) ?>
                    </h3>
                    <div class="mb-3">
                      <span class="fs-3 fw-bold text-warning-emphasis" style="color: #C59B27 !important;">
                        ₹<?= number_format($finalPrice, 2) ?>
                      </span>
                      <?php if ($discount > 0): ?>
                        <span class="text-muted text-decoration-line-through ms-2 fs-6">
                          ₹<?= number_format($price, 2) ?>
                        </span>
                        <span class="badge bg-danger ms-2" style="font-size: 0.75rem;">
                          Save <?= intval($discount) ?>%
                        </span>
                      <?php endif; ?>
                    </div>
                    <p class="text-muted small leading-relaxed mb-4" style="font-family: 'Outfit', sans-serif;">
                      <?= nl2br(htmlspecialchars($product['description'] ?: 'This masterpiece is crafted by experienced artisans using organic clay, natural mineral pigments, and traditional hand-sculpting techniques passed down through generations.')) ?>
                    </p>
                  </div>
                  <div class="d-flex gap-2">
                    <a href="contact.php?inquire=<?= urlencode($product['name']) ?>" class="btn w-100 fw-bold py-2 px-4 rounded-3 shadow-sm" style="background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%); color: #1A1612; border: none;">
                      <i class="fa-solid fa-paper-plane me-2"></i> Inquire / Order Now
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php
    }
  } else {
    ?>
    <div class="col-12 text-center py-5">
      <div class="p-5 bg-white rounded-4 border border-light-subtle shadow-sm mx-auto" style="max-width: 580px;">
        <div class="mb-3 text-warning opacity-75 fs-1">
          <i class="fa-solid fa-palette"></i>
        </div>
        <h4 class="font-serif fw-bold text-dark mb-2" style="font-family: 'Playfair Display', serif;">No Artworks Found</h4>
        <p class="text-muted small mb-4">
          <?= !empty($search) ? 'No artwork matching "' . htmlspecialchars($search) . '" was found in our collection.' : 'Currently no active products in this category.' ?>
        </p>
        <button type="button" onclick="resetAllFilters()" class="btn rounded-pill px-4 py-2 fw-semibold" style="background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%); color: #1A1612; border: none;">
          <i class="fa-solid fa-rotate-left me-2"></i> Reset All Filters
        </button>
      </div>
    </div>
<?php
  }
  return ob_get_clean();
}

// ---------------------------------------------------------
// 1. AJAX ENDPOINT (FOR ZERO PAGE RELOAD UPDATES)
// ---------------------------------------------------------
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
  header('Content-Type: application/json');

  $category = isset($_GET['category']) ? intval($_GET['category']) : 0;
  $search = isset($_GET['search']) ? $_GET['search'] : '';
  $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

  $productsResult = fetchProductsData($connect, $category, $search, $sort);
  $totalProducts = $productsResult ? mysqli_num_rows($productsResult) : 0;
  $htmlContent = renderProductCardsHTML($productsResult, $search);

  echo json_encode([
    'success' => true,
    'total' => $totalProducts,
    'html' => $htmlContent
  ]);
  exit();
}

// ---------------------------------------------------------
// 2. NORMAL PAGE LOAD INITIALIZATION
// ---------------------------------------------------------
$currentPage = 'collection.php';
include_once('nav.php');

// Fetch categories for filtering bar
$categoriesQuery = "SELECT * FROM categories WHERE status = 1 ORDER BY name ASC";
$categoriesResult = mysqli_query($connect, $categoriesQuery);

// Fetch initial products list
$initialProducts = fetchProductsData($connect, 0, '', 'newest');
$totalProducts = $initialProducts ? mysqli_num_rows($initialProducts) : 0;
?>

<!-- ====================================================
     EASY TO UNDERSTAND & CUSTOMIZABLE CSS STYLES
     ==================================================== -->
<style>
  /* EASY CUSTOMIZATION VARIABLES */
  :root {
    --banner-bg-image: url('asset/image/diverse_collection_banner.png');
    --gold-primary: #C59B27;
    --gold-light: #DFBA5A;
    --text-dark: #1A1612;
  }

  /* HERO BANNER SECTION */
  .collection-hero-section {
    position: relative;
    width: 100%;
    min-height: 280px;
    height: auto;
    padding: 85px 0 25px 0;
    background: var(--banner-bg-image) no-repeat center center / cover;
    overflow: hidden;
    margin-top: 0;
    display: flex;
    align-items: center;
    border-bottom: 2px solid rgba(212, 175, 55, 0.3);
  }

  /* Soft Ambient Overlay */
  .collection-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg,
        rgba(15, 12, 9, 0.52) 0%,
        rgba(26, 20, 15, 0.38) 50%,
        rgba(15, 12, 9, 0.65) 100%);
    z-index: 1;
  }

  /* Animated Radial Shimmer */
  .collection-hero-bg-anim {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 80% 30%, rgba(212, 175, 55, 0.12) 0%, transparent 60%);
    z-index: 2;
    pointer-events: none;
    animation: pulseGlow 8s ease-in-out infinite alternate;
  }

  @keyframes pulseGlow {
    0% {
      opacity: 0.5;
      transform: scale(1);
    }

    100% {
      opacity: 1;
      transform: scale(1.05);
    }
  }

  .collection-hero-content {
    position: relative;
    z-index: 3;
    width: 100%;
  }

  /* Title & Subtitle Styling */
  .collection-hero-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.4rem, 3.5vw, 2.4rem);
    font-weight: 700;
    color: #FFFFFF;
    line-height: 1.22;
    margin-bottom: 8px;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.6);
  }

  .text-gold-accent {
    background: linear-gradient(135deg, #ffdc69 0%, #ffd15c 50%, #ffc62a 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-style: italic;
  }

  .collection-hero-sub {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(0.85rem, 1.4vw, 0.98rem);
    color: #F3EFEA;
    max-width: 650px;
    margin-bottom: 0;
    font-weight: 400;
    line-height: 1.5;
    text-shadow: 0 1px 4px rgba(0, 0, 0, 0.7);
  }

  /* Pill Badge & Breadcrumb Navigation */
  .hero-badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(212, 175, 55, 0.18);
    color: #DFBA5A;
    border: 1px solid rgba(212, 175, 55, 0.35);
    backdrop-filter: blur(8px);
    padding: 4px 14px;
    border-radius: 50px;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    margin-bottom: 8px;
  }

  .collection-breadcrumb {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    font-size: 0.85rem;
    color: rgba(245, 242, 237, 0.8);
  }

  .collection-breadcrumb a {
    color: #DFBA5A;
    text-decoration: none;
  }

  .collection-breadcrumb a:hover {
    color: #FFF0BD;
    text-decoration: underline;
  }

  /* Dynamic Artwork Counter Chip */
  .hero-stat-chip {
    background: rgba(26, 22, 18, 0.45);
    border: 1px solid rgba(212, 175, 55, 0.25);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    padding: 8px 16px;
    color: #F5F2ED;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-size: 0.85rem;
  }

  .hero-stat-chip .stat-num {
    font-family: 'Playfair Display', serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: #DFBA5A;
    line-height: 1;
  }

  /* Responsive Mobile Sizing */
  @media (max-width: 991.98px) {
    .collection-hero-section {
      height: auto;
      min-height: 330px;
      padding: 85px 0 25px 0;
    }

    .collection-hero-title {
      font-size: 1.5rem;
      line-height: 1.25;
      margin-bottom: 6px;
    }

    .collection-hero-sub {
      font-size: 0.82rem;
      line-height: 1.45;
      margin-bottom: 10px;
    }

    .hero-badge-pill {
      font-size: 0.72rem;
      padding: 3px 10px;
      margin-bottom: 6px;
    }

    .hero-stat-chip {
      padding: 6px 14px;
      font-size: 0.78rem;
      margin-bottom: 6px;
    }

    .hero-stat-chip .stat-num {
      font-size: 1.1rem;
    }

    .collection-breadcrumb {
      justify-content: flex-start;
      font-size: 0.78rem;
      margin-top: 4px;
    }
  }

  @media (max-width: 575.98px) {
    .collection-hero-section {
      min-height: 350px;
      padding: 82px 0 20px 0;
    }

    .collection-hero-title {
      font-size: 1.35rem;
    }

    .collection-hero-sub {
      font-size: 0.8rem;
      line-height: 1.4;
    }
  }

  /* COLLECTION LAYOUT & FILTERS */
  .collection-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 40px 20px 80px 20px;
  }

  /* Category Filter Pills */
  .cat-filter-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    overflow-x: auto;
    padding: 6px 4px 14px 4px;
    scrollbar-width: thin;
    scrollbar-color: rgba(212, 175, 55, 0.3) transparent;
  }

  .cat-btn {
    background: #FFFFFF;
    color: #3A3530;
    border: 1px solid #E5E1DB;
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 500;
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
  }

  .cat-btn:hover {
    background: #FAF6EE;
    border-color: #C59B27;
    color: #9B781E;
    transform: translateY(-2px);
  }

  .cat-btn.active {
    background: linear-gradient(135deg, #1A1612 0%, #2A241D 100%);
    color: #DFBA5A;
    border-color: #C59B27;
    box-shadow: 0 4px 15px rgba(26, 22, 18, 0.15);
  }

  /* Filter Card & Search Bar */
  .filter-bar-card {
    background: #FFFFFF;
    border: 1px solid #EAE6DF;
    border-radius: 16px;
    padding: 16px 22px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    margin-bottom: 30px;
  }

  .search-input-group .form-control {
    border-radius: 50px 0 0 50px;
    border: 1px solid #E5E1DB;
    border-right: none;
    padding: 10px 20px;
    font-size: 0.92rem;
    background-color: #FAF8F5;
  }

  .search-input-group .form-control:focus {
    box-shadow: none;
    border-color: #C59B27;
    background-color: #FFFFFF;
  }

  .search-input-group .btn-search {
    border-radius: 0 50px 50px 0;
    background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%);
    color: #1A1612;
    border: 1px solid #C59B27;
    padding: 10px 24px;
    font-weight: 600;
  }

  .sort-select {
    border-radius: 50px;
    border: 1px solid #E5E1DB;
    padding: 10px 20px;
    font-size: 0.9rem;
    background-color: #FAF8F5;
    color: #3A3530;
    cursor: pointer;
  }

  /* Product Card Styling */
  .art-card {
    background: #FFFFFF;
    border: 1px solid #EAE6DF;
    border-radius: 18px;
    overflow: hidden;
    transition: all 0.35s cubic-bezier(0.25, 1, 0.5, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
  }

  .art-card:hover {
    transform: translateY(-8px);
    border-color: rgba(197, 155, 39, 0.4);
    box-shadow: 0 16px 36px rgba(42, 36, 29, 0.08);
  }

  .art-img-box {
    position: relative;
    width: 100%;
    padding-top: 100%;
    overflow: hidden;
    background-color: #F8F5F0;
  }

  .art-img-box img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
  }

  .art-card:hover .art-img-box img {
    transform: scale(1.08);
  }

  .art-badge-discount {
    position: absolute;
    top: 12px;
    left: 12px;
    background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%);
    color: #1A1612;
    font-weight: 700;
    font-size: 0.75rem;
    padding: 4px 10px;
    border-radius: 50px;
    z-index: 2;
  }

  .art-category-pill {
    position: absolute;
    bottom: 12px;
    left: 12px;
    background: rgba(26, 22, 18, 0.75);
    color: #E2DDD5;
    backdrop-filter: blur(6px);
    font-size: 0.75rem;
    padding: 4px 12px;
    border-radius: 50px;
    z-index: 2;
    border: 1px solid rgba(255, 255, 255, 0.15);
  }

  .art-card-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
  }

  .art-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem;
    font-weight: 700;
    color: #1A1612;
    margin-bottom: 6px;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .art-desc {
    font-size: 0.85rem;
    color: #7C7267;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.5;
  }

  .art-card-footer {
    margin-top: auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 14px;
    border-top: 1px solid #F3EFEA;
  }

  .art-price-main {
    font-family: 'Outfit', sans-serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: #C59B27;
  }

  .art-price-old {
    font-size: 0.85rem;
    color: #A0968B;
    text-decoration: line-through;
    margin-left: 6px;
  }

  .btn-view-art {
    background: rgba(212, 175, 55, 0.1);
    color: #9B781E;
    border: 1px solid rgba(197, 155, 39, 0.3);
    border-radius: 50px;
    padding: 7px 16px;
    font-size: 0.82rem;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-view-art:hover {
    background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%);
    color: #1A1612;
    border-color: #C59B27;
    transform: translateY(-2px);
  }

  /* Smooth Grid Transition */
  #products-grid-container {
    transition: opacity 0.3s ease;
  }

  /* ====================================================
     MOBILE HORIZONTAL PRODUCT SCROLL SLIDER
     ==================================================== */
  @media (max-width: 767.98px) {
    #products-grid-container {
      display: flex !important;
      flex-wrap: nowrap !important;
      overflow-x: auto !important;
      scroll-snap-type: x mandatory;
      -webkit-overflow-scrolling: touch;
      padding-bottom: 22px;
      padding-top: 6px;
      margin-left: -10px;
      margin-right: -10px;
      padding-left: 15px;
      padding-right: 15px;
      scrollbar-width: thin;
      scrollbar-color: #C59B27 #FAF6EE;
    }

    #products-grid-container::-webkit-scrollbar {
      height: 6px;
    }

    #products-grid-container::-webkit-scrollbar-track {
      background: #F3EFEA;
      border-radius: 10px;
    }

    #products-grid-container::-webkit-scrollbar-thumb {
      background: linear-gradient(90deg, #DFBA5A 0%, #C59B27 100%);
      border-radius: 10px;
    }

    /* Product Card Mobile Width & Scroll Snap */
    #products-grid-container>[class*="col-"] {
      flex: 0 0 85% !important;
      max-width: 310px !important;
      min-width: 270px !important;
      scroll-snap-align: start;
    }
  }
</style>

<!-- ====================================================
     HERO BANNER SECTION
     ==================================================== -->
<section class="collection-hero-section">
  <div class="collection-hero-overlay"></div>
  <div class="collection-hero-bg-anim"></div>
  <div class="container collection-hero-content">
    <div class="row align-items-center g-2">
      <div class="col-md-7 col-lg-8 text-start">
        <div class="hero-badge-pill">
          <i class="fa-solid fa-shapes me-1"></i> All Handicraft Collections
        </div>
        <h1 class="collection-hero-title">
          Handmade <span class="text-gold-accent">Art & Artisan Collections</span>
        </h1>
        <p class="collection-hero-sub">
          Discover our full range of handcrafted masterworks — ceramic pottery, eco-friendly tote bags, gemstone jewelry, terracotta decor, and custom artisan creations.
        </p>
      </div>
      <div class="col-md-5 col-lg-4 text-md-end">
        <div class="hero-stat-chip mb-2">
          <i class="fa-solid fa-gem fa-lg text-warning"></i>
          <div class="text-start">
            <div class="stat-num" id="total-artworks-count"><?= sprintf("%02d", $totalProducts) ?> Artworks</div>
            <div class="small text-muted" style="font-size:0.75rem; color:#D0C8BD !important;">Available in Collection</div>
          </div>
        </div>
        <nav class="collection-breadcrumb">
          <a href="index.php"><i class="fa-solid fa-house me-1"></i> Home</a>
          <i class="fa-solid fa-chevron-right fs-xs opacity-50"></i>
          <span class="text-light">Collections</span>
        </nav>
      </div>
    </div>
  </div>
</section>

<!-- ====================================================
     MAIN COLLECTION SECTION WITH AJAX CONTROLS
     ==================================================== -->
<div class="collection-container">

  <!-- Category Filter Pills (No Page Reload) -->
  <div class="cat-filter-wrapper mb-4">
    <button type="button" class="cat-btn active" data-cat-id="0">
      <i class="fa-solid fa-border-all me-1"></i> All Artworks
    </button>
    <?php if ($categoriesResult && mysqli_num_rows($categoriesResult) > 0): ?>
      <?php while ($cat = mysqli_fetch_assoc($categoriesResult)): ?>
        <button type="button" class="cat-btn" data-cat-id="<?= $cat['id'] ?>">
          <?= htmlspecialchars($cat['name']) ?>
        </button>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>

  <!-- Search & Sort Filter Bar -->
  <div class="filter-bar-card">
    <form id="search-form" class="row g-3 align-items-center" onsubmit="return false;">

      <!-- Search Input -->
      <div class="col-lg-6 col-md-7">
        <div class="input-group search-input-group">
          <input type="text" id="search-input" class="form-control"
            placeholder="Search artwork title, category, description...">
          <button class="btn btn-search" type="submit" id="search-btn">
            <i class="fa-solid fa-magnifying-glass me-1"></i> Search
          </button>
        </div>
      </div>

      <!-- Sort Selector & Reset Button -->
      <div class="col-lg-4 col-md-5 ms-auto d-flex align-items-center justify-content-md-end gap-2">
        <label class="form-label mb-0 small text-muted text-nowrap fw-medium">
          <i class="fa-solid fa-arrow-down-short-wide me-1"></i> Sort By:
        </label>
        <select id="sort-select" class="form-select sort-select w-auto">
          <option value="newest">Newest Arrivals</option>
          <option value="price_asc">Price: Low to High</option>
          <option value="price_desc">Price: High to Low</option>
          <option value="name_asc">Name: A to Z</option>
        </select>
        <button type="button" id="reset-filters-btn" onclick="resetAllFilters()"
          class="btn btn-sm btn-outline-secondary rounded-circle"
          title="Reset Filters"
          style="width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center;">
          <i class="fa-solid fa-rotate-left"></i>
        </button>
      </div>
    </form>
  </div>

  <!-- Dynamic Products Grid Container -->
  <div class="row g-4" id="products-grid-container">
    <?= renderProductCardsHTML($initialProducts); ?>
  </div>

</div>

<!-- ====================================================
     EASY TO READ AJAX JAVASCRIPT (NO PAGE RELOAD)
     ==================================================== -->
<script>
  // 1. Current State Variables
  let currentCategory = 0;
  let currentSort = 'newest';
  let currentSearch = '';
  let searchTimer = null;

  // 2. Main Function to Fetch Collection via AJAX
  function filterCollectionAJAX() {
    const gridContainer = document.getElementById('products-grid-container');
    const counterElement = document.getElementById('total-artworks-count');

    // Smooth opacity fade while loading
    if (gridContainer) gridContainer.style.opacity = '0.4';

    const apiUrl = `collection.php?ajax=1&category=${currentCategory}&sort=${currentSort}&search=${encodeURIComponent(currentSearch)}`;

    fetch(apiUrl)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Update product grid HTML
          if (gridContainer) gridContainer.innerHTML = data.html;

          // Update Artwork Counter in Banner
          if (counterElement) {
            const countStr = String(data.total).padStart(2, '0');
            counterElement.textContent = `${countStr} Artworks`;
          }
        }
        if (gridContainer) gridContainer.style.opacity = '1';
      })
      .catch(error => {
        console.error('Error fetching collection:', error);
        if (gridContainer) gridContainer.style.opacity = '1';
      });
  }

  // 3. Category Filter Button Click Event
  document.querySelectorAll('.cat-btn').forEach(button => {
    button.addEventListener('click', function() {
      // Toggle active class visually
      document.querySelectorAll('.cat-btn').forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');

      // Update state and fetch
      currentCategory = parseInt(this.getAttribute('data-cat-id')) || 0;
      filterCollectionAJAX();
    });
  });

  // 4. Live Search Input (Debounced 300ms)
  const searchInput = document.getElementById('search-input');
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      clearTimeout(searchTimer);
      searchTimer = setTimeout(() => {
        currentSearch = this.value.trim();
        filterCollectionAJAX();
      }, 300);
    });
  }

  // 5. Search Form Submission
  const searchForm = document.getElementById('search-form');
  if (searchForm) {
    searchForm.addEventListener('submit', function(e) {
      e.preventDefault();
      if (searchInput) currentSearch = searchInput.value.trim();
      filterCollectionAJAX();
    });
  }

  // 6. Sort Dropdown Change Event
  const sortSelect = document.getElementById('sort-select');
  if (sortSelect) {
    sortSelect.addEventListener('change', function() {
      currentSort = this.value;
      filterCollectionAJAX();
    });
  }

  // 7. Reset All Filters Function
  function resetAllFilters() {
    currentCategory = 0;
    currentSort = 'newest';
    currentSearch = '';

    if (searchInput) searchInput.value = '';
    if (sortSelect) sortSelect.value = 'newest';

    document.querySelectorAll('.cat-btn').forEach(btn => btn.classList.remove('active'));
    const allBtn = document.querySelector('.cat-btn[data-cat-id="0"]');
    if (allBtn) allBtn.classList.add('active');

    filterCollectionAJAX();
  }
</script>

<!-- Footer Inclusion -->
<?php include_once('footer.php'); ?>