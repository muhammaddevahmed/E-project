<?php
// Include database connection
include("php/db_connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $product_id = $_POST['product_id'];
    $user_name = htmlspecialchars($_POST['user_name']);
    $rating = intval($_POST['rating']);
    $review_text = htmlspecialchars($_POST['review_text']);

    // Validate input
    if (empty($product_id) || empty($user_name) || empty($rating) || empty($review_text)) {
        die("All fields are required.");
    }

    // Insert review into the database
    $sql = "INSERT INTO Reviews (product_id, user_name, rating, review_text) VALUES (:product_id, :user_name, :rating, :review_text)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);
    $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
    $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
    $stmt->bindParam(':review_text', $review_text, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // Redirect back to the product page
        header("Location: product-details.php?id=$product_id");
        exit();
    } else {
        die("Error submitting review.");
    }
}
?>