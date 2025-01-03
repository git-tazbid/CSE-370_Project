<?php

include("login_config.php");

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .login-container {
      max-width: 400px;
      margin: 64px auto;
      background: rgba(8, 127, 91, 0.05);
      padding: 32px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .login-container h2 {
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

    .error {
      color: red;
      text-align: center;
      margin-bottom: 16px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="login-container">
      <h2>Login</h2>
      <?php if (isset($error)) {
        echo "<p class='error'>$error</p>";
      } ?>
      <form action="login_config.php" method="POST">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
      </form>
    </div>
  </div>
</body>
</html>