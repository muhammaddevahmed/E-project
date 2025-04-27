<?php
include("components/header.php");

// Check user type
$user_type = $_SESSION['user_type'] ?? '';

// Assuming $logged_in_admin_id is set after admin login verification
$logged_in_admin_id = $_SESSION['user_id'] ?? 1; // Replace with actual admin ID from session

// Handle promo code generation (only if the user is not an employee)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'generate_promo' && $user_type !== 'employee') {
    $request_id = $_POST['request_id'];
    $discount_percent = 15; // Fixed 15% discount
    $expiry_days = 7; // Code expires in 7 days
    
    try {
        // Get request details
        $stmt = $pdo->prepare("SELECT * FROM promo_code_requests WHERE request_id = :request_id AND status = 'pending'");
        $stmt->execute(['request_id' => $request_id]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$request) {
            $_SESSION['error'] = 'Invalid or already processed request';
            header("Location: promo_codes.php");
            exit();
        }
        
        // Generate unique promo code
        $promo_code = 'PROMO' . strtoupper(substr(md5(uniqid()), 0, 8));
        
        // Create promo code
        $stmt = $pdo->prepare("INSERT INTO promo_codes 
                             (code, discount_percent, request_id, user_id, created_by, created_at, expires_at) 
                             VALUES (:code, :discount, :request_id, :user_id, :created_by, CURRENT_TIMESTAMP, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL :expiry_days DAY))");
        $stmt->execute([
            'code' => $promo_code,
            'discount' => $discount_percent,
            'request_id' => $request_id,
            'user_id' => $request['user_id'],
            'created_by' => $logged_in_admin_id,
            'expiry_days' => $expiry_days
        ]);
        
        // Update request status
        $stmt = $pdo->prepare("UPDATE promo_code_requests SET status = 'approved' WHERE request_id = :request_id");
        $stmt->execute(['request_id' => $request_id]);
        
        $_SESSION['message'] = 'Promo code generated successfully: ' . $promo_code;
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    }
    
    echo "<script>location.assign('promo_codes.php')</script>";
    exit();
}

// Handle request rejection (only if the user is not an employee)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'reject_request' && $user_type !== 'employee') {
    $request_id = $_POST['request_id'];
    
    try {
        $stmt = $pdo->prepare("UPDATE promo_code_requests SET status = 'rejected' WHERE request_id = :request_id");
        $stmt->execute(['request_id' => $request_id]);
        $_SESSION['message'] = 'Request rejected successfully';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    }
    
    echo "<script>location.assign('promo_codes.php')</script>";
    exit();
}

// Get all pending requests
$pending_requests = [];
try {
    $stmt = $pdo->query("SELECT r.*, u.username, u.email 
                        FROM promo_code_requests r
                        JOIN users u ON r.user_id = u.user_id
                        WHERE r.status = 'pending'
                        ORDER BY r.request_date DESC");
    $pending_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = 'Database error: ' . $e->getMessage();
}

// Get all generated promo codes
$promo_codes = [];
try {
    $stmt = $pdo->query("SELECT p.*, u.username AS user_name, a.username AS admin_name
                        FROM promo_codes p
                        JOIN users u ON p.user_id = u.user_id
                        JOIN users a ON p.created_by = a.user_id
                        ORDER BY p.created_at DESC");
    $promo_codes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = 'Database error: ' . $e->getMessage();
}
?>

<div class="container-fluid">
  <?php if ($user_type === 'employee'): ?>
  <div class="alert alert-warning" role="alert">
    You do not have permission to generate or reject promo codes. All actions are disabled.
  </div>
  <?php endif; ?>

  <div class="row">
    <div class="col-md-12">
      <h2>Promo Code Management</h2>

      <?php if (isset($_SESSION['message'])): ?>
      <div class="alert alert-success">
        <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></div>
      <?php endif; ?>

      <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
      </div>
      <?php endif; ?>

      <div class="card mb-4">
        <div class="card-header">
          <h4>Pending Promo Code Requests</h4>
        </div>
        <div class="card-body">
          <?php if (empty($pending_requests)): ?>
          <p>No pending requests</p>
          <?php else: ?>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Request ID</th>
                  <th>User</th>
                  <th>Email</th>
                  <th>Request Date</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($pending_requests as $request): ?>
                <tr>
                  <td><?php echo htmlspecialchars($request['request_id']); ?></td>
                  <td><?php echo htmlspecialchars($request['username']); ?></td>
                  <td><?php echo htmlspecialchars($request['email']); ?></td>
                  <td><?php echo date('M d, Y H:i', strtotime($request['request_date'])); ?></td>
                  <td>
                    <form method="post" style="display:inline;">
                      <input type="hidden" name="action" value="generate_promo">
                      <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
                      <button type="submit" class="btn btn-success btn-sm"
                        <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>Generate Code (15%)</button>
                    </form>
                    <form method="post" style="display:inline;">
                      <input type="hidden" name="action" value="reject_request">
                      <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
                      <button type="submit" class="btn btn-danger btn-sm"
                        <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>Reject</button>
                    </form>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h4>Generated Promo Codes</h4>
        </div>
        <div class="card-body">
          <?php if (empty($promo_codes)): ?>
          <p>No promo codes generated yet</p>
          <?php else: ?>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Code</th>
                  <th>Discount</th>
                  <th>User</th>
                  <th>Generated By</th>
                  <th>Created</th>
                  <th>Expires</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($promo_codes as $code): ?>
                <tr>
                  <td><?php echo htmlspecialchars($code['code']); ?></td>
                  <td><?php echo $code['discount_percent']; ?>%</td>
                  <td><?php echo htmlspecialchars($code['user_name']); ?></td>
                  <td><?php echo htmlspecialchars($code['admin_name']); ?></td>
                  <td><?php echo date('M d, Y', strtotime($code['created_at'])); ?></td>
                  <td><?php echo $code['expires_at'] ? date('M d, Y', strtotime($code['expires_at'])) : 'Never'; ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include("components/footer.php"); ?>