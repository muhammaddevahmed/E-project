<?php
session_start(); // Must be at the very top, before any output

include 'php/db_connection.php'; // Include your database connection file

// Login logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Prepare the SQL statement to fetch user details
        $stmt = $pdo->prepare("SELECT user_id, password_hash, user_type, full_name FROM Users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password
            if (password_verify($password, $user['password_hash'])) {
                // Store user details in the session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['full_name'] = $user['full_name']; 
              

                // Debug: Print session ID and variables
                echo "Session ID: " . session_id() . "<br>";
                echo "<pre>";
                print_r($_SESSION);
                echo "</pre>";

                // Redirect based on user type
                if ($user['user_type'] == 'admin') {
                    header("Location: admin_dashboard.php");
                } elseif ($user['user_type'] == 'employee') {
                    header("Location: employee_dashboard.php");
                } else {
                    header("Location: index.php"); // Redirect to checkout.php for customers
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
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </form>
    </div>
</body>
</html>