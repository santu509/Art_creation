<?php
session_start();
require_once '../connection.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    if (file_exists('dashboard.php')) {
        header("Location: dashboard.php");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - Siddha Art Creation</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../asset/image/logo.png">
    <!-- Bootstrap 5 CSS -->
    <link href="../asset/bootstrap-5.3.7-dist/css/bootstrap.min.css" rel="stylesheet" onerror="this.onerror=null;this.href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css';">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- AOS Animation Library CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --bg-page: #FAF8F5;
            --bg-left: #F5EFE6;
            --bg-card: #FFFFFF;
            --gold-primary: #D4AF37;
            --gold-deep: #B8860B;
            --gold-accent: #C59B27;
            --gold-light: #F3E5AB;
            --gold-gradient: linear-gradient(135deg, #DFBA5A 0%, #C59B27 50%, #9B781E 100%);
            --gold-glow: rgba(212, 175, 55, 0.25);
            --gold-border: rgba(212, 175, 55, 0.3);
            --text-dark: #2A241D;
            --text-muted: #7C7267;
            --input-bg: #F9F7F3;
            --input-border: #E8E2D7;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-page);
            background-image:
                radial-gradient(circle at 10% 20%, rgba(212, 175, 55, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(184, 134, 11, 0.08) 0%, transparent 40%);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        /* Main Split Card Container */
        .portal-wrapper {
            width: 100%;
            max-width: 960px;
            background-color: var(--bg-card);
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(184, 134, 11, 0.12), 0 5px 15px rgba(0, 0, 0, 0.04);
            border: 1px solid var(--gold-border);
            display: flex;
            flex-wrap: wrap;
            overflow: hidden;
            position: relative;
        }

        /* Left Side: Brand Hero Panel */
        .portal-hero {
            flex: 1 1 420px;
            background-color: var(--bg-left);
            background-image:
                radial-gradient(circle at 50% 30%, rgba(255, 255, 255, 0.8) 0%, transparent 70%),
                linear-gradient(135deg, rgba(245, 239, 230, 0.9) 0%, rgba(235, 225, 210, 0.9) 100%);
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            border-right: 1px solid var(--gold-border);
            position: relative;
        }

        .portal-hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 10%;
            right: 10%;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold-primary), transparent);
            opacity: 0.4;
        }

        .hero-logo-wrapper {
            width: 130px;
            height: 130px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .hero-logo-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 4px 10px rgba(184, 134, 11, 0.25));
            transition: transform 0.3s ease;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 34px;
            font-weight: 700;
            color: var(--gold-deep);
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
        }

        .hero-subtitle {
            font-size: 15px;
            color: var(--text-muted);
            line-height: 1.6;
            max-width: 320px;
            font-weight: 400;
        }

        /* Right Side: Form Panel */
        .portal-form-panel {
            flex: 1 1 480px;
            padding: 48px 44px;
            background-color: var(--bg-card);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 32px;
        }

        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .form-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 400;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 24px;
        }

        .form-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-dark);
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .forgot-link,
        .back-link {
            color: var(--gold-deep);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: color 0.2s ease;
            cursor: pointer;
        }

        .forgot-link:hover,
        .back-link:hover {
            color: var(--gold-primary);
            text-decoration: underline;
        }

        .input-group-custom {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            color: var(--gold-deep);
            font-size: 16px;
            pointer-events: none;
        }

        .form-control-custom {
            width: 100%;
            padding: 14px 44px 14px 44px;
            background-color: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: 12px;
            color: var(--text-dark);
            font-size: 15px;
            font-family: 'Outfit', sans-serif;
            transition: all 0.25s ease;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: var(--gold-primary);
            background-color: #FFFFFF;
            box-shadow: 0 0 0 4px var(--gold-glow);
        }

        .form-control-custom::placeholder {
            color: #A8A096;
        }

        .password-toggle-btn {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 16px;
            padding: 4px;
            transition: color 0.2s ease;
        }

        .password-toggle-btn:hover {
            color: var(--gold-deep);
        }

        /* Gold Solid Button */
        .btn-gold {
            width: 100%;
            padding: 15px;
            background: var(--gold-gradient);
            border: none;
            border-radius: 12px;
            color: #1A1612;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 18px rgba(197, 155, 39, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(197, 155, 39, 0.48);
            filter: brightness(1.06);
        }

        .btn-gold:active {
            transform: translateY(0);
        }

        /* Alerts */
        .alert-custom {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.3s ease;
        }

        .alert-custom-error {
            background-color: #FDF2F2;
            border: 1px solid #F8B4B4;
            color: #9B1C1C;
        }

        .alert-custom-success {
            background-color: #F0FDF4;
            border: 1px solid #BBF7D0;
            color: #166534;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Smooth AOS View Switching Animations */
        .form-view {
            display: none;
            opacity: 0;
        }

        .form-view.active {
            display: block;
            animation: aosFadeInUp 0.55s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .form-view.exiting {
            display: block;
            animation: aosFadeOutDown 0.25s cubic-bezier(0.7, 0, 0.84, 0) forwards;
        }

        @keyframes aosFadeInUp {
            0% {
                opacity: 0;
                transform: translate3d(0, 22px, 0) scale(0.97);
                filter: blur(4px);
            }
            100% {
                opacity: 1;
                transform: translate3d(0, 0, 0) scale(1);
                filter: blur(0);
            }
        }

        @keyframes aosFadeOutDown {
            0% {
                opacity: 1;
                transform: translate3d(0, 0, 0) scale(1);
                filter: blur(0);
            }
            100% {
                opacity: 0;
                transform: translate3d(0, -16px, 0) scale(0.97);
                filter: blur(4px);
            }
        }

        .back-link-wrapper {
            margin-top: 20px;
            text-align: center;
        }

        /* Mobile Brand Header (Hidden on Desktop, Visible on Mobile) */
        .mobile-brand-header {
            display: none;
            text-align: center;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px dashed var(--gold-border);
        }

        .mobile-logo-box {
            position: relative;
            width: 85px;
            height: 85px;
            margin: 0 auto 16px;
            background: var(--bg-left);
            border: 1.5px solid var(--gold-border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(212, 175, 55, 0.15);
        }

        .mobile-logo-img {
            max-width: 65%;
            max-height: 65%;
            object-fit: contain;
        }

        .secure-badge {
            position: absolute;
            bottom: -4px;
            right: -4px;
            background: var(--gold-gradient);
            color: #1A1612;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            border: 2px solid #FFFFFF;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .mobile-brand-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--gold-deep);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin: 0;
            line-height: 1.3;
        }

        .mobile-brand-title .secure-icon {
            color: var(--gold-primary);
            font-size: 20px;
            filter: drop-shadow(0 2px 4px rgba(184, 134, 11, 0.3));
        }

        /* Responsive Layout adjustments */
        @media (max-width: 850px) {
            .portal-wrapper {
                flex-direction: column;
                max-width: 480px;
                border-radius: 20px;
            }

            /* Hide Left Hero Panel on Mobile */
            .portal-hero {
                display: none !important;
            }

            /* Show Mobile Brand Header */
            .mobile-brand-header {
                display: block;
            }

            .portal-form-panel {
                padding: 36px 24px;
            }

            .form-title {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>

    <div class="portal-wrapper">

        <!-- LEFT PANEL: Brand Hero -->
        <div class="portal-hero">
            <div class="hero-logo-wrapper">
                <img src="../asset/image/logo.png" alt="Siddha Art Creation Logo" class="hero-logo-img">
            </div>
            <h1 class="hero-title">Admin Portal</h1>
            <p class="hero-subtitle">Secure access to the Siddha Art Creation management console.</p>
        </div>

        <!-- RIGHT PANEL: Interactive Forms -->
        <div class="portal-form-panel">

            <!-- Mobile Brand Header (Visible on Mobile) -->
            <div class="mobile-brand-header">
                <div class="mobile-logo-box">
                    <img src="../asset/image/logo.png" alt="Siddha Art Creation" class="mobile-logo-img">
                    <span class="secure-badge"><i class="fa-solid fa-shield-halved"></i></span>
                </div>
                <h1 class="mobile-brand-title">
                    <i class="fa-solid fa-shield-halved secure-icon"></i>
                    Siddha Art Creation Admin Panel
                </h1>
            </div>

            <!-- Alert Display Container -->
            <div id="alertContainer">
                <?php if (!empty($response['message'])): ?>
                    <div class="alert-custom alert-custom-<?php echo $response['status'] == 'success' ? 'success' : 'error'; ?>">
                        <i class="fa-solid <?php echo $response['status'] == 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation'; ?>"></i>
                        <span><?php echo htmlspecialchars($response['message']); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- LOGIN VIEW -->
            <div id="loginView" class="form-view active">
                <div class="form-header">
                    <h2 class="form-title">Sign In</h2>
                    <p class="form-subtitle">Enter your credentials to access the console.</p>
                </div>

                <form id="loginForm" action="login_action.php" method="POST">
                    <input type="hidden" name="action" value="login">

                    <div class="form-group">
                        <div class="form-label-row">
                            <label class="form-label" for="loginUsername">Username</label>
                        </div>
                        <div class="input-group-custom">
                            <i class="fa-regular fa-user input-icon"></i>
                            <input type="text" id="loginUsername" name="username" class="form-control-custom" placeholder="Enter admin username" required autocomplete="username">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label-row">
                            <label class="form-label" for="loginPassword">Password</label>
                            <a type="button" class="forgot-link" onclick="switchView('resetView')">Forgot Password?</a>
                        </div>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input type="password" id="loginPassword" name="password" class="form-control-custom" placeholder="••••••••" required autocomplete="current-password">
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('loginPassword', this)" title="Toggle Password">
                                <i class="fa-regular fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="btnLogin" class="btn-gold">
                        <span>Access Panel</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>
            </div>

            <!-- RESET PASSWORD VIEW -->
            <div id="resetView" class="form-view">
                <div class="form-header">
                    <h2 class="form-title">Reset Password</h2>
                    <p class="form-subtitle">Update your admin password directly below.</p>
                </div>

                <form id="resetForm" action="login_action.php" method="POST">
                    <input type="hidden" name="action" value="reset_password">

                    <div class="form-group">
                        <div class="form-label-row">
                            <label class="form-label" for="resetUsername">Username</label>
                        </div>
                        <div class="input-group-custom">
                            <i class="fa-regular fa-user input-icon"></i>
                            <input type="text" id="resetUsername" name="username" class="form-control-custom" placeholder="Enter your admin username" required autocomplete="username">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label-row">
                            <label class="form-label" for="resetPassword">New Password</label>
                        </div>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-lock input-icon"></i>
                            <input type="password" id="resetPassword" name="password" class="form-control-custom" placeholder="••••••••" required minlength="6">
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('resetPassword', this)" title="Toggle Password">
                                <i class="fa-regular fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label-row">
                            <label class="form-label" for="confirmPassword">Confirm Password</label>
                        </div>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-shield-halved input-icon"></i>
                            <input type="password" id="confirmPassword" name="confirm_password" class="form-control-custom" placeholder="••••••••" required minlength="6">
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('confirmPassword', this)" title="Toggle Password">
                                <i class="fa-regular fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="btnReset" class="btn-gold">
                        <span>Reset Password</span>
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>

                    <div class="back-link-wrapper">
                        <a type="button" class="back-link" onclick="switchView('loginView')">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to Sign In
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>

  
    <!-- AOS Animation Library JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 650,
                    easing: 'ease-out-cubic',
                    once: true
                });
            }
        });

        // Toggle Password Visibility
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        // Smooth AOS View Switching (Login vs Reset Password)
        function switchView(targetViewId) {
            clearAlert();
            const currentActive = document.querySelector('.form-view.active');
            const targetView = document.getElementById(targetViewId);

            if (!targetView) return;

            if (currentActive && currentActive !== targetView) {
                currentActive.classList.add('exiting');
                setTimeout(() => {
                    currentActive.classList.remove('active', 'exiting');
                    targetView.classList.add('active');
                    if (typeof AOS !== 'undefined') {
                        AOS.refresh();
                    }
                }, 240);
            } else {
                targetView.classList.add('active');
            }
        }

        // Show Custom Alert
        function showAlert(message, type = 'error') {
            const container = document.getElementById('alertContainer');
            const iconClass = type === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation';
            container.innerHTML = `
                <div class="alert-custom alert-custom-${type}">
                    <i class="fa-solid ${iconClass}"></i>
                    <span>${message}</span>
                </div>
            `;
        }

        function clearAlert() {
            document.getElementById('alertContainer').innerHTML = '';
        }

        // AJAX Form Submission Handler
        function handleFormSubmit(formId, buttonId, originalBtnHtml) {
            const form = document.getElementById(formId);
            const btn = document.getElementById(buttonId);

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                clearAlert();

                // Button loading state
                btn.disabled = true;
                btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i> Processing...`;

                const formData = new FormData(form);

                fetch('login_action.php', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        btn.disabled = false;
                        btn.innerHTML = originalBtnHtml;

                        if (data.status === 'success') {
                            showAlert(data.message, 'success');
                            if (data.redirect) {
                                setTimeout(() => {
                                    window.location.href = data.redirect;
                                }, 1200);
                            } else if (formId === 'resetForm') {
                                form.reset();
                                setTimeout(() => {
                                    switchView('loginView');
                                }, 1800);
                            }
                        } else {
                            showAlert(data.message || 'An error occurred. Please try again.', 'error');
                        }
                    })
                    .catch(err => {
                        btn.disabled = false;
                        btn.innerHTML = originalBtnHtml;
                        console.error("Fetch Error:", err);
                        showAlert('Server response error. Please try standard submission.', 'error');
                    });
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            handleFormSubmit('loginForm', 'btnLogin', '<span>Access Panel</span> <i class="fa-solid fa-arrow-right"></i>');
            handleFormSubmit('resetForm', 'btnReset', '<span>Reset Password</span> <i class="fa-solid fa-rotate-right"></i>');
        });
    </script>
</body>

</html>