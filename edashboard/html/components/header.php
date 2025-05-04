<?php
session_start();
include("php/query.php");



// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    // Redirect to login page if not logged in
    echo "<script>location.assign('../../website/login.php');</script>";
    exit();
}

// Restrict access based on user_type
if ($_SESSION['user_type'] !== 'admin' && $_SESSION['user_type'] !== 'employee') {
    // Redirect to login page or an error page if the user is not admin or employee
    echo "<script>location.assign('../../website/login.php');</script>";
    exit();
}

// Example for updating order status in admin
function updateOrderStatus($pdo, $order_id, $new_status) {
  $sql = "UPDATE orders SET status = ?, delivery_status = ? WHERE order_id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$new_status, $new_delivery_status, $order_id]);
  
  // The trigger will automatically create the notification
  return true;
}
?>
<!DOCTYPE html>


<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/"
  data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Dashboard - The Crafty Corner</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="shortcut icon" href="../../website/images/logo.png" type="image/x-icon">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />


  <!-- Icons. Uncomment required icon fonts -->
  <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="../assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />

  <!-- Page CSS -->

  <!-- Helpers -->
  <script src="../assets/vendor/js/helpers.js"></script>


  <script src="../assets/js/config.js"></script>
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->

      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <a href="index.php" class="app-brand-link">
            <span class="app-brand-logo demo">
              <img src="../../website/images/logo.png" alt="Your Logo" style="height: 30px; width: auto;">
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2 ">Dashboard</span>
          </a>


          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Dashboard -->
          <li class="menu-item active">
            <a href="index.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-home-circle"></i>
              <div data-i18n="Analytics">Dashboard</div>
            </a>
          </li>

          <!-- ------------------------ -->

          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Income</span>
          </li>

          <!-- Wallet -->
          <li class="menu-item">
            <a href="wallet.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-wallet"></i>
              <div data-i18n="Basic">Wallet</div>
            </a>
          </li>

          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">CATEGORIES</span>
          </li>


          <!-- All Categories -->
          <li class="menu-item">
            <a href="allcategories.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-collection"></i>
              <div data-i18n="Basic">All Categories</div>
            </a>
          </li>
          <!-- Add Category -->
          <li class="menu-item">
            <a href="addcategory.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-collection"></i>
              <div data-i18n="Basic">Add Category</div>
            </a>
          </li>
          <!-- ----------------------------------- -->
          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">PRODUCT</span>

          </li>

          <!-- Add Product -->
          <li class="menu-item">
            <a href="allproducts.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-collection"></i>
              <div data-i18n="Basic">All Products</div>
            </a>
          </li>
          <!-- Add Product -->
          <li class="menu-item">
            <a href="addproduct.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-collection"></i>
              <div data-i18n="Basic">Add Product</div>
            </a>
          </li>

          <!-- ---------------------------------- -->
          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">EMPLOYEES DETAIL</span>
          </li>
          <!-- All Product -->
          <li class="menu-item">
            <a href="employee.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-collection"></i>
              <div data-i18n="Basic">Employee </div>
            </a>
          </li>
          <!-- Add Product -->
          <li class="menu-item">
            <a href="allemployees.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-collection"></i>
              <div data-i18n="Basic">All Employees</div>
            </a>
          </li>




          <!-- ---------------------------------- -->

          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Invoices</span>
          </li>
          <!-- Add Product -->
          <li class="menu-item">
            <a href="invoice.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-collection"></i>
              <div data-i18n="Basic">Customer Invoices</div>
            </a>
          </li>

          <!-- ---------------------------------- -->

          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Delivery</span>
          </li>
          <!-- Add Product -->
          <li class="menu-item">
            <a href="delivery_report.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-collection"></i>
              <div data-i18n="Basic">Delivery Status</div>
            </a>
          </li>

          <!-- -------------- -->
          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Orders</span>
          </li>
          <!-- Add Product -->
          <li class="menu-item">
            <a href="orders.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-collection"></i>
              <div data-i18n="Basic">Customer Orders</div>
            </a>
          </li>

          <!-- -------------- -->
          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Stock</span>
          </li>
          <!-- Add Product -->
          <li class="menu-item">
            <a href="stockupdate.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-collection"></i>
              <div data-i18n="Basic">Stock Update</div>
            </a>
          </li>

          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Promo Codes</span>
          </li>
          <!-- Add Product -->
          <li class="menu-item">
            <a href="promo_codes.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-collection"></i>
              <div data-i18n="Basic">Promo Codes Request</div>
            </a>
          </li>

          <!-- -------------- -->
          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Returns</span>
          </li>
          <!-- Add Product -->
          <li class="menu-item">
            <a href="returns_update.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-collection"></i>
              <div data-i18n="Basic">Returns Request</div>
            </a>
          </li>

          <!-- -------------- -->
          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Reviews</span>
          </li>
          <!-- Add Product -->
          <li class="menu-item">
            <a href="review_item.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-collection"></i>
              <div data-i18n="Basic">Customer Reviews</div>
            </a>
          </li>

          <!-- -------------- -->
          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Feedbacks</span>
          </li>
          <!-- Add Product -->
          <li class="menu-item">
            <a href="customer_feedback.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-collection"></i>
              <div data-i18n="Basic">Customer Feedbacks</div>
            </a>
          </li>

          <!-- -------------- -->
        </ul>
      </aside>
      <!-- / Menu -->
      <div class="layout-page">

        <!-- Layout container -->

        <!-- Navbar -->
        <nav
          class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
          id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="bx bx-menu bx-sm"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <div
                      class="avatar-initial rounded-circle bg-label-primary d-flex align-items-center justify-content-center"
                      style="width: 40px; height: 40px; background: linear-gradient(135deg, #7fad39, #5a8a1a);">
                      <i class="bx bx-user text-white" style="font-size: 1.2rem;"></i>
                    </div>
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                            <span class="avatar-initial rounded-circle bg-label-primary">
                              <?php 
                        if (isset($_SESSION['username'])) {
                          echo strtoupper(substr($_SESSION['username'], 0, 1));
                        }
                      ?>
                            </span>
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <span class="fw-semibold d-block">
                            <?php 
                      if (isset($_SESSION['full_name'])) {
                        echo htmlspecialchars($_SESSION['full_name']);
                      } else {
                        echo "User";
                      }
                    ?>
                          </span>
                          <small class="text-muted">
                            <?php 
                      if (isset($_SESSION['user_type'])) {
                        echo ucfirst($_SESSION['user_type']);
                      }
                    ?>
                          </small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="profile.php">
                      <i class="bx bx-user me-2"></i>
                      <span class="align-middle">My Profile</span>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="logout.php">
                      <i class="bx bx-power-off me-2"></i>
                      <span class="align-middle">Log Out</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!--/ User -->
            </ul>
          </div>
        </nav>
        <!-- / Navbar -->