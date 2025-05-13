<?php
require 'connect_bd.php';

if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    

    $sql = "SELECT user_id, password FROM suppliers WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Проверка пароля
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id']; 
            header("Location: user_cabinet.php"); 
            exit();
        } else {
            echo "Неверный пароль.";
        }
    } else {
        echo "Пользователь с таким email не найден.";
    }
}

$conn->close();
?>
