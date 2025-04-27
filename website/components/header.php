<?php
include 'php/db_connection.php';
include 'php/queries.php';
session_start();

$is_logged_in = isset($_SESSION['user_id']);
$full_name = $is_logged_in && isset($_SESSION['full_name']) ? $_SESSION['full_name'] : "Guest User";

// Get unread notifications count
$notification_count = 0;
if ($is_logged_in) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->execute([$_SESSION['user_id']]);
    $notification_count = $stmt->fetchColumn();
}
// Store just the count in session
$_SESSION['notifications'] = $notification_count;
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="Ogani TEMPLATE">
  <meta name="keywords" content="Ogani, unica, creative, html">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>The Crafty Corner</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

  <!-- Css Styles -->
  <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
  <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
  <link rel="stylesheet" href="css/nice-select.css" type="text/css">
  <link rel="stylesheet" href="css/jquery-ui.min.css" type="text/css">
  <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
  <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
  <link rel="stylesheet" href="css/style.css" type="text/css">
  <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <style>
  .header__menu ul li a {
    font-size: 14px;
  }

  .header__menu__dropdown li a {
    font-size: 16px;
  }

  .header__cart .notification,
  .header__cart .wishlist,
  .humberger__menu__cart .notification,
  .humberger__menu__cart .wishlist {
    position: relative;
    margin-left: 15px;
    display: inline-block;
  }

  .header__cart .notification a,
  .header__cart .wishlist a,
  .humberger__menu__cart .notification a,
  .humberger__menu__cart .wishlist a {
    color: #7fad39;
    transition: color 0.3s ease;
  }

  .header__cart .notification a:hover,
  .header__cart .wishlist a:hover,
  .humberger__menu__cart .notification a:hover,
  .humberger__menu__cart .wishlist a:hover {
    color: #90EE90;
  }

  .header__cart .notification .badge,
  .header__cart .wishlist .badge,
  .humberger__menu__cart .notification .badge,
  .humberger__menu__cart .wishlist .badge {
    position: absolute;
    top: -10px;
    right: -10px;
    background-color: #FFFF00;
    color: #7fad39;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    font-weight: 600;
    transition: background-color 0.3s ease;
  }

  .humberger__menu__cart .notification .badge,
  .humberger__menu__cart .wishlist .badge {
    color: #000;
  }

  .header__cart .notification .badge:hover,
  .header__cart .wishlist .badge:hover,
  .humberger__menu__cart .notification .badge:hover,
  .humberger__menu__cart .wishlist .badge:hover {
    background-color: #FFFFE0;
  }
  </style>
</head>

