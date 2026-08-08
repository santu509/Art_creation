<?php include_once('includes/nav.php');
include_once('includes/connection.php'); ?>


<!-- Redesigned Interactive Hero Banner -->
<header class="hero-banner-wrapper">
    <div class="hero-bg-image"></div>
    <div class="hero-overlay"></div>

    <div class="container hero-content text-light py-4">
        <span class="section-tagline animate-up delay-1"><i class="fa-solid fa-sparkles me-2"></i>SIDDHA ART CREATION</span>
        <h1 class="banner-title animate-up delay-2">Frequently Asked Questions</h1>
        <p class="hero-subtitle animate-up delay-3">
            Everything you need to know about the creation, uniqueness, and care of your masterpiece.
        </p>
    </div>

    <!-- SVG Bottom Curve Divider -->
    <div class="custom-shape-divider-bottom">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
        </svg>
    </div>
</header>

<!-- Glassmorphism Search Bar -->
<div class="container search-container animate-up delay-3">
    <div class="search-wrapper">
        <i class="bi bi-search text-gold fs-5 me-3"></i>
        <input type="text" id="faqSearch" class="search-input" placeholder="Search questions, mediums, or details..." autocomplete="off">
        <button class="search-btn" aria-label="Search">
            <i class="bi bi-arrow-right"></i>
        </button>
    </div>
</div>

<main class="container mb-5 mt-4">

   <!-- FAQ Accordion List -->
<div class="faq-list">

    <?php 
    global $connect;
    $sql = "SELECT * FROM faq ORDER BY id ASC";
    $run = mysqli_query($connect, $sql);

    // Check if the query ran successfully and if there is data
    if ($run && mysqli_num_rows($run) > 0) {
        // Using a while loop is more memory-efficient for fetching rows
        while ($faq = mysqli_fetch_assoc($run)) {
            
            // Securing the data to prevent XSS attacks
            $question = htmlspecialchars($faq['question'], ENT_QUOTES, 'UTF-8');
            
            // NOTE: If your 'answer' column contains actual HTML tags (like <b>, <i>, <br>) 
            // from a text editor, remove htmlspecialchars() below and just use $faq['answer']
            $answer = htmlspecialchars($faq['answer'], ENT_QUOTES, 'UTF-8');
    ?>

        <!-- FAQ Item -->
        <div class="faq-item animate-up delay-1">
            <button class="faq-trigger" aria-expanded="false">
                <span><?php echo $question; ?></span>
                <div class="faq-icon-box">
                    <i class="bi bi-chevron-down"></i>
                </div>
            </button>
            <div class="faq-content">
                <div class="faq-body">
                    <?php echo $answer; ?>
                </div>
            </div>
        </div>

    <?php
        } 
    } 
    else {
    ?>
        <!-- No FAQ Message -->
        <div class="no-faq-message" style="text-align: center; padding: 20px; background-color: #f8f9fa; border-radius: 8px;">
            <h4>No FAQs Available</h4>
            <p>There are currently no frequently asked questions available. Please check back later.</p>
        </div>
    <?php
    }
    ?>

</div>

        <!-- Empty State for Search -->
        <div class="empty-state" id="emptyState">
            <i class="bi bi-search-heart"></i>
            <h4 class="serif fs-3 mt-2 text-dark">No Questions Found</h4>
            <p class="text-muted">Try searching for other terms like 'ship', 'gold', or 'time'.</p>
        </div>

    </div>

    <!-- Contact Section -->
    <section class="contact-cta">
        <h3 class="serif fs-2 text-dark mb-3">Have a Unique Question?</h3>
        <p class="text-muted mb-4 max-width-md mx-auto fs-6">If you are interested in a specific layout, sized commission, or want to discuss the philosophy behind a piece, feel free to reach out to us directly.</p>
        <a href="contact.php" class="btn btn-custom-gold">Contact With Us</a>
    </section>

</main>

<!-- ========================================== -->
<!-- FOOTER PLACEHOLDER (Update with footer.php if needed)-->
<!-- ========================================== -->
<?php include_once('includes/footer.php'); ?>



<!-- Interactive Javascript Logic -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const faqItems = document.querySelectorAll('.faq-item');
        const searchInput = document.getElementById('faqSearch');
        const emptyState = document.getElementById('emptyState');

        // Open 1st FAQ item by default on load
        if (faqItems.length > 0) {
            const firstItem = faqItems[0];
            const firstTrigger = firstItem.querySelector('.faq-trigger');
            const firstContent = firstItem.querySelector('.faq-content');

            firstItem.classList.add('active');
            firstTrigger.setAttribute('aria-expanded', 'true');
            firstContent.style.maxHeight = firstContent.scrollHeight + 'px';
        }

        // 1. Accordion Toggle Logic
        faqItems.forEach(item => {
            const trigger = item.querySelector('.faq-trigger');
            const content = item.querySelector('.faq-content');

            trigger.addEventListener('click', () => {
                const isOpen = item.classList.contains('active');

                // Close all active items first for clean accordion look
                faqItems.forEach(otherItem => {
                    if (otherItem !== item && otherItem.classList.contains('active')) {
                        otherItem.classList.remove('active');
                        otherItem.querySelector('.faq-trigger').setAttribute('aria-expanded', 'false');
                        otherItem.querySelector('.faq-content').style.maxHeight = '0';
                    }
                });

                // Toggle current item
                if (isOpen) {
                    item.classList.remove('active');
                    trigger.setAttribute('aria-expanded', 'false');
                    content.style.maxHeight = '0';
                } else {
                    item.classList.add('active');
                    trigger.setAttribute('aria-expanded', 'true');
                    content.style.maxHeight = content.scrollHeight + 'px';
                }
            });
        });

        // 2. Dynamic Search logic
        function searchFAQ() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visibleCount = 0;

            faqItems.forEach(item => {
                const question = item.querySelector('.faq-trigger span').textContent.toLowerCase();
                const answer = item.querySelector('.faq-body').textContent.toLowerCase();

                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.classList.remove('hidden-element');
                    visibleCount++;
                } else {
                    item.classList.add('hidden-element');

                    // Reset accordion state on search hide
                    item.classList.remove('active');
                    item.querySelector('.faq-trigger').setAttribute('aria-expanded', 'false');
                    item.querySelector('.faq-content').style.maxHeight = '0';
                }
            });

            // Show/hide empty state message
            if (visibleCount === 0) {
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
            }
        }

        // Live Search Input Event
        searchInput.addEventListener('input', searchFAQ);
    });
</script>