<?php

include("db_config.php");

session_start();
$login_success = FALSE;
if ($login_success) {
    $_SESSION['user_id'] = $user_id;
    header("Location: details.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = $_POST['user_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql_check = 'SELECT * FROM Users_Cred WHERE Username = ?';
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: register.php");
    } else {
        $sql_insert = 'INSERT INTO Users_Cred (User_Name, Email, Username, Password) VALUES (?, ?, ?, ?)';
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("ssss", $user_name, $email, $username, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            header("Location: details.php");
            exit();
        } else {
            header("Location: register.php");
        }
    }
    $stmt->close();
    $conn->close();
}
?>