<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Siddha Art Creation</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts (Playfair Display for headings, Inter for body text) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- AOS Animation Library CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary-accent: #d4af37; 
            --primary-accent-hover: #f1c40f;
            --primary-gradient: linear-gradient(135deg, #d4af37 0%, #b8860b 100%);
            --dark-bg: #110d0c; 
            --dark-card: #1c1513; 
            --light-bg: #fdfbf7; 
            --craft-bg: #f7f3ef; 
            --text-dark: #231f1d;
            --text-light: #fefcf9;
            --text-muted: #9e928a;
            --border-color: rgba(212, 175, 55, 0.15);
            --transition-smooth: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background-color: var(--light-bg);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1, h2, h3, h4, h5, h6, .font-serif {
            font-family: 'Playfair Display', serif;
            letter-spacing: 0.5px;
        }

        .text-accent {
            color: var(--primary-accent) !important;
        }

        /* Subtitle Styling */
        .subtitle {
            font-size: 0.8rem;
            letter-spacing: 5px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--primary-accent);
            display: inline-block;
            margin-bottom: 1.2rem;
            position: relative;
            padding-bottom: 10px;
        }
        
        .subtitle::after {
            content: '';
            display: block;
            width: 35px;
            height: 2px;
            background: var(--primary-gradient);
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            transition: width 0.4s ease;
        }

        .text-start .subtitle::after {
            left: 0;
            transform: none;
        }

        .text-start:hover .subtitle::after {
            width: 60px;
        }

        /* Hero Section with Ken Burns Effect - HEIGHT REDUCED HERE */
        .hero-section {
            height: 50vh!important; /* Reduced from 50vh */
         
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
            margin-top: 2.7%;
        }

        .hero-bg {
            position: absolute;
            top: 0; left: 0;
             width: 100%; 
             height: 100%;
            background: url('asset/image/592d04ba-5abd-4238-91de-81babd2dcac3.jpg') no-repeat center center/cover;
            background-attachment: fixed;
            z-index: 1;
            transform: scale(1.05);
            animation: kenBurns 20s infinite alternate ease-in-out;
            /* margin-top: 3%; */
        }

        @keyframes kenBurns {
            0% { transform: scale(1.05); }
            100% { transform: scale(1.15) translateY(-10px); }
        }

        .hero-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(180deg, rgba(17, 13, 12, 0.6) 0%, rgba(17, 13, 12, 0.9) 100%);
            z-index: 2;
        }

        .hero-content {
            position: relative;
            z-index: 3;
            max-width: 800px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(17, 13, 12, 0.4);
            backdrop-filter: blur(8px);
            border-radius: 4px;
        }

        .hero-content h1 {
            letter-spacing: 6px;
            text-shadow: 0 4px 15px rgba(0,0,0,0.6);
            font-weight: 700;
        }

        .hero-content p {
            letter-spacing: 3px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
            color: #e5dfd9;
        }

        /* Decorative Divider */
        .deco-line {
            width: 80px;
            height: 1px;
            background: var(--primary-gradient);
            margin: 1.5rem auto;
        }

        /* Discover Section */
        .discover-section {
            background-color: var(--light-bg);
            padding: 5rem 0;
            position: relative;
        }

        /* Decorative background elements */
        .discover-section::before {
            content: '';
            position: absolute;
            top: 10%; right: 5%;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(212,175,55,0.03) 0%, transparent 70%);
            pointer-events: none;
        }

        .discover-text {
            font-size: 1.05rem;
            line-height: 2;
            font-weight: 300;
            color: #554f4b;
            text-align: justify;
        }

        .discover-quote {
            border-left: 3px solid var(--primary-accent);
            padding-left: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            color: var(--text-dark);
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            line-height: 1.6;
        }

        /* Elegant Frame Art Gallery Effect */
        .img-wrapper {
            position: relative;
            z-index: 1;
            padding: 1.5rem;
            display: inline-block;
        }

        .img-wrapper::before {
            content: '';
            position: absolute;
            top: 0; right: 3rem; bottom: 3rem; left: 0;
            border: 1px solid var(--primary-accent);
            z-index: -1;
            transition: var(--transition-smooth);
        }

        .img-wrapper::after {
            content: '';
            position: absolute;
            top: 3rem; right: 0; bottom: 0; left: 3rem;
            border: 1px solid rgba(212, 175, 55, 0.3);
            z-index: -1;
            transition: var(--transition-smooth);
        }

        .discover-img {
            border-radius: 2px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.12);
            width: 100%;
            max-width: 480px;
            transition: var(--transition-smooth);
            position: relative;
            z-index: 2;
        }

        .img-wrapper:hover::before {
            transform: translate(-15px, -15px);
            border-color: var(--primary-accent-hover);
        }

        .img-wrapper:hover::after {
            transform: translate(15px, 15px);
        }

        .img-wrapper:hover .discover-img {
            transform: scale(1.02);
            box-shadow: 0 35px 70px rgba(212, 175, 55, 0.18);
        }

        /* Dark Wrapper (Why Choose Us) */
        .dark-wrapper {
            background-color: var(--dark-bg);
            color: var(--text-light);
            padding: 5rem 0; 
            background-image: radial-gradient(circle at 50% 50%, rgba(212, 175, 55, 0.05) 0%, transparent 70%);
            position: relative;
        }

        .dark-wrapper::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.2), transparent);
        }

        /* Premium Luxury Card Design - Dark */
        .dark-card {
            background-color: var(--dark-card);
            border: 1px solid rgba(212, 175, 55, 0.08);
            border-radius: 4px;
            padding: 3rem 2rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: var(--transition-smooth);
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .dark-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.05) 0%, transparent 100%);
            opacity: 0;
            transition: var(--transition-smooth);
            z-index: -1;
        }

        .dark-card::after {
            content: '';
            position: absolute;
            top: 0; left: 50%;
            width: 0; height: 2px;
            background: var(--primary-gradient);
            transition: var(--transition-smooth);
            transform: translateX(-50%);
        }

        .dark-card:hover {
            transform: translateY(-8px);
            border-color: rgba(212, 175, 55, 0.3);
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        }

        .dark-card:hover::before {
            opacity: 1;
        }

        .dark-card:hover::after {
            width: 100%;
        }

        /* Craft Section - Premium Card with Visual Imagery */
        .craft-wrapper {
            background-color: var(--craft-bg);
            color: var(--text-dark);
            padding: 5rem 0 6rem 0; /* Deep padding for overlap */
            position: relative;
        }

        .craft-card {
            background-color: #ffffff;
            border: 1px solid rgba(212, 175, 55, 0.12);
            border-radius: 4px;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: var(--transition-smooth);
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        }

        .craft-img-container {
            position: relative;
            overflow: hidden;
            height: 220px;
            width: 100%;
        }

        .craft-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition-smooth);
        }

        .craft-img-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(180deg, rgba(0,0,0,0) 50%, rgba(0,0,0,0.4) 100%);
            z-index: 2;
        }

        .craft-card-icon-wrap {
            position: absolute;
            bottom: 20px;
            right: 25px;
            width: 50px;
            height: 50px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
            z-index: 3;
            transition: var(--transition-smooth);
        }

        .craft-card-body {
            padding: 2.5rem 2rem 2rem 2rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .craft-card h4 {
            font-size: 1.25rem;
            letter-spacing: 1px;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--text-dark);
            position: relative;
            display: inline-block;
        }

        .craft-card p {
            font-size: 0.9rem;
            line-height: 1.8;
            color: #615a55;
            margin-bottom: 1.5rem;
        }

        .craft-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 45px rgba(212, 175, 55, 0.12);
            border-color: rgba(212, 175, 55, 0.3);
        }

        .craft-card:hover .craft-img-container img {
            transform: scale(1.08);
        }

        .craft-card:hover .craft-card-icon-wrap {
            transform: translateY(-5px) rotate(15deg);
            box-shadow: 0 12px 25px rgba(212, 175, 55, 0.5);
        }

        /* Universal Icon Styling */
        .card-icon {
            font-size: 2.5rem;
            color: var(--primary-accent);
            margin-bottom: 1.5rem;
            transition: var(--transition-smooth);
        }

        .dark-card:hover .card-icon {
            transform: scale(1.1) rotate(5deg);
            color: var(--primary-accent-hover);
        }

        /* Explore Link style */
        .craft-explore {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--primary-accent);
            text-decoration: none;
            font-weight: 600;
            margin-top: auto;
            display: inline-flex;
            align-items: center;
            transition: var(--transition-smooth);
        }

        .craft-explore i {
            margin-left: 6px;
            transition: var(--transition-smooth);
        }

        .craft-card:hover .craft-explore {
            color: var(--primary-accent-hover);
        }

        .craft-card:hover .craft-explore i {
            transform: translateX(5px);
        }

        /* Commitment Card (Floating overlap) */
        .commitment-container {
            background-color: var(--light-bg);
            position: relative;
            padding-bottom: 8rem;
        }

        .commitment-card {
            background-color: #ffffff;
            border-radius: 4px;
            padding: 4rem 3rem;
            box-shadow: 0 40px 80px rgba(17, 13, 12, 0.08);
            border: 1px solid rgba(212, 175, 55, 0.15);
            text-align: center;
            max-width: 900px;
            margin: 5rem auto 0 auto; 
            position: relative;
            z-index: 10;
            transition: var(--transition-smooth);
        }

        .commitment-card::before {
            content: '';
            position: absolute;
            top: 15px; left: 15px; right: 15px; bottom: 15px;
            border: 1px solid rgba(212, 175, 55, 0.08);
            pointer-events: none;
        }

        .commitment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 45px 90px rgba(212, 175, 55, 0.1);
        }

        .commitment-icon {
            font-size: 2.8rem;
            color: var(--primary-accent);
            margin-bottom: 2rem;
            display: inline-block;
        }

        .commitment-card h3 {
            font-weight: 700;
            letter-spacing: 4px;
            margin-bottom: 2rem;
            font-size: 2rem;
            text-transform: uppercase;
        }

        .commitment-text {
            font-size: 1.1rem;
            line-height: 2;
            color: #554f4b;
            margin-bottom: 2.5rem;
            font-weight: 300;
        }
        
        .commitment-footer {
            font-size: 1.25rem;
            font-style: italic;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        /* Decorative Leaf/Ornament */
        .ornament {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: rgba(212, 175, 55, 0.3);
        }
        .ornament::before, .ornament::after {
            content: '';
            width: 50px;
            height: 1px;
            background: rgba(212, 175, 55, 0.2);
            margin: 0 15px;
        }

        /* Responsive Adjustments - HEIGHT REDUCED HERE FOR MOBILE */
        @media (max-width: 992px) {
            .discover-section {
                padding: 6rem 0;
            }
            .img-wrapper {
                margin-top: 4rem;
            }
            .img-wrapper::before {
                right: 1.5rem; bottom: 1.5rem;
            }
            .img-wrapper::after {
                left: 1.5rem; top: 1.5rem;
            }
            .commitment-card {
                padding: 4rem 2rem;
                margin-top: -6rem;
            }
        }

        @media (max-width: 768px) {
            .hero-section { height: 30vh; min-height: 250px; } /* Reduced from 40vh/320px */
            .hero-content h1 { font-size: 2rem; letter-spacing: 3px; }
            .hero-content p { font-size: 0.85rem; letter-spacing: 1.5px; }
            .commitment-card {
                padding: 3rem 1.5rem;
                margin-top: -5rem;
                margin-left: 1rem;
                margin-right: 1rem;
            }
            .discover-section, .dark-wrapper, .craft-wrapper {
                padding: 5rem 0;
            }
            .craft-wrapper {
                padding-bottom: 8rem;
            }
            .discover-text {
                font-size: 0.95rem;
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <!-- PHP Includes -->
    <?php include_once('nav.php'); ?>
 
    <!-- Hero Section -->
    <header class="hero-section">
        <div class="hero-bg"></div>
        <div class="hero-overlay"></div>
        <div class="hero-content text-white px-4" data-aos="zoom-out" data-aos-duration="1500">
            <span class="subtitle text-accent" style="letter-spacing: 6px; font-size: 0.75rem;">Siddha Art Creation</span>
            <h1 class="display-4 font-serif mb-3">ABOUT SIDDHA ART</h1>
            <div class="deco-line"></div>
            <p class="fs-6 fw-light mb-0">A HAVEN WHERE ARTISTIC PASSION MEETS SKILLED HANDS</p>
        </div>
    </header>

    <!-- Discover Our Roots Section -->
    <section class="discover-section overflow-hidden">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 pe-lg-5 text-start" data-aos="fade-right" data-aos-duration="1200">
                    <span class="subtitle">Discover Our Roots</span>
                    <h2 class="display-5 mb-4 font-serif text-uppercase mt-2">A Pure Passion for Artistry</h2>
                    
                    <p class="discover-text mb-4">
                        The idea for Siddha Art Creation was born out of a profound love for traditional handcrafts. We set out to create a sanctuary where artistry is celebrated and preserved. From the first turn of the pottery wheel to the intricate weave of a textile, our passion for creating unique, authentic pieces is woven into every work.
                    </p>
                    
                    <div class="discover-quote">
                        "Art is not just what we make, but the connection we build between the past, the creator, and the collector."
                    </div>

                    <p class="discover-text mb-0">
                        We believe that true art is about connection and storytelling, and we are dedicated to making those connections tangible through our creations. We don't just create products; we craft moments of artistry and beauty that resonate across generations.
                    </p>
                </div>
                <div class="col-lg-6 text-center" data-aos="fade-left" data-aos-duration="1200" data-aos-delay="200">
                    <div class="img-wrapper mx-auto">
                        <img src="asset/image/about-pic.png" alt="Artisan making pottery" class="img-fluid discover-img">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dark Wrapper (Why Choose Us) -->
    <div class="dark-wrapper">
        <section class="container text-center">
            <div data-aos="fade-up" data-aos-duration="1000">
                <span class="subtitle text-accent">The Difference</span>
                <h3 class="display-6 text-white font-serif mb-2 mt-2">WHY CHOOSE US?</h3>
                <div class="ornament">
                    <i class="fa-solid fa-feather-pointed"></i>
                </div>
            </div>
            
            <div class="row g-4 justify-content-center mt-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="100">
                    <div class="dark-card">
                        <i class="fa-solid fa-dharmachakra card-icon"></i>
                        <h4>Authentic Handcrafts</h4>
                        <p>Our focus is on preserving traditional techniques and celebrating heritage, ensuring every piece tells a historical story.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="200">
                    <div class="dark-card">
                        <i class="fa-solid fa-stamp card-icon"></i>
                        <h4>Unique Art Pieces</h4>
                        <p>Each creation is an individual expression, meticulously made by hand so that no two items are exactly alike.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="300">
                    <div class="dark-card">
                        <i class="fa-solid fa-hands-holding-circle card-icon"></i>
                        <h4>Ethical Practices</h4>
                        <p>We prioritize responsible sourcing of all raw materials and actively support our local artisan communities.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- The Artisan Experience Section (New Distinct Light Theme) -->
    <div class="craft-wrapper">
        <section class="container text-center">
            <div data-aos="fade-up" data-aos-duration="1000">
                <span class="subtitle text-accent">Our Craft</span>
                <h2 class="display-6 font-serif mb-2 mt-2 text-uppercase">The Artisan Experience</h2>
                <div class="ornament">
                    <i class="fa-solid fa-circle-nodes"></i>
                </div>
            </div>
            
            <div class="row g-4 justify-content-center mt-4">
                <!-- Card 1: Pottery -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="100">
                    <div class="craft-card">
                        <div class="craft-img-container">
                            <img src="https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?q=80&w=600&auto=format&fit=crop" alt="Handmade Pottery">
                            <div class="craft-img-overlay"></div>
                            <div class="craft-card-icon-wrap">
                                <i class="fa-solid fa-jug-detergent"></i>
                            </div>
                        </div>
                        <div class="craft-card-body">
                            <h4>Handmade Pottery</h4>
                            <p>Explore our collection of wheel-thrown and hand-built ceramics, each glazed and fired with its own unique character.</p>
                            <a href="#" class="craft-explore">Explore Collection <i class="fa-solid fa-arrow-right-long"></i></a>
                        </div>
                    </div>
                </div>
                <!-- Card 2: Textiles -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="200">
                    <div class="craft-card">
                        <div class="craft-img-container">
                            <img src="https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=600&auto=format&fit=crop" alt="Textile Arts">
                            <div class="craft-img-overlay"></div>
                            <div class="craft-card-icon-wrap">
                                <i class="fa-solid fa-scroll"></i>
                            </div>
                        </div>
                        <div class="craft-card-body">
                            <h4>Textile Arts</h4>
                            <p>From intricate block printing to traditional hand weaving, our curated textiles are rich in texture and historical culture.</p>
                            <a href="#" class="craft-explore">Explore Collection <i class="fa-solid fa-arrow-right-long"></i></a>
                        </div>
                    </div>
                </div>
                <!-- Card 3: Jewelry -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="300">
                    <div class="craft-card">
                        <div class="craft-img-container">
                            <img src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?q=80&w=600&auto=format&fit=crop" alt="Artisan Jewelry">
                            <div class="craft-img-overlay"></div>
                            <div class="craft-card-icon-wrap">
                                <i class="fa-solid fa-hammer"></i>
                            </div>
                        </div>
                        <div class="craft-card-body">
                            <h4>Artisan Jewelry</h4>
                            <p>Discover intricate designs and exceptional quality craftsmanship in our custom-made, wearable jewelry pieces.</p>
                            <a href="#" class="craft-explore">Explore Collection <i class="fa-solid fa-arrow-right-long"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Our Commitment Floating Card Section -->
    <section class="commitment-container px-3 px-md-0">
        <div class="container">
            <div class="commitment-card" data-aos="fade-up" data-aos-duration="1200" data-aos-offset="100">
                <i class="fa-solid fa-palette commitment-icon"></i>
                <h3 class="font-serif">Our Commitment</h3>
                <p class="commitment-text">
                    We believe that art can transform our surroundings and enrich our lives. Our creations are meticulously crafted with a dedication to preserving traditional methods. We invite you to experience the beauty of handcraft and to connect with the artisans behind each piece.
                </p>
                <p class="font-serif text-accent commitment-footer mb-0">
                    Thank you for choosing Siddha Art Creation. Let's create something beautiful together.
                </p>
            </div>
        </div>
    </section>

    <!-- Footer Placeholder -->
    <?php include_once('footer.php');?>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation Library JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize Animate On Scroll
        AOS.init({
            once: true, // Animations happen only once on scroll down
            offset: 50, // Offset (in px) from the original trigger point
        });
    </script>
</body>
</html>