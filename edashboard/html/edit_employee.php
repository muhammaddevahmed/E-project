<?php
include("components/header.php");

// Check if ID is passed
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid employee ID.";
    exit;
}

$employee_id = (int)$_GET['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $role = trim($_POST['role']);

    // Simple validation
    if (empty($full_name) || empty($role)) {
        $error = "Please fill in all fields.";
    } else {
        // Update both users and employees tables
        $stmt = $pdo->prepare("
            UPDATE users u 
            JOIN employees e ON u.user_id = e.user_id
            SET u.full_name = :full_name, e.role = :role 
            WHERE e.employee_id = :employee_id
        ");
        $success = $stmt->execute([
            ':full_name' => $full_name,
            ':role' => $role,
            ':employee_id' => $employee_id
        ]);

        if ($success) {
            echo "<script>location.assign('allemployees.php')
            </script>";
            exit;
        } else {
            $error = "Failed to update employee.";
        }
    }
}

// Fetch current employee data
$stmt = $pdo->prepare("
    SELECT u.full_name, e.role 
    FROM employees e
    JOIN users u ON e.user_id = u.user_id
    WHERE e.employee_id = :employee_id
");
$stmt->execute([':employee_id' => $employee_id]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    echo "Employee not found.";
    exit;
}
?>

<style>
    form {
        max-width: 500px;
        margin: 40px auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    label {
        display: block;
        margin-bottom: 6px;
        font-weight: bold;
    }
    input[type="text"], select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }
    .btn {
        padding: 10px 15px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }
    .error {
        color: red;
        margin-bottom: 15px;
    }
</style>

<h2 style="text-align: center;">Edit Employee</h2>

<form method="POST">
    <?php if (!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <label for="full_name">Full Name</label>
    <input type="text" name="full_name" id="full_name" value="<?php echo htmlspecialchars($employee['full_name']); ?>" required>

    <label for="role">Role</label>
    <input type="text" name="role" id="role" value="<?php echo htmlspecialchars($employee['role']); ?>" required>

    <button type="submit" class="btn">Update</button>
</form>

<?php include("components/footer.php"); ?>
