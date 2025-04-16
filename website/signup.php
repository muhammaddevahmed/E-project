<?php
include 'php/db_connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if required fields are set
    if (!isset($_POST['username']) || !isset($_POST['full_name']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['confirm_password'])) {
        echo "<script>alert('All required fields must be filled.');</script>";
        exit();
    }

    // Check if passwords match
    if ($_POST['password'] !== $_POST['confirm_password']) {
        echo "<script>alert('Passwords do not match.');</script>";
        exit();
    }

    // Assign form data to variables
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = isset($_POST['phone']) ? $_POST['phone'] : ''; // Optional field
    $password = $_POST['password'];
    $address = isset($_POST['address']) ? $_POST['address'] : ''; // Optional field
    $user_type = 'customer'; // Default role

    // ✅ IMPROVED REGEX VALIDATION
    if (!preg_match("/^[a-zA-Z][a-zA-Z0-9_]{4,19}$/", $username)) {
        echo "<script>alert('Username must be 5-20 characters, start with a letter, and contain only letters, numbers, and underscores.');</script>";
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
        exit();
    }
    if (!empty($phone) && !preg_match("/^\+?[0-9]{7,15}$/", $phone)) {
        echo "<script>alert('Phone number must be 7-15 digits and can start with +.');</script>";
        exit();
    }
    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{6,}$/", $password)) {
        echo "<script>alert('Password must be at least 6 characters, with at least one letter and one number.');</script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    try {
        // ✅ CHECK IF EMAIL ALREADY EXISTS (ONLY EMAIL CHECK)
        $checkStmt = $pdo->prepare("SELECT user_id FROM Users WHERE email = :email");
        $checkStmt->bindValue(':email', $email);
        $checkStmt->execute();
    
        if ($checkStmt->rowCount() > 0) {
            echo "<script>
                alert('Error: This email is already registered. Please use a different email.');
                window.location.assign('signup.php');
            </script>";
            exit();
        }
    
        // ✅ INSERT USER IF EMAIL IS UNIQUE
        $stmt = $pdo->prepare("INSERT INTO Users (username, password_hash, full_name, email, phone, user_type, address) 
                               VALUES (:username, :password, :full_name, :email, :phone, :user_type, :address)");

        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $hashed_password);
        $stmt->bindValue(':full_name', $full_name);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':user_type', $user_type);
        $stmt->bindValue(':address', $address);

        if ($stmt->execute()) {
            echo "<script>alert('Signup successful! Please login.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Error: Signup failed.');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Database error: " . $e->getMessage() . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Crafty Corner - Sign Up</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="shortcut icon" href="images/logo.png" type="image/x-icon">
  <script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          primary: '#7fad39',
          'primary-dark': '#6e9c2a',
          'primary-light': '#a8cf6e',
        },
        fontFamily: {
          poppins: ['Poppins', 'sans-serif'],
        },
      }
    }
  }
  </script>
</head>

