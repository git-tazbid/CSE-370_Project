<?php
include("db_config.php");

session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    $sql_query = 'SELECT * FROM Users_Cred WHERE Username = ?';
    $stmt = $conn->prepare($sql_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            $_SESSION['user_id'] = $user['UserID'];
            header("Location: user_dashboard.php");
            exit;
        } else {
            echo "<script>alert('Invalid Username or Password.');</script>";
        }
    } else {
        echo "<script>alert('Invalid Username or Passwords.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>