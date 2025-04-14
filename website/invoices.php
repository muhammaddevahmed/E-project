<?php
include("components/header.php");

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

<script src="https://cdn.tailwindcss.com"></script>

<div class="bg-[#f5f7fa] text-[#212529] p-5">
  <div class="max-w-6xl mx-auto">
    <?php if (empty($invoices)): ?>
    <div class="text-center py-12 px-5 bg-white rounded-lg shadow-md">
      <i class="fas fa-file-invoice text-5xl text-gray-500 mb-5"></i>
      <h2 class="text-2xl text-gray-500 mb-4">No Invoices Found</h2>
      <p class="mb-6">You haven't made any purchases yet.</p>
      <a href="shop-grid.php"
        class="bg-[#7fad39] text-white px-4 py-2 rounded no-underline font-medium hover:bg-[#6e9c2a] transition-colors mt-8 inline-block">
        Start Shopping
      </a>
    </div>
    <?php else: ?>
    <?php foreach ($invoices as $invoice): ?>
    <div class="bg-white rounded-lg shadow-md p-6 mb-6 transition-all hover:translate-y-[-5px] hover:shadow-lg">
      <div class="flex justify-between items-center mb-5 pb-4 border-b border-gray-200">
        <div>
          <span class="font-bold text-[#7fad39] text-xl">Invoice #<?= htmlspecialchars($invoice['payment_id']) ?></span>
          <span> • <?= date('F j, Y', strtotime($invoice['payment_date'])) ?></span>
        </div>
        <span class="px-3 py-1 rounded-full text-sm font-medium 
          <?= $invoice['payment_status'] === 'pending' ? 'bg-[#fff3cd] text-[#856404]' : '' ?>
          <?= $invoice['payment_status'] === 'completed' ? 'bg-[#d4edda] text-[#155724]' : '' ?>
          <?= $invoice['payment_status'] === 'failed' ? 'bg-[#f8d7da] text-[#721c24]' : '' ?>">
          <?= ucfirst(htmlspecialchars($invoice['payment_status'])) ?>
        </span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
        <div>
          <h3 class="text-gray-500 text-base mb-2">Payment Method</h3>
          <p class="text-lg"><?= htmlspecialchars($invoice['payment_method']) ?></p>
        </div>
        <div>
          <h3 class="text-gray-500 text-base mb-2">Billing Address</h3>
          <p>
            <?= htmlspecialchars($invoice['first_name']) ?> <?= htmlspecialchars($invoice['last_name']) ?><br>
            <?= htmlspecialchars($invoice['address']) ?><br>
            <?= htmlspecialchars($invoice['city']) ?>, <?= htmlspecialchars($invoice['state']) ?>
            <?= htmlspecialchars($invoice['postcode']) ?><br>
            <?= htmlspecialchars($invoice['country']) ?>
          </p>
        </div>
        <div>
          <h3 class="text-gray-500 text-base mb-2">Contact Information</h3>
          <p>
            <?= htmlspecialchars($invoice['email']) ?><br>
            <?= htmlspecialchars($invoice['phone']) ?>
          </p>
        </div>
      </div>

      <div class="text-right text-2xl font-bold text-[#7fad39]">
        Total: $ <?= number_format($invoice['amount'], 2) ?>
      </div>

      <div class="flex justify-between items-center mt-5 pt-4 border-t border-gray-200">
        <div>
          <?php if (!empty($invoice['order_notes'])): ?>
          <p><strong>Notes:</strong> <?= htmlspecialchars($invoice['order_notes']) ?></p>
          <?php endif; ?>
        </div>
        <div>
          <a href="invoice_print.php?id=<?= $invoice['payment_id'] ?>"
            class="bg-[#7fad39] text-white px-4 py-2 rounded no-underline font-medium hover:bg-[#6e9c2a] transition-colors"
            target="_blank">
            <i class="fas fa-print"></i> Print Invoice
          </a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<?php
include("components/footer.php");
?>