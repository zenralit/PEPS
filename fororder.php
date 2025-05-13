<?php
require 'connect_bd.php';
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'] ?? null;
$user_data = [];
if ($user_id) {
    $stmt = $conn->prepare("SELECT firstname, lastname, email, phone, city, street, house, apart FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $stmt->close();
}


$conn->close();
