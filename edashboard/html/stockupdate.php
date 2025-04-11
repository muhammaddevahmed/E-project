<style>
body {
  font-family: Arial, sans-serif;
  margin: 20px;
}

h1 {
  color: #2c3e50;
  margin-bottom: 30px;
  text-align: center;
}


h2 {
  color: #333;

}

.alert {
  padding: 10px 15px;
  margin-bottom: 20px;
  border-radius: 4px;
}

.alert-success {
  background-color: #dff0d8;
  color: #3c763d;
  border: 1px solid #d6e9c6;
}

.alert-error {
  background-color: #f2dede;
  color: #a94442;
  border: 1px solid #ebccd1;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

th,
td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

th {
  background-color: #f2f2f2;
  font-weight: bold;
}

tr:hover {
  background-color: #f5f5f5;
}

.status-in-stock {
  color: #4caf50;
  font-weight: bold;
}

.status-out-of-stock {
  color: #f44336;
  font-weight: bold;
}

.stock-form {
  background-color: #f9f9f9;
  padding: 20px;
  border-radius: 5px;
  margin-top: 30px;
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

select,
input,
textarea {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

button {
  background-color: #4CAF50;
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #45a049;
}
</style><?php
include("components/header.php");

// Check user type
$user_type = $_SESSION['user_type'] ?? '';

// Initialize variables
$message = '';
$error = '';

// Handle stock update form submission (only if the user is not an employee)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock']) && $user_type !== 'employee') {
    try {
        $pdo->beginTransaction();
        
        // Get form data
        $product_id = $_POST['product_id'];
        $change_in_quantity = (int)$_POST['change_in_quantity'];
        $update_reason = $_POST['update_reason'];
        
        // 1. First get current stock quantity
        $get_current_query = "SELECT stock_quantity FROM products WHERE product_id = :product_id";
        $stmt = $pdo->prepare($get_current_query);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);
        $stmt->execute();
        $current_quantity = $stmt->fetchColumn();
        
        // 2. Update products table
        $update_product_query = "UPDATE products 
                               SET stock_quantity = stock_quantity + :change 
                               WHERE product_id = :product_id";
        $stmt = $pdo->prepare($update_product_query);
        $stmt->bindParam(':change', $change_in_quantity, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);
        $stmt->execute();
        
        // 3. Record the change in stock_update table
        $new_quantity = $current_quantity + $change_in_quantity;
        $insert_stock_query = "INSERT INTO stock_update 
                             (product_id, previous_quantity, new_quantity, quantity_change, update_reason) 
                             VALUES (:product_id, :prev_qty, :new_qty, :change, :reason)";
        $stmt = $pdo->prepare($insert_stock_query);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_STR);
        $stmt->bindParam(':prev_qty', $current_quantity, PDO::PARAM_INT);
        $stmt->bindParam(':new_qty', $new_quantity, PDO::PARAM_INT);
        $stmt->bindParam(':change', $change_in_quantity, PDO::PARAM_INT);
        $stmt->bindParam(':reason', $update_reason, PDO::PARAM_STR);
        $stmt->execute();
        
        $pdo->commit();
        $message = "Stock updated successfully!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Error updating stock: " . $e->getMessage();
    }
}

// Fetch all products with current stock status
try {
    $query = "SELECT p.*, 
              CASE 
                WHEN p.stock_quantity > 0 THEN 'In Stock' 
                ELSE 'Out of Stock' 
              END AS stock_status
              FROM products p
              ORDER BY p.stock_quantity, p.product_name";
    $stmt = $pdo->query($query);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<style>
/* Add your existing styles here */
</style>

<body>
  <h1>Stock Management</h1>

  <?php if (!empty($message)): ?>
  <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
  <?php elseif (!empty($error)): ?>
  <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <?php if ($user_type === 'employee'): ?>
  <div class="alert alert-warning">
    You do not have permission to update stock. All actions are disabled.
  </div>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Current Price</th>
        <th>Quantity</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product): ?>
      <tr>
        <td><?php echo htmlspecialchars($product['product_id']); ?></td>
        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
        <td>$<?php echo number_format($product['price'], 2); ?></td>
        <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
        <td class="status-<?php echo strtolower(str_replace(' ', '-', $product['stock_status'])); ?>">
          <?php echo htmlspecialchars($product['stock_status']); ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="stock-form">
    <h2>Update Stock</h2>
    <form method="POST">
      <div class="form-group">
        <label for="product_id">Select Product:</label>
        <select name="product_id" id="product_id" required <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
          <option value="">-- Select Product --</option>
          <?php foreach ($products as $product): ?>
          <option value="<?php echo htmlspecialchars($product['product_id']); ?>">
            <?php echo htmlspecialchars($product['product_name']); ?>
            (Current: <?php echo htmlspecialchars($product['stock_quantity']); ?>)
          </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="change_in_quantity">Quantity Change:</label>
        <input type="number" name="change_in_quantity" id="change_in_quantity" required
          <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
        <small>Positive number to add stock, negative to remove</small>
      </div>

      <div class="form-group">
        <label for="update_reason">Reason for Update:</label>
        <textarea name="update_reason" id="update_reason" rows="3" required
          <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>></textarea>
      </div>

      <button type="submit" name="update_stock" <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>Update
        Stock</button>
    </form>
  </div>

  <?php
include("components/footer.php");
  ?>