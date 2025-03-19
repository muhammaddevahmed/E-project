<?php
include 'php/db_connection.php'; 
include 'php/queries.php';


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="form-container">
        <h2>Sign Up</h2>
        <form method="POST" onsubmit="return validateForm()">
            <input type="text" name="username" id="username" placeholder="Username" required>
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <input type="text" name="phone" id="phone" placeholder="Phone">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <textarea name="address" placeholder="Address"></textarea>
            <button type="submit">Sign Up</button>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>

    <script>
        function validateForm() {
            const username = document.getElementById("username").value;
            const email = document.getElementById("email").value;
            const phone = document.getElementById("phone").value;
            const password = document.getElementById("password").value;

            const usernameRegex = /^[a-zA-Z0-9_]{5,20}$/;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phoneRegex = /^\+?[0-9]{7,15}$/;
            const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{6,}$/;

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
            if (!passwordRegex.test(password)) {
                alert("Password must be at least 6 characters, with at least one letter and one number.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
