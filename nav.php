<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidda Art Creation</title>
    <!-- Bootstrap 5 CSS -->
    <link href="asset/bootstrap-5.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Outfit and Playfair Display -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-color: #F5F2ED;
            --text-color: #3A3530;
            --accent-color: #B8860B;
            --accent-light: rgba(184, 134, 11, 0.1);
            --transition-smooth: all 0.8s cubic-bezier(0.25, 1, 0.5, 1);
        }

        body {
            background-color: #fcfbfa;
            font-family: 'Outfit', sans-serif;
            padding-top: 0;
            overflow-x: hidden;
        }

        /* Hero Section Demo */
        .hero-section {
            background: url('https://images.unsplash.com/photo-1547891654-e66ed7edd96c?q=80&w=2070&auto=format&fit=crop') no-repeat center center/cover;
            height: 100vh;
            width: 100%;
        }

        /* Navbar Initial State */
        .custom-navbar {
            background-color: var(--bg-color);
            padding: 8px 0;
            width: 100%;
            max-width: 100%;
            top: 0;
            left: 0;
            right: 0;
            margin: 0 auto;
            border-radius: 0px;
            box-shadow: 0 0 0 rgba(0, 0, 0, 0);
            backdrop-filter: blur(0px);
            -webkit-backdrop-filter: blur(0px);
            border-bottom: 1px solid rgba(58, 53, 48, 0.08);
            border-top: 1px solid transparent;
            border-left: 1px solid transparent;
            border-right: 1px solid transparent;
            transition: var(--transition-smooth);
            z-index: 1045;
        }

        /* Navbar Scrolled State (Floating, Sleek, Rounded) */
        .custom-navbar.navbar-scrolled {
            background-color: rgba(245, 242, 237, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(58, 53, 48, 0.08);
            padding: 6px 20px;
            top: 15px;
            width: 95%;
            max-width: 1300px;
            border-radius: 50px;
            border: 1px solid rgba(58, 53, 48, 0.05);
        }

        /* Centering navbar elements vertically */
        .navbar-collapse {
            align-items: center !important;
        }

        .navbar-nav {
            align-items: center !important;
            margin-bottom: 0 !important;
        }

        /* Logo Styling */
        .navbar-brand img {
            transition: var(--transition-smooth);
            width: 100px;
            height: 60px;
            object-fit: contain;
        }

        .navbar-scrolled .navbar-brand img {
            width: 100px !important;
            height: 55px;
        }


        /* Nav Links Styling - Base (Universal/Mobile defaults) */
        .navbar-nav .nav-link {
            color: var(--text-color);
            font-weight: 500;
            font-size: 1.02rem;
            position: relative;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--accent-color) !important;
        }

        /* Desktop specific Hover & Active Capsule + Bar styling (screens >= 992px) */
        @media (min-width: 992px) {
            .navbar-nav {
                position: relative;
                /* Indicator ke bound korar jonno */
            }

            .navbar-nav .nav-link {
                padding: 8px 20px !important;
                margin: 0 4px;
                border-radius: 30px;
                position: relative;
                z-index: 2;
                /* Text jate indicator er opore thake */
                transition: color 0.3s ease;
            }

            .navbar-nav .nav-link:hover,
            .navbar-nav .nav-link.active {
                color: var(--accent-color) !important;
                background-color: transparent !important;
                /* Purono background hide */
            }

            .navbar-nav .nav-link::after {
                display: none;
                /* Purono static underline hide */
            }

            /* Notun Smooth Sliding Indicator CSS */
            .nav-active-indicator {
                position: absolute;
                top: 0;
                left: 0;
                background-color: rgba(184, 134, 11, 0.08);
                /* Capsule background */
                border-radius: 30px;
                z-index: 1;
                /* Text er niche thakbe */
                transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1), width 0.4s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.3s ease;
                opacity: 0;
                pointer-events: none;
                /* Mouse er sathe jamela korbe na */
            }

            /* Sliding Indicator er Underline */
            .nav-active-indicator::after {
                content: '';
                position: absolute;
                bottom: 8px;
                left: 50%;
                transform: translateX(-50%);
                width: 18px;
                height: 3.5px;
                background-color: var(--accent-color);
                border-radius: 2px;
            }
        }

        /* Right Side Icons */
        .icon-link {
            color: var(--text-color);
            font-size: 1.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(58, 53, 48, 0.04);
            transition: var(--transition-smooth);
        }

        .icon-link:hover {
            color: var(--accent-color);
            background-color: var(--accent-light);
            transform: translateY(-2px);
        }

        /* Badge Customization */
        .custom-badge {
            font-size: 0.65rem;
            color: var(--bg-color);
            background-color: var(--accent-color);
            padding: 3px 6px;
            border: 1.5px solid var(--bg-color);
        }

        /* Buttons Styling */
        .btn-login {
            color: var(--text-color);
            font-weight: 500;
            font-size: 0.95rem;
            padding: 8px 20px;
            border-radius: 30px;
            transition: var(--transition-smooth);
            border: 1px solid transparent;
        }

        .btn-login:hover {
            color: var(--accent-color);
            background-color: rgba(184, 134, 11, 0.05);
            border-color: rgba(184, 134, 11, 0.15);
        }

        .btn-register {
            color: var(--bg-color);
            background-color: var(--text-color);
            font-weight: 500;
            font-size: 0.95rem;
            padding: 8px 22px;
            border-radius: 30px;
            border: 1px solid var(--text-color);
            transition: var(--transition-smooth);
            box-shadow: 0 4px 12px rgba(58, 53, 48, 0.1);
        }

        .btn-register:hover {
            color: var(--bg-color);
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(184, 134, 11, 0.2);
        }

        /* Mobile Menu Toggler */
        .navbar-toggler {
            border: 1px solid rgba(58, 53, 48, 0.15);
            background-color: rgba(58, 53, 48, 0.03);
            padding: 6px 10px;
            border-radius: 8px;
            transition: var(--transition-smooth);
        }
