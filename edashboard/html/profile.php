<style>
:root {
  --primary-color: #4361ee;
  --secondary-color: #3f37c9;
  --light-color: #f8f9fa;
  --dark-color: #212529;
  --success-color: #4bb543;
  --error-color: #ff3333;
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
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.profile-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}

.profile-title {
  font-size: 28px;
  color: var(--primary-color);
}

.logout-btn {
  background-color: var(--error-color);
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  transition: background-color 0.3s;
}

.logout-btn:hover {
  background-color: #cc0000;
}

.profile-container {
  display: flex;
  gap: 30px;
}

.profile-card {
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  padding: 30px;
  flex: 1;
}

.profile-card h2 {
  margin-bottom: 20px;
  color: var(--primary-color);
  border-bottom: 2px solid #eee;
  padding-bottom: 10px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
}

.form-control {
  width: 100%;
  padding: 10px 15px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 16px;
  transition: border-color 0.3s;
}

.form-control:focus {
  border-color: var(--primary-color);
  outline: none;
}



.btn1-block {

  display: inline-block;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 500;
  text-transform: capitalize;
  background-color: #28a745;
  color: white;
  border: none
}

.btn1-block:hover {
  background-color: rgb(86, 183, 108)
}

.alert {
  padding: 12px 15px;
  border-radius: 4px;
  margin-bottom: 20px;
}

.alert-success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.alert-error {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

.user-type-badge {
  display: inline-block;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 500;
  text-transform: capitalize;
}

.user-type-admin {
  background-color: #ffc107;
  color: #856404;
}

.user-type-employee {
  background-color: #17a2b8;
  color: white;
}

.user-type-customer {
  background-color: #28a745;
  color: white;
}

.profile-info {
  margin-bottom: 20px;
}

.profile-info p {
  margin-bottom: 10px;
  font-size: 16px;
}

.profile-info strong {
  display: inline-block;
  width: 120px;
}

@media (max-width: 768px) {
  .profile-container {
    flex-direction: column;
  }
}
</style>
<?php
include("components/header.php");

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables to prevent undefined index warnings
$error = '';
$success = '';
$password_error = '';
$password_success = '';

// Get user data
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM Users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<script>alert('User not found.'); window.location.href='logout.php';</script>";
    exit();
}

