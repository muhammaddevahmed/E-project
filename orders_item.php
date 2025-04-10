<style>
.order-card {
  background: #fff;
  border-left: 5px solidrgb(49, 87, 143);
  padding: 20px;
  margin-bottom: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

.order-header {
  font-weight: 600;
  font-size: 1.2rem;
}

.order-status {
  font-weight: 500;
}

.status-pending {
  color: #ffc107;
}

.status-completed {
  color: #28a745;
}

.status-cancelled {
  color: #dc3545;
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
    $stmt = $pdo->prepare("
        SELECT order_id, order_number, p_name, p_price, p_qty, date_time, status 
        FROM orders 
        WHERE u_id = :user_id 
        ORDER BY date_time DESC
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
        <div>Total Price: $<?= number_format($order['p_price'] * $order['p_qty'], 2) ?></div>
      </div>
      <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <div class="order-status 
                            <?= $order['status'] === 'pending' ? 'status-pending' : 
                                ($order['status'] === 'completed' ? 'status-completed' : 'status-cancelled') ?>">
          Status: <?= ucfirst($order['status']) ?>
        </div>
      </div>
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