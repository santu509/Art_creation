<?php
include('nav.php');

/** @var mysqli $connect */

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
  echo "<script>window.location.href='index.php';</script>";
  exit;
}

require_once('connection.php');

$userId = $_SESSION['user_id'];
$safeuserId = mysqli_real_escape_string($connect, $userId);

$sql = "SELECT * FROM users WHERE id='$safeuserId'";
$run = mysqli_query($connect, $sql);

$user = mysqli_fetch_assoc($run);


$userName = htmlspecialchars($user['name'] ?? $_SESSION['user_name'] ?? 'Art Patron');
$userEmail = htmlspecialchars($user['email'] ?? $_SESSION['user_email'] ?? '');
$userPhone = htmlspecialchars($user['phone'] ?? '');
$userImage = !empty($user['image']) ? htmlspecialchars($user['image']) : 'asset/image/default-image.jpg';
$createdAt = !empty($user['created_at']) ? date("F j, Y", strtotime($user['created_at'])) : 'Recent Member';

?>

<style>
  /* Profile Page Styling */
  .profile-hero-card {
    background: linear-gradient(135deg, #12110F 0%, #2A241E 100%);
    border-radius: 24px;
    color: #F5F2ED;
    position: relative;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(18, 17, 15, 0.15);
  }

  .profile-hero-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 350px;
    height: 350px;
    background: radial-gradient(circle, rgba(184, 134, 11, 0.15) 0%, transparent 70%);
    pointer-events: none;
  }

  .profile-avatar-wrapper {
    position: relative;
    width: 110px;
    height: 110px;
    border-radius: 50%;
    border: 4px solid #B8860B;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    background-color: #F5F2ED;
    margin-left: auto;
    margin-right: auto;
  }


  @media (min-width: 768px) {
    .profile-avatar-wrapper {
      margin-left: 0;
    }
  }

  .profile-avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
  }

  .profile-avatar-edit-badge {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 32px;
    height: 32px;
    background-color: #B8860B;
    color: #FFFFFF;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    border: 2px solid #12110F;
    cursor: pointer;
    transition: transform 0.2s ease;
  }

  .profile-avatar-edit-badge:hover {
    transform: scale(1.1);
  }

  .profile-tab-wrapper {
    background-color: #FFFFFF;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(58, 53, 48, 0.06);
    border: 1px solid rgba(58, 53, 48, 0.06);
    overflow: hidden;
  }

  .profile-nav-tabs {
    background-color: #F5F2ED;
    padding: 8px;
    border-bottom: 1px solid rgba(58, 53, 48, 0.08);
    gap: 8px;
  }

  .profile-nav-tabs .nav-link {
    color: #5C554E;
    font-weight: 600;
    font-size: 0.95rem;
    border: none;
    border-radius: 12px;
    padding: 12px 24px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .profile-nav-tabs .nav-link i {
    font-size: 1rem;
  }

  .profile-nav-tabs .nav-link.active {
    background-color: #FFFFFF !important;
    color: #B8860B !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  }

  .profile-nav-tabs .nav-link:hover:not(.active) {
    color: #3A3530;
    background-color: rgba(255, 255, 255, 0.5);
  }

  .info-grid-card {
    background-color: #FCFBFA;
    border: 1px solid #E5E1DB;
    border-radius: 16px;
    padding: 20px;
    height: 100%;
    transition: all 0.3s ease;
  }

  .info-grid-card:hover {
    border-color: #C5A880;
    box-shadow: 0 6px 20px rgba(184, 134, 11, 0.08);
  }

  .info-label {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: #8C857E;
    margin-bottom: 6px;
  }

  .info-value {
    font-size: 1rem;
    font-weight: 600;
    color: #3A3530;
    margin: 0;
    word-break: break-word;
  }

  .stat-mini-card {
    background-color: #FFFFFF;
    border: 1px solid #E5E1DB;
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.3s ease;
  }

  .stat-mini-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(58, 53, 48, 0.08);
  }

  .stat-icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 14px;
    background-color: rgba(184, 134, 11, 0.08);
    color: #B8860B;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
  }

  .form-control-profile {
    background-color: #FCFBFA;
    border: 1px solid #E5E1DB;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 0.95rem;
    color: #3A3530;
    transition: all 0.3s ease;
  }

  .form-control-profile:focus {
    background-color: #FFFFFF;
    border-color: #C5A880;
    box-shadow: 0 0 0 3px rgba(197, 168, 128, 0.15);
    outline: none;
  }

  .btn-save-profile {
    background-color: #B8860B;
    color: #FFFFFF;
    font-weight: 600;
    padding: 12px 30px;
    border-radius: 10px;
    border: none;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(184, 134, 11, 0.25);
  }

  .btn-save-profile:hover {
    background-color: #9A6F09;
    color: #FFFFFF;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(184, 134, 11, 0.35);
  }
