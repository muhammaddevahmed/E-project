<style>
:root {
  --primary-color: #4361ee;
  --secondary-color: #3f37c9;
  --light-color: #f8f9fa;
  --dark-color: #212529;
  --success-color: #4bb543;
  --warning-color: #ffc107;
  --danger-color: #dc3545;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
  background-color: #f5f7fa;
  color: var(--dark-color);
  padding: 20px;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}

h1 {
  color: var(--primary-color);
  margin-bottom: 20px;
}

.invoice-card {
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  padding: 25px;
  margin-bottom: 25px;
  transition: transform 0.3s, box-shadow 0.3s;
}

.invoice-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}

.invoice-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 1px solid #eee;
}

.invoice-id {
  font-weight: bold;
  color: var(--primary-color);
  font-size: 1.2rem;
}

.invoice-status {
  padding: 5px 12px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 500;
}

.status-pending {
  background-color: #fff3cd;
  color: #856404;
}

.status-completed {
  background-color: #d4edda;
  color: #155724;
}

.status-failed {
  background-color: #f8d7da;
  color: #721c24;
}

.invoice-details {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 20px;
}

.detail-group h3 {
  font-size: 1rem;
  color: #6c757d;
  margin-bottom: 8px;
}

.detail-group p {
  font-size: 1.05rem;
}

.invoice-amount {
  font-size: 1.5rem;
  font-weight: bold;
  color: var(--primary-color);
  text-align: right;
}

.invoice-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 20px;
  padding-top: 15px;
  border-top: 1px solid #eee;
}

.btn {
  padding: 8px 16px;
  border-radius: 4px;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s;
}

.btn-primary {
  background-color: var(--primary-color);
  color: white;
  border: none;
  margin-top: 30px
}

.btn-primary:hover {
  background-color: var(--secondary-color);
}

.btn-outline {
  background-color: transparent;
  border: 1px solid var(--primary-color);
  color: var(--primary-color);
}

.btn-outline:hover {
  background-color: var(--primary-color);
  color: white;
}

.empty-state {
  text-align: center;
  padding: 50px 20px;
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.empty-state i {
  font-size: 3rem;
  color: #6c757d;
  margin-bottom: 20px;
}

.empty-state h2 {
  color: #6c757d;
  margin-bottom: 15px;
}

@media (max-width: 768px) {
  .invoice-details {
    grid-template-columns: 1fr;
  }

  .invoice-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }

  .invoice-amount {
    text-align: left;
    margin-top: 10px;
  }
}
</style>

<?php
include 'php/db_connection.php';
include 'php/queries.php';
session_start();


// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user's invoices
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM Payments WHERE email = (SELECT email FROM Users WHERE user_id = :user_id) ORDER BY payment_date DESC");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Crafty Corner</title>
  <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
</head>

<div class="container">
  <div class="header">
    <h1>My Invoices</h1>
    <a href="profile.php" class="btn btn-outline">
      <i class="fas fa-arrow-left"></i> Back to Profile
    </a>
  </div>

  <?php if (empty($invoices)): ?>
  <div class="empty-state">
    <i class="fas fa-file-invoice"></i>
    <h2>No Invoices Found</h2>
    <p>You haven't made any purchases yet.</p> <br>
    <a href="shop-grid.php" class="btn btn-primary">Start Shopping</a>
  </div>
  <?php else: ?>
  <?php foreach ($invoices as $invoice): ?>
  <div class="invoice-card">
    <div class="invoice-header">
      <div>
        <span class="invoice-id">Invoice #<?= htmlspecialchars($invoice['payment_id']) ?></span>
        <span> • <?= date('F j, Y', strtotime($invoice['payment_date'])) ?></span>
      </div>
      <span class="invoice-status status-<?= htmlspecialchars($invoice['payment_status']) ?>">
        <?= ucfirst(htmlspecialchars($invoice['payment_status'])) ?>
      </span>
    </div>

    <div class="invoice-details">
      <div class="detail-group">
        <h3>Payment Method</h3>
        <p><?= htmlspecialchars($invoice['payment_method']) ?></p>
      </div>
      <div class="detail-group">
        <h3>Billing Address</h3>
        <p>
          <?= htmlspecialchars($invoice['first_name']) ?> <?= htmlspecialchars($invoice['last_name']) ?><br>
          <?= htmlspecialchars($invoice['address']) ?><br>
          <?= htmlspecialchars($invoice['city']) ?>, <?= htmlspecialchars($invoice['state']) ?>
          <?= htmlspecialchars($invoice['postcode']) ?><br>
          <?= htmlspecialchars($invoice['country']) ?>
        </p>
      </div>
      <div class="detail-group">
        <h3>Contact Information</h3>
        <p>
          <?= htmlspecialchars($invoice['email']) ?><br>
          <?= htmlspecialchars($invoice['phone']) ?>
        </p>
      </div>
    </div>

    <div class="invoice-amount">
      Total: $<?= number_format($invoice['amount'], 2) ?>
    </div>

    <div class="invoice-footer">
      <div>
        <?php if (!empty($invoice['order_notes'])): ?>
        <p><strong>Notes:</strong> <?= htmlspecialchars($invoice['order_notes']) ?></p>
        <?php endif; ?>
      </div>
      <div>
        <a href="invoice_print.php?id=<?= $invoice['payment_id'] ?>" class="btn btn-primary" target="_blank">
          <i class="fas fa-print"></i> Print Invoice
        </a>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>