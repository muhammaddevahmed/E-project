<?php
include("../php/db_connection.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
</head>
<body>
    <h1>Add Product</h1>
    <form action="save_product.php" method="post" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" id="product_name" required>
        <br><br>
        <label for="description">Description:</label>
        <textarea name="description" id="description"></textarea>
        <br><br>
        <label for="price">Price:</label>
        <input type="number" name="price" id="price" step="0.01" required>
        <br><br>
        <label for="stock_quantity">Stock Quantity:</label>
        <input type="number" name="stock_quantity" id="stock_quantity" required>
        <br><br>
        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id" required>
            <?php
            include 'db.php';
            $sql = "SELECT * FROM Categories";
            $stmt = $pdo->query($sql);
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($categories as $category) {
                echo "<option value='{$category['category_id']}'>{$category['category_name']}</option>";
            }
            ?>
        </select>
        <br><br>
        <label for="image">Product Image:</label>
        <input type="file" name="image" id="image" accept="image/*" required>
        <br><br>
        <button type="submit">Add Product</button>
    </form>
</body>
</html>