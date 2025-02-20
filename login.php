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
            margin-bottom: 50px;
            font-size: 42px;
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
            background: #a5c6dc;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 50%;
            margin-top: 10px;
            color: black;
            font-weight: bold;
        }

        .login-button:hover {
            background: #87afc7;
        }
    </style>
</head>
<body>
    
    <div class="login-container">
        <h1>LOGIN</h1>
        <h1>Welcome, Admin</h1>
        <form method="POST" action="">
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
