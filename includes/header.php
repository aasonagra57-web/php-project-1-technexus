<?php 
// checks whether php session is started or not
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if user session does not exist
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Corona Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="assets/vendors/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/vendors/owl-carousel-2/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/vendors/owl-carousel-2/owl.theme.default.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
  <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
    <a class="sidebar-brand brand-logo text-white font-weight-bold text-decoration-none" href="index.php" style="font-size: 22px; letter-spacing: 1px;">
      <span style="color: #00d25b;">TECH</span>NEXUS
    </a>
    <a class="sidebar-brand brand-logo-mini text-white font-weight-bold text-decoration-none" href="index.php">TN</a>
  </div>
  
  <ul class="nav">
    <!-- User Profile Badge -->
    <li class="nav-item profile">
      <div class="profile-desc">
        <div class="profile-pic">
          <div class="count-indicator">
            <img class="img-xs rounded-circle" src="assets/images/faces/face15.jpg" alt="">
            <span class="count bg-success"></span>
          </div>
          <div class="profile-name"> 
          <!-- takes username and role when logged in -->
            <h5 class="mb-0 font-weight-normal"><?php echo htmlspecialchars($_SESSION['username']); ?></h5>
            <span><?php echo ucfirst($_SESSION['role']); ?></span>
          </div>
        </div>
      </div>
    </li>
    
    <li class="nav-item nav-category">
      <span class="nav-link">Navigation</span>
    </li>
    
    <!-- Dashboard Link -->
    <li class="nav-item menu-items">
      <a class="nav-link" href="index.php">
        <span class="menu-icon"><i class="mdi mdi-speedometer"></i></span>
        <span class="menu-title">Notice Board</span>
      </a>
    </li>

    <!-- Add Notice Link (Admin Only) if sessoin variable role exists and it strictly equal to admin -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <li class="nav-item menu-items">
      <a class="nav-link" href="add_notice.php">
        <span class="menu-icon"><i class="mdi mdi-playlist-plus"></i></span>
        <span class="menu-title">Post New Notice</span>
      </a>
    </li>
    <?php endif; ?> <!--used because non-admin users will not see this menu item-->

    <!-- Logout Link -->
    <li class="nav-item menu-items">
      <a class="nav-link" href="logout.php">
        <span class="menu-icon"><i class="mdi mdi-logout text-danger"></i></span>
        <span class="menu-title text-danger">Logout</span>
      </a>
    </li>
  </ul>
</nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar p-0 fixed-top d-flex flex-row">
          <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
            <a class="navbar-brand brand-logo-mini" href="index.php"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
          </div>
          <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
  <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
    <span class="mdi mdi-menu"></span>
  </button>
  
  <ul class="navbar-nav navbar-nav-right w-100 justify-content-end">
    <li class="nav-item dropdown">
      <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
        <div class="navbar-profile">
          <img class="img-xs rounded-circle" src="assets/images/faces/face15.jpg" alt="">
          <p class="mb-0 d-none d-sm-block navbar-profile-name"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
          <i class="mdi mdi-menu-down d-none d-sm-block"></i>
        </div>
      </a>
      <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
        <h6 class="p-3 mb-0">Profile Options</h6>
        <div class="dropdown-divider"></div>
        <a href="logout.php" class="dropdown-item preview-item">
          <div class="preview-thumbnail">
            <div class="preview-icon bg-dark rounded-circle">
              <i class="mdi mdi-logout text-danger"></i>
            </div>
          </div>
          <div class="preview-item-content">
            <p class="preview-subject mb-1">Log out</p>
          </div>
        </a>
      </div>
    </li>
  </ul>
  
  <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
    <span class="mdi mdi-format-line-spacing"></span>
  </button>
</div>