<?php
require 'connect_bd.php';


if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}


if (!isset($_SESSION['user_id'])) {
    die("Вы не авторизованы!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $firstname = $conn->real_escape_string($_POST['firstname']);
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $address = $conn->real_escape_string($_POST['address']);
    $phone = $conn->real_escape_string($_POST['phone']);
    
   
    $sql = "";
    
   
    if (!empty($_FILES['profile_pic']['name'])) {
        $profilePicName = basename($_FILES['profile_pic']['name']); 
        $profilePicTmpName = $_FILES['profile_pic']['tmp_name'];
        $uploadDir = 'uploads/';
        
       
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);  
        }
        
        $uploadFile = $uploadDir . $profilePicName;
        
        // Загружаем файл на сервер
        if (move_uploaded_file($profilePicTmpName, $uploadFile)) {
           
            $sql = "UPDATE users SET firstname='$firstname', lastname='$lastname', city='$address', phone='$phone', profile_pic='$uploadFile' WHERE user_id='$user_id'";
        } else {
            echo "Ошибка загрузки изображения.";
        }
    } else {
        
        $sql = "UPDATE users SET firstname='$firstname', lastname='$lastname', city='$address', phone='$phone' WHERE user_id='$user_id'";
    }

   
    if (!empty($sql)) {
        if ($conn->query($sql) === TRUE) {
            // echo "Информация успешно обновлена!";
        } else {
        }
        header( "location: user_cabinet.php");
    }
    header( "location: user_cabinet.php");
}
header("location: user_cabinet.php");

$conn->close();

