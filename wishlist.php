<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// =========================================================
// WISHLIST PAGE - SAVED ARTWORKS GALLERY (HORIZONTAL LIST VIEW)
// =========================================================
include_once('includes/connection.php');
global $connect;

$currentPage = 'wishlist.php';
include_once('includes/nav.php');

$isLoggedIn = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
$userId = $isLoggedIn ? intval($_SESSION['user_id']) : 0;

$wishlistProducts = [];
if ($isLoggedIn && $userId > 0) {
    $query = "SELECT p.*, c.name as category_name, w.id as wishlist_id, w.created_at as saved_at 
              FROM wishlist w 
              JOIN products p ON w.product_id = p.id 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE w.user_id = ? AND p.status = 1 
              ORDER BY w.id DESC";
    $stmt = mysqli_prepare($connect, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $wishlistProducts[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!-- Hero Top Section -->
<section class="wishlist-hero-section">
    <div class="wishlist-hero-overlay"></div>
    <div class="container position-relative z-2 wishlist-hero-content text-center">
        <span class="badge rounded-pill px-3 py-1 text-uppercase fw-semibold mb-2" style="background: rgba(212, 175, 55, 0.2); color: #DFBA5A; border: 1px solid rgba(212, 175, 55, 0.35); font-size: 0.72rem; letter-spacing: 1px;">
            <i class="fa-solid fa-heart me-1"></i> My Curated Favorites
        </span>
        <h1 class="wishlist-title">
            Your Saved <span class="text-gold-gradient">Artworks Gallery</span>
        </h1>
        <p class="wishlist-subtitle mx-auto mb-0">
            A personalized collection of your favorite handcrafted clay sculptures, sacred idols, and terracotta creations.
        </p>
    </div>
</section>

<!-- Main Gallery Content -->
<div class="wishlist-container">

    <!-- Header Stats Bar -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mb-4 pb-3 border-bottom border-light-subtle gap-2">
        <div>
            <h4 class="font-serif fw-bold mb-1" style="font-family: 'Playfair Display', serif; color: #1A1612;">
                Saved Pieces (<span id="wishlistHeaderCount"><?= count($wishlistProducts) ?></span>)
            </h4>
            <p class="text-muted small mb-0">Manage your saved pieces or commission direct orders.</p>
        </div>
        <div>
            <a href="collection.php" class="btn btn-outline-dark rounded-pill px-4 py-2 small fw-semibold" style="font-size: 0.85rem; border-color: #E2DDD5;">
                <i class="fa-solid fa-plus me-1"></i> Add More Artworks
            </a>
        </div>
    </div>

    <!-- Logged Out Warning State -->
    <?php if (!$isLoggedIn): ?>
        <div class="empty-wishlist-card">
            <div class="empty-icon-circle">
                <i class="fa-solid fa-user-lock"></i>
            </div>
            <h3 class="empty-title">Please Log In to View Wishlist</h3>
            <p class="empty-desc">
                Log in to your account to save your favorite artisan pieces and access them across all your devices.
            </p>
            <button type="button" class="btn-gold-action" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="fa-solid fa-right-to-bracket"></i> Log In to Account
            </button>
        </div>

    <!-- Logged In: Horizontal Row List OR Empty State -->
    <?php else: ?>
        <div id="wishlistGridContainer" class="d-flex flex-column gap-3 <?= empty($wishlistProducts) ? 'd-none' : '' ?>">
            <?php foreach ($wishlistProducts as $prod):
                $imgSrc = !empty($prod['image']) ? "uploads/" . htmlspecialchars($prod['image']) : "asset/image/default-image.jpg";
                $catName = !empty($prod['category_name']) ? $prod['category_name'] : "Handcrafted Art";
                $price = floatval($prod['price']);
                $discount = floatval($prod['discount_percentage'] ?? 0);
                $finalPrice = ($discount > 0) ? $price - ($price * ($discount / 100)) : $price;
            ?>
                <div class="wishlist-item-wrapper" id="wishlist-card-<?= $prod['id'] ?>">
                    <div class="wishlist-row-card">
                        <!-- Thumbnail Image Box -->
                        <div class="wishlist-thumb-box">
                            <span class="wishlist-thumb-badge">Available</span>
                            <?php if ($discount > 0): ?>
                                <span class="wishlist-discount-badge"><?= intval($discount) ?>% OFF</span>
                            <?php endif; ?>
                            <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($prod['name']) ?>" onerror="this.src='asset/image/default-image.jpg';">
                        </div>

                        <!-- Product Information Box -->
                        <div class="wishlist-info-box">
                            <span class="wishlist-cat-tag"><?= htmlspecialchars($catName) ?></span>
                            <a href="product_details.php?id=<?= $prod['id'] ?>" class="wishlist-prod-title" title="<?= htmlspecialchars($prod['name']) ?>">
                                <?= htmlspecialchars($prod['name']) ?>
                            </a>
                            <div class="wishlist-price-wrap">
                                <span class="wishlist-price-current">₹<?= number_format($finalPrice, 2) ?></span>
                                <?php if ($discount > 0): ?>
                                    <span class="wishlist-price-old">₹<?= number_format($price, 2) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Action Buttons Box -->
                        <div class="wishlist-actions-box">
                            <a href="product_details.php?id=<?= $prod['id'] ?>" class="btn-explore-artwork">
                                Explore Artwork <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                            <button type="button" class="btn-trash-row" title="Remove from Wishlist" onclick="removeFromWishlistPage(<?= $prod['id'] ?>)">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty Wishlist State -->
        <div id="emptyWishlistState" class="empty-wishlist-card <?= !empty($wishlistProducts) ? 'd-none' : '' ?>">
            <div class="empty-icon-circle">
                <i class="fa-solid fa-heart-crack"></i>
            </div>
            <h3 class="empty-title">Your Gallery is Empty</h3>
            <p class="empty-desc">
                You haven't saved any handcrafted creations to your wishlist yet. Explore our curated collections to save your favorite artisan pieces.
            </p>
            <a href="collection.php" class="btn-gold-action">
                <i class="fa-solid fa-compass"></i> Explore Art Collections
            </a>
        </div>
    <?php endif; ?>

</div>

<!-- Remove Item Handler for wishlist.php -->
<script>
    function removeFromWishlistPage(productId) {
        const cardWrapper = document.getElementById(`wishlist-card-${productId}`);
        if (cardWrapper) {
            cardWrapper.classList.add('removing');
        }

        fetch('actions/wishlist_action.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `product_id=${productId}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, "info");

                    setTimeout(() => {
                        if (cardWrapper) {
                            cardWrapper.remove();
                        }

                        // Update header count & badge
                        const headerCount = document.getElementById('wishlistHeaderCount');
                        if (headerCount) {
                            headerCount.innerText = data.wishlist_count;
                        }

                        const navBadges = document.querySelectorAll('.wishlist-badge-count, #wishlistNavBadge, #wishlistMobileNavBadge');
                        navBadges.forEach(b => {
                            if (b && typeof data.wishlist_count !== 'undefined') {
                                b.innerText = data.wishlist_count;
                            }
                        });

                        // Check if grid is now empty
                        const remainingCards = document.querySelectorAll('.wishlist-item-wrapper');
                        if (remainingCards.length === 0) {
                            document.getElementById('wishlistGridContainer')?.classList.add('d-none');
                            document.getElementById('emptyWishlistState')?.classList.remove('d-none');
                        }
                    }, 350);
                } else {
                    if (cardWrapper) {
                        cardWrapper.classList.remove('removing');
                    }
                    showToast(data.message || "Failed to remove item.", "error");
                }
            })
            .catch(err => {
                if (cardWrapper) {
                    cardWrapper.classList.remove('removing');
                }
                showToast("Connection error. Please try again.", "error");
            });
    }
</script>

<?php include_once('includes/footer.php'); ?>
