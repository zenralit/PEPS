<?php

if (!isset($_SESSION['user_id'])) {
    echo "Вы не авторизованы.";
    exit();
}

require 'connect_bd_no.php';

if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT firstname, phone, lastname, email, city, status, online_status FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
  
   
    $stat = $row['online_status'];
    $status = $row['status']; 

     function wait($status): void{

        switch ($status) {
            case 'ожидайте рассмотрения':
                echo '<p style="color: green;" >ожидайте, пока проверят вашу кандидатуру </p> <br/>';
                break;
            case 'поставщик':
                echo '<a href="add_prod.php" style="color: white;" >добавить продукт</a> <br/>';
                break;
            case 'администратор':
                echo '<a href="test_admin.php" style="color: white;" >админ панель</a>';
                break;
            case "главный администратор":
                 echo '<a href="test_admin.php" style="color: white;" >админ панель</a>';
            break;
        }
}
function foradm($status){
    switch ($status){
        case 'главный администратор':
            echo '<h2><a href="add_new_admin.php" style="color: white;" >супер админ панель</a></h2> </br>';
            break;
    }
}




    $status = match ($status) {
        "superadmin" => "главный администратор",
        "admin" => "администратор",
        "wait" => "ожидайте рассмотрения",
        "suppliers" => "поставщик",
        "user" => "пользователь",
    };



    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $phone = $row['phone'];
    $email = $row['email'];
    $address = $row['city'];

    
} else {
    echo "Ошибка: не удалось найти пользователя.";
}


$stmt->close();
$conn->close();

