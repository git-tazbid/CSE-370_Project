<?php
include("db_config.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql_query = $conn->prepare("SELECT * FROM Admins_Cred WHERE Username = ? AND Password = ?");
    $sql_query->bind_param("ss", $username, $password);
    $sql_query->execute();
    $result = $sql_query->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "<script>alert('Invalid Username or Password');</script>";
    }
    $sql_query->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CalFit+ | Admin Access!</title>
    <link rel="stylesheet" href="css/admin-login.css">
</head>
<body>
    <div class="form-container">
        <h2> Admin </h2>
        <form method="POST" action="admin_login.php" class="admin-login-form">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Log In</button>
        </form>
    </div>
</body>
</html>