<?php
// Ensure no output before headers
ob_start();

// Set strict error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't output errors to response

// Include your database connection
require_once 'php/connection.php';

// Set JSON header first
header('Content-Type: application/json');

try {
    // Validate order_id exists
    if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
        throw new Exception('Order ID is required');
    }

    $orderId = $_GET['order_id'];

    // Initialize response
    $response = [
        'order_id' => $orderId,
        'delivery_id' => null,
        'delivery_status' => 'pending',
        'estimated_delivery_date' => null,
        'actual_delivery_date' => null,
        'delivery_notes' => null
    ];

    // Get delivery details if they exist
    $stmt = $pdo->prepare("SELECT * FROM deliveries WHERE order_id = :order_id LIMIT 1");
    $stmt->execute([':order_id' => $orderId]);
    
    if ($delivery = $stmt->fetch()) {
        $response['delivery_id'] = $delivery['delivery_id'];
        $response['estimated_delivery_date'] = $delivery['estimated_delivery_date'];
        $response['actual_delivery_date'] = $delivery['actual_delivery_date'];
        $response['delivery_notes'] = $delivery['delivery_notes'];
    }

    // Get order status
    $stmt = $pdo->prepare("SELECT delivery_status FROM orders WHERE order_id = :order_id LIMIT 1");
    $stmt->execute([':order_id' => $orderId]);
    
    if ($order = $stmt->fetch()) {
        $response['delivery_status'] = $order['delivery_status'];
    }

    // Clean any output buffers and send JSON
    ob_end_clean();
    echo json_encode($response);
    exit;

} catch (PDOException $e) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit;
} catch (Exception $e) {
    ob_end_clean();
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
?>