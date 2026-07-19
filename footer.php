<style>
    /* ==========================================
       Sidda Art Creation Luxury Footer Styling 
    ========================================== */
    :root {
        --footer-bg: #12110fe3;
        /* Deep Luxury Espresso Dark */
        --footer-card-bg: #1c1917bd;
        /* Subtle Dark Surface */
        --footer-gold: #B8860B;
        /* Warm Gold Accent */
        --footer-gold-light: #C5A880;
        /* Light Gold Highlight */
        --footer-cream: #F5F2ED;
        /* Warm Cream Off-White */
        --footer-muted: #A0968E;
        /* Soft muted text */
    }

    .footer_main {
        background: linear-gradient(180deg, #181512 0%, var(--footer-bg) 100%);
        color: var(--footer-cream);
        border-top: 3px solid var(--footer-gold);
        font-family: 'Outfit', 'DM Sans', sans-serif;
        padding-top: 2.4rem;
        padding-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .footer_main::before {
        content: '';
        position: absolute;
        top: -100px;
        left: 50%;
        transform: translateX(-50%);
        width: 600px;
        height: 200px;
        background: radial-gradient(circle, rgba(184, 134, 11, 0.08) 0%, transparent 70%);
        pointer-events: none;
    }

    .foot_upper {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-size: 1.35rem;
        color: var(--footer-gold-light);
        letter-spacing: 0.8px;
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
        width: 45px;
        height: 3px;
        background: linear-gradient(90deg, var(--footer-gold) 0%, rgba(184, 134, 11, 0.2) 100%);
        border-radius: 3px;
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
        color: var(--footer-muted);
        font-size: 0.98rem;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        display: inline-flex;
        align-items: center;
    }

    .foot_li a i {
        margin-right: 10px;
        font-size: 0.8rem;
        color: var(--footer-gold);
        transition: transform 0.3s ease;
    }

    /* Hover Animation for Links */
    .foot_li a:hover {
        color: var(--footer-cream);
        transform: translateX(6px);
    }

    .foot_li a:hover i {
        color: var(--footer-gold-light);
        transform: translateX(3px);
    }

    /* Contact Info Items */
    .footer-contact-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .footer-contact-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: rgba(184, 134, 11, 0.1);
        color: var(--footer-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        margin-right: 12px;
        flex-shrink: 0;
        border: 1px solid rgba(184, 134, 11, 0.2);
    }

    .footer_main p {
        color: var(--footer-muted);
        font-size: 0.95rem;
        margin-bottom: 0;
        line-height: 1.5;
    }

    /* Social Icons with Glowing Tooltips */
    .btn_foot {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(184, 134, 11, 0.3);
        color: var(--footer-gold-light);
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(5px);
        transition: all 0.35s cubic-bezier(0.25, 1, 0.5, 1);
        text-decoration: none;
        margin-right: 10px;
        margin-top: 12px;
        position: relative;
    }

    .btn_foot:hover {
        background: var(--footer-gold);
        color: #FFFFFF;
        border-color: var(--footer-gold);
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 8px 20px rgba(184, 134, 11, 0.35);
    }

    /* Map Frame Styling */
    .footer-map-container {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(184, 134, 11, 0.25);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
        transition: all 0.3s ease;
    }

    .footer-map-container:hover {
        border-color: var(--footer-gold);
        box-shadow: 0 10px 30px rgba(184, 134, 11, 0.2);
    }

    /* Custom Bootstrap Tooltip Styling Overrides */
    .tooltip-inner {
        background-color: var(--footer-gold) !important;
        color: #FFFFFF !important;
        font-weight: 600 !important;
        font-size: 0.8rem !important;
        padding: 6px 12px !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
    }
    .bs-tooltip-top .tooltip-arrow::before,
    .bs-tooltip-auto[data-popper-placement^="top"] .tooltip-arrow::before {
        border-top-color: var(--footer-gold) !important;
    }

    /* Scroll To Top Floating Button */
    .btn-scroll-top {
        position: fixed;
        bottom: 25px;
        right: 25px;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #B8860B 0%, #8B6508 100%);
        color: #FFFFFF;
        border: 2px solid rgba(245, 242, 237, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        cursor: pointer;
        z-index: 9990;
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px) scale(0.9);
        transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        box-shadow: 0 8px 25px rgba(184, 134, 11, 0.35);
        outline: none;
    }

    .btn-scroll-top.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }

    .btn-scroll-top:hover {
        background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
        color: #FFFFFF;
        transform: translateY(-5px) scale(1.08);
        box-shadow: 0 12px 30px rgba(184, 134, 11, 0.5);
    }

    .btn-scroll-top:active {
        transform: translateY(-2px) scale(0.98);
    }

    @media (max-width: 991px) {
        .btn-scroll-top {
            bottom: 75px; /* Offset above the mobile bottom nav bar */
            right: 20px;
            width: 44px;
            height: 44px;
            font-size: 1rem;
        }
    }

    /* Copyright Area */
    .copyright-area {
        border-top: 1px solid rgba(184, 134, 11, 0.15);
        padding-top: 1.5rem;
        margin-top: 8px;
        text-align: center;
    }

    .copyright-text {
        font-size: 0.92rem;
        color: var(--footer-muted);
        letter-spacing: 0.3px;
    }

    .copyright-text span {
        color: var(--footer-gold-light);
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
                    <li><a href="aboutus.php"><i class="fa-solid fa-chevron-right"></i> About Us</a></li>
                    <li><a href="menu.php"><i class="fa-solid fa-chevron-right"></i> Collections</a></li>
                    <li><a href="contact.php"><i class="fa-solid fa-chevron-right"></i> Contact US</a></li>
                    <li><a href="faq.php"><i class="fa-solid fa-chevron-right"></i> FAQ & Assistance</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6">
                <h4 class="foot_upper">Contact Us</h4>
                
                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <i class="fa fa-map-marker-alt"></i>
                    </div>
                    <p>Harinabari Durga Mondir Chaita Mali, West Bengal, 721444</p>
                </div>

                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <i class="fa fa-phone"></i>
                    </div>
                    <p>+91 12345 67890</p>
                </div>

                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <i class="fa fa-envelope"></i>
                    </div>
                    <p>santusau123@gmail.com</p>
                </div>

                <!-- Social Media Icons with Tooltips -->
                <div class="d-flex mt-3">
                    <a class="btn_foot" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Follow us on Twitter"><i class="fab fa-twitter"></i></a>
                    <a class="btn_foot" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Follow us on Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn_foot" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Subscribe on YouTube"><i class="fab fa-youtube"></i></a>
                    <a class="btn_foot" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Connect on LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Location Map -->
            <div class="col-lg-3 col-md-6">
                <h4 class="foot_upper">Locate Us</h4>
                <div class="footer-map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d29608.737075876234!2d87.67248027431641!3d21.931009200000027!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a02d90034ab0659%3A0xc1da4d48e4b096db!2sHarinabari%20Durga%20Mondir!5e0!3m2!1sen!2sin!4v1784434843070!5m2!1sen!2sin" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                </div>
            </div>

            <!-- Customer Care -->
            <div class="col-lg-3 col-md-6">
                <h4 class="foot_upper">Customer Care</h4>
                <ul class="foot_li">
                    <li><a href="#"><i class="fa fa-shopping-cart"></i> Shipping & Delivery</a></li>
                    <li><a href="#"><i class="fa-solid fa-file-contract"></i> Privacy Policy</a></li>
                    <li><a href="#"><i class="fa-solid fa-heart-circle-plus"></i> Cart & Favorite</a></li>
                    <li><a href="#"><i class="fa fa-question-circle"></i> FAQs</a></li>
                </ul>
            </div>

        </div>

        <!-- Copyright -->
        <div class="row copyright-area">
            <div class="col-12">
                <p class="copyright-text mb-0">
                    &copy; <?php echo date("Y"); ?> All Rights Reserved By <span>Siddha Art Creation</span>
                </p>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->

<!-- Floating Scroll To Top Button -->
<button type="button" class="btn-scroll-top" id="btnScrollTop" aria-label="Scroll to top">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<!-- JS Libraries -->
<script src="asset/bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
<script src="asset/bootstrap-5.3.7-dist/js/jquery.min.js"></script>

<script>
    // Initialize Bootstrap Tooltips for Social Icons and Scroll Button
    document.addEventListener("DOMContentLoaded", function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Scroll To Top Button Visibility & Smooth Scroll
        const btnScrollTop = document.getElementById('btnScrollTop');
        if (btnScrollTop) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 280) {
                    btnScrollTop.classList.add('show');
                } else {
                    btnScrollTop.classList.remove('show');
                }
            });

            btnScrollTop.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    });
</script>
</body>

</html>