<?php

  include("db-config.php");

  $trainers = $conn->query("SELECT * FROM Trainer_Info");

  if (!isset($_SESSION['User_ID'])) {
      echo "<script>alert('Please log in to book a trainer.'); window.location.href = 'login.php';</script>";
      exit;
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $trainer_name = $_POST['trainer'];
      $date = $_POST['date'];
      $user_id = $_SESSION['user_id'];

      $check_query = $conn->prepare("SELECT * FROM bookings WHERE trainer_name = ? AND date = ? AND status = 'upcoming'");
      $check_query->bind_param("ss", $trainer_name, $date);
      $check_query->execute();
      $result = $check_query->get_result();

      if ($result->num_rows > 0) {
          echo "<script>alert('Trainer is not available on this date. Please choose a different date.'); window.history.back();</script>";
      } else {
          $query = $conn->prepare("INSERT INTO bookings (trainer_name, user_id, date, status) VALUES (?, ?, ?, 'upcoming')");
          $query->bind_param("sis", $trainer_name, $user_id, $date);

          if ($query->execute()) {
              echo "<script>alert('Booking successful!'); window.location.href = 'user-dashboard.php';</script>";
          } else {
              echo "<script>alert('Failed to book the trainer. Please try again.'); window.history.back();</script>";
          }
      }
  }  
?>