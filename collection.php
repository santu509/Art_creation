<?php
// =========================================================
// COLLECTION PAGE WITH AJAX FILTERING (NO PAGE RELOAD)
// =========================================================

include_once('connection.php');
global $connect;

// Helper function to query products dynamically
// Helper function to query products dynamically with limit and page offset
function fetchProductsData($connect, $category = 0, $search = '', $sort = 'newest', $page = 1, $limit = 8)
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

  // 1. Total count for pagination calculation
  $countQuery = "SELECT COUNT(p.id) as total FROM products p WHERE $whereSQL";
  $countRes = mysqli_query($connect, $countQuery);
  $totalProducts = 0;
  if ($countRes && $row = mysqli_fetch_assoc($countRes)) {
    $totalProducts = intval($row['total']);
  }

  $orderBy = "p.id DESC";
  if ($sort === 'price_asc') {
    $orderBy = "p.price ASC";
  } elseif ($sort === 'price_desc') {
    $orderBy = "p.price DESC";
  } elseif ($sort === 'name_asc') {
    $orderBy = "p.name ASC";
  }

  $page = max(1, intval($page));
  $limit = max(1, intval($limit));
  $offset = ($page - 1) * $limit;

  $query = "SELECT p.*, c.name as category_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE $whereSQL 
              ORDER BY $orderBy 
              LIMIT $limit OFFSET $offset";

  $result = mysqli_query($connect, $query);

  return [
    'result' => $result,
    'total' => $totalProducts,
    'page' => $page,
    'totalPages' => ceil($totalProducts / $limit)
  ];
}

