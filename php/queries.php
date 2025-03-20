<?php
// Fetch categories
$sql = "SELECT * FROM Categories";
$stmt = $pdo->query($sql);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch products
$sql = "SELECT * FROM Products";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Signup

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $user_type = 'customer'; // Default role

    // ✅ REGEX VALIDATION
    if (!preg_match("/^[a-zA-Z0-9_]{5,20}$/", $username)) {
        echo "<script>alert('Username must be 5-20 characters and contain only letters, numbers, and underscores.');</script>";
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
        exit();
    }
    if (!empty($phone) && !preg_match("/^\+?[0-9]{7,15}$/", $phone)) {
        echo "<script>alert('Phone number must be 7-15 digits and can start with +.');</script>";
        exit();
    }
    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{6,}$/", $password)) {
        echo "<script>alert('Password must be at least 6 characters, with at least one letter and one number.');</script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    try {
        // ✅ CHECK IF EMAIL ALREADY EXISTS
        $checkStmt = $pdo->prepare("SELECT user_id FROM Users WHERE email = :email");
        $checkStmt->bindValue(':email', $email);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            echo "<script>alert('Error: Email already exists.');</script>";
            exit();
        }

        // ✅ INSERT USER IF EMAIL IS UNIQUE
        $stmt = $pdo->prepare("INSERT INTO Users (username, password_hash, full_name, email, phone, user_type, address) 
                               VALUES (:username, :password, :full_name, :email, :phone, :user_type, :address)");

        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $hashed_password);
        $stmt->bindValue(':full_name', $full_name);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':user_type', $user_type);
        $stmt->bindValue(':address', $address);

        if ($stmt->execute()) {
            echo "<script>alert('Signup successful! Please login.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Error: Signup failed.');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Database error: " . $e->getMessage() . "');</script>";
    }
}



//Shop Page
// Fetch all categories
$sql_categories = "SELECT * FROM Categories";
$stmt_categories = $pdo->query($sql_categories);
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

// Fetch products with sorting, pagination, and category filtering
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default'; // Default sorting
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$limit = 9; // Number of products per page
$offset = ($page - 1) * $limit; // Offset for pagination
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0; // Selected category

// Base SQL query
$sql_products = "SELECT * FROM Products";

// Apply category filter
if ($category_id > 0) {
    $sql_products .= " WHERE category_id = :category_id";
}

// Apply sorting
if ($sort === 'price_asc') {
    $sql_products .= " ORDER BY price ASC";
} elseif ($sort === 'price_desc') {
    $sql_products .= " ORDER BY price DESC";
} elseif ($sort === 'name_asc') {
    $sql_products .= " ORDER BY product_name ASC";
} elseif ($sort === 'name_desc') {
    $sql_products .= " ORDER BY product_name DESC";
} else {
    $sql_products .= " ORDER BY product_id DESC"; // Default sorting
}

// Add pagination
$sql_products .= " LIMIT :limit OFFSET :offset";

// Prepare and execute the query
$stmt_products = $pdo->prepare($sql_products);
if ($category_id > 0) {
    $stmt_products->bindParam(':category_id', $category_id, PDO::PARAM_INT);
}
$stmt_products->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt_products->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt_products->execute();
$products = $stmt_products->fetchAll(PDO::FETCH_ASSOC);

// Count total products for pagination
$sql_total = "SELECT COUNT(*) as total FROM Products";
if ($category_id > 0) {
    $sql_total .= " WHERE category_id = :category_id";
}
$stmt_total = $pdo->prepare($sql_total);
if ($category_id > 0) {
    $stmt_total->bindParam(':category_id', $category_id, PDO::PARAM_INT);
}
$stmt_total->execute();
$total_products = $stmt_total->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_products / $limit); // Total pages





?>
