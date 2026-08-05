<?php
// =========================================================
// WISHLIST PAGE - SAVED ARTWORKS GALLERY (HORIZONTAL LIST VIEW)
// =========================================================
include_once('connection.php');
global $connect;

$currentPage = 'wishlist.php';
include_once('nav.php');

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

<style>
    :root {
        --bg-theme: #FAF8F5;
        --text-dark: #1A1612;
        --gold-primary: #C59B27;
        --gold-light: #DFBA5A;
        --border-subtle: #EAE6DF;
    }

    body {
        background-color: var(--bg-theme);
        color: var(--text-dark);
        font-family: 'Outfit', sans-serif;
    }

    /* Hero Banner Section */
    .wishlist-hero-section {
        position: relative;
        width: 100%;
        min-height: 250px;
        padding: 95px 0 35px 0;
        background: url('asset/image/artisan_craft_banner.png') no-repeat center center / cover;
        overflow: hidden;
        border-bottom: 2px solid rgba(212, 175, 55, 0.3);
    }

    .wishlist-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(26, 22, 18, 0.88) 0%, rgba(26, 22, 18, 0.7) 50%, rgba(18, 15, 12, 0.92) 100%);
        z-index: 1;
    }

    .wishlist-hero-content {
        position: relative;
        z-index: 2;
    }

    .wishlist-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.8rem, 3.8vw, 2.6rem);
        font-weight: 700;
        color: #FFFFFF;
        margin-bottom: 8px;
    }

    .text-gold-gradient {
        background: linear-gradient(135deg, #FFF0BD 0%, #DFBA5A 50%, #C59B27 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-style: italic;
    }

    .wishlist-subtitle {
        font-size: clamp(0.85rem, 1.3vw, 1rem);
        color: #E2DDD5;
        max-width: 600px;
    }

    /* Wishlist Main Container */
    .wishlist-container {
        max-width: 1060px;
        margin: 0 auto;
        padding: 40px 20px 80px 20px;
    }

    /* Horizontal Wishlist Row Card */
    .wishlist-row-card {
        background: #FFFFFF;
        border: 1px solid var(--border-subtle);
        border-radius: 20px;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        gap: 24px;
        transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 4px 18px rgba(42, 36, 29, 0.02);
        position: relative;
    }

    .wishlist-row-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(197, 155, 39, 0.12);
        border-color: rgba(212, 175, 55, 0.4);
    }

    /* Thumbnail Box */
    .wishlist-thumb-box {
        position: relative;
        width: 120px;
        height: 120px;
        flex-shrink: 0;
        border-radius: 14px;
        overflow: hidden;
        background: #F9F7F3;
        border: 1px solid #F0ECE4;
    }

    .wishlist-thumb-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .wishlist-row-card:hover .wishlist-thumb-box img {
        transform: scale(1.08);
    }

    /* Badges on Thumbnail */
    .wishlist-thumb-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        background: #DEF7EC;
        border: 1px solid #B3E3CE;
        color: #03543F;
        padding: 3px 8px;
        border-radius: 50px;
        font-size: 0.68rem;
        font-weight: 700;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .wishlist-thumb-badge::before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 6px;
        background-color: #0E9F6E;
        border-radius: 50%;
    }

    .wishlist-discount-badge {
        position: absolute;
        bottom: 8px;
        right: 8px;
        background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%);
        color: #1A1612;
        font-size: 0.68rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 50px;
        box-shadow: 0 2px 8px rgba(197, 155, 39, 0.3);
        z-index: 2;
    }

    /* Content Info Section */
    .wishlist-info-box {
        flex-grow: 1;
        min-width: 0;
    }

    .wishlist-cat-tag {
        font-size: 0.73rem;
        color: #9B8A74;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-weight: 700;
        margin-bottom: 4px;
        display: block;
    }

    .wishlist-prod-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1A1612;
        margin-bottom: 6px;
        text-decoration: none;
        display: block;
        transition: color 0.3s ease;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .wishlist-row-card:hover .wishlist-prod-title {
        color: var(--gold-primary);
    }

    .wishlist-price-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .wishlist-price-current {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--gold-primary);
        font-family: 'Outfit', sans-serif;
    }

    .wishlist-price-old {
        font-size: 0.88rem;
        color: #A59E96;
        text-decoration: line-through;
        font-family: 'Outfit', sans-serif;
    }

    /* Actions Section */
    .wishlist-actions-box {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .btn-explore-artwork {
        background: #FDFBF7;
        color: #4A4036;
        border: 1px solid #EBE5D9;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 10px 22px;
        border-radius: 50px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.35s ease;
        white-space: nowrap;
    }

    .wishlist-row-card:hover .btn-explore-artwork {
        background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%);
        color: #FFFFFF;
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(197, 155, 39, 0.25);
    }

    .btn-trash-row {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #FFFFFF;
        color: #E63946;
        border: 1px solid rgba(230, 57, 70, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .btn-trash-row:hover {
        background: #E63946;
        color: #FFFFFF;
        border-color: transparent;
        transform: scale(1.1);
        box-shadow: 0 6px 18px rgba(230, 57, 70, 0.35);
    }

    /* Mobile Responsive Stacking (< 768px) */
    @media (max-width: 767.98px) {
        .wishlist-row-card {
            flex-wrap: wrap;
            padding: 14px 16px;
            gap: 14px;
        }

        .wishlist-thumb-box {
            width: 90px;
            height: 90px;
            border-radius: 12px;
        }

        .wishlist-info-box {
            width: calc(100% - 104px);
        }

        .wishlist-prod-title {
            font-size: 1.1rem;
            white-space: normal;
        }

        .wishlist-price-current {
            font-size: 1.1rem;
        }

        .wishlist-actions-box {
            width: 100%;
            justify-content: space-between;
            margin-top: 4px;
            padding-top: 12px;
            border-top: 1px solid #F3EFEA;
        }

        .btn-explore-artwork {
            flex-grow: 1;
            justify-content: center;
            padding: 9px 16px;
            font-size: 0.85rem;
        }
    }

    /* Empty Wishlist Card */
    .empty-wishlist-card {
        background: #FFFFFF;
        border-radius: 24px;
        border: 1px solid var(--border-subtle);
        box-shadow: 0 10px 40px rgba(42, 36, 29, 0.04);
        padding: 60px 30px;
        text-align: center;
        max-width: 620px;
        margin: 40px auto;
    }

    .empty-icon-circle {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(197, 155, 39, 0.1);
        border: 2px solid rgba(197, 155, 39, 0.25);
        color: var(--gold-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 24px auto;
        box-shadow: 0 8px 25px rgba(197, 155, 39, 0.15);
    }

    .empty-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.85rem;
        font-weight: 700;
        color: #1A1612;
        margin-bottom: 12px;
    }

    .empty-desc {
        color: #6C6356;
        font-size: 0.98rem;
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .btn-gold-action {
        background: linear-gradient(135deg, #DFBA5A 0%, #C59B27 100%);
        color: #1A1612;
        font-weight: 700;
        padding: 13px 34px;
        border-radius: 50px;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.35s ease;
        box-shadow: 0 6px 20px rgba(197, 155, 39, 0.3);
    }

    .btn-gold-action:hover {
        color: #1A1612;
        transform: translateY(-3px);
        box-shadow: 0 10px 28px rgba(197, 155, 39, 0.45);
    }

    /* Wishlist Item Removal Animation */
    .wishlist-item-wrapper {
        transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
    }

    .wishlist-item-wrapper.removing {
        opacity: 0;
        transform: scale(0.92) translateX(-30px);
    }
</style>

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

        fetch('wishlist_action.php', {
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

<?php include_once('footer.php'); ?>
