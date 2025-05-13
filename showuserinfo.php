<?php

// $host = 'localhost'; 
// $db = 'users'; 
// $user = 'root'; 
// $pass = 'zeent'; 

// $conn = new mysqli($host, $user, $pass, $db);
require 'connect_bd.php';


   
   
    if (isset($_SESSION['user_id'])) {
        
        $user_id = $_SESSION['user_id'];
    
        
        $sql = "SELECT firstname,phone,lastname,email, city, lastname, profile_pic, online_status, status FROM users WHERE id = '$user_id'";
        $result = $conn->query($sql);
    
        if ($result->num_rows == 1) {
           
            $row = $result->fetch_assoc();
            $status= $row['status'];
            $stat = $row['online_status'];
            $phone = $row['phone'];
            $firstname= $row['firstname'];
            $lastname= $row['lastname'];
            $email= $row['email'];
            $address= $row['city'];
            $pp= $row['profile_pic'];

          
           
           
        } else {
            echo "Ошибка: не удалось найти пользователя.";
        }
    } else {
        

    }

    $test = "test";

    $conn->close();
    