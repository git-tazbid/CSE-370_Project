<?php
include("db_config.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<p>Please log in to update your diet routine.</p>";
    exit;
}

$userID = $_SESSION['user_id'];

$query = "SELECT DietID, Calories FROM Diet_Plans";
$result = $conn->query($query);

if (!$result) {
    die("Failed to fetch diet plans: " . $conn->error);
}

$dietPlans = [];
while ($row = $result->fetch_assoc()) {
    $dietPlans[] = $row;
}

if (isset($_POST['add'])) {
    $day = $_POST['day'];
    $dietID = intval($_POST['diet']);

    $validDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    if (!in_array($day, $validDays)) {
        echo "<p>Invalid day selected.</p>";
        exit;
    }

    $query = "INSERT INTO users_routine (UserID, $day)
              VALUES (?, ?)
              ON DUPLICATE KEY UPDATE $day = ?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Failed to prepare query: " . $conn->error);
    }

    $stmt->bind_param("iii", $userID, $dietID, $dietID);

    if ($stmt->execute()) {

        echo "<script>alert('Diet plan successfully added to $day!');</script>";
    } else {
        echo "<p>Failed to update routine: " . $stmt->error . "</p>";
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
    <title>Update Routine</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: auto;
            padding: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #f0f0f0, #d3d3d3);
        }

        h1 {
            text-align: center;
            position: absolute;
            top: 100px;
            width: 100%;
            font-size: 2.1rem;
            color: #333;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        select,
        button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #087f5b;
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #087f5b;
        }
    </style>
</head>

<body>
    <h1>Update Your Routine</h1>
    <form method="POST">
        <label for="day">Select Day:</label>
        <select name="day" id="day" required>
            <option value="Sunday">Sunday</option>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
        </select>

        <label for="diet">Select Diet Plan:</label>
        <select name="diet" id="diet" required>
            <?php foreach ($dietPlans as $plan): ?>
                <option value="<?= $plan['DietID']; ?>">
                    DietID: <?= $plan['DietID']; ?> (<?= $plan['Calories']; ?> Calories)
                </option>
            <?php endforeach; ?>
        </select>

        <input type="hidden" name="userID" value="1">
        <!-- Replace with actual user ID ekhaneo omne logged in user er userid ta lagbe ami just 1 disi amar code kaaj korar jonno for now-->
        <button type="submit" name="add">Add/Update</button>
        <button type="button" onclick="window.location.href='view_new_routine.php'">View New Routine</button>
        <button type="button" onclick="window.location.href='user_dashboard.php'">Back to Dashboard</button>
    </form>
</body>
</html>