<?php
include("components/header.php");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        
        echo '<div class="alert alert-success">Employee added successfully!</div>';
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}
?>

<!-- Employee Data Entry Form -->
<div class="container-fluid pt-4 px-4">
    <div class="row bg-light rounded mx-0">
        <div class="col-md-12">
            <h3>Add New Employee</h3>
            <form method="POST" class="needs-validation" novalidate>
                <div class="row g-3">
                    <!-- Personal Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5>Personal Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                                    <div class="invalid-feedback">Please enter the employee's full name.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback">Please enter a valid email address.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Account Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5>Account Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                    <div class="invalid-feedback">Please choose a username.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="invalid-feedback">Please provide a password.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    <div class="invalid-feedback">Passwords must match.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="role" class="form-label">Employee Role</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="" selected disabled>Select Role</option>
                                        <option value="manager">Manager</option>
                                        <option value="sales">Sales</option>
                                        <option value="support">Support</option>
                                        <option value="developer">Developer</option>
                                        <option value="designer">Designer</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a role.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Add Employee</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form Validation Script -->
<script>
// Client-side form validation
(function () {
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
    
    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                
                form.classList.add('was-validated')
            }, false)
        })
})()
</script>

<?php
include("components/footer.php");
?>