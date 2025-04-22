<?php
header('Content-Type: application/json');
require_once 'php/connection.php';

// Function to map category names to Font Awesome icons
function getCategoryIcon($category_name) {
    $category_name = strtolower($category_name);
    if (stripos($category_name, 'art') !== false || stripos($category_name, 'paint') !== false) {
        return 'fas fa-paint-brush';
    } elseif (stripos($category_name, 'stationery') !== false || stripos($category_name, 'stationary') !== false) {
        return 'fas fa-sticky-note';
    } elseif (stripos($category_name, 'calligraphy') !== false || stripos($category_name, 'pen') !== false) {
        return 'fas fa-pen';
    } elseif (stripos($category_name, 'sketch') !== false || stripos($category_name, 'draw') !== false) {
        return 'fas fa-pencil-alt';
    } else {
        return 'fas fa-shopping-basket'; // Default icon
    }
}

try {
    $query = "
        SELECT 
            c.category_name,
            SUM(o.p_price * o.p_qty) as revenue,
            COUNT(DISTINCT o.order_id) as order_count
        FROM categories c
        LEFT JOIN products p ON c.category_id = p.category_id
        LEFT JOIN orders o ON p.product_id = o.product_id
        GROUP BY c.category_id, c.category_name
        ORDER BY revenue DESC
    ";
    
    $stmt = $pdo->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Add icon to each category and ensure numeric values
    foreach ($results as &$result) {
        $result['revenue'] = floatval($result['revenue'] ?? 0);
        $result['order_count'] = intval($result['order_count']);
        $result['icon'] = getCategoryIcon($result['category_name']);
    }

    echo json_encode($results);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>