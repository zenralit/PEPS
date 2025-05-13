<?php

require 'connect_bd.php';


if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}



$user_id = $_SESSION['user_id'];


            $sql= "UPDATE users set status='wait' WHERE user_id = $user_id ";
            $result = $conn->query($sql);
            $_SESSION['status'] = 'wait'; 
            header ('location: user_cabinet.php');

        
$conn->close();
