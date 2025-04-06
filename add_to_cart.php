<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve product details from the form
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $image_path = $_POST['image_path'];
    $quantity = $_POST['quantity'];

    // Initialize the cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product already exists in the cart
    if (array_key_exists($product_id, $_SESSION['cart'])) {
        // Update the quantity if the product exists
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add a new product to the cart
        $_SESSION['cart'][$product_id] = [
            'product_name' => $product_name,
            'price' => $price,
            'image_path' => $image_path,
            'quantity' => $quantity
        ];
    }

    // Set a success message and redirect to the cart page
    // $_SESSION['message'] = "Product added to cart successfully!";
    // header("Location: shop-grid.php");
    // exit();
    echo "<script>
    alert('Product added to cart successfully!');
    window.location.href = 'shop-grid.php';
</script>";
exit();
}


?>