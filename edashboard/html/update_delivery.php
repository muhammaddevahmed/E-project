<?php
include("components/header.php");



if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: delivery_report.php');
  exit;
}

$orderId = $_POST['order_id'] ?? '';
$deliveryId = $_POST['delivery_id'] ?? null;
$deliveryStatus = $_POST['delivery_status'] ?? 'pending';
$estimatedDate = $_POST['estimated_delivery_date'] ?? null;
$actualDate = $_POST['actual_delivery_date'] ?? null;
$notes = $_POST['delivery_notes'] ?? null;

try {
    $pdo->beginTransaction();

    // Update or insert delivery record
    if ($deliveryId) {
        $stmt = $pdo->prepare("
            UPDATE deliveries SET
                delivery_status = :delivery_status,
                estimated_delivery_date = :estimated_date,
                actual_delivery_date = :actual_date,
                delivery_notes = :notes,
                updated_at = CURRENT_TIMESTAMP()
            WHERE delivery_id = :delivery_id
        ");
        $stmt->execute([
            ':delivery_status' => $deliveryStatus,
            ':estimated_date' => $estimatedDate ?: null,
            ':actual_date' => $actualDate ?: null,
            ':notes' => $notes ?: null,
            ':delivery_id' => $deliveryId
        ]);
    } else {
        // Get user_id and product_id from orders table
        $stmt = $pdo->prepare("
            SELECT u_id, product_id FROM orders 
            WHERE order_id = :order_id 
            LIMIT 1
        ");
        $stmt->execute([':order_id' => $orderId]);
        $order = $stmt->fetch();

        $stmt = $pdo->prepare("
            INSERT INTO deliveries (
                order_id, user_id, product_id, delivery_status,
                estimated_delivery_date, actual_delivery_date,
                delivery_notes, created_at, updated_at
            ) VALUES (
                :order_id, :user_id, :product_id, :delivery_status,
                :estimated_date, :actual_date,
                :notes, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP()
            )
        ");
        $stmt->execute([
            ':order_id' => $orderId,
            ':user_id' => $order['u_id'],
            ':product_id' => $order['product_id'],
            ':delivery_status' => $deliveryStatus,
            ':estimated_date' => $estimatedDate ?: null,
            ':actual_date' => $actualDate ?: null,
            ':notes' => $notes ?: null
        ]);
        $deliveryId = $pdo->lastInsertId();
    }

    // Update order status to match
    $stmt = $pdo->prepare("
        UPDATE orders SET
            delivery_status = :delivery_status
        WHERE order_id = :order_id
    ");
    $stmt->execute([
        ':delivery_status' => $deliveryStatus,
        ':order_id' => $orderId
    ]);

    $pdo->commit();
    echo "<script>location.assign('delivery_report.php?success=1');</script>";
exit;
} catch (PDOException $e) {
    $pdo->rollBack();
    header('Location: delivery_report.php?error=' . urlencode($e->getMessage()));
}
?>