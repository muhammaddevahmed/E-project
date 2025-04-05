<?php
session_start();
include 'php/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get invoice ID from URL
$invoice_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get invoice details
$stmt = $pdo->prepare("SELECT * FROM Payments WHERE payment_id = :id AND email = (SELECT email FROM Users WHERE user_id = :user_id)");
$stmt->bindParam(':id', $invoice_id, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$invoice = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$invoice) {
    die("Invoice not found or you don't have permission to view it.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
  <title>Invoice #<?= $invoice['payment_id'] ?></title>
  <style>
  body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    color: #333;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
  }

  .invoice-header {
    text-align: center;
    margin-bottom: 30px;
  }

  .invoice-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
  }

  .invoice-details {
    display: flex;
    justify-content: space-between;
    margin-bottom: 30px;
  }

  .from,
  .to {
    width: 48%;
  }

  .invoice-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
  }

  .invoice-table th,
  .invoice-table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
  }

  .invoice-table th {
    background-color: #f5f5f5;
  }

  .invoice-total {
    text-align: right;
    font-size: 18px;
    font-weight: bold;
  }

  .invoice-footer {
    margin-top: 50px;
    text-align: center;
    font-size: 14px;
    color: #777;
  }

  .status {
    padding: 5px 10px;
    border-radius: 4px;
    font-weight: bold;
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

  @media print {
    body {
      padding: 0;
    }

    .no-print {
      display: none;
    }
  }
  </style>
</head>

<body>
  <div class="no-print" style="text-align: right; margin-bottom: 20px;">
    <button onclick="window.print()"
      style="padding: 8px 15px; background: #4361ee; color: white; border: none; border-radius: 4px; cursor: pointer;">
      Print Invoice
    </button>
  </div>

  <div class="invoice-header">
    <div class="invoice-title">INVOICE</div>
    <div>Date: <?= date('F j, Y', strtotime($invoice['payment_date'])) ?></div>
    <div>Invoice #: <?= $invoice['payment_id'] ?></div>
    <div>
      Status: <span class="status status-<?= $invoice['payment_status'] ?>">
        <?= ucfirst($invoice['payment_status']) ?>
      </span>
    </div>
  </div>

  <div class="invoice-details">
    <div class="from">
      <h3>From:</h3>
      <p>
        The Crafty Corner<br>
        Aptech Sfc<br>
        Karachi, Sindh<br>
        Phone: (123) 456-7890<br>
        Email: thecraftycorner@gmail.com
      </p>
    </div>
    <div class="to">
      <h3>To:</h3>
      <p>
        <?= htmlspecialchars($invoice['first_name']) ?> <?= htmlspecialchars($invoice['last_name']) ?><br>
        <?= htmlspecialchars($invoice['address']) ?><br>
        <?= htmlspecialchars($invoice['city']) ?>, <?= htmlspecialchars($invoice['state']) ?>
        <?= htmlspecialchars($invoice['postcode']) ?><br>
        <?= htmlspecialchars($invoice['country']) ?><br>
        Phone: <?= htmlspecialchars($invoice['phone']) ?><br>
        Email: <?= htmlspecialchars($invoice['email']) ?>
      </p>
    </div>
  </div>

  <table class="invoice-table">
    <thead>
      <tr>
        <th>Description</th>
        <th>Payment Method</th>
        <th>Amount</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Payment for Order #<?= $invoice['payment_id'] ?></td>
        <td><?= htmlspecialchars($invoice['payment_method']) ?></td>
        <td>$<?= number_format($invoice['amount'], 2) ?></td>
      </tr>
    </tbody>
  </table>

  <div class="invoice-total">
    Total: $<?= number_format($invoice['amount'], 2) ?>
  </div>

  <?php if (!empty($invoice['order_notes'])): ?>
  <div style="margin-top: 30px;">
    <h3>Notes:</h3>
    <p><?= htmlspecialchars($invoice['order_notes']) ?></p>
  </div>
  <?php endif; ?>

  <div class="invoice-footer">
    Thank you for Choosing Us!<br>
    If you have any questions about this invoice, please contact<br>
    thecraftycorner@gmail.com or call (123) 456-7890
  </div>

  <script>
  // Automatically trigger print dialog when page loads
  window.onload = function() {
    // You can uncomment this if you want auto-print
    // window.print();
  };
  </script>
</body>

</html>