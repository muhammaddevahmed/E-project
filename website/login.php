<?php
session_start(); 
include 'php/db_connection.php';

// Login logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT user_id, password_hash, user_type, full_name FROM Users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['full_name'] = $user['full_name']; 
              
                // Redirect based on user type
                if ($user['user_type'] == 'admin') {
                    header("Location: ../edashboard/html/index.php");
                } elseif ($user['user_type'] == 'employee') {
                    header("Location: ../edashboard/html/index.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                echo "<script>alert('Invalid password!');</script>";
            }
        } else {
            echo "<script>alert('Email not found!');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Crafty Corner - Login</title>
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
      <p class="text-gray-300 mt-1">Welcome back! Please login</p>
    </div>

    <!-- Form Section -->
    <div class="p-6 sm:p-8">
      <form method="POST">
        <!-- Email Field -->
        <div class="mb-4">
          <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
          <input type="email" name="email"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
            placeholder="your@email.com" required>
        </div>

        <!-- Password Field -->
        <div class="mb-6">
          <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
          <div class="relative">
            <input type="password" name="password" id="password"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
              placeholder="Enter your password" required>
            <button type="button" id="togglePassword"
              class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeIcon">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Submit Button -->
        <button type="submit"
          class="w-full bg-primary hover:bg-primary-dark text-white py-3 px-4 rounded-lg font-medium transition duration-300 hover:shadow-md transform hover:-translate-y-0.5">
          Login
        </button>

        <!-- Links Section -->
        <div class="mt-6 text-center text-sm space-y-3">
          <p class="text-gray-600">Don't have an account?
            <a href="signup.php" class="text-primary hover:text-primary-dark font-medium hover:underline">Sign Up</a>
          </p>
          <p>
            <a href="index.php" class="text-primary hover:text-primary-dark font-medium hover:underline">Return to Home
              Page</a>
          </p>
        </div>
      </form>
    </div>
  </div>

  <!-- JavaScript for Show/Hide Password -->
  <script>
  const togglePassword = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');
  const eyeIcon = document.getElementById('eyeIcon');

  togglePassword.addEventListener('click', () => {
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
  </script>
</body>

</html>