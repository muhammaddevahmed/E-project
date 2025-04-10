<?php
include("../php/db_connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../php/db_connection.php'; // Ensure database connection

    $category_name = $_POST['category_name'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../images/categories/'; // Corrected path (outside Add/)
        
        // Ensure the directory exists
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create directory with full permissions
        }

        $file_name = time() . "_" . basename($_FILES['image']['name']); // Unique filename
        $file_path = $upload_dir . $file_name;
        $db_file_path = 'images/categories/' . $file_name; // Relative path for database

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
            try {
                // Insert category into the database
                $sql = "INSERT INTO Categories (category_name, image_path) VALUES (:category_name, :image_path)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':category_name', $category_name);
                $stmt->bindParam(':image_path', $db_file_path);
                $stmt->execute();

                echo "Category added successfully!";
            } catch (PDOException $e) {
                echo "Database Error: " . $e->getMessage();
            }
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "No image uploaded or upload error.";
    }
}



?>