<?php
include("php/query.php");

?>
<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Dashboard - The Crafty Corner</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="../../website/images/logo.png" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

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

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
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
              <a href="addproduct.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-collection"></i>
                <div data-i18n="Basic">Add Product</div>
              </a>
            </li>
                                    <!-- Add Product -->
                                    <li class="menu-item">
              <a href="allproducts.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-collection"></i>
                <div data-i18n="Basic">ALL Product</div>
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

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          

          <!-- / Navbar -->