</style>

<div class="container my-5 pt-4" style="max-width: 1000px;">

  <!-- User Header Hero Card -->
  <div class="profile-hero-card p-4 p-md-5 mb-4 mt-2 mt-lg-5">
    <div class="row align-items-center justify-content-center text-center text-md-start flex-column flex-md-row">

      <div class="col-12 col-md-auto d-flex justify-content-center mb-3 mb-md-0">
        <div class="profile-avatar-wrapper">
          <img src="<?php echo $userImage; ?>" alt="<?php echo $userName; ?>" class="profile-avatar-img" id="heroDisplayAvatar">
          <label for="quickAvatarInput" class="profile-avatar-edit-badge" title="Change Avatar">
            <i class="fa-solid fa-camera"></i>
          </label>
        </div>
      </div>

      <div class="col-12 col-md mt-2 mt-md-0">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-2 mb-1">
          <h2 class="mb-0 fw-semibold text-white" style="font-family: 'Playfair Display', serif;" id="heroDisplayName"><?php echo $userName; ?></h2>
          <span class="badge rounded-pill bg-warning text-dark px-3 py-2 fw-semibold fs-7" style="background-color: #C5A880 !important; color: #12110F !important;">
            <i class="fa-solid fa-crown me-1"></i> Art Patron
          </span>
        </div>
        <p class="mb-2 text-white-50" id="heroDisplayEmail"><i class="fa-regular fa-envelope me-2"></i><?php echo $userEmail; ?></p>
        <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-3 text-white-50 small">
          <span><i class="fa-regular fa-calendar me-1"></i>Joined: <?php echo $createdAt; ?></span>
          <span><i class="fa-solid fa-circle-check text-success me-1"></i>Verified Member</span>
        </div>
      </div>

    </div>
  </div>

  <!-- Main Tabbed Interface -->
  <div class="profile-tab-wrapper mb-5">
    <div class="nav nav-tabs profile-nav-tabs" id="profileTab" role="tablist">
      <button class="nav-link active" id="display-tab" data-bs-toggle="tab" data-bs-target="#display-content" type="button" role="tab" aria-controls="display-content" aria-selected="true">
        <i class="fa-regular fa-id-card"></i> My Overview
      </button>
      <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit-content" type="button" role="tab" aria-controls="edit-content" aria-selected="false">
        <i class="fa-solid fa-user-pen"></i> Update Details
      </button>
    </div>

    <div class="tab-content p-4 p-md-5" id="profileTabContent">

      <!-- TAB 1: Normal Display Tab -->
      <div class="tab-pane fade show active" id="display-content" role="tabpanel" aria-labelledby="display-tab">

        <h4 class="mb-4 text-dark" style="font-family: 'Playfair Display', serif; font-weight: 600;">Personal Details</h4>

        <!-- Information Grid -->
        <div class="row g-3 mb-5">
          <div class="col-md-6 col-lg-4">
            <div class="info-grid-card">
              <div class="info-label">Full Name</div>
              <p class="info-value" id="gridName"><?php echo $userName; ?></p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="info-grid-card">
              <div class="info-label">Email Address</div>
              <p class="info-value"><?php echo $userEmail; ?></p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="info-grid-card">
              <div class="info-label">Phone Number</div>
              <p class="info-value" id="gridPhone"><?php echo !empty($userPhone) ? $userPhone : 'Not Provided'; ?></p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="info-grid-card">
              <div class="info-label">Membership Status</div>
              <p class="info-value text-success"><i class="fa-solid fa-shield-halved me-1"></i> Active Member</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="info-grid-card">
              <div class="info-label">Joined Date</div>
              <p class="info-value"><?php echo $createdAt; ?></p>
            </div>
          </div>
        </div>

        <!-- Quick Stats / Shortcut Cards -->
        <h4 class="mb-4 text-dark" style="font-family: 'Playfair Display', serif; font-weight: 600;">Gallery Dashboard</h4>
        <div class="row g-3">
          <div class="col-md-4">
            <div class="stat-mini-card">
              <div class="stat-icon-circle">
                <i class="fa-solid fa-palette"></i>
              </div>
              <div>
                <h5 class="mb-0 fw-bold">0 Orders</h5>
                <small class="text-muted">Artwork Purchases</small>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="stat-mini-card">
              <div class="stat-icon-circle">
                <i class="fa-regular fa-heart"></i>
              </div>
              <div>
                <h5 class="mb-0 fw-bold">0 Items</h5>
                <small class="text-muted">Saved Wishlist</small>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- TAB 2: Update Profile Tab -->
      <div class="tab-pane fade" id="edit-content" role="tabpanel" aria-labelledby="edit-tab">

        <!-- Profile Information Form -->
        <div class="card border-0 bg-light p-4 mb-4 rounded-4" style="border: 1px solid #E5E1DB !important;">
          <h4 class="mb-4 text-dark" style="font-family: 'Playfair Display', serif; font-weight: 600;">
            <i class="fa-solid fa-pen-to-square me-2 text-warning" style="color: #B8860B !important;"></i> Edit Personal Info
          </h4>

          <form id="profileInfoForm" enctype="multipart/form-data">
            <!-- Hidden avatar quick file input sync -->
            <input type="file" id="quickAvatarInput" name="image" accept="image/*" style="display: none;" onchange="syncAvatarInput(this)">

            <div class="row g-3">
              <!-- Avatar Preview & Upload -->
              <div class="col-12 mb-3">
                <label class="form-label fw-semibold text-dark">Profile Picture</label>
                <div class="d-flex align-items-center gap-3">
                  <img src="<?php echo $userImage; ?>" id="editFormAvatarPreview" class="rounded-circle border border-2 border-warning" width="70" height="70" style="object-fit: cover; border-color: #B8860B !important;">
                  <div>
                    <input type="file" class="form-control form-control-profile" id="profileImageInput" name="image" accept="image/*" onchange="previewProfileImage(this)">
                    <small class="text-muted">Allowed formats: JPG, PNG, WEBP (Max 2MB)</small>
                  </div>
                </div>
              </div>

              <!-- Full Name -->
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold text-dark">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-profile" name="name" id="inputName" value="<?php echo $userName; ?>" required>
              </div>

              <!-- Phone Number -->
              <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold text-dark">Phone Number</label>
                <input type="tel" class="form-control form-control-profile" name="phone" id="inputPhone" value="<?php echo $userPhone; ?>" placeholder="+91 98765 43210">
              </div>

              <!-- Email Address (Read-only) -->
              <div class="col-12 mb-3">
                <label class="form-label fw-semibold text-dark">Email Address</label>
                <input type="email" class="form-control form-control-profile bg-white" value="<?php echo $userEmail; ?>" readonly disabled>
                <small class="text-muted"><i class="fa-solid fa-lock me-1"></i>Email address is verified and cannot be changed.</small>
              </div>

              <div class="col-12 mt-2">
                <button type="submit" class="btn btn-save-profile" id="btnSaveInfo">
                  <i class="fa-solid fa-floppy-disk me-2"></i> Save Profile Details
                </button>
              </div>
            </div>
          </form>
        </div>


      </div>
    </div>
  </div>