// Helper function to render Desktop Pagination HTML
function renderPaginationHTML($currentPage, $totalPages)
{
  if ($totalPages <= 1) {
    return '';
  }

  ob_start();
?>
  <nav class="pagination-nav my-4" aria-label="Products Page Navigation">
    <ul class="pagination justify-content-center gap-2 mb-0">
      <!-- Previous Button -->
      <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
        <button type="button" class="page-link page-nav-btn" onclick="changeCollectionPage(<?= $currentPage - 1 ?>)" aria-label="Previous Page">
          <i class="fa-solid fa-chevron-left"></i>
        </button>
      </li>

      <!-- Page Numbers -->
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
          <button type="button" class="page-link page-num-btn" onclick="changeCollectionPage(<?= $i ?>)">
            <?= $i ?>
          </button>
        </li>
      <?php endfor; ?>

      <!-- Next Button -->
      <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
        <button type="button" class="page-link page-nav-btn" onclick="changeCollectionPage(<?= $currentPage + 1 ?>)" aria-label="Next Page">
          <i class="fa-solid fa-chevron-right"></i>
        </button>
      </li>
    </ul>
  </nav>
  <?php
  return ob_get_clean();
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
        <div class="modern-product-card animate-up delay-3">
          <div class="d-flex flex-column flex-grow-1">
            <div class="img-container">
              <span class="badge-available">Available</span>
              <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($product['name']) ?>" loading="lazy" onerror="this.src='asset/image/default-image.jpg';">
              <?php if ($discount > 0): ?>
                <span class="badge-discount-corner">
                  <i class="fa-solid fa-tag me-1"></i><?= intval($discount) ?>% OFF
                </span>
              <?php endif; ?>
              <div class="action-buttons">
                <div class="action-btn" title="Add to Cart"><i class="fa-solid fa-cart-plus"></i></div>
                <div class="action-btn" title="Add to Wishlist"><i class="fa-regular fa-heart"></i></div>
              </div>
            </div>
            <div class="card-info">
              <div class="d-flex align-items-center mb-1">
                <span class="cat-name mb-0"><?= htmlspecialchars($product['category_name'] ?? 'Handcrafted Art') ?></span>
              </div>
              <h4 class="prod-name" title="<?= htmlspecialchars($product['name']) ?>"><?= htmlspecialchars($product['name']) ?></h4>
              <div class="price-box">
                <span class="price-current">₹<?= number_format($finalPrice, 2) ?></span>
                <?php if ($discount > 0): ?>
                  <span class="price-old ms-2">₹<?= number_format($price, 2) ?></span>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <a href="product_details.php?id=<?= $product['id'] ?>" class="add-to-cart-btn mt-3 mt-auto text-decoration-none">
            Explore Product <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
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
  $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

  $data = fetchProductsData($connect, $category, $search, $sort, $page, 8);
  $htmlContent = renderProductCardsHTML($data['result'], $search);
  $paginationContent = renderPaginationHTML($data['page'], $data['totalPages']);

  echo json_encode([
    'success' => true,
    'total' => $data['total'],
    'page' => $data['page'],
    'totalPages' => $data['totalPages'],
    'html' => $htmlContent,
    'paginationHtml' => $paginationContent
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

// Read category GET parameter if provided from index.php or direct link
$selectedCategory = isset($_GET['category']) ? intval($_GET['category']) : 0;

// Fetch initial products list (Default 8 per page for desktop)
$initialData = fetchProductsData($connect, $selectedCategory, '', 'newest', 1, 8);
$initialProducts = $initialData['result'];
$totalProducts = $initialData['total'];
$initialPaginationHTML = renderPaginationHTML($initialData['page'], $initialData['totalPages']);
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
    background: linear-gradient(135deg, #ffdc69 0%, #ffdd89 50%, #ffe431 100%);
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

  /* Modern Product Card Styling (Identical to index.php) */
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

  /* Smooth Grid Transition */
  #products-grid-container {
    transition: opacity 0.3s ease;
  }

  /* -----------------------------------------
     LARGE SCREEN DESKTOP PAGINATION STYLING
  ----------------------------------------- */
  .pagination-nav {
    display: block;
  }

  /* Hide Pagination on Mobile / Touch Screens (where horizontal scroll / swipe is active) */
  @media (max-width: 767.98px) {
    .pagination-nav {
      display: none !important;
    }
  }

  .pagination .page-link {
    border-radius: 50% !important;
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-family: 'Outfit', sans-serif;
    font-weight: 600;
    font-size: 0.9rem;
    color: #4A4036;
    background-color: #FFFFFF;
    border: 1px solid #EBE5D9;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    transition: all 0.3s ease;
    cursor: pointer;
  }

  .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%);
    color: #1A1612;
    border-color: transparent;
    box-shadow: 0 4px 14px rgba(197, 155, 39, 0.35);
  }

  .pagination .page-link:hover:not(.active) {
    background-color: #FAF6EE;
    color: #C59B27;
    border-color: #C59B27;
    transform: translateY(-2px);
  }

  .pagination .page-item.disabled .page-link {
    opacity: 0.4;
    cursor: not-allowed;
    pointer-events: none;
    background-color: #F8F5F0;
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

  /* Staggered Text Keyframe Animations */
  .animate-up {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeUp 0.8s cubic-bezier(0.25, 1, 0.5, 1) forwards;
  }

  .delay-1 {
    animation-delay: 0.15s;
  }

  .delay-2 {
    animation-delay: 0.3s;
  }

  .delay-3 {
    animation-delay: 0.45s;
  }

  .delay-4 {
    animation-delay: 0.6s;
  }

  @keyframes fadeUp {
    to {
      opacity: 1;
      transform: translateY(0);
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
        <div class="hero-badge-pill animate-up delay-1">
          <i class="fa-solid fa-shapes me-1"></i> All Handicraft Collections
        </div>
        <h1 class="collection-hero-title animate-up delay-2">
          Handmade <span class="text-gold-accent">Art & Artisan Collections</span>
        </h1>
        <p class="collection-hero-sub animate-up delay-3">
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
    <button type="button" class="cat-btn <?= ($selectedCategory == 0) ? 'active' : '' ?>" data-cat-id="0">
      <i class="fa-solid fa-border-all me-1"></i> All Artworks
    </button>
    <?php if ($categoriesResult && mysqli_num_rows($categoriesResult) > 0): ?>
      <?php while ($cat = mysqli_fetch_assoc($categoriesResult)): ?>
        <button type="button" class="cat-btn <?= ($selectedCategory == $cat['id']) ? 'active' : '' ?>" data-cat-id="<?= $cat['id'] ?>">
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

  <!-- Dynamic Desktop Pagination Container (Hidden on Mobile) -->
  <div id="pagination-container">
    <?= $initialPaginationHTML ?>
  </div>

</div>

<!-- ====================================================
     EASY TO READ AJAX JAVASCRIPT (NO PAGE RELOAD)
     ==================================================== -->
<script>
  // 1. Current State Variables
  let currentCategory = <?= $selectedCategory ?>;
  let currentSort = 'newest';
  let currentSearch = '';
  let currentPageNum = 1;
  let searchTimer = null;

  // 2. Main Function to Fetch Collection via AJAX
  function filterCollectionAJAX() {
    const gridContainer = document.getElementById('products-grid-container');
    const paginationContainer = document.getElementById('pagination-container');
    const counterElement = document.getElementById('total-artworks-count');

    // Smooth opacity fade while loading
    if (gridContainer) gridContainer.style.opacity = '0.4';

    const apiUrl = `collection.php?ajax=1&category=${currentCategory}&sort=${currentSort}&search=${encodeURIComponent(currentSearch)}&page=${currentPageNum}`;

    fetch(apiUrl)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Update product grid HTML
          if (gridContainer) gridContainer.innerHTML = data.html;

          // Update Desktop Pagination HTML
          if (paginationContainer) paginationContainer.innerHTML = data.paginationHtml;

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

  // 3. Page Switch Handler (Zero Page Reload)
  function changeCollectionPage(pageNum) {
    currentPageNum = pageNum;
    filterCollectionAJAX();

    const gridContainer = document.getElementById('products-grid-container');
    if (gridContainer) {
      gridContainer.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
    }
  }

  // 4. Category Filter Button Click Event
  document.querySelectorAll('.cat-btn').forEach(button => {
    button.addEventListener('click', function() {
      // Toggle active class visually
      document.querySelectorAll('.cat-btn').forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');

      // Update state and fetch
      currentCategory = parseInt(this.getAttribute('data-cat-id')) || 0;
      currentPageNum = 1; // Reset to Page 1
      filterCollectionAJAX();
    });
  });

  // 5. Live Search Input (Debounced 300ms)
  const searchInput = document.getElementById('search-input');
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      clearTimeout(searchTimer);
      searchTimer = setTimeout(() => {
        currentSearch = this.value.trim();
        currentPageNum = 1; // Reset to Page 1
        filterCollectionAJAX();
      }, 300);
    });
  }

  // 6. Search Form Submission
  const searchForm = document.getElementById('search-form');
  if (searchForm) {
    searchForm.addEventListener('submit', function(e) {
      e.preventDefault();
      if (searchInput) currentSearch = searchInput.value.trim();
      currentPageNum = 1; // Reset to Page 1
      filterCollectionAJAX();
    });
  }

  // 7. Sort Dropdown Change Event
  const sortSelect = document.getElementById('sort-select');
  if (sortSelect) {
    sortSelect.addEventListener('change', function() {
      currentSort = this.value;
      currentPageNum = 1; // Reset to Page 1
      filterCollectionAJAX();
    });
  }

  // 8. Reset All Filters Function
  function resetAllFilters() {
    currentCategory = 0;
    currentSort = 'newest';
    currentSearch = '';
    currentPageNum = 1;

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