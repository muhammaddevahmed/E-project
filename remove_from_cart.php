<?php
// Include database connection
include("php/db_connection.php");

// Start the session (if using session-based cart)
session_start();

// Check if cart_id is provided
if (isset($_GET['cart_id']) && !empty($_GET['cart_id'])) {
    $cart_id = $_GET['cart_id'];

    // Delete the item from the cart
    $sql = "DELETE FROM Cart WHERE cart_id = :cart_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $stmt->execute();
}

// Redirect back to the cart page
header("Location: shoping-cart.php");
exit();
?>