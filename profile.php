<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once('includes/connection.php');
global $connect;
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
  header("Location: index.php");
  exit();
}

include('includes/nav.php');


$userId = $_SESSION['user_id'];
$safeuserId = mysqli_real_escape_string($connect, $userId);

$sql = "SELECT * FROM users WHERE id='$safeuserId'";
$run = mysqli_query($connect, $sql);

$user = mysqli_fetch_assoc($run);


$userName = htmlspecialchars($user['name'] ?? $_SESSION['user_name'] ?? 'Art Patron');
$userEmail = htmlspecialchars($user['email'] ?? $_SESSION['user_email'] ?? '');
$userPhone = htmlspecialchars($user['phone'] ?? '');
$userImage = (!empty($user['image']) && file_exists($user['image'])) ? htmlspecialchars($user['image']) : 'asset/image/default-image.jpg';
$createdAt = !empty($user['created_at']) ? date("F j, Y", strtotime($user['created_at'])) : 'Recent Member';

?>

<!-- Short Height Full-Width Top Page Banner -->
<section class="profile-top-banner">
  <div class="profile-top-overlay"></div>
</section>

<div class="container mb-5" style="max-width: 1000px; margin-top: -65px;">

  <!-- User Header Hero Card (Exact original layout) -->
  <div class="profile-hero-card p-4 p-md-5 mb-4 position-relative z-2">
    <div class="row align-items-center justify-content-center text-center text-md-start flex-column flex-md-row">

      <div class="col-12 col-md-auto d-flex justify-content-center mb-3 mb-md-0">
        <div class="profile-avatar-wrapper">
          <img src="<?php echo $userImage; ?>" alt="<?php echo $userName; ?>" class="profile-avatar-img" id="heroDisplayAvatar" onerror="this.onerror=null; this.src='asset/image/default-image.jpg';">
          <label for="quickAvatarInput" class="profile-avatar-edit-badge" title="Change Avatar">
            <i class="fa-solid fa-camera"></i>
          </label>
        </div>
      </div>

      <div class="col-12 col-md mt-2 mt-md-0">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-2 mb-1">
          <h2 class="mb-0 fw-semibold text-white" style="font-family: 'Playfair Display', serif;" id="heroDisplayName"><?php echo $userName; ?></h2>
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
                  <img src="<?php echo $userImage; ?>" id="editFormAvatarPreview" class="rounded-circle border border-2 border-warning" width="70" height="70" style="object-fit: cover; border-color: #B8860B !important;" onerror="this.onerror=null; this.src='asset/image/default-image.jpg';">
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

        fetch('actions/profile_action.php?action=update_profile', {
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
                const imgUrl = data.image + '?v=' + new Date().getTime();
                document.getElementById('heroDisplayAvatar').src = imgUrl;
                document.getElementById('editFormAvatarPreview').src = imgUrl;
                const navPic = document.getElementById('navProfilePic');
                const mobileNavPic = document.getElementById('mobileNavProfilePic');
                if (navPic) navPic.src = imgUrl;
                if (mobileNavPic) mobileNavPic.src = imgUrl;
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

<?php include('includes/footer.php'); ?>