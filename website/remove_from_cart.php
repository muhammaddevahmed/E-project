<?php
session_start(); // Start session

// Handle removing an item from the cart
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        $_SESSION['message'] = "Product removed from cart successfully!";
    }
    header("Location: shoping-cart.php");
    exit();
}
?>