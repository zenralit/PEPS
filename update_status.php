<?php 
session_start();
require 'connect_bd_no.php'; 
 $userId = $_SESSION['user_id'];
if (isset($_SESSION['user_id'])) {
    

   
    $sql = "UPDATE users SET online_status = 'online', last_login = NOW() WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
} else{
    $sql = "UPDATE users SET online_status = 'offline' WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
}