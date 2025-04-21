<?php
session_start();


if (isset($_POST['id']) && isset($_POST['quantity'])) {
    $id = $_POST['id'];
    $quantity = (int)$_POST['quantity'];

    // Check stock quantity
    try {
        $query = "SELECT stock_quantity FROM products WHERE product_id = :product_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['product_id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $stock_quantity = $row ? $row['stock_quantity'] : 0;
        if ($quantity > $stock_quantity) {
            echo "Error: Quantity exceeds available stock.";
            exit();
        }

        // Update quantity in session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $_SESSION['cart'][$id]['quantity'] = $quantity;

        echo "Cart updated successfully"; // Response for AJAX
    } catch (PDOException $e) {
        echo "Error: Database error.";
    }
}
?>