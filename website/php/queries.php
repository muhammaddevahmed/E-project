<?php
// Fetch categories
$sql = "SELECT * FROM categories";
$stmt = $pdo->query($sql);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch products
$sql = "SELECT * FROM products";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);





//Shop Page
// Fetch all categories
$sql_categories = "SELECT * FROM categories";
$stmt_categories = $pdo->query($sql_categories);
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

// Fetch products with sorting, pagination, and category filtering
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default'; // Default sorting
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$limit = 9; // Number of products per page
$offset = ($page - 1) * $limit; // Offset for pagination
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0; // Selected category

// Base SQL query
$sql_products = "SELECT * FROM products";

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