// Set default values for form fields
$user['username'] = $user['username'] ?? '';
$user['full_name'] = $user['full_name'] ?? '';
$user['email'] = $user['email'] ?? '';
$user['phone'] = $user['phone'] ?? '';
$user['address'] = $user['address'] ?? '';
$user['user_type'] = $user['user_type'] ?? 'customer';
$user['created_at'] = $user['created_at'] ?? date('Y-m-d H:i:s');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'])) {
    $username = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    
    // Validate inputs
    if (!preg_match("/^[a-zA-Z0-9_]{5,20}$/", $username)) {
        $error = "Username must be 5-20 characters and contain only letters, numbers, and underscores.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!empty($phone) && !preg_match("/^\+?[0-9]{7,15}$/", $phone)) {
        $error = "Phone number must be 7-15 digits and can start with +.";
    } else {
        try {
            // Check if email is already taken by another user
            $checkStmt = $pdo->prepare("SELECT user_id FROM Users WHERE email = :email AND user_id != :user_id");
            $checkStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $checkStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                $error = "Email already exists.";
            } else {
                // Check if username is already taken by another user
                $checkUsernameStmt = $pdo->prepare("SELECT user_id FROM Users WHERE username = :username AND user_id != :user_id");
                $checkUsernameStmt->bindParam(':username', $username, PDO::PARAM_STR);
                $checkUsernameStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $checkUsernameStmt->execute();

                if ($checkUsernameStmt->rowCount() > 0) {
                    $error = "Username already exists.";
                } else {
                    // Update user data
                    $updateStmt = $pdo->prepare("UPDATE Users SET 
                        username = :username,
                        full_name = :full_name,
                        email = :email,
                        phone = :phone,
                        address = :address
                        WHERE user_id = :user_id");

                    $updateStmt->bindParam(':username', $username, PDO::PARAM_STR);
                    $updateStmt->bindParam(':full_name', $full_name, PDO::PARAM_STR);
                    $updateStmt->bindParam(':email', $email, PDO::PARAM_STR);
                    $updateStmt->bindParam(':phone', $phone, PDO::PARAM_STR);
                    $updateStmt->bindParam(':address', $address, PDO::PARAM_STR);
                    $updateStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

                    if ($updateStmt->execute()) {
                      $success = "Profile updated successfully!";
                      // Refresh user data
                      $stmt = $pdo->prepare("SELECT * FROM Users WHERE user_id = :user_id");
                      $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                      $stmt->execute();
                      $user = $stmt->fetch(PDO::FETCH_ASSOC);
                      
                      // Update the session if username changed - FIXED THIS LINE
                      if (isset($_SESSION['username'])) {
                          $_SESSION['username'] = $username;
                      }
                  } else {
                      $error = "Error updating profile.";
                  }
                }
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

// Handle password change
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (!password_verify($current_password, $user['password_hash'])) {
        $password_error = "Current password is incorrect.";
    } elseif (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{6,}$/", $new_password)) {
        $password_error = "Password must be at least 6 characters, with at least one letter and one number.";
    } elseif ($new_password !== $confirm_password) {
        $password_error = "New passwords do not match.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $updateStmt = $pdo->prepare("UPDATE Users SET password_hash = :password WHERE user_id = :user_id");
        $updateStmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $updateStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
        if ($updateStmt->execute()) {
            $password_success = "Password changed successfully!";
        } else {
            $password_error = "Error changing password.";
        }
    }
}
?>







<div class="container">
  <div class="profile-header">
    <h1 class="profile-title">My Profile</h1>
    <a href="logout.php" class="logout-btn">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </div>

  <?php if (isset($error)): ?>
  <div class="alert alert-error">
    <?php echo htmlspecialchars($error); ?>
  </div>
  <?php endif; ?>

  <?php if (isset($success)): ?>
  <div class="alert alert-success">
    <?php echo htmlspecialchars($success); ?>
  </div>
  <?php endif; ?>

  <div class="profile-container">
    <div class="profile-card">
      <h2>Profile Information</h2>

      <div class="profile-info">
        <p><strong>User ID:</strong> <?php echo htmlspecialchars($user['user_id']); ?></p>
        <p><strong>Account Type:</strong>
          <span class="user-type-badge user-type-<?php echo htmlspecialchars($user['user_type']); ?>">
            <?php echo htmlspecialchars($user['user_type']); ?>
          </span>
        </p>
        <p><strong>Member Since:</strong> <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
      </div>

      <form method="POST" onsubmit="return validateProfileForm()">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" class="form-control"
            value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="form-group">
          <label for="full_name">Full Name</label>
          <input type="text" id="full_name" name="full_name" class="form-control"
            value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" class="form-control"
            value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="form-group">
          <label for="phone">Phone</label>
          <input type="text" id="phone" name="phone" class="form-control"
            value="<?php echo htmlspecialchars($user['phone']); ?>">
        </div>

        <div class="form-group">
          <label for="address">Address</label>
          <textarea id="address" name="address" class="form-control"
            rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
        </div>

        <button type="submit" class=" btn1-block">Update Profile</button>
      </form>
    </div>

    <div class="profile-card">
      <h2>Change Password</h2>

      <?php if (isset($password_error)): ?>
      <div class="alert alert-error">
        <?php echo htmlspecialchars($password_error); ?>
      </div>
      <?php endif; ?>

      <?php if (isset($password_success)): ?>
      <div class="alert alert-success">
        <?php echo htmlspecialchars($password_success); ?>
      </div>
      <?php endif; ?>

      <form method="POST">
        <div class="form-group">
          <label for="current_password">Current Password</label>
          <input type="password" id="current_password" name="current_password" class="form-control" required>
        </div>

        <div class="form-group">
          <label for="new_password">New Password</label>
          <input type="password" id="new_password" name="new_password" class="form-control" required>
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm New Password</label>
          <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
        </div>

        <button type="submit" name="change_password" class=" btn1-block">Change Password</button>
      </form>
    </div>
  </div>
</div>

<?php
include("components/footer.php");
  ?>

<script>
function validateProfileForm() {
  const username = document.getElementById("username").value;
  const email = document.getElementById("email").value;
  const phone = document.getElementById("phone").value;

  const usernameRegex = /^[a-zA-Z0-9_]{5,20}$/;
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const phoneRegex = /^\+?[0-9]{7,15}$/;

  if (!usernameRegex.test(username)) {
    alert("Username must be 5-20 characters long and contain only letters, numbers, and underscores.");
    return false;
  }

  if (!emailRegex.test(email)) {
    alert("Invalid email format.");
    return false;
  }

  if (phone && !phoneRegex.test(phone)) {
    alert("Phone number must be 7-15 digits and can start with +.");
    return false;
  }

  return true;
}
</script>