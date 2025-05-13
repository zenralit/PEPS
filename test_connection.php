<?php
// Попробуем подключиться с использованием PDO
$host = 'localhost';
$db = 'users';  // Имя базы данных
$user = 'root';  // Имя пользователя MySQL
$pass = 'zeent';      // Пароль, если есть

try {
    // Создаём новый объект PDO
    $conn = new PDO("mysql:host=localhost;dbname=users", "root", "zeent");
    // Устанавливаем режим ошибок на исключения
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully with PDO"; 
} catch (PDOException $e) {
    // Если есть ошибка подключения, выводим её
    echo "Connection failed: " . $e->getMessage();
}


