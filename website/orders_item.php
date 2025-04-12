<style>
.order-card {
  background: #fff;
  border-left: 5px solid rgb(49, 87, 143);
  padding: 20px;
  margin-bottom: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
  position: relative;
}

.order-header {
  font-weight: 600;
  font-size: 1.2rem;
  margin-bottom: 10px;
}

.order-status {
  font-weight: 500;
  margin-bottom: 5px;
}

.status-pending {
  color: #ffc107;
}

.status-processing {
  color: #17a2b8;
}

.status-shipped {
  color: #007bff;
}

.status-delivered {
  color: #28a745;
}

.delivery-info {
  background: #f8f9fa;
  padding: 15px;
  border-radius: 8px;
  margin-top: 15px;
}

.delivery-date {
  font-size: 0.9rem;
  color: #6c757d;
}

.delivery-label {
  font-weight: 600;
  color: #495057;
}

.delivery-notes {
  margin-top: 10px;
  font-size: 0.9rem;
  color: #6c757d;
  border-top: 1px solid #eee;
  padding-top: 10px;
}
</style>

<?php
include("components/header.php");

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$orders = [];

try {
    // Get orders with delivery information
    $stmt = $pdo->prepare("
        SELECT o.order_id, o.order_number, o.p_name, o.p_price, o.p_qty, 
               o.date_time, o.status, o.delivery_status,
               d.estimated_delivery_date, d.actual_delivery_date, d.delivery_notes
        FROM orders o
        LEFT JOIN deliveries d ON o.order_id = d.order_id
        WHERE o.u_id = :user_id 
        ORDER BY o.date_time DESC
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_msg = "Failed to load orders: " . $e->getMessage();
}
?>

<div class="container py-5">
  <h2 class="mb-4 text-center">My Orders</h2>

  <?php if (isset($error_msg)): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error_msg) ?></div>
  <?php endif; ?>

  <?php if (!empty($orders)): ?>
  <?php foreach ($orders as $order): ?>
  <div class="order-card">
    <div class="row">
      <div class="col-md-8">
        <div class="order-header">
          <?= htmlspecialchars($order['p_name']) ?> (x<?= $order['p_qty'] ?>)
        </div>
        <div>Order #: <?= htmlspecialchars($order['order_number']) ?></div>
        <div>Date: <?= date('d M Y, h:i A', strtotime($order['date_time'])) ?></div>
        <div>Total Price: Rs <?= number_format($order['p_price'] * $order['p_qty'], 2) ?></div>
      </div>
      <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <div class="order-status 
                        <?= $order['status'] === 'pending' ? 'status-pending' : 
                            ($order['status'] === 'completed' ? 'status-completed' : 'status-cancelled') ?>">
          Order Status: <?= ucfirst($order['status']) ?>
        </div>
      </div>
    </div>

    <!-- Delivery Information Section -->
    <div class="delivery-info mt-3">
      <div
        class="order-status 
                      <?= strtolower($order['delivery_status']) === 'pending' ? 'status-pending' : 
                          (strtolower($order['delivery_status']) === 'processing' ? 'status-processing' :
                          (strtolower($order['delivery_status']) === 'shipped' ? 'status-shipped' : 'status-delivered')) ?>">
        Delivery Status: <?= ucfirst($order['delivery_status'] ?? 'pending') ?>
      </div>

      <?php if ($order['estimated_delivery_date']): ?>
      <div class="delivery-date">
        <span class="delivery-label">Estimated Delivery:</span>
        <?= date('d M Y', strtotime($order['estimated_delivery_date'])) ?>
      </div>
      <?php endif; ?>

      <?php if ($order['actual_delivery_date']): ?>
      <div class="delivery-date">
        <span class="delivery-label">Delivered On:</span>
        <?= date('d M Y', strtotime($order['actual_delivery_date'])) ?>
      </div>
      <?php endif; ?>

      <?php if (!empty($order['delivery_notes'])): ?>
      <div class="delivery-notes">
        <span class="delivery-label">Delivery Notes:</span>
        <?= htmlspecialchars($order['delivery_notes']) ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
  <?php else: ?>
  <div class="alert alert-info text-center">You have no orders yet.</div>
  <?php endif; ?>
</div>

<?php
include("components/footer.php");
?>