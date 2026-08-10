<?php
include('includes/nav.php');
?>

<!-- Full-Width Hero Banner Section -->
<section class="hero-contact-section">
  <div class="hero-contact-overlay"></div>
  <div class="container position-relative z-2 h-100 d-flex align-items-center justify-content-center text-center" style="padding-top: 75px;">
    <div class="row justify-content-center w-100">
      <div class="col-12 col-lg-8">
        <span class="badge rounded-pill px-3 py-2 fw-semibold text-uppercase mb-3 animate-up delay-1" style="background-color: rgba(212, 175, 55, 0.15); color: #DFBA5A; border: 1px solid rgba(212, 175, 55, 0.35); backdrop-filter: blur(8px); letter-spacing: 1px;">
          <i class="fa-regular fa-paper-plane me-1"></i> We're Here For You
        </span>
        <h1 class="display-4 fw-bold text-white mb-3 animate-up delay-2" style="font-family: 'Playfair Display', serif;">
          Get In Touch With <span style="background: linear-gradient(135deg, #FFF0BD 0%, #DFBA5A 50%, #C59B27 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-style: italic;">Us</span>
        </h1>
        <div class="mx-auto mb-4 animate-up delay-2" style="width: 70px; height: 2px; background: linear-gradient(90deg, #DFBA5A 0%, #C59B27 100%);"></div>
        <p class="lead text-light mb-0 animate-up delay-3" style="font-family: 'Outfit', sans-serif; font-size: 1.15rem; color: #E2DDD5 !important; line-height: 1.6;">
          Have a question about our handmade clay idols, terracotta home decor, custom hand-sculpted clay statues, or order delivery? Our art curation team is always ready to assist you.
        </p>
      </div>
    </div>
  </div>
</section>

<div class="container my-5" style="max-width: 1140px;">

  <!-- Quick Info Cards (4 Columns) -->
  <div class="row g-4 mb-5 animate-up delay-3">
    <!-- Location Card -->
    <div class="col-md-6 col-lg-3">
      <div class="contact-card-info text-center text-md-start">
        <div class="contact-icon-box mx-auto mx-md-0">
          <i class="fa-solid fa-location-dot"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2" style="font-family: 'Playfair Display', serif;">Visit Our Workshop</h5>
        <p class="text-muted small mb-0">Uttar Nischinta, Analberia, West Bengal, 721444</p>
      </div>
    </div>

    <!-- Phone Card -->
    <div class="col-md-6 col-lg-3">
      <div class="contact-card-info text-center text-md-start">
        <div class="contact-icon-box mx-auto mx-md-0">
          <i class="fa-solid fa-phone"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2" style="font-family: 'Playfair Display', serif;">Call Us Directly</h5>
        <p class="text-muted small mb-1">+91 9775085649</p>
        <p class="text-muted small mb-0">+91 6297657671</p>
      </div>
    </div>

    <!-- Email Card -->
    <div class="col-md-6 col-lg-3">
      <div class="contact-card-info text-center text-md-start">
        <div class="contact-icon-box mx-auto mx-md-0">
          <i class="fa-regular fa-envelope"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2" style="font-family: 'Playfair Display', serif;">Email Inquiries</h5>
        <p class="text-muted small mb-1">siddhaartcreation@gmail.com</p>
      </div>
    </div>

    <!-- Working Hours Card -->
    <div class="col-md-6 col-lg-3">
      <div class="contact-card-info text-center text-md-start">
        <div class="contact-icon-box mx-auto mx-md-0">
          <i class="fa-regular fa-clock"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2" style="font-family: 'Playfair Display', serif;">Store Hours</h5>
        <p class="text-muted small mb-1">Mon–Sun: Open</p>
      </div>
    </div>
  </div>

  <!-- Get In Touch Form & Interactive Map Section -->
  <div class="row g-4 mb-5 align-items-stretch">
    <!-- Contact Form -->
    <div class="col-lg-6">
      <div class="contact-form-wrapper h-100">
        <h3 class="fw-bold text-dark mb-2" style="font-family: 'Playfair Display', serif;">Send Us A <span style="color: #9A6F09;">Message</span></h3>
        <p class="text-muted small mb-4">Fill in the details below and our art curation experts will reach out to you within 24 hours.</p>

        <form id="contactUsForm" onsubmit="submitContactForm(event)">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold text-dark">Your Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control form-control-contact" name="name" placeholder="John Doe" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold text-dark">Email Address <span class="text-danger">*</span></label>
              <input type="email" class="form-control form-control-contact" name="email" placeholder="john@example.com" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold text-dark">Phone Number</label>
              <input type="tel" class="form-control form-control-contact" name="phone" placeholder="+91 99999 99999">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold text-dark">Inquiry Subject</label>
              <select class="form-select form-control-contact" name="subject">
                <option value="General Inquiry">General Inquiry</option>
                <option value="Custom Artwork">Custom Artwork</option>
                <option value="Workshop Visit">Workshop Visit</option>
                <option value="Order & Delivery Support">Order & Delivery Support</option>
                <option value="Others">Others</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold text-dark">Your Message <span class="text-danger">*</span></label>
              <textarea class="form-control form-control-contact" name="message" rows="4" placeholder="How can we assist you today?" required></textarea>
            </div>
            <div class="col-12 mt-3">
              <button type="submit" class="btn btn-contact-submit w-100 w-md-auto" id="btnSubmitContact">
                <i class="fa-regular fa-paper-plane me-2"></i> Send Message
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Embedded Interactive Map -->
    <div class="col-lg-6">
      <div class="map-frame-wrapper">
       <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4380.527850166815!2d87.70801892531237!3d21.926086417003113!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a02d90060397891%3A0x89e04d62228802ea!2sSiddha%20art%20creation!5e0!3m2!1sen!2sin!4v1786337047288!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
      </div>
    </div>
  </div>

</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const contactUsForm = document.getElementById('contactUsForm');
    if (contactUsForm) {
      contactUsForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const btnSubmit = document.getElementById('btnSubmitContact');
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Sending...';

        setTimeout(() => {
          btnSubmit.disabled = false;
          btnSubmit.innerHTML = '<i class="fa-regular fa-paper-plane me-2"></i> Send Message';
          showToast('Thank you for contacting Siddha Art Creation! We will get back to you shortly.', 'success');
          contactUsForm.reset();
        }, 1200);
      });
    }
  });
  function submitContactForm(event) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('contactUsForm'));

    fetch('actions/contact_action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showToast(data.message, 'success');
            document.getElementById('contactUsForm').reset();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Something went wrong. Please try again.', 'error');
    });
  }
</script>

<?php include('includes/footer.php'); ?>