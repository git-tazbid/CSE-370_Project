<?php
include("db_config.php");
session_start();

if (!isset($_SESSION['trainer_id'])) {
    header("Location: trainer_login.php");
    exit;
}

$trainer_id = $_SESSION['trainer_id'];


$stmt = $conn->prepare(
    'SELECT ai.AppointmentID, u.User_Name AS Trainee_Name, ai.Appointment_Time, ai.Status 
    FROM Appointments_Info ai
    INNER JOIN Users_Cred u ON ai.UserID = u.UserID
    WHERE ai.TrainerID = ?'
);
$stmt->bind_param('i', $trainer_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['status'])) {
        $appointment_id = $_POST['id'];
        $new_status = $_POST['status'];

        $update_status_stmt = $conn->prepare('UPDATE Appointments_Info SET Status = ? WHERE AppointmentID = ?');
        $update_status_stmt->bind_param('si', $new_status, $appointment_id);

        if ($update_status_stmt->execute()) {
            echo "<script>alert('Status updated successfully.'); window.location.href='trainer_dashboard.php';</script>";
        } else {
            echo "<script>alert('An unexpected error occurred. Please try again later.');</script>";
        }

        $update_status_stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef7f6;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 20px auto;
            max-width: 900px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .logout-container {
            text-align: right;
            margin-bottom: 20px;
        }
        .logout-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #f44336;
            color: white;
            font-size: 14px;
            cursor: pointer;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #4caf50;
            color: white;
        }
        button {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .completed {
            background-color: #4caf50;
            color: white;
        }
        .canceled {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logout-container">
            <form action="logout.php" method="post">
                <button type="submit" class="logout-btn">Log Out</button>
            </form>
        </div>
        <h1>Trainer Dashboard</h1>
        <table>
            <tr>
                <th>Trainee's Name</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['Trainee_Name']) ?></td>
                        <td><?= htmlspecialchars($row['Appointment_Time']) ?></td>
                        <td><?= htmlspecialchars($row['Status']) ?></td>
                        <td>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="id" value="<?= $row['AppointmentID'] ?>">
                                <button type="submit" name="status" value="Completed" class="completed">Completed</button>
                            </form>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="id" value="<?= $row['AppointmentID'] ?>">
                                <button type="submit" name="status" value="Canceled" class="canceled">Canceled</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No appointments booked</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>