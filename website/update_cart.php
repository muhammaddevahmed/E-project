<?php
session_start();

if (isset($_POST['id']) && isset($_POST['quantity'])) {
    $id = $_POST['id'];
    $quantity = (int) $_POST['quantity'];

    // Assuming you already have a session cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Update quantity in session
    $_SESSION['cart'][$id]['quantity'] = $quantity;

    echo "Cart updated successfully"; // Response for AJAX
}
?>
