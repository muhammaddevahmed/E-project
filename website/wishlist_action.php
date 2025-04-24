<?php
session_start();
require_once 'php/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'redirect' => 'login.php',
        'message' => 'Please login to manage your wishlist'
    ]);
    exit;
}

$action = $_POST['action'] ?? '';
$product_id = $_POST['product_id'] ?? '';
$user_id = $_SESSION['user_id'];

if (!in_array($action, ['add', 'remove']) || empty($product_id)) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

try {
    if ($action === 'add') {
        // Check if already in wishlist
        $check = $pdo->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
        $check->execute([$user_id, $product_id]);
        
        if ($check->rowCount() === 0) {
            $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $product_id]);
        }
    } else {
        $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
    }
    
    // Get updated count
    $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
    $count_stmt->execute([$user_id]);
    $count = $count_stmt->fetchColumn();
    
    echo json_encode([
        'success' => true,
        'action' => $action,
        'count' => $count
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>