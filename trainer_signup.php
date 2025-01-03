<?php
include("db_config.php");

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $trainer_name = $_POST['trainer_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql_check = 'SELECT * FROM Trainers_Cred WHERE Email = ?';
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already exists.');</script>";
    } else {
        $sql_insert = 'INSERT INTO Trainers_Cred (Trainer_Name, Email, Password) VALUES (?, ?, ?)';
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("sss", $trainer_name, $email, $hashed_password);
        $_SESSION['trainer_id'] = $conn->insert_id;


        if ($stmt->execute()) {
            header("Location: trainer_dashboard.php");
        } else {
            error_log("Database error: " . $stmt->error);
            echo "An unexpected error occurred. Please try again later.";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
