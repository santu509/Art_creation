<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
    /* ==========================================
       Cafe Theme Variables 
    ========================================== */
    :root {
        --cafe-dark: #342319;
        /* Deep Espresso Brown */
        --cafe-primary: #A67B5B;
        /* Warm Caramel / Coffee */
        --cafe-bg: #FAF8F5;
        /* Warm Creamy Off-White */
        --text-muted-dark: #B0A39A;
        /* Soft greyish brown for text */
    }

    /* ==========================================
       Premium Footer Styling
    ========================================== */
    .footer_main {
        background-color: var(--cafe-dark);
        color: var(--cafe-bg);
        border-top: 3px solid var(--cafe-primary);
        font-family: 'DM Sans', sans-serif;
        padding-top: 3rem;
        padding-bottom: 1.5rem;
    }

    .foot_upper {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        color: var(--cafe-primary);
        letter-spacing: 1px;
        position: relative;
        padding-bottom: 12px;
        margin-bottom: 25px;
    }

    /* Decorative Line under Headings */
    .foot_upper::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 2px;
        background-color: var(--cafe-primary);
        border-radius: 2px;
    }

    /* Footer Links */
    .foot_li {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .foot_li li {
        margin-bottom: 12px;
    }

    .foot_li a {
        text-decoration: none;
        color: var(--text-muted-dark);
        font-size: 1.05rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }

    .foot_li a i {
        margin-right: 8px;
        font-size: 0.8rem;
        color: var(--cafe-primary);
        transition: all 0.3s ease;
    }

    /* Hover Animation for Links */
    .foot_li a:hover {
        color: var(--cafe-primary);
        transform: translateX(8px);
    }

    .foot_li a:hover i {
        transform: translateX(4px);
    }

    /* Contact & Info Text */
    .footer_main p {
        color: var(--text-muted-dark);
        font-size: 1rem;
        margin-bottom: 12px;
    }

    .footer_main p i {
        color: var(--cafe-primary);
        margin-right: 12px;
        font-size: 1.1rem;
    }

    .footer_main h5 {
        color: var(--cafe-bg);
        margin-top: 15px;
        font-family: 'Playfair Display', serif;
    }

    /* Social Icons */
    .btn_foot {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(166, 123, 91, 0.4);
        color: var(--cafe-primary);
        background: transparent;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
        margin-right: 8px;
        margin-top: 10px;
    }

    .btn_foot:hover {
        background: var(--cafe-primary);
        color: #ffffff;
        border-color: var(--cafe-primary);
        transform: translateY(-4px);
        box-shadow: 0 6px 15px rgba(166, 123, 91, 0.3);
    }

    /* Newsletter Input */
    .newsletter-input {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(166, 123, 91, 0.3);
        color: var(--cafe-bg);
        border-radius: 30px;
        padding-left: 20px;
    }

    .newsletter-input:focus {
        border-color: var(--cafe-primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(166, 123, 91, 0.15);
        background: rgba(255, 255, 255, 0.08);
    }

    .newsletter-input::placeholder {
        color: rgba(176, 163, 154, 0.6);
    }

    /* Subscribe Button */
    .foot_sign_up_btn {
        background: linear-gradient(135deg, #A67B5B 0%, #8B5A2B 100%);
        color: #ffffff;
        border: none;
        font-weight: 600;
        border-radius: 30px;
        padding: 0 25px;
        transition: all 0.3s ease;
        top: 4px;
        right: 4px;
        bottom: 4px;
        height: calc(100% - 8px);
    }

    .foot_sign_up_btn:hover {
        background: linear-gradient(135deg, #8B5A2B 0%, #5a2a02 100%);
        transform: scale(0.98);
    }

    /* Copyright Area */
    .copyright-area {
        border-top: 1px solid rgba(166, 123, 91, 0.15);
        margin-top: 3rem;
        padding-top: 1.5rem;
        text-align: center;
    }

    .copyright-text {
        font-size: 0.95rem;
        color: var(--text-muted-dark);
    }

    .copyright-text span {
        color: var(--cafe-primary);
        font-weight: 600;
    }
</style>

<!-- Footer Start -->
<div class="container-fluid footer_main wow fadeIn" data-wow-delay="0.1s">
    <div class="container">
        <div class="row g-5">

            <!-- Company Links -->
            <div class="col-lg-3 col-md-6">
                <h4 class="foot_upper">Explore</h4>
                <ul class="foot_li">
                    <li><a href="index.php"><i class="fa-solid fa-chevron-right"></i> Home</a></li>
                    <li><a href="about_us.php"><i class="fa-solid fa-chevron-right"></i> About Us</a></li>
                    <li><a href="menu.php"><i class="fa-solid fa-chevron-right"></i> Menu</a></li>
                    <li><a href="contact_us.php"><i class="fa-solid fa-chevron-right"></i> Contact</a></li>
                    <li><a href="services.php"><i class="fa-solid fa-chevron-right"></i> Our Service</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6">
                <h4 class="foot_upper">Contact Us</h4>
                <p><i class="fa fa-map-marker-alt"></i>CCLMS, Contai, India</p>
                <p><i class="bi bi-telephone"></i>+91 12345 67890</p>
                <p><i class="fa fa-envelope"></i>santusau123@gmail.com</p>

                <div class="d-flex mt-3">
                    <a class="btn_foot" href="#"><i class="fab fa-twitter"></i></a>
                    <a class="btn_foot" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn_foot" href="#"><i class="fab fa-youtube"></i></a>
                    <a class="btn_foot" href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Opening Hours -->
            <div class="col-lg-3 col-md-6">
                <h4 class="foot_upper">Customer Care</h4>
                <ul class="foot_li">
                        <li><a href="#"><i class="fa fa-shopping-cart"></i> Shipping Policy</a></li>
                        <li><a href="#"><i class="fa-solid fa-heart-circle-plus"></i> Cart & Favorite</a></li>
                        <li><a href="#"><i class="fa fa-question-circle"></i> FAQs</a></li>
                    </ul>
                
            </div>

            <!-- Newsletter -->
            <div class="col-lg-3 col-md-6">
                <h4 class="foot_upper">Newsletter</h4>
                <p>Subscribe to get our latest updates, offers and more.</p>
                
            </div>

        </div>

        <!-- Copyright -->
        <div class="row copyright-area">
            <div class="col-12">
                <p class="copyright-text mb-0">
                    &copy; <?php echo date("Y"); ?> All Rights Reserved By Siddha Art Creation</span>
                </p>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->

<!-- JS Libraries -->
<script src="asset/bootstrap-5.3.7-dist/js/jquery.min.js"></script>
<script src="asset/bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>