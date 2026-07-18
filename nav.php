<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Navbar</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
             height: 2000px;
            background-color: #f4f4f4;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 0;
            overflow-x: hidden;
        }

        /* Hero Section Demo */
        .hero-section {
            background: url('https://images.unsplash.com/photo-1497935586351-b67a49e012bf?q=80&w=2071&auto=format&fit=crop') no-repeat center center/cover;
            height: 100vh;
            width: 100%;
        }

        /* Navbar Initial State (Transparent, Full Width) */
        .custom-navbar {
              background-color: #ffffff;
            padding: 20px 0;
            width: 100%;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            transition: all 0.4s ease-in-out;
            z-index: 1030;
        }

        /* Navbar Scrolled State (Floating, White, Rounded) */
        .custom-navbar.navbar-scrolled {
            background-color: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 10px 20px;
            top: 15px; /* স্ক্রিনের উপর থেকে কিছুটা নিচে */
            width: 95%; /* দুপাশ থেকে কিছুটা জায়গা ছাড়বে */
            max-width: 1300px;
            border-radius: 50px; /* কর্নারগুলো রাউন্ডেড হবে */
        }

        /* Logo Styling and Animation */
        .navbar-brand img {
            transition: all 0.4s ease-in-out;
        }
        /* স্ক্রল করার পর লোগো কিছুটা ছোট হবে */
        .navbar-scrolled .navbar-brand img {
            width: 110px;
            height: 60px;
        }

        /* Nav Links Styling */
        .navbar-nav .nav-link {
            color: #fff; /* হিরো ইমেজের উপর সাদা লেখা ভালো ফুটবে */
            font-weight: 600;
            font-size: 1.05rem;
            margin: 0 10px;
            transition: color 0.3s ease;
        }
        
        /* স্ক্রল করার পর লেখার রঙ পরিবর্তন হয়ে ডার্ক হবে */
        .navbar-scrolled .navbar-nav .nav-link {
            color: #333;
        }

        .navbar-nav .nav-link:hover {
            color: #d38c1c !important;
        }
        
        /* Active Link with Underline */
        .navbar-nav .nav-item .active {
            color: #d38c1c !important;
            position: relative;
        }
        .navbar-nav .nav-item .active::after {
            content: '';
            display: block;
            width: 25px;
            height: 3px;
            background-color: #d38c1c;
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        /* Right Side Icons */
        .icon-link, .heart-icon {
            color: #fff; /* শুরুতে আইকনের রঙ সাদা */
            font-size: 1.3rem;
            transition: transform 0.2s ease, color 0.4s ease;
        }
        
        /* স্ক্রল করার পর আইকনের রঙ পরিবর্তন */
        .navbar-scrolled .icon-link {
            color: #d38c1c;
        }
        .navbar-scrolled .heart-icon {
            color: #e63946;
        }

        .icon-link:hover, .heart-icon:hover {
            transform: scale(1.1);
        }
        
        /* Favorite Badge */
        .custom-badge {
            font-size: 0.65rem;
            border: 1px solid #e63946;
            color: white;
            background-color: #e63946;
            padding: 2px 5px;
            transition: all 0.4s ease;
        }
        /* স্ক্রল করার পর ব্যাজ স্টাইল আপডেট */
        .navbar-scrolled .custom-badge {
            color: #e63946;
            background-color: white;
        }

        /* Profile Picture Border */
        .profile-container {
            border: 2px solid #ffb703;
            padding: 3px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.2);
            transition: all 0.4s ease;
        }
        .navbar-scrolled .profile-container {
            background-color: transparent;
        }
        
        .profile-pic {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }

        /* Mobile View Adjustments */
        @media (max-width: 991px) {
            .navbar-collapse {
                background-color: #ffffff;
                padding: 15px;
                border-radius: 15px;
                margin-top: 10px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
            .navbar-nav .nav-link {
                color: #333 !important; /* মোবাইলে মেনু খুললে লেখা সবসময় ডার্ক থাকবে */
            }
            .icon-link { color: #d38c1c !important; }
            .heart-icon { color: #e63946 !important; }
            .custom-navbar.navbar-scrolled {
                border-radius: 20px;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg custom-navbar fixed-top" id="mainNavbar">
    <div class="container-fluid px-lg-4">
        
        <!-- Left Side: Logo -->
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="asset/image/logo.png" alt="Logo" class="rounded-circle" width="110px" height="60">
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="background-color: rgba(255,255,255,0.8);">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Middle: Nav Links & Right: Icons -->
        <div class="collapse navbar-collapse" id="navbarNav">
            
            <!-- Middle: Navigation Links -->
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="far fa-question-circle"></i> FAQ</a>
                </li>
            </ul>

            <!-- Right Side: Add to Cart, Profile -->
            <div class="d-flex align-items-center gap-4 mt-3 mt-lg-0">
                
                <!-- History / Cart Icon -->
                <a href="#" class="icon-link text-decoration-none">
                    <i class="fa fa-shopping-cart"></i>
                </a>

                <!-- Favorite with Badge -->
                <a href="#" class="heart-icon position-relative text-decoration-none">
                    <i class="fas fa-heart"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill custom-badge">
                        0
                    </span>
                </a>

                <!-- Profile Picture -->
                <a href="#" class="profile-container text-decoration-none">
                    <img src="https://via.placeholder.com/40" alt="Profile" class="profile-pic bg-secondary">
                </a>
            </div>
            
        </div>
    </div>
</nav>

<!-- Demo Hero Section -->
<div class="hero-section"></div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript for Scroll Effect -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const navbar = document.getElementById('mainNavbar');
        
        window.addEventListener('scroll', function() {
            // ইউজার 50px এর বেশি নিচে স্ক্রল করলে রাউন্ডেড স্টাইল অ্যাপ্লাই হবে
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });
    });
</script>

</body>
</html>