<body
  class="min-h-screen bg-gradient-to-br from-[#f5f7fa] via-[#e4f0d5] to-[#d4e8b7] font-poppins flex items-center justify-center p-4">
  <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">
    <!-- Header Section -->
    <div class="bg-gray-800 text-white py-5 px-6 text-center">
      <h1 class="text-2xl font-bold">The Crafty Corner</h1>
      <p class="text-gray-300 mt-1">Create your account</p>
    </div>

    <!-- Form Section -->
    <div class="p-6 sm:p-8">
      <form method="POST" onsubmit="return validateForm()">
        <!-- Username Field -->
        <div class="mb-4">
          <label for="username" class="block text-gray-700 text-sm font-medium mb-2">Username</label>
          <input type="text" name="username" id="username"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
            placeholder="5-20 chars, start with letter" required>
        </div>

        <!-- Full Name Field -->
        <div class="mb-4">
          <label for="full_name" class="block text-gray-700 text-sm font-medium mb-2">Full Name</label>
          <input type="text" name="full_name"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
            placeholder="Your full name" required>
        </div>

        <!-- Email Field -->
        <div class="mb-4">
          <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
          <input type="email" name="email" id="email"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
            placeholder="your@email.com" required>
        </div>

        <!-- Phone Field -->
        <div class="mb-4">
          <label for="phone" class="block text-gray-700 text-sm font-medium mb-2">Phone (optional)</label>
          <input type="text" name="phone" id="phone"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
            placeholder="+1234567890">
        </div>

        <!-- Password Field -->
        <div class="mb-4">
          <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
          <input type="password" name="password" id="password"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
            placeholder="6+ chars with letter & number" required>
          <div class="text-xs text-gray-500 mt-1">
            Password must contain at least 6 characters with at least one letter and one number.
          </div>
        </div>

        <!-- Confirm Password Field -->
        <div class="mb-6">
          <label for="confirm_password" class="block text-gray-700 text-sm font-medium mb-2">Confirm Password</label>
          <input type="password" name="confirm_password" id="confirm_password"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
            placeholder="Re-enter your password" required>
          <div id="passwordMatch" class="text-xs mt-1 hidden"></div>
        </div>

        <!-- Address Field -->
        <div class="mb-6">
          <label for="address" class="block text-gray-700 text-sm font-medium mb-2">Address (optional)</label>
          <textarea name="address"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
            placeholder="Your address" rows="3"></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit"
          class="w-full bg-primary hover:bg-primary-dark text-white py-3 px-4 rounded-lg font-medium transition duration-300 hover:shadow-md transform hover:-translate-y-0.5">
          Sign Up
        </button>

        <!-- Links Section -->
        <div class="mt-6 text-center text-sm space-y-2">
          <p class="text-gray-600">Already have an account?
            <a href="login.php" class="text-primary hover:text-primary-dark font-medium hover:underline">Login</a>
          </p>
          <p>
            <a href="index.php" class="text-primary hover:text-primary-dark font-medium hover:underline">Return to Home
              Page</a>
          </p>
        </div>
      </form>
    </div>
  </div>

  <script>
  function validateForm() {
    const username = document.getElementById("username").value;
    const email = document.getElementById("email").value;
    const phone = document.getElementById("phone").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm_password").value;

    // Improved username regex: must start with letter, then letters, numbers or underscores
    const usernameRegex = /^[a-zA-Z][a-zA-Z0-9_]{4,19}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^\+?[0-9]{7,15}$/;
    const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{6,}$/;

    if (!usernameRegex.test(username)) {
      alert(
        "Username must be:\n- 5-20 characters long\n- Start with a letter\n- Contain only letters, numbers, and underscores"
      );
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
    if (!passwordRegex.test(password)) {
      alert("Password must be at least 6 characters with at least one letter and one number.");
      return false;
    }
    if (password !== confirmPassword) {
      alert("Passwords do not match.");
      return false;
    }
    return true;
  }

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

  // Show password requirements when password field is focused
  document.getElementById('password').addEventListener('focus', function() {
    const requirementsDiv = document.createElement('div');
    requirementsDiv.className = 'text-xs text-gray-500 mt-1';
    requirementsDiv.innerHTML = `
      <p>Password must:</p>
      <ul class="list-disc pl-5">
        <li>Be at least 6 characters long</li>
        <li>Contain at least one letter</li>
        <li>Contain at least one number</li>
      </ul>
    `;
    this.parentNode.appendChild(requirementsDiv);
  });

  // Hide requirements when password field loses focus (if they're not already in the DOM)
  document.getElementById('password').addEventListener('blur', function() {
    const requirementsDiv = this.parentNode.querySelector('.text-xs.text-gray-500.mt-1:not(:first-child)');
    if (requirementsDiv) {
      requirementsDiv.remove();
    }
  });
  </script>
</body>

</html>