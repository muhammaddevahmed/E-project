<?php
session_start();
require_once 'php/db_connection.php';

header('Content-Type: application/json');

$response = ['success' => false, 'count' => 0];

try {
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM wishlist WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $count = $stmt->fetchColumn();
        $response = ['success' => true, 'count' => $count];
    } else {
        $response = ['success' => true, 'count' => 0];
    }
} catch (PDOException $e) {
    $response['error'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
?>