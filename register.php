<?php

require 'connect_bd_no.php';

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $conn->real_escape_string($_POST['firstname']);
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // хешируем пароль

   
    $sql = "SELECT user_id FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Пользователь с таким email уже существует.";
    } else {
        
        $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$password')";
        
        if ($conn->query($sql) === TRUE) {

            $sql = "SELECT user_id, status FROM users WHERE email = '$email'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $_SESSION['user_id'] = $row['user_id']; 
            $_SESSION['status'] = $row['status']; 
           
            header("Location: main_page.php"); 

            exit();
        } else {
            echo "Ошибка: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
