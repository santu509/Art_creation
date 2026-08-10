<!-- Footer Start -->
<footer class="container-fluid footer_main wow fadeIn" data-wow-delay="0.1s">
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
                    <p>Uttar Nischinta, Analberia, West Bengal, 721444</p>
                </div>

                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <i class="fa fa-phone"></i>
                    </div>
                    <div class="d-flex flex-column align-items-center">
                        <a href="tel:+919775085649" class="text-decoration-none">
                            <p>+91 9775085649</p>
                        </a>
                        <a href="tel:+916297657671" class="text-decoration-none">
                            <p>+91 6297657671</p>
                        </a>
                    </div>
                </div>

                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <i class="fa fa-envelope"></i>
                    </div>
                    <p>siddhaartcreation@gmail.com</p>
                </div>

                <!-- Social Media Icons with Tooltips -->
                <!-- <div class="d-flex mt-3">
                    <a class="btn_foot" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Follow us on Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn_foot" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Subscribe on YouTube"><i class="fab fa-youtube"></i></a>
                    <a class="btn_foot" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Follow on Instagram"><i class="fa-brands fa-instagram"></i></a>
                </div> -->
            </div>

            <!-- Location Map -->
            <div class="col-lg-3 col-md-6">
                <h4 class="foot_upper">Locate Us</h4>
                <div class="footer-map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4380.527850166815!2d87.70801892531237!3d21.926086417003113!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a02d90060397891%3A0x89e04d62228802ea!2sSiddha%20art%20creation!5e0!3m2!1sen!2sin!4v1786337047288!5m2!1sen!2sin" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                </div>
            </div>

            <!-- Customer Care -->
            <div class="col-lg-3 col-md-6">
                <h4 class="foot_upper">Customer Care</h4>
                <ul class="foot_li">
                    <li><a href="#"><i class="fa fa-shopping-cart"></i> Shipping & Delivery</a></li>
                    <li><a href="#"><i class="fa-solid fa-file-contract"></i> Privacy Policy</a></li>
                    <li><a href="wishlist.php"><i class="fa-solid fa-heart-circle-plus"></i> Cart & Favorite</a></li>
                    <li><a href="faq.php"><i class="fa fa-question-circle"></i> FAQs</a></li>
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
</footer>
<!-- Footer End -->

<!-- Floating Scroll To Top Button -->
<button type="button" class="btn-scroll-top" id="btnScrollTop" aria-label="Scroll to top">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<!-- JS Libraries -->
<script src="asset/bootstrap-5.3.7-dist/js/bootstrap.bundle.min.js"></script>
<script src="asset/bootstrap-5.3.7-dist/js/jquery.min.js"></script>
<!-- AOS Animation Library JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    // Initialize Bootstrap Tooltips for Social Icons and Scroll Button
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize AOS (Animate On Scroll)
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 1000,
                once: true,
                offset: 50
            });
        }

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
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