</div>

<script>
  // Realtime image preview
  function previewProfileImage(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('editFormAvatarPreview').src = e.target.result;
        document.getElementById('heroDisplayAvatar').src = e.target.result;
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  function syncAvatarInput(quickInput) {
    if (quickInput.files && quickInput.files[0]) {
      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(quickInput.files[0]);
      document.getElementById('profileImageInput').files = dataTransfer.files;
      previewProfileImage(quickInput);

      // Switch to edit tab automatically
      const editTabEl = document.getElementById('edit-tab');
      const tab = new bootstrap.Tab(editTabEl);
      tab.show();
    }
  }

  document.addEventListener("DOMContentLoaded", function() {

    // Profile Details Form Submission
    const profileInfoForm = document.getElementById('profileInfoForm');
    if (profileInfoForm) {
      profileInfoForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const btnSave = document.getElementById('btnSaveInfo');
        btnSave.disabled = true;
        btnSave.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...';

        const formData = new FormData(this);

        fetch('profile_action.php?action=update_profile', {
            method: 'POST',
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            btnSave.disabled = false;
            btnSave.innerHTML = '<i class="fa-solid fa-floppy-disk me-2"></i> Save Profile Details';

            if (data.status === 'success') {
              showToast(data.message, 'success');

              // Update DOM elements on page
              document.getElementById('heroDisplayName').innerText = data.name;
              document.getElementById('gridName').innerText = data.name;

              const newPhone = document.getElementById('inputPhone').value;
              document.getElementById('gridPhone').innerText = newPhone ? newPhone : 'Not Provided';

              if (data.image) {
                document.getElementById('heroDisplayAvatar').src = data.image;
                const navPic = document.getElementById('navProfilePic');
                const mobileNavPic = document.getElementById('mobileNavProfilePic');
                if (navPic) navPic.src = data.image;
                if (mobileNavPic) mobileNavPic.src = data.image;
              }
            } else {
              showToast(data.message, 'error');
            }
          })
          .catch(err => {
            btnSave.disabled = false;
            btnSave.innerHTML = '<i class="fa-solid fa-floppy-disk me-2"></i> Save Profile Details';
            showToast('Connection failed. Please try again.', 'error');
          });
      });
    }

  });
</script>

<?php include('footer.php'); ?>