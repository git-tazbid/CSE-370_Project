<?php
include("db_config.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<p>You need to log in to view your routine.</p>";
    exit;
}

$userID = $_SESSION['user_id']; 

$query = "SELECT * FROM users_routine WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: user_dashboard.php");
    exit;
}

$routine = $result->fetch_assoc();
$stmt->close();

$days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

$dietQuery = "
    SELECT md.DietID, md.MealID, md.DetailID, md.Item, md.Calories AS ItemCalories, 
           m.Calories AS MealCalories, dp.Calories AS DietCalories
    FROM meal_details md
    JOIN meal m ON md.DietID = m.DietID AND md.MealID = m.MealID
    JOIN diet_plans dp ON md.DietID = dp.DietID
    WHERE md.DietID = ?
    ORDER BY md.MealID, md.DetailID
";

$dietStmt = $conn->prepare($dietQuery);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Routine</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1,
        h2 {
            text-align: center;
            color: #087f5b;
        }

        .top-right-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #087f5b;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        .top-right-button a {
            color: white;
            text-decoration: none;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #087f5b;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        strong {
            color: #087f5b;

            p {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <button class="top-right-button"><a href="final_change_routine.php">Change Diet Plans</a></button>
    <h1>Your Weekly Diet Routine</h1>

    <?php foreach ($days as $day): ?>
        <h2><?= $day; ?></h2>
        <?php if (!empty($routine[$day])): ?>
            <p style="text-align: center;"><strong>Diet Plan ID:</strong> <?= $routine[$day]; ?></p>
            <table>
                <thead>
                    <tr>
                        <th>Meal</th>
                        <th>Item</th>
                        <th>Calories</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $dietID = $routine[$day];
                    $dietStmt->bind_param("i", $dietID);
                    $dietStmt->execute();
                    $dietResult = $dietStmt->get_result();

                    $currentMealID = null;

                    while ($row = $dietResult->fetch_assoc()):
                        if ($currentMealID !== $row['MealID']):
                            $currentMealID = $row['MealID'];
                            $mealName = $currentMealID === 1 ? "Breakfast" : ($currentMealID === 2 ? "Lunch" : "Dinner");
                            ?>
                            <tr>
                                <td colspan="3"><strong><?= $mealName; ?> (<?= $row['MealCalories']; ?> Calories)</strong></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td></td>
                            <td><?= $row['Item']; ?></td>
                            <td><?= $row['ItemCalories']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center;">No diet plan set for <?= $day; ?>.</p>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php
    $dietStmt->close();
    $conn->close();
    ?>
    <button class="top-right-button" style="top: 50px;"><a href="user_dashboard.php">Back to Dashboard</a></button>
</body>
</html>