<?php
  include("register_config.php")
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .register-container {
      max-width: 480px;
      margin: 64px auto;
      background: rgba(8, 127, 91, 0.05);
      padding: 32px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .register-container h2 {
      text-align: center;
      margin-bottom: 24px;
      color: #087f5b;
    }

    .form-group {
      margin-bottom: 16px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-size: 14px;
      color: #333;
    }

    .form-group input {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 16px;
    }

    .btn {
      display: block;
      width: 100%;
      text-align: center;
      padding: 12px;
      border: none;
      border-radius: 8px;
      background-color: #087f5b;
      color: #fff;
      font-size: 16px;
      cursor: pointer;
      text-transform: uppercase;
    }

    .btn:hover {
      background-color: #099269;
    }

    .form-footer {
      margin-top: 16px;
      text-align: center;
      font-size: 14px;
    }

    .form-footer a {
      color: #087f5b;
      text-decoration: none;
    }

    .form-footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="register-container">
    <h2>Create Account</h2>
    <form action="register_config.php" method="post" onsubmit="return validateForm();">
      <div class="form-group">
        <label for="user_name">Full Name:</label>
        <input type="text" name="user_name" id="user_name" placeholder="Enter your full name" required>
      </div>

      <div class="form-group">
        <label for="full_name">Email:</label>
        <input type="email" name="email" id="email" placeholder="Enter your email" required>
      </div>

      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" placeholder="Enter your username" required>
      </div>

      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required>
      </div>
      
      <button type="submit" class="btn">Register</button>
    </form>
    <div class="form-footer">
      Already have an account? <a href="login.php">Login here</a>.
    </div>
  </div>

  <script>
    function validateForm() {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirm_password').value;

      if (password !== confirmPassword) {
        alert('Passwords do not match!');
        return false;
      }
      return true;
    }
  </script>
</body>
</html>