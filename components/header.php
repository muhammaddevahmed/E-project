
<?php
include 'php/db_connection.php';
include 'php/queries.php';
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$full_name = $is_logged_in && isset($_SESSION['full_name']) ? $_SESSION['full_name'] : "Guest User";


?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
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

    <style>
    .header__menu ul li a {
        font-size: 16px; /* Adjust the font size as needed */
    }

    .header__menu__dropdown li a {
        font-size: 16px; /* Adjust the dropdown font size as needed */
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
    <a href="shoping-cart.php">
        <i class="fa fa-shopping-bag"></i>
        <span id="cart-item-count">
            <?php
            $count = 0;
            if(isset($_SESSION['cart'])){
                $count = count($_SESSION['cart']);
            }
            echo $count;
            ?>
        </span> 
    </a>
</li>
    </ul>
    <div class="header__cart__price">
    Subtotal: <span id="cart-subtotal-price">
        <?php
        $subtotal = 0; // Initialize subtotal
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                if (isset($item['price']) && isset($item['quantity'])) {
                    $subtotal += $item['price'] * $item['quantity']; // Calculate subtotal
                }
            }
        }
        echo '$' . number_format($subtotal, 2); // Format and display subtotal
        ?>
    </span>
</div>
</div>
        <div class="humberger__menu__widget">
            <div class="header__top__right__language">
                <img src="img/language.png" alt="">
                <div>English</div>
                <span class="arrow_carrot-down"></span>
                <ul>
                    <li><a href="#">Spanis</a></li>
                    <li><a href="#">English</a></li>
                </ul>
            </div>
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
                <li><a href="#">Pages</a>
                    <ul class="header__menu__dropdown">
                        <li><a href="./returns.php">Returns </a></li>
                        <li><a href="./shoping-cart.php">Shoping Cart</a></li>
                        <li><a href="./checkout.php">Check Out</a></li>
                        
                    </ul>
                </li>
                <li><a href="./returns.php">Returns</a></li>
                <li><a href="./contact.php">Contact</a></li>
            </ul>
        </nav>
        <div id="mobile-menu-wrap"></div>
        <div class="header__top__right__social">
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-linkedin"></i></a>
            <a href="#"><i class="fa fa-pinterest-p"></i></a>
        </div>
        <div class="humberger__menu__contact">
            <ul>
                <li><i class="fa fa-envelope"></i></li>
                <li>Free Shipping for all Order of $99</li>
            </ul>
        </div>
    </div>
    <!-- Humberger End -->

    <!-- Header Section Begin -->
    <header class="header ">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__left">
                            <ul>
                            <li><i class="fa fa-envelope"></i> <?= $is_logged_in ? $full_name : 'Guest User' ?></li>
                                <li>Free Shipping for all Order of $99</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__right">
                            <div class="header__top__right__social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-linkedin"></i></a>
                                <a href="#"><i class="fa fa-pinterest-p"></i></a>
                            </div>
                            <div class="header__top__right__language">
                                <img src="img/language.png" alt="">
                                <div>English</div>
                                <span class="arrow_carrot-down"></span>
                                <ul>
                                    <li><a href="#">Spanis</a></li>
                                    <li><a href="#">English</a></li>
                                </ul>
                            </div>
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
                            <li><a href="#">Pages</a>
                                <ul class="header__menu__dropdown">
                                    <li><a href="./returns.php">Returns</a></li>
                                    <li><a href="./shoping-cart.php">Shoping Cart</a></li>
                                    <li><a href="./checkout.php">Check Out</a></li>
                                    
                                </ul>
                            </li>
                            <li><a href="./returns.php">Returns</a></li>
                            <li><a href="./contact.php">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__cart">
                    <ul>
      

        <li>
    <a href="shoping-cart.php">
        <i class="fa fa-shopping-bag"></i>
        <span id="cart-item-count">
            <?php
            $count = 0;
            if(isset($_SESSION['cart'])){
                $count = count($_SESSION['cart']);
            }
            echo $count;
            ?>
        </span> 
    </a>
</li>
    </ul>
    
    <div class="header__cart__price">
    Subtotal: <span id="cart-subtotal-price">
        <?php
        $subtotal = 0; // Initialize subtotal
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                if (isset($item['price']) && isset($item['quantity'])) {
                    $subtotal += $item['price'] * $item['quantity']; // Calculate subtotal
                }
            }
        }
        echo '$' . number_format($subtotal, 2); // Format and display subtotal
        ?>
    </span>
</div>


            </div>
            <div class="humberger__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
 </header>

 