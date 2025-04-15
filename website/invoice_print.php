<?php
include 'php/db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$payment_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// 1. Get Payment Details and verify user ownership
$stmt = $pdo->prepare("
    SELECT p.* 
    FROM Payments p
    JOIN Users u ON p.email = u.email
    WHERE p.payment_id = :payment_id 
    AND u.user_id = :user_id
");
$stmt->execute([':payment_id' => $payment_id, ':user_id' => $user_id]);
$invoice = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$invoice) {
    die("Invoice not found or access denied.");
}

// 2. Get products for this specific payment using the payment_id relationship
$stmt = $pdo->prepare("
    SELECT o.* 
    FROM Orders o
    WHERE o.payment_id = :payment_id
");
$stmt->execute([':payment_id' => $payment_id]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate totals
$subtotal = array_sum(array_map(function($p) { 
    return $p['p_price'] * $p['p_qty']; 
}, $products));
$tax_rate = 0.10;
$tax = $subtotal * $tax_rate;
$total = $subtotal + $tax;

// If no products found, use payment amount as total
if (empty($products)) {
    $subtotal = $invoice['amount'] / 1.10;
    $tax = $subtotal * $tax_rate;
    $total = $invoice['amount'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Invoice #<?= htmlspecialchars($invoice['payment_id']) ?></title>
  <style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

  body {
    font-family: 'Inter', sans-serif;
    margin: 0;
    padding: 0;
    color: #333;
    background-color: #f5f5f5;
  }



  .invoice-container {
    max-width: 800px;
    margin: 30px auto;
    padding: 40px;
    background: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  }

  .header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e0e0e0;
    color: #6a9a2b
  }

  .logo {
    font-size: 24px;
    font-weight: 700;
    color: #000;
  }

  .logo span {
    color: #666;
    font-weight: 400;
  }

  .invoice-title h1 {
    font-size: 28px;
    margin: 0 0 5px 0;
    color: #000;
    font-weight: 600;
  }

  .invoice-title .number {
    color: #7fad39;
    font-size: 14px;
    font-weight: 400;
  }

  .details {
    display: flex;
    margin-bottom: 40px;
  }

  h2 {
    font-size: 14px;
    color: #666;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 500;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin: 30px 0;
  }

  th {
    text-align: left;
    padding: 12px 15px;
    background: #f9f9f9;
    color: #666;
    border-bottom: 1px solid #e0e0e0;
    font-weight: 500;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  td {
    padding: 15px;
    border-bottom: 1px solid #e0e0e0;
    color: #444;
    font-size: 14px;
  }

  .text-right {
    text-align: right;
  }

  .total-row td {
    font-weight: 600;
    color: #000;
    border-bottom: none;
    background: #f9f9f9;
    font-size: 15px;
  }

  .footer {
    margin-top: 50px;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
    color: #999;
    font-size: 13px;
  }

  .footer p {
    margin: 5px 0;
  }

  @media print {
    body {
      background: white;
      padding: 0;
    }

    .no-print {
      display: none;
    }

    .invoice-container {
      box-shadow: none;
      padding: 0;
      margin: 0;
    }
  }

  .warning {
    background-color: #fff3cd;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
    color: #856404;
    font-size: 14px;
    border-left: 4px solid #ffeeba;
  }

  .from-to {
    display: flex;
    margin-bottom: 30px;
  }

  .from,
  .to {
    flex: 1;
  }

  .from p,
  .to p {
    margin: 5px 0;
    line-height: 1.5;
    color: #444;
  }

  .invoice-meta {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #e0e0e0;
  }

  .meta-item {
    text-align: center;
    flex: 1;
  }

  .meta-item strong {
    display: block;
    color: #666;
    font-size: 12px;
    text-transform: uppercase;
    margin-bottom: 5px;
    letter-spacing: 0.5px;
  }

  .meta-item span {
    color: #000;
    font-weight: 500;
  }

  .print-btn {
    background: #7fad39;
    color: white;
    border: none;
    padding: 12px 25px;
    margin-top: 20px;
    cursor: pointer;
    border-radius: 4px;
    font-family: 'Inter', sans-serif;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.3s ease;
  }

  .print-btn:hover {
    background: #6a9a2b;
  }

  .logo {
    display: flex;
    align-items: center;
    font-size: 24px;
    font-weight: 700;
    color: #000;
  }

  .logo-img {
    height: 60px;
    /* Your desired height */
    width: 180px;
    /* Your desired width */
    object-fit: contain;
    /* Prevents distortion */
    margin-right: 15px;
  }

  .logo span {
    color: #666;
    font-weight: 400;
    margin-left: 10px;
  }
  </style>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Crafty Corner</title>
    <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
  </head>

<body>
  <div class="invoice-container">
    <div class="header">
      <div class="logo">
        <img src="images/logo.png" alt="The Crafty Corner Logo" style="height: 70px; margin-right: 10px;">
        <span class="name">INVOICE</span>
      </div>
      <div class=" invoice-title">
        <h1>#<?= htmlspecialchars($invoice['payment_id']) ?></h1>
        <div class="number">Issued: <?= date('F j, Y', strtotime($invoice['payment_date'])) ?></div>
      </div>
    </div>

    <?php if (empty($products)): ?>
    <div class="warning">
      <p><strong>Note:</strong> No order details found for this payment. Please update your order records to link them
        to this payment.</p>
    </div>
    <?php endif; ?>

    <div class="from-to">
      <div class="from">
        <h2>From</h2>
        <p>
          The Crafty Corner<br>
          123 Creative Street<br>
          Craftville, CV 12345<br>
          contact@craftycorner.com
        </p>
      </div>
      <div class="to">
        <h2>Bill To</h2>
        <p>
          <?= htmlspecialchars($invoice['first_name'] . ' ' . $invoice['last_name']) ?><br>
          <?= htmlspecialchars($invoice['address']) ?><br>
          <?= htmlspecialchars($invoice['city']) ?>, <?= htmlspecialchars($invoice['state']) ?>
          <?= htmlspecialchars($invoice['postcode']) ?><br>
          <?= htmlspecialchars($invoice['email']) ?><br>
          <?= htmlspecialchars($invoice['phone']) ?>
        </p>
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Qty</th>
          <th class="text-right">Total</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
        <tr>
          <td><?= htmlspecialchars($product['p_name']) ?></td>
          <td>Rs <?= number_format($product['p_price'], 2) ?></td>
          <td><?= htmlspecialchars($product['p_qty']) ?></td>
          <td class="text-right">Rs <?= number_format($product['p_price'] * $product['p_qty'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
          <td colspan="4" style="text-align: center; color: #999;">
            Product details not available
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
      <tfoot>

        <tr>
          <td colspan="3" class="text-right">Subtotal</td>
          <td class="text-right">Rs <?= number_format($subtotal, 2) ?></td>
        </tr>
        <tr class="total-row">
          <td colspan="3" class="text-right">TOTAL</td>
          <td class="text-right">Rs <?= number_format($subtotal, 2) ?></td>
        </tr>
      </tfoot>
    </table>

    <div class="invoice-meta">
      <div class="meta-item">
        <strong>Payment Method</strong>
        <span><?= strtoupper(htmlspecialchars($invoice['payment_method'])) ?></span>
      </div>
      <div class="meta-item">
        <strong>Payment Status</strong>
        <span><?= strtoupper(htmlspecialchars($invoice['payment_status'])) ?></span>
      </div>
      <div class="meta-item">
        <strong>Due Date</strong>
        <span><?= date('F j, Y', strtotime($invoice['payment_date'])) ?></span>
      </div>
    </div>

    <div class="footer">
      <?php if (!empty($invoice['order_notes'])): ?>
      <p><strong>Notes:</strong> <?= htmlspecialchars($invoice['order_notes']) ?></p>
      <?php endif; ?>
      <p>Thank you for your business!</p>
      <button class="print-btn no-print" onclick="window.print()">
        Print Invoice
      </button>
      <p style="margin-top: 30px;">The Crafty Corner © <?= date('Y') ?>. All rights reserved.</p>
    </div>
  </div>
</body>

</html>