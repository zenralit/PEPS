<?php

require 'connect_bd.php';


if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}



$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conname = $conn->real_escape_string($_POST['comname']);
    $contname = $conn->real_escape_string($_POST['contname']);
    $contphone = $conn->real_escape_string($_POST['contphone']);
    $email = $conn->real_escape_string($_POST['email']);
    

   
    $sql = "SELECT supplier_id FROM suppliers WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Пользователь с таким email уже существует.";
    } else {
        
        $sql = "INSERT INTO suppliers (user_id, company_name, contact_pohone, contact_name, email) VALUES ('$user_id', '$conname', '$contphone', '$contname', '$email')";
        if ($conn->query($sql) === TRUE) {
            $sql= "UPDATE users set status='suppliers' WHERE user_id = $user_id ";
            if($conn ->query($sql)=== TRUE){
                $sql = "SELECT supplier_id FROM suppliers Where user_id = $user_id";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $_SESSION['supp_id'] = $row['supplier_id'];
            header("Location: user_cabinet.php");
            $_SESSION['status'] = 'suppliers'; 

            exit();
        } else {
            echo "Ошибка: " . $sql . "<br>" . $conn->error;
        }
    }
}
}
$conn->close();
