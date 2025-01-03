<?php
include("db_config.php");

session_start();

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['gender'], $_POST['age'], $_POST['height'], $_POST['weight'], $_POST['target_weight'], $_POST['address'])) {

    if (!isset($_SESSION['user_id'])) {
      $error_message = "User not logged in.";
    } else {
      $user_id = $_SESSION['user_id'];
      $gender = trim($_POST['gender']);
      $age = intval($_POST['age']);
      $height = floatval($_POST['height']);
      $weight = floatval($_POST['weight']);
      $target_weight = floatval($_POST['target_weight']);
      $address = trim($_POST['address']);


      if ($age <= 0 || $height <= 0 || $weight <= 0 || $target_weight <= 0) {
        $error_message = "Invalid input. All fields must contain positive values.";
      } else {

        $sql_check = "SELECT UserID FROM Users_Profile WHERE UserID = ?";
        $stmt_check = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt_check, 'i', $user_id);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {

          $sql_update = "UPDATE Users_Profile SET Age = ?, Current_Height = ?, Current_Weight = ?, Target_Weight = ?, Gender = ?, Address = ? WHERE UserID = ?";
          $stmt_update = mysqli_prepare($conn, $sql_update);
          mysqli_stmt_bind_param($stmt_update, 'idddsis', $age, $height, $weight, $target_weight, $gender, $user_id, $address);

          if (mysqli_stmt_execute($stmt_update)) {

            $bmr = ($gender === 'male') ?
              (10 * $weight) + (6.25 * $height) - (5 * $age) + 5 :
              (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;


            $sql_bmr = "INSERT INTO BMR_History (UserID, Bmr) VALUES (?, ?) ON DUPLICATE KEY UPDATE Bmr = ?";
            $stmt_bmr = mysqli_prepare($conn, $sql_bmr);
            mysqli_stmt_bind_param($stmt_bmr, 'idd', $user_id, $bmr, $bmr);
            mysqli_stmt_execute($stmt_bmr);
            mysqli_stmt_close($stmt_bmr);

            $_SESSION['user_id'] = $user_id;
            header('Location: user_dashboard.php');
            exit();
          } else {
            $error_message = "Error updating profile: " . mysqli_error($conn);
          }
          mysqli_stmt_close($stmt_update);
        } else {

          $sql_insert = "INSERT INTO Users_Profile (UserID, Age, Current_Height, Current_Weight, Target_Weight, Gender, Address) VALUES (?, ?, ?, ?, ?, ?, ?)";
          $stmt_insert = mysqli_prepare($conn, $sql_insert);
          mysqli_stmt_bind_param($stmt_insert, 'iddddss', $user_id, $age, $height, $weight, $target_weight, $gender, $address);

          if (mysqli_stmt_execute($stmt_insert)) {

            $bmr = ($gender === 'male') ?
              (10 * $weight) + (6.25 * $height) - (5 * $age) + 5 :
              (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;


            $sql_bmr = "INSERT INTO BMR_History (UserID, Bmr) VALUES (?, ?)";
            $stmt_bmr = mysqli_prepare($conn, $sql_bmr);
            mysqli_stmt_bind_param($stmt_bmr, 'id', $user_id, $bmr);
            mysqli_stmt_execute($stmt_bmr);
            mysqli_stmt_close($stmt_bmr);

            $_SESSION['user_id'] = $user_id;
            header('Location: user_dashboard.php');
            exit();
          } else {
            $error_message = "Error inserting profile: " . mysqli_error($conn);
          }
          mysqli_stmt_close($stmt_insert);
        }

        mysqli_stmt_close($stmt_check);
      }
    }
  } else {
    $error_message = "All fields are required.";
  }
}
?>






<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Details</title>

  <style>
    .details-container {
      max-width: 480px;
      margin: 64px auto;
      background: rgba(8, 127, 91, 0.05);
      padding: 32px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .details-container h2 {
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
      font-size: 16px;
      color: #333;
      font-family: 'Oswald', sans-serif;

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

    .form-group select {
      width: auto;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 16px;
      background-color: #fff;
      color: #333;
      font-family: 'Oswald', sans-serif;
    }

    .form-group select:focus {
      border-color: #087f5b;
      outline: none;
      box-shadow: 0 0 4px rgba(8, 127, 91, 0.5);
    }
  </style>
</head>

<body>
  <div class="details-container">
    <?php
    if (!empty($error_message)) {
      echo "<p style='color: red; text-align: center;'>$error_message</p>";
    }
    ?>
    <form action="details.php" method="post">
      <div class="form-group">
        <label for="gender">Gender:</label>
        <select name="gender" id="gender" required>
          <option value="" disabled selected>Select your gender</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
      </div>

      <div class="form-group">
        <label for="age">Age:</label>
        <input type="text" name="age" id="age" placeholder="Enter your age" required>
      </div>
      <div class="form-group">
        <label for="height">Height:</label>
        <input type="text" name="height" id="height" placeholder="Enter your height (in cm)" required>
      </div>
      <div class="form-group">
        <label for="weight">Weight:</label>
        <input type="text" name="weight" id="weight" placeholder="Enter your weight (in kg)" required>
      </div>
      <div class="form-group">
        <label for="target_weight">Target Weight:</label>
        <input type="text" name="target_weight" id="target_weight" placeholder="Enter your target weight (in kg)"
          required>
      </div>
      <div class="form-group">
        <label for="address">Address:</label>
        <input type="text" name="address" id="address" placeholder="Enter your address" required>
      </div>
      <button type="submit" class="btn">Submit</button>
    </form>
  </div>
</body>
</html>