<?php
include("components/header.php");



// Initialize variables
$total_balance = 0;
$today_income = 0;
$payment_history = [];
$payment_methods = [];
$chart_data = [];

try {
    // Get total balance (all completed payments)
    $stmt = $pdo->query("SELECT SUM(amount) as total FROM payments WHERE payment_status = 'completed'");
    $total_balance = $stmt->fetchColumn() ?? 0;

    // Get today's income
    $stmt = $pdo->query("SELECT SUM(amount) as total FROM payments 
                        WHERE payment_status = 'completed' 
                        AND DATE(payment_date) = CURDATE()");
    $today_income = $stmt->fetchColumn() ?? 0;

    // Get recent payment history
    $stmt = $pdo->query("
        SELECT p.*, u.username 
        FROM payments p
        LEFT JOIN users u ON p.email = u.email
        ORDER BY payment_date DESC 
        LIMIT 10
    ");
    $payment_history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get payment method distribution
    $stmt = $pdo->query("
        SELECT payment_method, COUNT(*) as count, SUM(amount) as total
        FROM payments
        WHERE payment_status = 'completed'
        GROUP BY payment_method
    ");
    $payment_methods = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get weekly income data for chart
    $stmt = $pdo->query("
        SELECT 
            DATE_FORMAT(payment_date, '%Y-%m-%d') as day,
            SUM(amount) as daily_income
        FROM payments
        WHERE payment_status = 'completed'
        AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY day
        ORDER BY day
    ");
    $chart_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error_msg = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Wallet Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/chart.js" rel="stylesheet">
  <style>
  .dashboard-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .balance-card {
    background: #7fad39;
    /* Updated background color */
    color: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .income-card {
    background: #7fad39;
    /* Updated background color */
    color: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .payment-method {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
  }

  .progress {
    height: 10px;
    background: #e9ecef;
    border-radius: 5px;
    margin-top: 5px;
  }

  .progress-bar {
    height: 100%;
    border-radius: 5px;
    background: #4e73df;
  }

  .payment-row {
    border-bottom: 1px solid #eee;
    padding: 15px 0;
  }

  .status-completed {
    color: #28a745;
  }

  .status-pending {
    color: #ffc107;
  }

  .status-failed {
    color: #dc3545;
  }

  .heading {
    font-size: 2.5rem;
    font-weight: 700;
    color: #7fad39;
    margin-bottom: 1rem;
    text-align: center;
  }
  </style>
</head>

<body>
  <div class="container-fluid py-4">
    <h2 class="heading">Wallet Dashboard</h2>

    <?php if(isset($error_msg)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error_msg) ?></div>
    <?php endif; ?>

    <div class="row">
      <!-- Balance Cards -->
      <div class="col-md-6">
        <div class="dashboard-card balance-card">
          <h5>Total Balance</h5>
          <h2>$ <?= number_format($total_balance, 2) ?></h2>
        </div>
      </div>
      <div class="col-md-6">
        <div class="dashboard-card income-card">
          <h5>Today's Income</h5>
          <h2>Rs <?= number_format($today_income, 2) ?></h2>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <!-- Payment Methods -->
      <div class="col-md-6">
        <div class="dashboard-card">
          <h5>Payment Methods</h5>
          <?php foreach ($payment_methods as $method): ?>
          <div class="payment-method">
            <span><?= htmlspecialchars($method['payment_method']) ?></span>
            <span>Rs <?= number_format($method['total'], 2) ?></span>
          </div>
          <div class="progress">
            <div class="progress-bar" style="width: <?= ($method['total'] / $total_balance) * 100 ?>%"></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Weekly Income Chart -->
      <div class="col-md-6">
        <div class="dashboard-card">
          <h5>Weekly Income</h5>
          <canvas id="incomeChart" height="200"></canvas>
        </div>
      </div>
    </div>

    <!-- Recent Payments -->
    <div class="dashboard-card mt-4">
      <h5>Recent Payments</h5>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Customer</th>
              <th>Method</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($payment_history as $payment): ?>
            <tr class="payment-row">
              <td>#<?= htmlspecialchars($payment['payment_id']) ?></td>
              <td><?= htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']) ?></td>
              <td><?= htmlspecialchars($payment['payment_method']) ?></td>
              <td>$ <?= number_format($payment['amount'], 2) ?></td>
              <td class="status-<?= htmlspecialchars($payment['payment_status']) ?>">
                <?= ucfirst(htmlspecialchars($payment['payment_status'])) ?>
              </td>
              <td><?= date('M d, Y H:i', strtotime($payment['payment_date'])) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
  // Weekly Income Chart
  const ctx = document.getElementById('incomeChart').getContext('2d');
  const incomeChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: [<?php foreach ($chart_data as $data): ?> "<?= date('D', strtotime($data['day'])) ?>",
        <?php endforeach; ?>
      ],
      datasets: [{
        label: 'Daily Income',
        data: [<?php foreach ($chart_data as $data): ?><?= $data['daily_income'] ?>, <?php endforeach; ?>],
        backgroundColor: 'rgba(78, 115, 223, 0.5)',
        borderColor: 'rgba(78, 115, 223, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  // Auto-refresh every 30 seconds
  setTimeout(function() {
    location.reload();
  }, 30000);
  </script>
</body>

</html>
<?php include("components/footer.php"); ?>