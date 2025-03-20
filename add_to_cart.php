<?php
// Include database connection
include("php/db_connection.php");

// Start the session (if using session-based cart)
session_start();

// Debugging: Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Form submitted!<br>"; // Debugging message
    print_r($_POST); // Debugging: Print form data

    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']);

    // Validate input
    if (empty($product_id) || empty($quantity) || $quantity < 1) {
        die("Invalid input.");
    }

    // Check if the product already exists in the cart for the user
    $user_id = $_SESSION['user_id'] ?? 0; // Replace with actual user ID if you have user authentication
    $sql = "SELECT * FROM Cart WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);
    $stmt->execute();
    $existing_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_item) {
        // Update the quantity if the product already exists in the cart
        $new_quantity = $existing_item['quantity'] + $quantity;
        $sql = "UPDATE Cart SET quantity = :quantity WHERE cart_id = :cart_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':quantity', $new_quantity, PDO::PARAM_INT);
        $stmt->bindParam(':cart_id', $existing_item['cart_id'], PDO::PARAM_INT);
        $stmt->execute();
    } else {
        // Insert new item into the cart
        $sql = "INSERT INTO Cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Redirect back to the product page or cart page
    header("Location: product-details.php?id=$product_id");
    exit();
} else {
    echo "Form not submitted."; // Debugging message
}
?>