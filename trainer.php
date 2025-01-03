<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CalFit+ Trainer</title>
  <style>
    body {
      font-family: 'Oswald', sans-serif;
      margin: auto;
      padding: auto;
      background-image: url('images/trainer.jpg');
      background-position: center;
      filter-blur: 10px;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      width: 90%;
      max-width: 400px;
      background-color: rgba(255, 255, 255, 0.9);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .container h2 {
      text-align: center;
      color: rgba(8, 127, 91, 0.38);
      text-shadow: 0px 1px 2px #087f5b;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    .form-group input {
      width: 94%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 12px;
    }

    .form-group button {
      width: 100%;
      padding: 10px;
      background-color: #087f5b;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }

    .form-group button:hover {
      background-color: #087f5b;
    }

    .toggle-link {
      text-align: center;
      margin-top: 10px;
    }

    .toggle-link a {
      color: #087f5b;
      text-decoration: none;
    }

    .toggle-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="container" id="login-form">
    <h2>Login</h2>
    <form method="POST" action="trainer_login.php">
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="form-group">
        <button type="submit">Login</button>
      </div>
    </form>
    <div class="toggle-link">
      <p>Don't have an account? <a href="#" onclick="showSignUpForm()">Sign up here</a>.</p>
    </div>
  </div>

  <div class="container" id="signup-form" style="display: none;">
    <h2>Sign Up</h2>
    <form method="POST" action="trainer_signup.php">
      <div class="form-group">
        <label for="name">Full Name:</label>
        <input type="text" id="trainer_name" name="trainer_name" required>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="form-group">
        <button type="submit">Sign Up</button>
      </div>
    </form>
    <div class="toggle-link">
      <p>Already have an account? <a href="#" onclick="showLoginForm()">Log in here</a>.</p>
    </div>
  </div>

  <script>
    document.getElementById('login-form').style.display = 'none';
    document.getElementById('signup-form').style.display = 'block';
    function showSignUpForm() {
      document.getElementById('login-form').style.display = 'none';
      document.getElementById('signup-form').style.display = 'block';
    }

    function showLoginForm() {
      document.getElementById('signup-form').style.display = 'none';
      document.getElementById('login-form').style.display = 'block';
    }
  </script>
</body>
</html>