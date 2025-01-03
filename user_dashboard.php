<?php
session_start();
include("db_config.php");

if (!isset($_SESSION['user_id'])) {
  echo "<p>No user information found. Please log in.</p>";
  exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_weight'])) {
  $newWeight = (float)$_POST['new_weight'];

  $updateSql = "UPDATE Users_Profile SET Current_Weight = ? WHERE UserID = ?";
  $stmt = $conn->prepare($updateSql);
  $stmt->bind_param("di", $newWeight, $userId);

  if ($stmt->execute()) {
    echo "<script>alert('Weight updated successfully.');</script>";
  } else {
    echo "<script>alert('Error updating weight. Please try again later.');</script>";
  }

  $stmt->close();
}

$sql = "SELECT UserID, Age, Current_Height, Current_Weight, Target_Weight, Gender FROM Users_Profile WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: 'Oswald', sans-serif;
      background-size: cover;
      background-attachment: fixed;
      color: rgba(0, 0, 0, 0.76);
      text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.8);
      font-size: 1.2rem;
    }

    .dashboard-container {
      max-width: 1200px;
      margin: 50px auto;
      background: rgba(255, 255, 255, 0.9);
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .header {
      text-align: center;
      padding: 10px 0;
      font-size: 2rem;
      font-weight: bold;
    }

    .logout-button {
      font-size: 1rem;
      padding: 10px 20px;
      color: #fff;
      background: #087f5b;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      float: right;
    }

    .logout-button:hover {
      background: #087f5b;
    }

    .section {
      margin-bottom: 45px;
      position: relative;
      text-align: center;
      font-weight: bold;
    }

    .section h2 {
      font-size: 1.5rem;
      border-bottom: 2px solid #087f5b;
      padding-bottom: 5px;
      margin-bottom: 15px;
      text-align: center;
    }

    .weight-info {
      display: flex;
      justify-content: space-between;
      background: #f4f4f4;
      padding: 10px;
      border-radius: 10px;
    }

    .weight-info div {
      text-align: center;
      font-size: 1.2rem;
      display: inline-block;
      width: 50%;
    }

    .update-weight {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }

    .update-weight input[type="number"] {
      padding: 10px;
      font-size: 1rem;
      margin-right: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 100px;
    }

    .update-weight button {
      padding: 10px 20px;
      font-size: 1rem;
      color: #fff;
      background: #087f5b;
      border: none;
      border-radius: 10px;
      cursor: pointer;
    }

    .update-weight button:hover {
      background: #087f5b;
    }

    .redirect-buttons {
      text-align: center;
      margin-top: 20px;
      display: flex;
      justify-content: center;
      gap: 10px;
    }

    .redirect-buttons button {
      padding: 10px 20px;
      margin: 5px;
      font-size: 1rem;
      color: #fff;
      background: #087f5b;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      margin: 5px;

    }

    .redirect-buttons button:hover {
      background: #087f5b;
    }
  </style>
</head>

<body>
  <div class="dashboard-container">
    <div class="header">User Dashboard</div>
    <button class="logout-button" onclick="window.location.href='index.php'">Logout</button>

    <?php
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();

      $bmr = ($row['Gender'] === 'male') ?
        (10 * $row['Current_Weight']) + (6.25 * $row['Current_Height']) - (5 * $row['Age']) + 5 :
        (10 * $row['Current_Weight']) + (6.25 * $row['Current_Height']) - (5 * $row['Age']) - 161;

      echo "<div class='section'>";
      echo "<h2>BMR</h2>";
      echo "<p>Your Basal Metabolic Rate (BMR): <strong>" . round($bmr, 2) . " kcal/day</strong></p>";
      echo "</div>";

      echo "<div class='section'>";
      echo "<h2>Weight Information</h2>";
      echo "<div class='weight-info'>";
      echo "<div><p><u><strong>Current Weight</strong></u></p><p id='current-weight'>" . htmlspecialchars($row['Current_Weight']) . " kg</p></div>";
      echo "<div><p><u><strong>Target Weight</strong></u></p><p>" . htmlspecialchars($row['Target_Weight']) . " kg</p></div>";
      echo "</div>";

      // Add a form for updating the weight
      echo "<form method='POST' action='' class='update-weight'>";
      echo "<input type='number' name='new_weight' placeholder='New Weight' required>";
      echo "<button type='submit'>Update Weight</button>";
      echo "</form>";

      echo "<div class='redirect-buttons'>";
      echo "<button onclick=\"window.location.href='final_change_routine.php'\">View Diet Plans</button>";
      echo "<button onclick=\"window.location.href='view_new_routine.php'\">View Routine</button>";
      echo "<button onclick=\"window.location.href='book_trainer.php'\">Appoint a Trainer</button>";
      echo "</div>";
    } else {
      echo "<p>No user information found.</p>";
    }
    ?>

  </div>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>
