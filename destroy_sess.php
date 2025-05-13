<?php
session_start();
if (isset($_SESSION['user_id'])) {
    require 'connect_bd_no.php';
    $userId = $_SESSION['user_id'];

    $sql = "UPDATE users SET online_status = 'offline' WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
}

session_destroy();
header("Location: login.php");
exit;