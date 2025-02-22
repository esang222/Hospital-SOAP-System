<?php
session_start();
include "config.php";

// Handle Login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and trim input values
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if fields are empty
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username and Password are required!";
        header("Location: login.php");
        exit;
    }

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($row = $result->fetch_assoc()) {
        // Compare passwords directly since we are not using hash
        if ($password === $row['password']) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            $_SESSION['error'] = "Invalid password! Please try again.";
        }
    } else {
        $_SESSION['error'] = "User not found!";
    }

    // Redirect back with an error message
    header("Location: login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Login</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap');

        * {
            font-family: 'Lexend', sans-serif;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #3b8d99, #6b9fb8);
        }

        .container {
            display: flex;
            width: 900px;
            height: 500px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .login-form {
            width: 50%;
            padding: 10px 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form h1 {
            font-size: 35px;
            color: #176B87;
            text-wrap: nowrap;
            text-align: center;
            margin: 20px 0;
        }

        .login-form h2 {
            font-size: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .input-group {
            width: 100%;
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin: 10px 0;
            text-align: center;
        }

        .login-button {
            background: #176B87;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            color: white;
            font-weight: bold;
            width: 100%;
            margin-top: 10px;
        }

        .login-button:hover {
            background: #1B5568FF;
        }

        .image-container {
            width: 50%;
            background: url('img/medical.jpg') no-repeat center center;
            background-size: cover;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-form">
        <h1>Welcome to Pulse+</h1>
        <h2>Please Login</h2>

        <form method="POST" action="login.php">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <p class="error-message"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>

            <button type="submit" class="login-button">Login</button>
        </form>
    </div>
    <div class="image-container"></div>
</div>

</body>
</html>
