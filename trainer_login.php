<?php
include("db_config.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    $sql_query = 'SELECT * FROM Trainers_Cred WHERE Email = ?';
    $stmt = $conn->prepare($sql_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $trainer = $result->fetch_assoc();
        if (password_verify($password, $trainer['Password'])) {
            $_SESSION['trainer_id'] = $trainer['TrainerID'];
            header("Location: trainer_dashboard.php");
            exit;
        } else {
            echo "<script>alert('Invalid Email or Password.');</script>";
        }
    } else {
        echo "<script>alert('Invalid Email or Passwords.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