.dropdown-toggle::after {
    border: none;
}
        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.25rem rgba(184, 134, 11, 0.2);
            border-color: var(--accent-color);
        }

        .navbar-toggler-icon {
            filter: invert(18%) sepia(8%) saturate(545%) hue-rotate(342deg) brightness(91%) contrast(85%);
        }

        /* Mobile View Adjustments */
        @media (max-width: 991px) {
            .custom-navbar.navbar-scrolled {
                border-radius: 40px;
                width: 95%;
                top: 10px;
            }

            .navbar-collapse {
                background-color: var(--bg-color);
                padding: 20px;
                border-radius: 15px;
                margin-top: 15px;
                box-shadow: 0 8px 24px rgba(58, 53, 48, 0.08);
                border: 1px solid rgba(58, 53, 48, 0.05);
            }

            .navbar-nav .nav-link {
                margin: 8px 0;
            }

            .navbar-nav .nav-item .nav-link::after {
                display: none;
            }

            .btn-login,
            .btn-register {
                width: 100%;
                text-align: center;
                margin-left: 0 !important;
                margin-top: 10px;
            }
        }

        /* Toast Notification Container */
        .toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
        }

        .custom-toast {
            background-color: #12110F;
            color: #F5F2ED;
            padding: 14px 24px;
            border-radius: 8px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
            animation: slideIn 0.3s ease forwards, fadeOut 0.3s ease 4s forwards;
            border-left: 4px solid #C5A880;
            min-width: 280px;
            font-weight: 500;
        }

        .custom-toast.success {
            border-left-color: #2ec4b6;
        }

        .custom-toast.error {
            border-left-color: #e63946;
        }

        .custom-toast i {
            font-size: 1.1rem;
        }

        .custom-toast.success i {
            color: #2ec4b6;
        }

        .custom-toast.error i {
            color: #e63946;
        }

        @keyframes slideIn {
            from {
                transform: translateX(120%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(50px);
            }
        }

        /* Modal Custom Styling */
        .auth-modal .modal-dialog {
            max-width: 850px;
            margin: 1.75rem auto;
        }

        .auth-modal .modal-content {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
            background-color: transparent;
        }

        .modal-body-split {
            display: flex;
            min-height: 580px;
            padding: 0;
        }

        /* Left Pane (Dark) */
        .modal-split-left {
            width: 45%;
            background-color: #342319;
            color: #F5F2ED;
            padding: 45px 35px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .modal-split-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 10% 20%, rgba(184, 134, 11, 0.08) 0%, transparent 60%);
            pointer-events: none;
        }

        .modal-split-left .brand-logo {
            max-height: 48px;
            width: auto;
            align-self: flex-start;
            margin-bottom: 20px;
            filter: brightness(1.2);
        }

        .modal-split-left h3 {
            font-family: 'Playfair Display', serif;
            font-size: 2.1rem;
            font-weight: 500;
            line-height: 1.3;
            margin-top: 20px;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        .modal-split-left h3 span {
            font-style: italic;
            color: #C5A880;
        }

        .modal-split-left p.desc {
            color: #A59E96;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 35px;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .feature-icon-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: rgba(184, 134, 11, 0.08);
            border: 1px solid rgba(184, 134, 11, 0.15);
            color: #C5A880;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .feature-text {
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: #F5F2ED;
        }

        .feature-subtext {
            font-size: 0.8rem;
            color: #A59E96;
            font-weight: 400;
            margin-top: 4px;
            line-height: 1.4;
        }

        .left-footer {
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #7D756C;
            margin-top: 40px;
            border-top: 1px solid rgba(245, 242, 237, 0.06);
            padding-top: 15px;
        }

        /* Right Pane (Light) */
        .modal-split-right {
            width: 55%;
            background-color: #FFFFFF;
            padding: 45px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .modal-close-btn {
            position: absolute;
            top: 20px;
            right: 25px;
            background: none;
            border: none;
            font-size: 1.3rem;
            color: #A59E96;
            cursor: pointer;
            transition: color 0.2s ease;
            z-index: 10;
        }

        .modal-close-btn:hover {
            color: #3A3530;
        }

        .modal-split-right h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #2C2724;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .modal-split-right p.subtitle {
            color: #8C857E;
            font-size: 0.88rem;
            margin-bottom: 30px;
        }

        /* Form controls */
        .form-group-custom {
            margin-bottom: 18px;
            position: relative;
        }

        .form-label-custom {
            display: block;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: #5C554E;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 15px;
            color: #A59E96;
            font-size: 0.9rem;
        }

        .input-custom {
            width: 100%;
            padding: 11px 16px 11px 40px;
            border: 1px solid #E5E1DB;
            border-radius: 8px;
            font-size: 0.92rem;
            color: #3A3530;
            background-color: #FCFBFA;
            transition: all 0.3s ease;
        }

        .input-custom:focus {
            outline: none;
            border-color: #C5A880;
            box-shadow: 0 0 0 3px rgba(197, 168, 128, 0.12);
            background-color: #FFFFFF;
        }

        .input-custom:disabled {
            background-color: #F5F2ED;
            color: #8C857E;
            cursor: not-allowed;
            border-color: #E5E1DB;
        }

        /* Eye toggle for password */
        .password-toggle-btn {
            position: absolute;
            right: 15px;
            background: none;
            border: none;
            color: #A59E96;
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            height: 100%;
        }

        .password-toggle-btn:hover {
            color: #3A3530;
        }

        /* Forgot Password Link */
        .forgot-password-link {
            position: absolute;
            right: 0;
            top: 0;
            font-size: 0.72rem;
            color: #C5A880;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-password-link:hover {
            text-decoration: underline;
            color: #B8860B;
        }

        /* Action buttons inside inputs */
        .btn-input-action {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background-color: #12110F;
            color: #F5F2ED;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            z-index: 5;
            letter-spacing: 0.5px;
        }

        .btn-input-action:hover {
            background-color: #B8860B;
        }

        .btn-input-action:disabled {
            background-color: #A59E96;
            cursor: not-allowed;
        }

        /* Main Action Button */
        .btn-auth-action {
            background-color: #d09748;
            color: #FFFFFF;
            border: none;
            width: 100%;
            padding: 13px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
            margin-top: 15px;
            box-shadow: 0 4px 15px rgba(197, 168, 128, 0.2);
        }

        .btn-auth-action:hover {
            background-color: #B8860B;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(184, 134, 11, 0.25);
        }

        /* Auth Modals Footer */
        .auth-footer-text {
            text-align: center;
            font-size: 0.82rem;
            color: #8C857E;
            margin-top: 25px;
        }

        .auth-footer-text a {
            color: #C5A880;
            text-decoration: none;
            font-weight: 600;
        }

        .auth-footer-text a:hover {
            text-decoration: underline;
            color: #B8860B;
        }

        .modal-sub-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            font-size: 0.72rem;
            color: #A59E96;
            margin-top: 15px;
        }

        .modal-sub-links a {
            color: #A59E96;
            text-decoration: none;
        }

        .modal-sub-links a:hover {
            color: #3A3530;
        }

        /* OTP Input styling */
        .otp-inputs-container {
            display: flex;
            gap: 8px;
            justify-content: space-between;
        }

        .otp-input {
            width: 42px;
            height: 42px;
            border: 1px solid #E5E1DB;
            border-radius: 8px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 600;
            background-color: #FCFBFA;
            color: #3A3530;
            transition: all 0.3s ease;
        }

        .otp-input:focus {
            outline: none;
            border-color: #C5A880;
            box-shadow: 0 0 0 3px rgba(197, 168, 128, 0.12);
            background-color: #FFFFFF;
        }

        /* Hidden fields section */
        .hidden-auth-fields {
            opacity: 0;
            max-height: 0;
            overflow: hidden;
            transition: all 0.6s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .hidden-auth-fields.visible {
            opacity: 1;
            max-height: 600px;
            margin-top: 15px;
        }

        /* Profile picture in Navbar styling */
        .profile-container {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #C5A880;
            padding: 2px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-smooth);
            cursor: pointer;
            background-color: transparent;
        }
