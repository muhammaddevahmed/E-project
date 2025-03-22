<?php
include 'php/db_connection.php'; 



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if required fields are set
    if (!isset($_POST['username']) || !isset($_POST['full_name']) || !isset($_POST['email']) || !isset($_POST['password'])) {
        echo "<script>alert('All required fields must be filled.');</script>";
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

    // ✅ REGEX VALIDATION
    if (!preg_match("/^[a-zA-Z0-9_]{5,20}$/", $username)) {
        echo "<script>alert('Username must be 5-20 characters and contain only letters, numbers, and underscores.');</script>";
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
        // ✅ CHECK IF EMAIL ALREADY EXISTS
        $checkStmt = $pdo->prepare("SELECT user_id FROM Users WHERE email = :email");
        $checkStmt->bindValue(':email', $email);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            echo "<script>alert('Error: Email already exists.');</script>";
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