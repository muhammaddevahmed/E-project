<?php
include("components/header.php");

// ✅ Step 1: Validate and get employee_id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid employee ID.";
    include("components/footer.php");
    exit;
}

$employee_id = (int)$_GET['id'];

// ✅ Step 2: Fetch employee & user data
$stmt = $pdo->prepare("
    SELECT e.user_id, u.full_name, e.role 
    FROM employees e
    JOIN users u ON e.user_id = u.user_id
    WHERE e.employee_id = :employee_id
");
$stmt->execute([':employee_id' => $employee_id]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

// ✅ Step 3: Stop if not found
if (!$employee) {
    echo "Employee not found.";
    include("components/footer.php");
    exit;
}

$user_id = $employee['user_id'];

// ✅ Step 4: If POST confirmed, delete from both tables
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    try {
        $pdo->beginTransaction();

        // Delete from employees table
        $stmt = $pdo->prepare("DELETE FROM employees WHERE employee_id = :employee_id");
        $stmt->execute([':employee_id' => $employee_id]);

        // Delete from users table
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $user_id]);

        $pdo->commit();

        echo "<script>location.assign('allemployees.php')
        </script>";
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Failed to delete: " . $e->getMessage();
    }
}
?>

<!-- ✅ HTML confirmation -->
<style>
.confirm-box {
  max-width: 500px;
  margin: 60px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 5px;
  text-align: center;
  font-family: Arial, sans-serif;
}

.confirm-box h2 {
  margin-bottom: 20px;
}

.confirm-box form {
  display: inline-block;
}

.btn {
  padding: 10px 15px;
  text-decoration: none;
  border-radius: 3px;
  font-size: 14px;
  margin: 0 5px;
  border: none;
  cursor: pointer;
}

.btn-cancel {
  background-color: #777;
  color: white;
}

.btn-delete {
  background-color: #f44336;
  color: white;
}
</style>

<div class="confirm-box">
  <h2>Delete Employee</h2>
  <p>
    Are you sure you want to delete
    <strong><?php echo htmlspecialchars($employee['full_name']); ?></strong>
    (Role: <?php echo htmlspecialchars($employee['role']); ?>)?
  </p>

  <form method="POST">
    <input type="hidden" name="confirm" value="yes">
    <button type="submit" class="btn btn-delete">Yes, Delete</button>
    <a href="allemployees.php" class="btn btn-cancel">Cancel</a>
  </form>
</div>

<?php include("components/footer.php"); ?>