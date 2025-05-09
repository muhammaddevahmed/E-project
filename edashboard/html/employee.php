<?php
include("components/header.php");

// Check user type
$user_type = $_SESSION['user_type'] ?? '';

// Handle form submission (only if the user is not an employee)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $user_type !== 'employee') {
    try {
        $pdo->beginTransaction();

        // Insert into users table
        $userSql = "INSERT INTO users (username, password_hash, full_name, email, phone, user_type, address) 
                    VALUES (?, ?, ?, ?, ?, 'employee', ?)";
        $userStmt = $pdo->prepare($userSql);
        
        // Hash the password
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $userStmt->execute([
            $_POST['username'],
            $passwordHash,
            $_POST['full_name'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['address']
        ]);
        
        // Get the last inserted user ID
        $user_id = $pdo->lastInsertId();
        
        // Insert into employees table
        $employeeSql = "INSERT INTO employees (user_id, role) VALUES (?, ?)";
        $employeeStmt = $pdo->prepare($employeeSql);
        $employeeStmt->execute([$user_id, $_POST['role']]);
        
        $pdo->commit();
        
        $success = "Employee added successfully!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Error: " . $e->getMessage();
    }
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
.heading {
  font-size: 2.5rem;
  font-weight: 700;
  color: #7fad39;
  margin-bottom: 1rem;
  text-align: center;
}

/* Ensure input has padding to avoid overlap with the eye icon */
.password-input-container {
  position: relative;
}

.password-input-container input {
  padding-right: 2.5rem;
  /* Space for the eye icon */
}

.password-input-container button {
  position: absolute;
  top: 50%;
  right: 0.75rem;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
}

.password-input-container i {
  color: #6b7280;
  /* Tailwind gray-500 */
}

.password-input-container i:hover {
  color: #7fad39;
  /* Tailwind primary color */
}
</style>

<!-- Employee Data Entry Form -->
<div class="container-fluid pt-4 px-4">
  <?php if (isset($success)): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($success) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif; ?>

  <?php if (isset($error)): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif; ?>

  <?php if ($user_type === 'employee'): ?>
  <div class="alert alert-warning" role="alert">
    You do not have permission to add employees. All fields are disabled.
  </div>
  <?php endif; ?>

  <div class="row bg-light rounded mx-0">
    <div class="col-md-12">
      <h3 class="heading">Add New Employee</h3>
      <form method="POST" class="needs-validation" novalidate>
        <div class="row g-3">
          <!-- Personal Information -->
          <div class="col-md-6">
            <div class="card mb-4">
              <div class="card-header" style="background-color: #7fad39; color: white;">
                <h5>Personal Information</h5>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label for="full_name" class="form-label">Full Name</label>
                  <input type="text" class="form-control" id="full_name" name="full_name" required
                    <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
                  <div class="invalid-feedback">Please enter the employee's full name.</div>
                </div>

                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" required
                    <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
                  <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>

                <div class="mb-3">
                  <label for="phone" class="form-label">Phone</label>
                  <input type="tel" class="form-control" id="phone" name="phone"
                    <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
                </div>

                <div class="mb-3">
                  <label for="address" class="form-label">Address</label>
                  <textarea class="form-control" id="address" name="address" rows="3"
                    <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>></textarea>
                </div>
              </div>
            </div>
          </div>

          <!-- Account Information -->
          <div class="col-md-6">
            <div class="card mb-4">
              <div class="card-header" style="background-color: #7fad39; color: white;">
                <h5>Account Information</h5>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" class="form-control" id="username" name="username" required
                    <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
                  <div class="invalid-feedback">Please choose a username.</div>
                </div>

                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <div class="password-input-container">
                    <input type="password" class="form-control" id="password" name="password" required
                      <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
                    <button type="button" onclick="togglePassword('password')"
                      <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
                      <i class="far fa-eye"></i>
                    </button>
                  </div>
                  <div id="passwordRequirements" class="text-xs mt-2 hidden">
                    <ul>
                      <li class="flex items-center" id="reqLength"><span class="mr-1">✓</span> At least 8 characters
                      </li>
                      <li class="flex items-center" id="reqLower"><span class="mr-1">✓</span> Contains a lowercase
                        letter</li>
                      <li class="flex items-center" id="reqUpper"><span class="mr-1">✓</span> Contains an uppercase
                        letter</li>
                      <li class="flex items-center" id="reqNumber"><span class="mr-1">✓</span> Contains a number</li>
                      <li class="flex items-center" id="reqSpecial"><span class="mr-1">✓</span> Contains a special
                        character
                        (@$!%*?&)</li>
                    </ul>
                  </div>
                  <div class="invalid-feedback">Please provide a password.</div>
                </div>

                <div class="mb-3">
                  <label for="confirm_password" class="form-label">Confirm Password</label>
                  <div class="password-input-container">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required
                      <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
                    <button type="button" onclick="togglePassword('confirm_password')"
                      <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
                      <i class="far fa-eye"></i>
                    </button>
                  </div>
                  <div id="passwordMatch" class="text-xs mt-1 hidden"></div>
                  <div class="invalid-feedback">Passwords must match.</div>
                </div>

                <div class="mb-3">
                  <label for="role" class="form-label">Employee Role</label>
                  <select class="form-select" id="role" name="role" required
                    <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>
                    <option value="" selected disabled>Select Role</option>
                    <option value="manager">Manager</option>
                    <option value="Shop Keeper">Shop Keeper</option>
                    <option value="Call support">Call Support</option>
                    <option value="Sales man">Sales Man</option>
                    <option value="Cashier">Cashier</option>
                  </select>
                  <div class="invalid-feedback">Please select a role.</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="text-end">
          <button type="submit" class="btn" style="background-color: #7fad39; color: white;"
            <?php echo ($user_type === 'employee') ? 'disabled' : ''; ?>>Add Employee</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Form Validation and Password Functionality Script -->
<script>
// Client-side form validation
(function() {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Password confirmation validation
  var password = document.getElementById('password')
  var confirm_password = document.getElementById('confirm_password')

  function validatePassword() {
    if (password.value != confirm_password.value) {
      confirm_password.setCustomValidity("Passwords don't match")
    } else {
      confirm_password.setCustomValidity('')
    }
  }

  password.onchange = validatePassword
  confirm_password.onkeyup = validatePassword

  // Loop over forms and prevent submission if invalid
  Array.prototype.slice.call(forms)
    .forEach(function(form) {
      form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()

// Toggle password visibility
function togglePassword(fieldId) {
  const field = document.getElementById(fieldId);
  const icon = field.nextElementSibling.querySelector('i');
  if (field.type === "password") {
    field.type = "text";
    icon.classList.replace('fa-eye', 'fa-eye-slash');
  } else {
    field.type = "password";
    icon.classList.replace('fa-eye-slash', 'fa-eye');
  }
}

// Real-time password validation
document.getElementById('password').addEventListener('input', function() {
  const password = this.value;
  const requirements = document.getElementById('passwordRequirements');
  const reqLength = document.getElementById('reqLength');
  const reqLower = document.getElementById('reqLower');
  const reqUpper = document.getElementById('reqUpper');
  const reqNumber = document.getElementById('reqNumber');
  const reqSpecial = document.getElementById('reqSpecial');

  // Show requirements when typing
  if (password.length > 0) {
    requirements.classList.remove('hidden');
  } else {
    requirements.classList.add('hidden');
  }

  // Check each requirement
  if (password.length >= 8) {
    reqLength.querySelector('span').textContent = '✓';
    reqLength.querySelector('span').className = 'mr-1 text-green-600';
  } else {
    reqLength.querySelector('span').textContent = '✗';
    reqLength.querySelector('span').className = 'mr-1 text-red-600';
  }

  if (/[a-z]/.test(password)) {
    reqLower.querySelector('span').textContent = '✓';
    reqLower.querySelector('span').className = 'mr-1 text-green-600';
  } else {
    reqLower.querySelector('span').textContent = '✗';
    reqLower.querySelector('span').className = 'mr-1 text-red-600';
  }

  if (/[A-Z]/.test(password)) {
    reqUpper.querySelector('span').textContent = '✓';
    reqUpper.querySelector('span').className = 'mr-1 text-green-600';
  } else {
    reqUpper.querySelector('span').textContent = '✗';
    reqUpper.querySelector('span').className = 'mr-1 text-red-600';
  }

  if (/\d/.test(password)) {
    reqNumber.querySelector('span').textContent = '✓';
    reqNumber.querySelector('span').className = 'mr-1 text-green-600';
  } else {
    reqNumber.querySelector('span').textContent = '✗';
    reqNumber.querySelector('span').className = 'mr-1 text-red-600';
  }

  if (/[@$!%*?&]/.test(password)) {
    reqSpecial.querySelector('span').textContent = '✓';
    reqSpecial.querySelector('span').className = 'mr-1 text-green-600';
  } else {
    reqSpecial.querySelector('span').textContent = '✗';
    reqSpecial.querySelector('span').className = 'mr-1 text-red-600';
  }
});

// Real-time password match validation
document.getElementById('confirm_password').addEventListener('input', function() {
  const password = document.getElementById('password').value;
  const confirmPassword = this.value;
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
});
</script>

<?php
include("components/footer.php");
?>