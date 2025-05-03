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
  border: none;
}

.btn1-block:hover {
  background-color: rgb(86, 183, 108);
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

/* Ensure toggle button is visible */
.toggle-password {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  z-index: 10;
  padding: 0;
}

.toggle-password svg {
  width: 20px;
  height: 20px;
  stroke: #666;
}

.form-group .relative {
  position: relative;
  width: 100%;
}

.password-requirements {
  margin-top: 8px;
  padding: 8px;
  background-color: #f8f9fa;
  border-radius: 4px;
  font-size: 13px;
}

.requirement {
  display: flex;
  align-items: center;
  margin-bottom: 4px;
}

.requirement-icon {
  margin-right: 8px;
  width: 16px;
  text-align: center;
}

.requirement.valid .requirement-icon {
  color: #4bb543;
}

.requirement.invalid .requirement-icon {
  color: #ff3333;
}

.requirement-text {
  flex: 1;
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
                        
                        // Update the session if username changed
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

        <button type="submit" class="btn1-block">Update Profile</button>
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
          <div class="relative">
            <input type="password" id="current_password" name="current_password" class="form-control" required>
            <button type="button" class="toggle-password" data-target="current_password">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeIcon_current_password">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
          </div>
        </div>
        <div class="form-group">
          <label for="new_password">New Password</label>
          <div class="relative">
            <input type="password" id="new_password" name="new_password" class="form-control" required>
            <button type="button" class="toggle-password" data-target="new_password">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeIcon_new_password">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
          </div>
          <div id="passwordRequirements" class="password-requirements">
            <div class="requirement" id="reqLength">
              <span class="requirement-icon">✗</span>
              <span class="requirement-text">At least 8 characters</span>
            </div>
            <div class="requirement" id="reqLower">
              <span class="requirement-icon">✗</span>
              <span class="requirement-text">Contains a lowercase letter</span>
            </div>
            <div class="requirement" id="reqUpper">
              <span class="requirement-icon">✗</span>
              <span class="requirement-text">Contains an uppercase letter</span>
            </div>
            <div class="requirement" id="reqNumber">
              <span class="requirement-icon">✗</span>
              <span class="requirement-text">Contains a number</span>
            </div>
            <div class="requirement" id="reqSpecial">
              <span class="requirement-icon">✗</span>
              <span class="requirement-text">Contains a special character (@$!%*?&)</span>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm New Password</label>
          <div class="relative">
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            <button type="button" class="toggle-password" data-target="confirm_password">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeIcon_confirm_password">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
          </div>
        </div>

        <button type="submit" name="change_password" class="btn1-block">Change Password</button>
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

// Show/Hide Password functionality
document.addEventListener('DOMContentLoaded', function() {
  const toggleButtons = document.querySelectorAll('.toggle-password');

  toggleButtons.forEach(button => {
    button.addEventListener('click', function() {
      const targetId = this.getAttribute('data-target');
      const passwordInput = document.getElementById(targetId);
      const eyeIcon = document.getElementById(`eyeIcon_${targetId}`);

      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);

      // Toggle eye icon
      if (type === 'text') {
        eyeIcon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        `;
      } else {
        eyeIcon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
      }
    });
  });
});

// Password requirement validation
const newPasswordInput = document.getElementById('new_password');
const confirmPasswordInput = document.getElementById('confirm_password');

newPasswordInput.addEventListener('input', function() {
  const password = this.value;

  // Length requirement
  const lengthValid = password.length >= 8;
  updateRequirement('reqLength', lengthValid);

  // Lowercase requirement
  const lowerValid = /[a-z]/.test(password);
  updateRequirement('reqLower', lowerValid);

  // Uppercase requirement
  const upperValid = /[A-Z]/.test(password);
  updateRequirement('reqUpper', upperValid);

  // Number requirement
  const numberValid = /\d/.test(password);
  updateRequirement('reqNumber', numberValid);

  // Special character requirement
  const specialValid = /[@$!%*?&]/.test(password);
  updateRequirement('reqSpecial', specialValid);

  // Check password match if confirm password has value
  if (confirmPasswordInput.value.length > 0) {
    checkPasswordMatch();
  }
});

confirmPasswordInput.addEventListener('input', checkPasswordMatch);

function updateRequirement(id, isValid) {
  const element = document.getElementById(id);
  const icon = element.querySelector('.requirement-icon');

  if (isValid) {
    element.classList.add('valid');
    element.classList.remove('invalid');
    icon.textContent = '✓';
  } else {
    element.classList.add('invalid');
    element.classList.remove('valid');
    icon.textContent = '✗';
  }
}

function checkPasswordMatch() {
  const password = newPasswordInput.value;
  const confirmPassword = confirmPasswordInput.value;
  const matchDiv = document.getElementById('passwordMatch');

  if (confirmPassword.length > 0) {
    matchDiv.classList.remove('hidden');
    if (password === confirmPassword) {
      matchDiv.textContent = "Passwords match!";
      matchDiv.className = "text-xs mt-1 text-green-600";
    } else {
      matchDiv.textContent = "Passwords do not match!";
      matchDiv.className = "text-xs mt-1 text-red-600";
    }
  } else {
    matchDiv.classList.add('hidden');
  }
}
</script>