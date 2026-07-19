<?php
include('nav.php');
?>

<style>
  /* Contact Page Styling */
  .contact-hero {
    background: linear-gradient(135deg, #12110F 0%, #2A241E 100%);
    border-radius: 24px;
    color: #F5F2ED;
    position: relative;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(18, 17, 15, 0.15);
  }

  .contact-hero::before {
    content: '';
    position: absolute;
    top: -40%;
    right: -10%;
    width: 380px;
    height: 380px;
    background: radial-gradient(circle, rgba(184, 134, 11, 0.18) 0%, transparent 70%);
    pointer-events: none;
  }

  .contact-card-info {
    background-color: #FFFFFF;
    border: 1px solid #E5E1DB;
    border-radius: 18px;
    padding: 26px 20px;
    transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
    height: 100%;
    box-shadow: 0 4px 15px rgba(58, 53, 48, 0.03);
  }

  .contact-card-info:hover {
    transform: translateY(-5px);
    border-color: #C5A880;
    box-shadow: 0 12px 30px rgba(184, 134, 11, 0.12);
  }

  .contact-icon-box {
    width: 55px;
    height: 55px;
    border-radius: 14px;
    background-color: rgba(184, 134, 11, 0.08);
    color: #B8860B;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    margin-bottom: 18px;
    transition: all 0.3s ease;
  }

  .contact-card-info:hover .contact-icon-box {
    background-color: #B8860B;
    color: #FFFFFF;
    transform: scale(1.08);
  }

  .contact-form-wrapper {
    background-color: #FFFFFF;
    border-radius: 20px;
    border: 1px solid rgba(58, 53, 48, 0.08);
    box-shadow: 0 10px 30px rgba(58, 53, 48, 0.05);
    padding: 35px;
  }

  .form-control-contact {
    background-color: #FCFBFA;
    border: 1px solid #E5E1DB;
    border-radius: 12px;
    padding: 14px 18px;
    font-size: 0.98rem;
    color: #3A3530;
    transition: all 0.3s ease;
  }

  .form-control-contact:focus {
    background-color: #FFFFFF;
    border-color: #C5A880;
    box-shadow: 0 0 0 4px rgba(197, 168, 128, 0.15);
    outline: none;
  }

  .btn-contact-submit {
    background-color: #B8860B;
    color: #FFFFFF;
    font-weight: 600;
    padding: 14px 36px;
    border-radius: 12px;
    border: none;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(184, 134, 11, 0.25);
  }

  .btn-contact-submit:hover {
    background-color: #9A6F09;
    color: #FFFFFF;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(184, 134, 11, 0.35);
  }

  .map-frame-wrapper {
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid #E5E1DB;
    box-shadow: 0 10px 30px rgba(58, 53, 48, 0.06);
    height: 100%;
    min-height: 420px;
  }
</style>

<div class="container my-5 pt-3 pt-lg-4 " style="max-width: 1140px;">

  <!-- Contact Hero Header -->
  <div class="contact-hero p-4 p-md-5 mb-5 mt-3 mt-lg-5">
    <div class="row align-items-center text-center text-md-start">
      <div class="col-lg-8">
        <span class="badge rounded-pill px-3 py-2 fw-semibold mb-3" style="background-color: rgba(184, 134, 11, 0.2); color: #C5A880; border: 1px solid rgba(184, 134, 11, 0.3);">
          <i class="fa-regular fa-paper-plane me-1"></i> We're Here For You
        </span>
        <h1 class="display-5 fw-bold text-white mb-3" style="font-family: 'Playfair Display', serif;">Get In Touch With Us</h1>
        <p class="lead text-white-50 mb-0" style="font-size: 1.1rem; max-width: 650px;">
          Have a question about our handmade products, custom orders, or delivery? Our team is always ready to assist you.
        </p>
      </div>
      <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
        <div class="d-inline-flex p-3 rounded-circle" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
          <i class="fa-solid fa-palette text-warning display-4" style="color: #B8860B !important;"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Info Cards (4 Columns) -->
  <div class="row g-4 mb-5">
    <!-- Location Card -->
    <div class="col-md-6 col-lg-3">
      <div class="contact-card-info text-center text-md-start">
        <div class="contact-icon-box mx-auto mx-md-0">
          <i class="fa-solid fa-location-dot"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2" style="font-family: 'Playfair Display', serif;">Visit Our Workshop</h5>
        <p class="text-muted small mb-0">Harinabari Durga Mondir Chaita Mali, West Bengal 721444</p>
      </div>
    </div>

    <!-- Phone Card -->
    <div class="col-md-6 col-lg-3">
      <div class="contact-card-info text-center text-md-start">
        <div class="contact-icon-box mx-auto mx-md-0">
          <i class="fa-solid fa-phone"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2" style="font-family: 'Playfair Display', serif;">Call Us Directly</h5>
        <p class="text-muted small mb-1">+91 12345 67890</p>
        <p class="text-muted small mb-0">+91 98765 43210</p>
      </div>
    </div>

    <!-- Email Card -->
    <div class="col-md-6 col-lg-3">
      <div class="contact-card-info text-center text-md-start">
        <div class="contact-icon-box mx-auto mx-md-0">
          <i class="fa-regular fa-envelope"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2" style="font-family: 'Playfair Display', serif;">Email Inquiries</h5>
        <p class="text-muted small mb-1">santusau123@gmail.com</p>
        <p class="text-muted small mb-0">support@siddhaart.com</p>
      </div>
    </div>

    <!-- Working Hours Card -->
    <div class="col-md-6 col-lg-3">
      <div class="contact-card-info text-center text-md-start">
        <div class="contact-icon-box mx-auto mx-md-0">
          <i class="fa-regular fa-clock"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2" style="font-family: 'Playfair Display', serif;">Store Hours</h5>
        <p class="text-muted small mb-1">Mon - Sat: 10:00 AM - 7:00 PM</p>
        <p class="text-muted small mb-0">Sunday: Closed</p>
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
                <option value="Custom Artwork ">Custom Artwork</option>
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
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d29608.737075876234!2d87.67248027431641!3d21.931009200000027!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a02d90034ab0659%3A0xc1da4d48e4b096db!2sHarinabari%20Durga%20Mondir!5e0!3m2!1sen!2sin!4v1784434843070!5m2!1sen!2sin" width="100%" height="100%" style="border:0; min-height: 420px;" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
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

    fetch('contact_action.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          showToast(data.message);
          document.getElementById('contactUsForm').reset();
        } else {
          showToast(data.message);
        }
      });
  }
</script>

<?php include('footer.php'); ?>