.profile-pic {
    width: 45px;
    height: 40px;
    object-fit: cover;
    
    /* Changes made below */
    display: block;      /* Ensures vertical margin is respected */
    margin-top: 10px;    /* Use px or rem instead of % for predictable spacing */
    
    border-radius: 50%;
    border: 2px solid #b0f1f6;
    box-shadow: 0 4px 15px rgba(176, 241, 246, 0.5);
}

        .profile-dropdown-menu {
            background-color: #F5F2ED;
            border: 1px solid rgba(58, 53, 48, 0.1);
            box-shadow: 0 10px 30px rgba(58, 53, 48, 0.1);
            border-radius: 12px;
            padding: 10px;
            min-width: 230px;
            margin-top: 8px !important;
        }

        .profile-dropdown-menu .dropdown-header {
            padding: 10px 15px;
            border-bottom: 1px solid rgba(58, 53, 48, 0.05);
            text-align: left;
        }

        .profile-dropdown-menu .dropdown-item {
            color: #3A3530;
            font-size: 0.88rem;
            padding: 8px 15px;
            border-radius: 6px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            text-align: left;
        }

        .profile-dropdown-menu .dropdown-item:hover {
            background-color: rgba(184, 134, 11, 0.08);
            color: #B8860B;
        }

        /* Mobile layout for split modal */
        @media (max-width: 768px) {
            .auth-modal .modal-dialog {
                max-width: 95%;
                margin: 1rem auto;
            }

            .modal-body-split {
                flex-direction: column;
                min-height: auto;
            }

            .modal-split-left {
                display: none;
            }

            .modal-split-right {
                width: 100%;
                padding: 35px 25px;
            }

            .feature-list {
                gap: 15px;
            }

            .modal-split-left h3 {
                font-size: 1.6rem;
            }
        }

        /* Mobile Offcanvas Fixes */
        @media (max-width: 991px) {

            /* Parent limitation soriye dewar jonno filter nullify kora holo (Left gap fix) */
            .custom-navbar,
            .custom-navbar.navbar-scrolled {
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
            }

            /* Offcanvas ke puro screen jure set kora holo (Full width fix) */
            #navbarNav.offcanvas {
                background-color: var(--bg-color) !important;
                height: 100vh !important;
                z-index: 1055 !important;
                max-width: 100vw !important;
                /* 80% er jaygay 100vw kora holo */
                width: 80vw !important;
                /* Puro screen nishchit korbe */
            }

            /* Offcanvas body background fix */
            #navbarNav .offcanvas-body {
                background-color: var(--bg-color) !important;
                overflow-y: auto;
                z-index: 1056 !important;
            }

            /* Offcanvas Header background fix */
            #navbarNav .offcanvas-header {
                background-color: var(--bg-color) !important;
                border-bottom: 1px solid rgba(58, 53, 48, 0.1);
            }

            /* Reduce navbar height and elements size on mobile */
            .custom-navbar {
                padding: 7px 0 !important;
            }

            .navbar-brand img,
            .navbar-scrolled .navbar-brand img {
                height: 45px !important;
                width: auto !important;
            }

            .profile-container {
                width: 35px !important;
                height: 35px !important;
            }

            .navbar-toggler {
                padding: 4px 8px !important;
            }
        }

        /* Mobile Bottom Navigation Bar styling */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #FFFFFF;
            height: 57px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            border-top: 1px solid rgba(58, 53, 48, 0.08);
            box-shadow: 0 -4px 15px rgba(58, 53, 48, 0.05);
            z-index: 1030;
            padding-bottom: env(safe-area-inset-bottom);
        }

        .mobile-bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #8C857E;
            text-decoration: none;
            font-size: 0.72rem;
            font-weight: 500;
            width: 25%;
            height: 100%;
            transition: all 0.2s ease;
            background: none;
            border: none;
            padding: 0;
        }

        .mobile-bottom-nav-item i {
            font-size: 1.25rem;
            margin-bottom: 4px;
            transition: all 0.2s ease;
        }

        .mobile-bottom-nav-item:hover,
        .mobile-bottom-nav-item.active {
            color: #B8860B;
            /* Gold Accent */
        }

        /* Hide on desktop, show only on mobile */
        @media (min-width: 992px) {
            .mobile-bottom-nav {
                display: none !important;
            }
        }

        /* Adjust body padding on mobile to not hide content behind the navbar */
        @media (max-width: 991px) {
            body {
                padding-bottom: 45px !important;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg custom-navbar fixed-top" id="mainNavbar">
        <div class="container px-lg-4">

            <?php
            $isLoggedIn = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
            $userName = $isLoggedIn ? $_SESSION['user_name'] : '';
            $userEmail = $isLoggedIn ? $_SESSION['user_email'] : '';
            $userImage = ($isLoggedIn && !empty($_SESSION['user_image'])) ? $_SESSION['user_image'] : 'asset/image/default-image.jpg';
            ?>

            <!-- Left Side: Logo -->
            <a class="navbar-brand d-flex align-items-center" href="#">
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
                        <li><a class="dropdown-item" href="#"><i class="fa-regular fa-user me-2"></i>My Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear me-2"></i>Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><button class="dropdown-item text-danger border-0 bg-transparent w-100 text-start" type="button" onclick="handleLogout()"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</button></li>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler <?php echo $isLoggedIn ? '' : 'ms-auto'; ?>" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Offcanvas Sidebar Menu (Slides Left to Right on Mobile, displays inline on Desktop) -->
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
                            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="aboutus.php">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Collection</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">FAQ</a>
                        </li>
                    </ul>

                    <!-- Right Side: Cart, Wishlist, Login & Create Account / Profile -->
                    <div class="d-flex align-items-lg-center flex-column flex-lg-row gap-3 mt-3 mt-lg-0">

                       <!-- Cart Icon -->
<a href="#" class="icon-link position-relative text-decoration-none d-inline-flex" title="Cart">
    <i class="fa-solid fa-cart-shopping"></i>
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill custom-badge">
        0
    </span>
</a>

                        <!-- Wishlist Icon -->
                        <a href="#" class="icon-link position-relative text-decoration-none" title="Wishlist">
                            <i class="fa-regular fa-heart"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill custom-badge">
                                0
                            </span>
                        </a>

                        <!-- Auth Buttons (Visible when Logged Out) -->
                        <?php if (!$isLoggedIn): ?>
                            <div class="d-flex align-items-center gap-2" id="authButtons">
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
                                    <li><a class="dropdown-item" href="#"><i class="fa-regular fa-user me-2"></i>My Profile</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear me-2"></i>Settings</a></li>
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
                        <div>
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
                                    <a href="#" class="forgot-password-link">Forgot Password?</a>
                                    <div class="input-wrapper">
                                        <i class="fa-solid fa-lock input-icon-left"></i>
                                        <input type="password" id="loginPassword" name="password" class="input-custom" placeholder="••••••••" required>
                                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('loginPassword', this)">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <button type="submit" class="btn-auth-action">
                                    Sign In <i class="fa-solid fa-arrow-right-long ms-1"></i>
                                </button>
                            </form>
                        </div>

                        <div>
                            <p class="auth-footer-text">New to Siddha Art? <a href="#" onclick="switchModals('loginModal', 'registerModal')">Create an account</a></p>
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
                            <h3>Join the Creator <span>Circle</span></h3>
                            <p class="desc">Unlock exclusive access to rare artworks and join a community of world-class creators.</p>

                            <div class="feature-list">
                                <div class="feature-item">
                                    <div class="feature-icon-circle">
                                        <i class="fa-regular fa-star"></i>
                                    </div>
                                    <div>
                                        <div class="feature-text">Exclusive Previews</div>
                                        <div class="feature-subtext">Be the first to see masterworks before public release.</div>
                                    </div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-icon-circle">
                                        <i class="fa-solid fa-palette"></i>
                                    </div>
                                    <div>
                                        <div class="feature-text">Artist Support</div>
                                        <div class="feature-subtext">Direct channels for mentorship and creative growth.</div>
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
                            <p class="subtitle">Begin your artistic journey with us today.</p>

                            <form id="registerForm" onsubmit="handleRegisterSubmit(event)">
                                <!-- Name and Email -->
                                <div class="form-group-custom">
                                    <label class="form-label-custom">Full Name</label>
                                    <div class="input-wrapper">
                                        <i class="fa-regular fa-user input-icon-left"></i>
                                        <input type="text" id="registerName" name="name" class="input-custom" placeholder="e.g., Leonardo da Vinci" required>
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
                                <a href="#">Artist Guidelines</a>
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
    <div class="mobile-bottom-nav">
        <!-- Home -->
        <a href="index.php" class="mobile-bottom-nav-item" id="bottomNavHome">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
        </a>
        <!-- Shop -->
        <a href="menu.php" class="mobile-bottom-nav-item" id="bottomNavShop">
            <i class="fa-solid fa-bag-shopping"></i>
            <span>Shop</span>
        </a>
        <!-- Cart -->
        <a href="cart.php" class="mobile-bottom-nav-item" id="bottomNavCart">
            <div class="position-relative d-inline-flex">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill custom-badge" style="font-size: 0.58rem; padding: 2px 5px;">
                    0
                </span>
            </div>
            <span>Cart</span>
        </a>
        <!-- Account -->
        <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true): ?>
            <a href="profile.php" class="mobile-bottom-nav-item" id="bottomNavAccount">
                <i class="fa-solid fa-user"></i>
                <span>Account</span>
            </a>
        <?php else: ?>
            <button type="button" class="mobile-bottom-nav-item" id="bottomNavAccount" data-bs-toggle="modal" data-bs-target="#loginModal">
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

            fetch('register.php?action=register', {
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

            fetch('login.php', {
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
            fetch('login.php?action=logout')
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

        // Automatic active bottom nav state detection
        function setActiveBottomNav() {
            const path = window.location.pathname;
            const page = path.split("/").pop();

            document.querySelectorAll('.mobile-bottom-nav-item').forEach(item => {
                item.classList.remove('active');
            });

            if (page.includes('index') || page === '') {
                document.getElementById('bottomNavHome')?.classList.add('active');
            } else if (page.includes('menu') || page.includes('shop') || page.includes('product')) {
                document.getElementById('bottomNavShop')?.classList.add('active');
            } else if (page.includes('cart')) {
                document.getElementById('bottomNavCart')?.classList.add('active');
            } else if (page.includes('profile') || page.includes('account') || page.includes('user')) {
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

                // Protita link e mouse gele indicator move korbe
                navLinks.forEach(link => {
                    link.addEventListener('mouseenter', function() {
                        setIndicator(this);
                    });
                });

                // Nav theke mouse beriye gele abar Active item e phire asbe
                navContainer.addEventListener('mouseleave', function() {
                    const activeLink = navContainer.querySelector('.nav-link.active');
                    if (activeLink) {
                        setIndicator(activeLink);
                    } else {
                        indicator.style.opacity = '0';
                    }
                });

                // Page load er pore initial state set kora
                setTimeout(() => {
                    const activeLink = navContainer.querySelector('.nav-link.active');
                    if (activeLink) setIndicator(activeLink);
                }, 200);
            }
        });
    </script>