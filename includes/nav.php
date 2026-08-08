<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once(__DIR__ . '/connection.php');
global $connect;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidda Art Creation</title>
    <!-- Bootstrap 5 CSS -->
    <link href="asset/bootstrap-5.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Outfit and Playfair Display -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    <!-- Google Fonts (Playfair Display for headings, Inter for body text) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- AOS Animation Library CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="asset/image/logo.png">
    <link rel="shortcut icon" type="image/png" href="asset/image/logo.png">
    <link rel="apple-touch-icon" href="asset/image/logo.png">

    <!-- Master Global Scoped Stylesheet -->
    <link rel="stylesheet" href="asset/css/style.css">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg custom-navbar fixed-top" id="mainNavbar">
        <div class="container px-lg-4">

            <?php
            $currentPage = basename($_SERVER['PHP_SELF']);
            $isLoggedIn = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
            $userName = $isLoggedIn ? $_SESSION['user_name'] : '';
            $userEmail = $isLoggedIn ? $_SESSION['user_email'] : '';
            $userImage = ($isLoggedIn && !empty($_SESSION['user_image'])) ? $_SESSION['user_image'] : 'asset/image/default-image.jpg';

            // Fetch initial wishlist count & item IDs for active user
            $initialWishlistCount = 0;
            $userWishlistIds = [];
            if ($isLoggedIn && isset($_SESSION['user_id'])) {
                $uId = intval($_SESSION['user_id']);
                $wlRes = mysqli_query($connect, "SELECT product_id FROM wishlist WHERE user_id = $uId");
                if ($wlRes) {
                    while ($wlRow = mysqli_fetch_assoc($wlRes)) {
                        $userWishlistIds[] = intval($wlRow['product_id']);
                    }
                    $initialWishlistCount = count($userWishlistIds);
                }
            }
            ?>

            <!-- Left Side: Logo -->
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="asset/image/logo.png" alt="Logo">
            </a>

            <!-- Mobile Profile Dropdown (Only visible on Mobile when Logged In) -->
            <?php if ($isLoggedIn): ?>
                <div class="dropdown d-block d-lg-none ms-auto me-3" id="mobileProfileDropdown">
                    <button class="profile-container text-decoration-none dropdown-toggle border-0" type="button" id="mobileProfileMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo htmlspecialchars($userImage); ?>" alt="Profile" class="profile-pic" id="mobileNavProfilePic">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end profile-dropdown-menu" aria-labelledby="mobileProfileMenuLink">
                        <li class="dropdown-header">
                            <h6 class="mb-0" style="font-family: 'Outfit', sans-serif; font-weight: 600; color: #3A3530;"><?php echo htmlspecialchars($userName); ?></h6>
                            <small class="text-muted"><?php echo htmlspecialchars($userEmail); ?></small>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item <?php echo ($currentPage == 'profile.php') ? 'active' : ''; ?>" href="profile.php"><i class="fa-regular fa-user me-2"></i>My Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><button class="dropdown-item text-danger border-0 bg-transparent w-100 text-start" type="button" onclick="handleLogout()"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</button></li>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler custom-toggler <?php echo $isLoggedIn ? '' : 'ms-auto'; ?>" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>

            <!-- Offcanvas Sidebar Menu -->
            <div class="offcanvas offcanvas-lg offcanvas-start" tabindex="-1" id="navbarNav" aria-labelledby="navbarNavLabel" style="background-color: #F5F2ED;">
                <!-- Header for mobile menu only -->
                <div class="offcanvas-header d-lg-none">
                    <img src="asset/image/logo.png" alt="Logo" style="max-height: 45px;">
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close" style="filter: brightness(0.2);"></button>
                </div>

                <div class="offcanvas-body">
                    <!-- Middle: Navigation Links -->
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage == 'index.php' || $currentPage == '') ? 'active' : ''; ?>" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage == 'aboutus.php') ? 'active' : ''; ?>" href="aboutus.php">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage == 'collection.php') ? 'active' : ''; ?>" href="collection.php">Collections</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage == 'contact.php') ? 'active' : ''; ?>" href="contact.php">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentPage == 'faq.php') ? 'active' : ''; ?>" href="faq.php">FAQ</a>
                        </li>
                    </ul>

                    <!-- Right Side: Wishlist, Login & Create Account / Profile -->
                    <div class="d-flex align-items-lg-center flex-column flex-lg-row gap-3 mt-3 mt-lg-0">

                        <!-- Wishlist Icon (Desktop only) -->
                        <a href="wishlist.php" class="icon-link position-relative text-decoration-none d-none d-lg-inline-flex" title="Saved Artworks">
                            <i class="<?php echo ($initialWishlistCount > 0) ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart'; ?>" id="navWishlistIcon"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill custom-badge wishlist-badge-count" id="wishlistNavBadge">
                                <?php echo $initialWishlistCount; ?>
                            </span>
                        </a>

                        <!-- Auth Buttons (Visible when Logged Out) -->
                        <?php if (!$isLoggedIn): ?>
                            <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 w-100 w-lg-auto" id="authButtons">
                                <!-- Login Button -->
                                <button type="button" class="btn btn-login" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>

                                <!-- Create an Account Button -->
                                <button type="button" class="btn btn-register" data-bs-toggle="modal" data-bs-target="#registerModal">Create an Account</button>
                            </div>
                        <?php endif; ?>

                        <!-- Profile Dropdown (Visible when Logged In on Desktop only) -->
                        <?php if ($isLoggedIn): ?>
                            <div class="dropdown d-none d-lg-block" id="profileDropdown">
                                <button class="profile-container text-decoration-none dropdown-toggle border-0" type="button" id="profileMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="<?php echo htmlspecialchars($userImage); ?>" alt="Profile" class="profile-pic" id="navProfilePic">
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end profile-dropdown-menu" aria-labelledby="profileMenuLink">
                                    <li class="dropdown-header">
                                        <h6 class="mb-0" id="profileName" style="font-family: 'Outfit', sans-serif; font-weight: 600; color: #3A3530;"><?php echo htmlspecialchars($userName); ?></h6>
                                        <small class="text-muted" id="profileEmail"><?php echo htmlspecialchars($userEmail); ?></small>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item <?php echo ($currentPage == 'profile.php') ? 'active' : ''; ?>" href="profile.php"><i class="fa-regular fa-user me-2"></i>My Profile</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><button class="dropdown-item text-danger border-0 bg-transparent w-100 text-start" type="button" id="btnLogout"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</button></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>



    <!-- Toast Notifications Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Login Modal -->
    <div class="modal fade auth-modal" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body-split">
                    <!-- Left Pane -->
                    <div class="modal-split-left">
                        <img src="asset/image/logo.png" alt="Logo" class="brand-logo">
                        <div>
                            <h3>Welcome <span>Back</span></h3>
                            <p class="desc">Continue your journey into the world of fine art and exclusive creations. Your curated gallery awaits your arrival.</p>

                            <div class="feature-list">
                                <div class="feature-item">
                                    <div class="feature-icon-circle">
                                        <i class="fa-solid fa-palette"></i>
                                    </div>
                                    <div>
                                        <div class="feature-text">Artisan Quality</div>
                                    </div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-icon-circle">
                                        <i class="fa-solid fa-globe"></i>
                                    </div>
                                    <div>
                                        <div class="feature-text">Global Gallery</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="left-footer">
                            Crafted for the connoisseur
                        </div>
                    </div>

                    <!-- Right Pane -->
                    <div class="modal-split-right">
                        <button type="button" class="modal-close-btn" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>

                        <!-- SIGN IN VIEW -->
                        <div id="loginModalSignInView">
                            <h2>Sign In</h2>
                            <p class="subtitle">Enter your credentials to login.</p>

                            <form id="loginForm" onsubmit="handleLoginSubmit(event)">
                                <div class="form-group-custom">
                                    <label class="form-label-custom">Email Address</label>
                                    <div class="input-wrapper">
                                        <i class="fa-regular fa-envelope input-icon-left"></i>
                                        <input type="email" id="loginEmail" name="email" class="input-custom" placeholder="name@example.com" required>
                                    </div>
                                </div>

                                <div class="form-group-custom">
                                    <label class="form-label-custom">Password</label>
                                    <div class="input-wrapper">
                                        <i class="fa-solid fa-lock input-icon-left"></i>
                                        <input type="password" id="loginPassword" name="password" class="input-custom" placeholder="••••••••" required>
                                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('loginPassword', this)">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="text-end mt-2">
                                        <a href="#" class="forgot-password-link text-decoration-none" onclick="event.preventDefault(); showLoginView('reset');" style="font-size: 0.82rem; font-weight: 600; color: #B8860B;">Forgot Password?</a>
                                    </div>
                                </div>

                                <button type="submit" class="btn-auth-action mt-2">
                                    Sign In <i class="fa-solid fa-arrow-right-long ms-1"></i>
                                </button>
                            </form>

                            <div class="mt-4">
                                <p class="auth-footer-text">New to Siddha Art? <a href="#" onclick="switchModals('loginModal', 'registerModal')">Create an account</a></p>
                            </div>
                        </div>

                        <!-- RESET PASSWORD VIEW (Matching Screenshot) -->
                        <div id="loginModalResetView" style="display: none;">
                            <h2>Reset Password</h2>
                            <p class="subtitle">Update your password directly below.</p>

                            <form id="resetPasswordForm" onsubmit="handleResetPasswordSubmit(event)">
                                <div class="form-group-custom mb-3">
                                    <label class="form-label-custom">Email Address</label>
                                    <div class="input-wrapper">
                                        <i class="fa-regular fa-envelope input-icon-left"></i>
                                        <input type="email" id="resetEmail" name="email" class="input-custom" placeholder="name@example.com" required>
                                    </div>
                                </div>

                                <div class="form-group-custom mb-3">
                                    <label class="form-label-custom">New Password</label>
                                    <div class="input-wrapper">
                                        <i class="fa-solid fa-lock input-icon-left"></i>
                                        <input type="password" id="resetNewPassword" name="new_password" class="input-custom" placeholder="••••••••" required>
                                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('resetNewPassword', this)">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group-custom mb-3">
                                    <label class="form-label-custom">Confirm Password</label>
                                    <div class="input-wrapper">
                                        <i class="fa-solid fa-shield-halved input-icon-left"></i>
                                        <input type="password" id="resetConfirmPassword" name="confirm_password" class="input-custom" placeholder="••••••••" required>
                                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('resetConfirmPassword', this)">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <button type="submit" class="btn-auth-action mt-2" id="btnResetSubmit">
                                    Reset Password <i class="fa-solid fa-rotate-right ms-1"></i>
                                </button>
                            </form>

                            <div class="mt-4 text-center">
                                <a href="#" onclick="event.preventDefault(); showLoginView('signin');" class="text-decoration-none fw-semibold" style="color: #B8860B; font-size: 0.88rem;">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Sign In
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade auth-modal" id="registerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body-split">
                    <!-- Left Pane -->
                    <div class="modal-split-left">
                        <img src="asset/image/logo.png" alt="Logo" class="brand-logo">
                        <div>
                            <h3>Discover Authentic <span>Artistry</span></h3>
                            <p class="desc">Explore and shop our premium collection of handcrafted clay idols, exclusive jewellery, and terracotta decor.</p>

                            <div class="feature-list">
                                <div class="feature-item">
                                    <div class="feature-icon-circle">
                                        <i class="fa-regular fa-star"></i>
                                    </div>
                                    <div>
                                        <div class="feature-text">SAVE YOUR FAVORITES</div>
                                        <div class="feature-subtext">Easily save the handcrafted pieces you love to your personal wishlist and purchase them anytime.</div>
                                    </div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-icon-circle">
                                        <i class="fa-solid fa-palette"></i>
                                    </div>
                                    <div>
                                        <div class="feature-text">CUSTOM ART ORDERS</div>
                                        <div class="feature-subtext">Get priority access to request personalized sculptures, unique idols, and custom-crafted items just for you.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="left-footer">
                            © 2026 Siddha Art Creation
                        </div>
                    </div>

                    <!-- Right Pane -->
                    <div class="modal-split-right">
                        <button type="button" class="modal-close-btn" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
                        <div>
                            <h2>Create Account</h2>
                            <p class="subtitle">Begin your seamless shopping experience today.</p>

                            <form id="registerForm" onsubmit="handleRegisterSubmit(event)">
                                <!-- Name and Email -->
                                <div class="form-group-custom">
                                    <label class="form-label-custom">Full Name</label>
                                    <div class="input-wrapper">
                                        <i class="fa-regular fa-user input-icon-left"></i>
                                        <input type="text" id="registerName" name="name" class="input-custom" placeholder="e.g., Siddha Art Creation" required>
                                    </div>
                                </div>

                                <div class="form-group-custom">
                                    <label class="form-label-custom">Email Address</label>
                                    <div class="input-wrapper position-relative">
                                        <i class="fa-regular fa-envelope input-icon-left"></i>
                                        <input type="email" id="registerEmail" name="email" class="input-custom" placeholder="you@example.com" required style="padding-right: 95px;">
                                        <button type="button" class="btn-input-action" id="btnSendOtp" onclick="handleSendOtp()">Verify</button>
                                        <i class="fa-solid fa-circle-check text-success" id="emailVerifiedCheck" style="display: none; position: absolute; right: 15px; top: 50%; transform: translateY(-50%); font-size: 1.25rem; z-index: 10;"></i>
                                    </div>
                                </div>

                                <!-- OTP Inputs Section (Hidden initially) -->
                                <div id="otpSection" style="display: none; margin-top: 20px;">
                                    <label class="form-label-custom">Enter 6-Digit OTP</label>
                                    <div class="otp-inputs-container mb-3">
                                        <input type="text" class="otp-input" maxlength="1" onkeyup="moveOtpFocus(this, 'otp2')" id="otp1">
                                        <input type="text" class="otp-input" maxlength="1" onkeyup="moveOtpFocus(this, 'otp3')" id="otp2">
                                        <input type="text" class="otp-input" maxlength="1" onkeyup="moveOtpFocus(this, 'otp4')" id="otp3">
                                        <input type="text" class="otp-input" maxlength="1" onkeyup="moveOtpFocus(this, 'otp5')" id="otp4">
                                        <input type="text" class="otp-input" maxlength="1" onkeyup="moveOtpFocus(this, 'otp6')" id="otp5">
                                        <input type="text" class="otp-input" maxlength="1" onkeyup="moveOtpFocus(this, null)" id="otp6">
                                    </div>
                                    <button type="button" class="btn-auth-action" id="btnConfirmOtp" onclick="handleConfirmOtp()" style="background-color: #12110F; margin-top: 10px;">Confirm OTP</button>

                                    <div class="text-center mt-3" id="otpTimerWrapper">
                                        <span id="otpTimerText" style="font-size: 0.82rem; color: #8C857E;">
                                            Resend OTP in <strong id="otpCountdown">05:00</strong>
                                        </span>
                                        <button type="button" class="btn btn-link text-decoration-none p-0" id="btnResendOtp" onclick="handleSendOtp()" style="display: none; font-size: 0.82rem; color: #B8860B; font-weight: 600;">Resend OTP</button>
                                    </div>
                                </div>

                                <!-- Hidden registration fields shown after OTP verified -->
                                <div class="hidden-auth-fields" id="hiddenRegisterFields">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Password</label>
                                        <div class="input-wrapper">
                                            <i class="fa-solid fa-lock input-icon-left"></i>
                                            <input type="password" id="registerPassword" name="password" class="input-custom" placeholder="••••••••" required>
                                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('registerPassword', this)">
                                                <i class="fa-regular fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Phone Number</label>
                                        <div class="input-wrapper">
                                            <i class="fa-solid fa-phone input-icon-left"></i>
                                            <input type="tel" id="registerPhone" name="phone" class="input-custom" placeholder="+1 (555) 000-0000">
                                        </div>
                                    </div>

                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Profile Image (Optional)</label>
                                        <div class="input-wrapper">
                                            <i class="fa-regular fa-image input-icon-left"></i>
                                            <input type="file" id="registerProfileImage" name="profile_image" class="input-custom" accept="image/*" style="padding-left: 45px;">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn-auth-action" id="btnFinalRegister">
                                        Create <i class="fa-solid fa-arrow-right-long ms-1"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div id="registerFooterLinks">
                            <p class="auth-footer-text">Already have an account? <a href="#" onclick="switchModals('registerModal', 'loginModal')">Sign in here</a></p>
                            <div class="modal-sub-links">
                                <a href="#">Shopping Policy</a>
                                <span>•</span>
                                <a href="#">Privacy</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Mobile Bottom Navigation Bar (Visible only on mobile screens) -->
    <?php
    $curNavPage = isset($currentPage) ? $currentPage : basename($_SERVER['PHP_SELF']);
    ?>
    <div class="mobile-bottom-nav">
        <!-- Home -->
        <a href="index.php" class="mobile-bottom-nav-item <?php echo ($curNavPage == 'index.php' || $curNavPage == '') ? 'active' : ''; ?>" id="bottomNavHome">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
        </a>
        <!-- Shop / Explore / Collections -->
        <a href="collection.php" class="mobile-bottom-nav-item <?php echo ($curNavPage == 'collection.php') ? 'active' : ''; ?>" id="bottomNavShop">
            <i class="fa-solid fa-grip"></i>
            <span>Explore</span>
        </a>
        <!-- Wishlist -->
        <a href="wishlist.php" class="mobile-bottom-nav-item <?php echo ($curNavPage == 'wishlist.php') ? 'active' : ''; ?>" id="bottomNavWishlist">
            <div class="position-relative d-inline-flex">
                <i class="<?php echo ($initialWishlistCount > 0) ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart'; ?>" id="mobileNavWishlistIcon"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill custom-badge wishlist-badge-count" id="wishlistMobileNavBadge" style="font-size: 0.58rem; padding: 2px 5px;">
                    <?php echo $initialWishlistCount; ?>
                </span>
            </div>
            <span>Wishlist</span>
        </a>
        <!-- Account -->
        <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true): ?>
            <a href="profile.php" class="mobile-bottom-nav-item <?php echo ($curNavPage == 'profile.php') ? 'active' : ''; ?>" id="bottomNavAccount">
                <i class="fa-solid fa-user"></i>
                <span>Account</span>
            </a>
        <?php else: ?>
            <button type="button" class="mobile-bottom-nav-item <?php echo ($curNavPage == 'profile.php' || $curNavPage == 'login.php') ? 'active' : ''; ?>" id="bottomNavAccount" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="fa-solid fa-user"></i>
                <span>Account</span>
            </button>
        <?php endif; ?>
    </div>



    <!-- Custom Script for Navbar States and Modals -->
    <script>
        let otpTimerInterval = null;

        document.addEventListener("DOMContentLoaded", function() {
            const navbar = document.getElementById('mainNavbar');

            // Scroll logic
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('navbar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                }
            });

            // Bind logout button
            const btnLogout = document.getElementById('btnLogout');
            if (btnLogout) {
                btnLogout.addEventListener('click', handleLogout);
            }

            // Bind backspace listeners for OTP
            document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && input.value === '' && index > 0) {
                        inputs[index - 1].focus();
                    }
                });
            });
        });

        // Toggle Password Input Visibility
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Modal Switching Logic
        function switchModals(fromId, toId) {
            // Hide active modal
            const activeModalEl = document.getElementById(fromId);
            const activeModal = bootstrap.Modal.getInstance(activeModalEl) || new bootstrap.Modal(activeModalEl);
            activeModal.hide();

            // Show target modal after transition delay
            setTimeout(() => {
                const targetModalEl = document.getElementById(toId);
                const targetModal = new bootstrap.Modal(targetModalEl);
                targetModal.show();
            }, 350);
        }

        // Custom Toast Notification trigger
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `custom-toast ${type}`;

            let icon = '<i class="fa-solid fa-circle-check"></i>';
            if (type === 'error') {
                icon = '<i class="fa-solid fa-circle-xmark"></i>';
            } else if (type === 'info') {
                icon = '<i class="fa-solid fa-circle-info"></i>';
            }

            toast.innerHTML = `${icon} <span>${message}</span>`;
            container.appendChild(toast);

            // Remove toast from DOM after animations complete
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }

        // OTP Input Focus Management
        function moveOtpFocus(current, nextId) {
            if (current.value.length === 1 && nextId) {
                document.getElementById(nextId).focus();
            }
        }

        // Timer Countdown for Resend OTP (5 min = 300 seconds)
        function startOtpTimer(durationSeconds) {
            if (otpTimerInterval) {
                clearInterval(otpTimerInterval);
            }

            const timerText = document.getElementById('otpTimerText');
            const resendBtn = document.getElementById('btnResendOtp');
            const countdownDisplay = document.getElementById('otpCountdown');

            timerText.style.display = "inline";
            resendBtn.style.setProperty('display', 'none', 'important');

            let timeRemaining = durationSeconds;

            function updateTimerDisplay() {
                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;
                countdownDisplay.innerText = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }

            updateTimerDisplay();

            otpTimerInterval = setInterval(() => {
                timeRemaining--;
                if (timeRemaining <= 0) {
                    clearInterval(otpTimerInterval);
                    timerText.style.display = "none";
                    resendBtn.style.setProperty('display', 'inline-block', 'important');
                } else {
                    updateTimerDisplay();
                }
            }, 1000);
        }

        // Step 1: Send OTP handler (AJAX POST)
        function handleSendOtp() {
            const name = document.getElementById('registerName').value.trim();
            const email = document.getElementById('registerEmail').value.trim();
            const btnSend = document.getElementById('btnSendOtp');
            const btnResend = document.getElementById('btnResendOtp');

            if (!name || !email) {
                showToast("Please enter your Full Name and Email Address first.", "error");
                return;
            }

            const formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);

            // Show sending state
            if (btnResend && btnResend.style.display !== 'none') {
                btnResend.disabled = true;
                btnResend.innerText = "Sending...";
            } else {
                btnSend.disabled = true;
                btnSend.innerText = "Sending...";
            }

            fetch('register.php?action=send_otp', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    // Restore buttons
                    if (btnResend) {
                        btnResend.disabled = false;
                        btnResend.innerText = "Resend OTP";
                    }
                    btnSend.innerText = "Verify";

                    if (data.status === 'success') {
                        document.getElementById('registerName').disabled = true;
                        document.getElementById('registerEmail').readOnly = true;

                        // Slide/fade open OTP input block
                        document.getElementById('otpSection').style.display = "block";

                        showToast(data.message, "success");

                        // Clear any previous otp inputs
                        document.querySelectorAll('.otp-input').forEach(input => input.value = "");

                        // Start 5-minute countdown
                        startOtpTimer(300);

                        // Autofocus first digit box
                        setTimeout(() => {
                            document.getElementById('otp1').focus();
                        }, 100);
                    } else {
                        showToast(data.message, "error");
                        if (btnResend && btnResend.style.display !== 'none') {
                            // Keep resend enabled
                        } else {
                            btnSend.disabled = false;
                        }
                    }
                })
                .catch(err => {
                    showToast("Failed to connect to authentication server. Please try again.", "error");
                    if (btnResend) {
                        btnResend.disabled = false;
                        btnResend.innerText = "Resend OTP";
                    }
                    btnSend.disabled = false;
                    btnSend.innerText = "Verify";
                });
        }

        // Step 2: Confirm OTP handler (AJAX POST)
        function handleConfirmOtp() {
            const email = document.getElementById('registerEmail').value.trim();
            const otpDigits = [
                document.getElementById('otp1').value,
                document.getElementById('otp2').value,
                document.getElementById('otp3').value,
                document.getElementById('otp4').value,
                document.getElementById('otp5').value,
                document.getElementById('otp6').value
            ];

            const otpCode = otpDigits.join("");

            if (otpCode.length < 6) {
                showToast("Please enter the complete 6-digit OTP code.", "error");
                return;
            }

            const formData = new FormData();
            formData.append('email', email);
            formData.append('otp', otpCode);

            const btnConfirm = document.getElementById('btnConfirmOtp');
            btnConfirm.disabled = true;
            btnConfirm.innerText = "Confirming...";

            fetch('register.php?action=verify_otp', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    btnConfirm.disabled = false;
                    btnConfirm.innerText = "Confirm OTP";

                    if (data.status === 'success') {
                        showToast(data.message, "success");

                        // Clear countdown timer
                        if (otpTimerInterval) {
                            clearInterval(otpTimerInterval);
                        }

                        // Hide OTP section
                        document.getElementById('otpSection').style.display = "none";

                        // Hide Verify button & Show Green Tick + Verified Style
                        document.getElementById('btnSendOtp').style.setProperty('display', 'none', 'important');

                        const emailInput = document.getElementById('registerEmail');
                        emailInput.readOnly = true;
                        emailInput.style.paddingRight = "45px";

                        document.getElementById('emailVerifiedCheck').style.display = "block";

                        // Reveal hidden fields (Password, phone, avatar, register button)
                        const hiddenFields = document.getElementById('hiddenRegisterFields');
                        hiddenFields.classList.add('visible');
                    } else {
                        showToast(data.message, "error");
                    }
                })
                .catch(err => {
                    showToast("Failed to verify OTP. Please try again.", "error");
                    btnConfirm.disabled = false;
                    btnConfirm.innerText = "Confirm OTP";
                });
        }

        // Step 3: Register Form submission (AJAX POST)
        function handleRegisterSubmit(event) {
            event.preventDefault();

            const form = document.getElementById('registerForm');
            const formData = new FormData(form);

            // Disabled fields are not gathered by default. Append explicitly:
            formData.append('name', document.getElementById('registerName').value);
            formData.append('email', document.getElementById('registerEmail').value);

            fetch('actions/register_action.php?action=register', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message, "success");

                        // Hide Modal
                        const modalEl = document.getElementById('registerModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();

                        // Reload the page to reflect PHP Session states in navbar
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showToast(data.message, "error");
                    }
                })
                .catch(err => {
                    showToast("Registration failed. Please try again.", "error");
                });
        }

        // Login Form Submission (AJAX POST)
        function handleLoginSubmit(event) {
            event.preventDefault();

            const form = document.getElementById('loginForm');
            const formData = new FormData(form);

            fetch('actions/login_action.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message, "success");

                        // Hide modal
                        const modalEl = document.getElementById('loginModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();

                        // Reload the page to reflect PHP Session states in navbar
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showToast(data.message, "error");
                    }
                })
                .catch(err => {
                    showToast("Login connection failed. Please try again.", "error");
                });
        }

        // Logout handler (AJAX GET)
        function handleLogout() {
            fetch('actions/login_action.php?action=logout')
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        showToast(data.message, "info");

                        // Reload the page to reflect PHP Session states in navbar
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showToast(data.message, "error");
                    }
                })
                .catch(err => {
                    showToast("Logout failed. Please try again.", "error");
                });
        }

        // Toggle between Sign In and Reset Password views inside Login Modal
        function showLoginView(view) {
            const signInView = document.getElementById('loginModalSignInView');
            const resetView = document.getElementById('loginModalResetView');
            if (view === 'reset') {
                if (signInView) signInView.style.display = 'none';
                if (resetView) resetView.style.display = 'block';
            } else {
                if (resetView) resetView.style.display = 'none';
                if (signInView) signInView.style.display = 'block';
            }
        }

        // Handle Reset Password Submit (AJAX POST)
        function handleResetPasswordSubmit(event) {
            event.preventDefault();

            const form = document.getElementById('resetPasswordForm');
            const formData = new FormData(form);
            const btn = document.getElementById('btnResetSubmit');
            const originalText = btn ? btn.innerHTML : 'Reset Password';

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = 'Resetting... <i class="fa-solid fa-spinner fa-spin ms-1"></i>';
            }

            fetch('actions/login_action.php?action=reset_password', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast(data.message, "success");
                    form.reset();
                    setTimeout(() => {
                        showLoginView('signin');
                    }, 1500);
                } else {
                    showToast(data.message, "error");
                }
            })
            .catch(err => {
                showToast("Password reset failed. Please try again.", "error");
            })
            .finally(() => {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            });
        }

        // Automatic active bottom nav state detection
        function setActiveBottomNav() {
            const path = window.location.pathname;
            const page = path.split("/").pop().toLowerCase();

            document.querySelectorAll('.mobile-bottom-nav-item').forEach(item => {
                item.classList.remove('active');
            });

            if (page.includes('index') || page === '') {
                document.getElementById('bottomNavHome')?.classList.add('active');
            } else if (page.includes('collection') || page.includes('menu') || page.includes('shop') || page.includes('product')) {
                document.getElementById('bottomNavShop')?.classList.add('active');
            } else if (page.includes('wishlist')) {
                document.getElementById('bottomNavWishlist')?.classList.add('active');
            } else if (page.includes('profile') || page.includes('account') || page.includes('user') || page.includes('login')) {
                document.getElementById('bottomNavAccount')?.classList.add('active');
            }
        }

        document.addEventListener("DOMContentLoaded", setActiveBottomNav);


        document.addEventListener("DOMContentLoaded", function() {
            // --- Smooth Sliding Hover Effect for Desktop Nav ---
            const navContainer = document.querySelector('.navbar-nav');
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

            if (window.innerWidth >= 992 && navContainer && navLinks.length > 0) {
                // Notun indicator div toiri kora hocche
                const indicator = document.createElement('div');
                indicator.classList.add('nav-active-indicator');
                navContainer.appendChild(indicator);

                // Position set korar function
                function setIndicator(link) {
                    const linkRect = link.getBoundingClientRect();
                    const containerRect = navContainer.getBoundingClientRect();

                    indicator.style.width = `${linkRect.width}px`;
                    indicator.style.height = `${linkRect.height}px`;
                    // Parent er perspective theke position calculate kora
                    indicator.style.transform = `translate(${linkRect.left - containerRect.left}px, ${linkRect.top - containerRect.top}px)`;
                    indicator.style.opacity = '1';
                }

                navLinks.forEach(link => {
                    link.addEventListener('mouseenter', function() {
                        setIndicator(this);
                    });
                });

                navContainer.addEventListener('mouseleave', function() {
                    const activeLink = navContainer.querySelector('.nav-link.active');
                    if (activeLink) {
                        setIndicator(activeLink);
                    } else {
                        indicator.style.opacity = '0';
                    }
                });

                setTimeout(() => {
                    const activeLink = navContainer.querySelector('.nav-link.active');
                    if (activeLink) setIndicator(activeLink);
                }, 200);
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            // Initialize all Bootstrap Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Transparent to Solid Navbar on Scroll
            const mainNav = document.getElementById("mainNavbar") || document.querySelector(".custom-navbar");
            if (mainNav) {
                function handleNavbarScroll() {
                    if (window.scrollY > 50) {
                        mainNav.classList.add("nav-scrolled", "navbar-scrolled");
                    } else {
                        mainNav.classList.remove("nav-scrolled", "navbar-scrolled");
                    }
                }
                window.addEventListener("scroll", handleNavbarScroll);
                handleNavbarScroll(); // Initial check on load
            }
        });

        // =========================================================
        // GLOBAL WISHLIST JS ENGINE
        // =========================================================
        window.isUserLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
        window.userWishlistIds = <?php echo json_encode($userWishlistIds); ?>;

        function toggleWishlist(productId, btnElement) {
            if (!window.isUserLoggedIn) {
                showToast("Please log in to save artworks to your wishlist.", "error");
                const loginModalEl = document.getElementById('loginModal');
                if (loginModalEl) {
                    const modal = bootstrap.Modal.getOrCreateInstance(loginModalEl);
                    modal.show();
                }
                return;
            }

            if (!productId || productId <= 0) return;

            // Visual feedback during request
            if (btnElement) {
                btnElement.style.pointerEvents = 'none';
                btnElement.style.opacity = '0.7';
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
                    if (btnElement) {
                        btnElement.style.pointerEvents = 'auto';
                        btnElement.style.opacity = '1';
                    }

                    if (data.success) {
                        // Update badges across both Desktop & Mobile navigation bars
                        const badges = document.querySelectorAll('.wishlist-badge-count, #wishlistNavBadge, #wishlistMobileNavBadge');
                        badges.forEach(badge => {
                            if (badge && typeof data.wishlist_count !== 'undefined') {
                                badge.innerText = data.wishlist_count;
                            }
                        });

                        // Update Navbar Wishlist Heart Icons (Desktop & Mobile)
                        if (typeof data.wishlist_count !== 'undefined') {
                            const desktopNavIcon = document.getElementById('navWishlistIcon');
                            const mobileNavIcon = document.getElementById('mobileNavWishlistIcon');
                            if (data.wishlist_count > 0) {
                                if (desktopNavIcon) desktopNavIcon.className = 'fa-solid fa-heart text-danger';
                                if (mobileNavIcon) mobileNavIcon.className = 'fa-solid fa-heart text-danger';
                            } else {
                                if (desktopNavIcon) desktopNavIcon.className = 'fa-regular fa-heart';
                                if (mobileNavIcon) mobileNavIcon.className = 'fa-regular fa-heart';
                            }
                        }

                        // Update icon across ALL buttons on the page for this product_id
                        const allMatchingBtns = document.querySelectorAll(`[data-product-id="${productId}"]`);
                        allMatchingBtns.forEach(btn => {
                            const icon = btn.querySelector('i');
                            if (data.action === 'added') {
                                if (icon) {
                                    icon.className = 'fa-solid fa-heart text-danger';
                                }
                                btn.setAttribute('title', 'Remove from Wishlist');
                            } else if (data.action === 'removed') {
                                if (icon) {
                                    icon.className = 'fa-regular fa-heart';
                                }
                                btn.setAttribute('title', 'Add to Wishlist');
                            }
                        });

                        showToast(data.message, data.action === 'added' ? 'success' : 'info');
                    } else {
                        showToast(data.message || "Action failed.", "error");
                    }
                })
                .catch(err => {
                    if (btnElement) {
                        btnElement.style.pointerEvents = 'auto';
                        btnElement.style.opacity = '1';
                    }
                    showToast("Network error. Please try again.", "error");
                });
        }

        // Auto-highlight active wishlist hearts on DOM Load
        document.addEventListener('DOMContentLoaded', function() {
            if (Array.isArray(window.userWishlistIds) && window.userWishlistIds.length > 0) {
                window.userWishlistIds.forEach(id => {
                    const btns = document.querySelectorAll(`[data-product-id="${id}"]`);
                    btns.forEach(btn => {
                        const icon = btn.querySelector('i');
                        if (icon) {
                            icon.className = 'fa-solid fa-heart text-danger';
                        }
                        btn.setAttribute('title', 'Remove from Wishlist');
                    });
                });
            }
        });
    </script>