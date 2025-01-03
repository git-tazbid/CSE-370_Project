<?php
include("db_config.php");

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mealCategory = $_POST["meal_category"];
    $mealName = $_POST["meal_item"];
    $mealCalories = intval($_POST["meal_calories"]);

    $dietMapping = [
        "Breakfast" => 1,
        "Lunch" => 2,
        "Dinner" => 3,
    ];
    $dietID = $dietMapping[$mealCategory] ?? null;
    $mealID = 1;

    $check_query = $conn->prepare("SELECT 1 FROM Meal WHERE DietID = ? AND MealID = ?");
    $check_query->bind_param("ii", $dietID, $mealID);
    $check_query->execute();
    $check_query->store_result();

    if ($check_query->num_rows > 0) {
        $sql_query = $conn->prepare("INSERT INTO Meal_Details (DietID, MealID, Item, Calories) VALUES (?, ?, ?, ?)");
        $sql_query->bind_param("iisi", $dietID, $mealID, $mealName, $mealCalories);

        if ($sql_query->execute()) {
            echo "<script>alert('Meal item added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding meal item: " . $conn->error . "');</script>";
        }
        $sql_query->close();
    } else {
        echo "<script>alert('Invalid DietID and MealID combination. Please check the Meal table.');</script>";
    }

    $check_query->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin-dashboard.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .logout-container {
            position: absolute;
            top: 10px;
            right: 10px;
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
        .dashboard-container {
            max-width: 600px;
            margin: 80px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        form.meal-form {
            margin-top: 20px;
        }
        form label {
            display: block;
            margin-bottom: 5px;
        }
        form input, form select, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form button {
            background-color: #4caf50;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <form action="logout.php" method="post">
            <button type="submit" class="logout-btn">Log Out</button>
        </form>
    </div>
    <div class="dashboard-container">
        <h1>Admin Dashboard</h1>
        <form method="POST" class="meal-form">
            <label for="meal_category">Meal Category:</label>
            <select id="meal_category" name="meal_category" required>
                <option value="Breakfast">Breakfast</option>
                <option value="Lunch">Lunch</option>
                <option value="Dinner">Dinner</option>
            </select>
            <label for="meal_item">Meal Item:</label>
            <input type="text" id="meal_item" name="meal_item" placeholder="Enter meal item" required>
            <label for="meal_calories">Calories:</label>
            <input type="number" id="meal_calories" name="meal_calories" placeholder="Enter calories" required>
            <button type="submit">Add Meal</button>
        </form>
    </div>
</body>
</html>
