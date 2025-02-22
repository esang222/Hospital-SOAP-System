<?php
session_start();
include "config.php";

// Handle Login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if fields are empty
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username and Password are required!";
        header("Location: login.php");
        exit();
    }

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid password!";
        }
    } else {
        $_SESSION['error'] = "User not found!";
    }

    // Redirect back with an error message
    header("Location: login.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Login</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap');

        * {
            font-family: 'Lexend';
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            height: 100vh;
            background: url('img/medical.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
        }

        .login-container {
            background: rgba(31, 91, 110, 0.8);
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 800px;
            height: 600px;
            color: white;
            margin-left: 40rem;
        }

        h1 {
            margin-bottom: 30px;
            font-size: 42px;
        }

        h2 {
            margin-bottom: 50px;
            font-size: 32px;
        }

        .input-group {
            position: relative;
            width: 100%;
            margin-bottom: 20px;
        }

        label {
            position: absolute;
            top: -30px;
            left: 130px;
            font-size: 18px;
            /* color: white; */
            /* background: rgba(31, 91, 110, 0.8); */
            padding: 2px 6px;
            border-radius: 5px;
        }

        input {
            width: 60%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background: white;
            margin-bottom: 20px;
        }

        .login-button {
            background: #9FCCEBFF;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 20px;
            width: 30%;
            margin-top: 10px;
            color: black;
            font-weight: 500;
        }

        .login-button:hover {
            background: #87afc7;
        }
    </style>
</head>
<body>
    
<div class="login-container">
        <h1>Welcome, Admin</h1>        
        <h2>Login to Pulse+</h2>

        <!-- Show error messages if any -->
        <?php if (isset($_SESSION['error'])): ?>
            <p class="error-message"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
    </div>

</body>
</html>
