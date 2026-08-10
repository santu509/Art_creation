<?php
// =========================================================
// COLLECTION PAGE WITH AJAX FILTERING (NO PAGE RELOAD)
// =========================================================

include_once('includes/connection.php');
global $connect;

// Helper function to query products dynamically
// Helper function to fetch active user wishlist IDs
function fetchUserWishlistIds($connect)
{
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  $userWishlistIds = [];
  $isLoggedIn = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
  if ($isLoggedIn && isset($_SESSION['user_id'])) {
    $uId = intval($_SESSION['user_id']);
    $wlRes = mysqli_query($connect, "SELECT product_id FROM wishlist WHERE user_id = $uId");
    if ($wlRes) {
      while ($wlRow = mysqli_fetch_assoc($wlRes)) {
        $userWishlistIds[] = intval($wlRow['product_id']);
      }
    }
  }
  return $userWishlistIds;
}

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
function renderProductCardsHTML($productsResult, $search = '', $userWishlistIds = [])
{
  ob_start();
  if ($productsResult && mysqli_num_rows($productsResult) > 0) {
    while ($product = mysqli_fetch_assoc($productsResult)) {
      $imgSrc = !empty($product['image']) ? 'uploads/' . htmlspecialchars($product['image']) : 'asset/image/default-image.jpg';
      $price = floatval($product['price']);
      $discount = floatval($product['discount_percentage']);
      $finalPrice = ($discount > 0) ? $price - ($price * ($discount / 100)) : $price;
      $isWishlisted = in_array(intval($product['id']), $userWishlistIds);
      $iconClass = $isWishlisted ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart';
      $titleText = $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist';
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
                <div class="action-btn btn-wishlist" title="<?= $titleText ?>" data-product-id="<?= $product['id'] ?>" onclick="toggleWishlist(<?= $product['id'] ?>, this)"><i class="<?= $iconClass ?>"></i></div>
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
          <a href="product_details.php?id=<?= encodeId($product['id']) ?>" class="add-to-cart-btn mt-3 mt-auto text-decoration-none">
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

  $category = isset($_GET['category']) ? decodeId($_GET['category']) : 0;
  $search = isset($_GET['search']) ? $_GET['search'] : '';
  $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
  $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

  $userWishlistIds = fetchUserWishlistIds($connect);
  $data = fetchProductsData($connect, $category, $search, $sort, $page, 8);
  $htmlContent = renderProductCardsHTML($data['result'], $search, $userWishlistIds);
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
include_once('includes/nav.php');

// Fetch categories for filtering bar
$categoriesQuery = "SELECT * FROM categories WHERE status = 1 ORDER BY name ASC";
$categoriesResult = mysqli_query($connect, $categoriesQuery);

// Read category GET parameter if provided from index.php or direct link
$selectedCategory = isset($_GET['category']) ? decodeId($_GET['category']) : 0;

// Fetch initial products list (Default 8 per page for desktop)
$userWishlistIds = fetchUserWishlistIds($connect);
$initialData = fetchProductsData($connect, $selectedCategory, '', 'newest', 1, 8);
$initialProducts = $initialData['result'];
$totalProducts = $initialData['total'];
$initialPaginationHTML = renderPaginationHTML($initialData['page'], $initialData['totalPages']);
?>

<!-- ====================================================
     EASY TO UNDERSTAND & CUSTOMIZABLE CSS STYLES
     ==================================================== -->
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
    <?= renderProductCardsHTML($initialProducts, '', $userWishlistIds); ?>
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

          // Sync wishlist heart icon states across JS and DOM
          if (window.syncWishlistUI) window.syncWishlistUI();
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
<?php include_once('includes/footer.php'); ?>