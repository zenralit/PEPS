<?php
require 'connect_bd.php';

if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    

    $sql = "SELECT user_id, password, status FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

      
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id']; 
            $userId = $_SESSION['user_id'];
            $_SESSION['status'] = $row['status']; 
            $_SESSION['cool']= false;
            if ($_SESSION['status'] == 'superadmin' ){
                $_SESSION['cool']= true;
            $_SESSION['supp_id'] = $row['user_id'];$_SESSION['admin_id'] = $row['user_id'];}
            if ($_SESSION['status'] == 'suppliers' ){
                $_SESSION['supp_id'] = $row['user_id'];}

                $currentTime = date('Y-m-d H:i:s');
                $sql = "UPDATE users SET online_status = 'online', last_login = ? WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $currentTime, $userId);
                $stmt->execute();

            header("Location: user_cabinet.php"); 
            exit();
        } else {
            echo '<script>alert("пароль не правильный")</script>';
            header("Location: auto_main.html");
        }
    } else {
        echo "<script>alert('Пользователь с таким email не найден')</script>";
        header("Location: auto_main.html");
    }
}


$conn->close();
?>
