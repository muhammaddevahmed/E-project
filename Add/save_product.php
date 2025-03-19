<?php
include("../php/db_connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../php/db_connection.php'; // Ensure database connection

    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $category_id = $_POST['category_id'];
    $warranty_period = $_POST['warranty_period'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../images/products/'; // Ensure correct path
        
        // Ensure the directory exists
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create directory with full permissions
        }

        $file_name = time() . "_" . basename($_FILES['image']['name']); // Unique filename
        $file_path = $upload_dir . $file_name;
        $db_file_path = 'images/products/' . $file_name; // Relative path for database

        // Get the file extension
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        // List of allowed image formats
        $allowed_formats = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'tiff'];

        // Validate file format
        if (in_array($file_extension, $allowed_formats)) {
            // Validate if the file is an image using getimagesize()
            $image_info = getimagesize($_FILES['image']['tmp_name']);
            if ($image_info !== false) {
                // File is a valid image
                if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
                    try {
                        // Generate a unique product ID (e.g., PE10001)
                        $product_id = substr($product_name, 0, 2) . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);

                        // Insert product into the database
                        $sql = "INSERT INTO Products (product_id, product_name, description, price, stock_quantity, category_id, image_path, warranty_period) 
                                VALUES (:product_id, :product_name, :description, :price, :stock_quantity, :category_id, :image_path, :warranty_period)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':product_id', $product_id);
                        $stmt->bindParam(':product_name', $product_name);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':price', $price);
                        $stmt->bindParam(':stock_quantity', $stock_quantity);
                        $stmt->bindParam(':category_id', $category_id);
                        $stmt->bindParam(':image_path', $db_file_path);
                        $stmt->bindParam(':warranty_period', $warranty_period);
                        $stmt->execute();

                        echo "Product added successfully!";
                    } catch (PDOException $e) {
                        echo "Database Error: " . $e->getMessage();
                    }
                } else {
                    echo "Failed to upload image.";
                }
            } else {
                echo "Uploaded file is not a valid image.";
            }
        } else {
            echo "Invalid file format. Allowed formats: " . implode(", ", $allowed_formats);
        }
    } else {
        echo "No image uploaded or upload error.";
    }
}
?>