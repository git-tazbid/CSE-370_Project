<?php
include("db_config.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];


$sql_trainers = 'SELECT TrainerID, Trainer_Name FROM Trainers_Cred';
$trainers_result = $conn->query($sql_trainers);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $trainer_id = $_POST['trainer_id'];
    $appointment_time = $_POST['appointment_time'];

    $sql_check_availability = 'SELECT * FROM Appointments_Info WHERE TrainerID = ? AND Appointment_Time = ?';
    $stmt = $conn->prepare($sql_check_availability);
    $stmt->bind_param("is", $trainer_id, $appointment_time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Trainer is not available at this time. Please choose a different time.');</script>";
    } else {
        $status = 'Booked'; 
        $sql_insert = 'INSERT INTO Appointments_Info (UserID, TrainerID, Appointment_Time, Status) VALUES (?, ?, ?, ?)';
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("iiss", $user_id, $trainer_id, $appointment_time, $status);

        if ($stmt->execute()) {
            echo "<script>alert('Appointment successfully booked.'); window.location.href='book_trainer.php';</script>";
            exit;
        } else {
            echo "<script>alert('An unexpected error occurred. Please try again later.');</script>";
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Trainer</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 50px auto;
            max-width: 600px;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        select, input, button {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        select {
            width: 100%;
        }

        button {
            background-color: #087f5b;
            color: white;
            border: none;
            cursor: pointer;
        }

        .alert {
            color: #d9534f;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Book Trainer</h1>
        <form method="post">
            <label for="trainer_id">Trainer:</label>
            <select id="trainer_id" name="trainer_id" required>
                <?php while ($trainer = $trainers_result->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($trainer['TrainerID']) ?>">
                        <?= htmlspecialchars($trainer['Trainer_Name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="appointment_time">Appointment Time:</label>
            <input type="datetime-local" id="appointment_time" name="appointment_time" required>

            <button type="submit">Book Appointment</button>
        </form>
    </div>
</body>
</html>