<body>
  <!-- Page Preloder -->
  <div id="preloder">
    <div class="loader"></div>
  </div>

  <!-- Humberger Begin -->
  <div class="humberger__menu__overlay"></div>
  <div class="humberger__menu__wrapper">
    <div class="humberger__menu__logo">
      <a href="#"><img src="img/logo.png" alt=""></a>
    </div>
    <div class="humberger__menu__cart">
      <ul>
        <li>
          <a href="wishlist.php" class="wishlist">
            <i class="fa fa-heart"></i>
            <span id="wishlist-item-count">
              <?php echo $wishlist_count; ?>
            </span>
          </a>
        </li>
        <li>
          <a href="shoping-cart.php">
            <i class="fa fa-shopping-bag"></i>
            <span id="cart-item-count">
              <?php
                        $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                        echo $cart_count;
                        ?>
            </span>
          </a>
        </li>
      </ul>
      <div class="header__cart__price">
        Subtotal: <span id="cart-subtotal-price">
          <?php
                $subtotal = 0;
                if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) {
                        if (isset($item['price']) && isset($item['quantity'])) {
                            $subtotal += $item['price'] * $item['quantity'];
                        }
                    }
                }
                echo 'Rs ' . $subtotal;
                ?>
        </span>
        <span class="notification">
          <a href="notifications.php">
            <i class="fa fa-bell"></i>
            <span class="badge" id="notification-count">
              <?php
    // Check if it's an array first, then count, otherwise treat as direct count
    $notification_count = isset($_SESSION['notifications']) ? 
                         (is_array($_SESSION['notifications']) ? count($_SESSION['notifications']) : $_SESSION['notifications']) : 
                         0;
    echo $notification_count;
    ?>
            </span>
          </a>
        </span>
      </div>
    </div>
    <div class="humberger__menu__widget">
      <?php if ($is_logged_in): ?>
      <div class="header__top__right__language">
        <i class="fa fa-user"></i>
        <div>My Account</div>
        <span class="arrow_carrot-down"></span>
        <ul>
          <li><a href="profile.php">Profile</a></li>
          <li><a href="invoices.php">Invoices</a></li>
          <li><a href="orders_item.php">My Orders</a></li>
        </ul>
      </div>
      <?php endif; ?>
      <div class="header__top__right__auth">
        <?php if ($is_logged_in): ?>
        <a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
        <?php else: ?>
        <a href="login.php"><i class="fa fa-user"></i> Login</a>
        <?php endif; ?>
      </div>
    </div>
    <nav class="humberger__menu__nav mobile-menu">
      <ul>
        <li class="active"><a href="./index.php">Home</a></li>
        <li><a href="./shop-grid.php">Shop</a></li>
        <li class="active"><a href="./about.php">About Us</a></li>
        <li><a href="./returns.php">Returns</a></li>
        <li><a href="./contact.php">Contact</a></li>
      </ul>
    </nav>
    <div id="mobile-menu-wrap"></div>
    <div class="header__top__right__social">
      <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
      <a href="https://twitter.com/"><i class="fa-brands fa-twitter"></i></a>
      <a href="https://www.linkedin.com"><i class="fab fa-linkedin-in"></i></a>
      <a href="https://www.pinterest.com/"><i class="fab fa-pinterest-p"></i></a>
    </div>
    <div class="humberger__menu__contact">
      <ul>
        <li><i class="fa fa-envelope"></i></li>
        <li>Free Shipping for all Order of Rs 2000</li>
      </ul>
    </div>
  </div>
  <!-- Humberger End -->

  <!-- Header Section Begin -->
  <header class="header">
    <div class="header__top">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 col-md-6">
            <div class="header__top__left">
              <ul>
                <li><i class="fa fa-envelope"></i> <?= $is_logged_in ? $full_name : 'Guest User' ?></li>
                <li>Free Shipping for all Order of Rs.2000</li>
              </ul>
            </div>
          </div>
          <div class="col-lg-6 col-md-6">
            <div class="header__top__right">
              <div class="header__top__right__social">
                <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                <a href="https://twitter.com/"><i class="fa-brands fa-twitter"></i></a>
                <a href="https://www.linkedin.com"><i class="fab fa-linkedin-in"></i></a>
                <a href="https://www.pinterest.com/"><i class="fab fa-pinterest-p"></i></a>
              </div>
              <?php if ($is_logged_in): ?>
              <div class="header__top__right__language">
                <i class="fa fa-user"></i>
                <div>My Account</div>
                <span class="arrow_carrot-down"></span>
                <ul>
                  <li><a href="profile.php">Profile</a></li>
                  <li><a href="invoices.php">Invoices</a></li>
                  <li><a href="orders_item.php">My Orders</a></li>
                </ul>
              </div>
              <?php endif; ?>
              <div class="header__top__right__auth">
                <?php if ($is_logged_in): ?>
                <a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
                <?php else: ?>
                <a href="login.php"><i class="fa fa-user"></i> Login</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-lg-3">
          <div class="header__logo">
            <a href="./index.php"><img src="images/logo.png" alt=""></a>
          </div>
        </div>
        <div class="col-lg-6">
          <nav class="header__menu">
            <ul>
              <li class="active"><a href="./index.php">Home</a></li>
              <li><a href="./shop-grid.php">Shop</a></li>
              <li><a href="./about.php">About</a></li>
              <li><a href="./returns.php">Returns</a></li>
              <li><a href="./contact.php">Contact</a></li>
            </ul>
          </nav>
        </div>
        <div class="col-lg-3">
          <div class="header__cart">
            <ul>
              <li>
                <a href="wishlist.php" class="wishlist">
                  <i class="fa fa-heart"></i>
                  <span id="wishlist-item-count">
                    <?php echo $wishlist_count; ?>
                  </span>
                </a>
              </li>
              <li>
                <a href="shoping-cart.php">
                  <i class="fa fa-shopping-bag"></i>
                  <span id="cart-item-count">
                    <?php
                                    $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                                    echo $cart_count;
                                    ?>
                  </span>
                </a>
              </li>
            </ul>
            <div class="header__cart__price">
              Subtotal: <span id="cart-subtotal-price">
                <?php
                            $subtotal = 0;
                            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                                foreach ($_SESSION['cart'] as $item) {
                                    if (isset($item['price']) && isset($item['quantity'])) {
                                        $subtotal += $item['price'] * $item['quantity'];
                                    }
                                }
                            }
                            echo 'Rs ' . $subtotal;
                            ?>
              </span>
              <span class="notification">
                <a href="notifications.php">
                  <i class="fa fa-bell"></i>
                  <span class="badge" id="notification-count">
                    <?php
    // Check if it's an array first, then count, otherwise treat as direct count
    $notification_count = isset($_SESSION['notifications']) ? 
                         (is_array($_SESSION['notifications']) ? count($_SESSION['notifications']) : $_SESSION['notifications']) : 
                         0;
    echo $notification_count;
    ?>
                </a>
              </span>
            </div>
          </div>
          <div class="humberger__open">
            <i class="fa fa-bars"></i>
          </div>
        </div>
      </div>
    </div>
  </header>