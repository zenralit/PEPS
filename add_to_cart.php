<?php

require 'connect_bd.php';

if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
} 

if(!isset($_SESSION['user_id'])){
    header ('location:auto_main.html');
}
function add_to_cart($conn, $user_id, $product_id, $quantity) {
  
    $sql_check = "SELECT quantity FROM cart_items WHERE user_id = ? AND product_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $user_id, $product_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {  
       
        $row = $result_check->fetch_assoc();
        $new_quantity = $row['quantity'] + $quantity;

        $sql_update = "UPDATE cart_items SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("iii", $new_quantity, $user_id, $product_id);
        $stmt_update->execute();
        $stmt_update->close();
    } else {
      $sql_insert = "INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt_insert->execute();
        $stmt_insert->close();
        } 

    
    $stmt_check->close();
}


if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id']; 
    if ($quantity<1){
        echo "введено неккоректное количество. не балуйтесь с кодом сайта)";
        exit();
    }
    add_to_cart($conn, $user_id, $product_id, $quantity);
 

    header('location: user_cabinet.php');}
exit();

?>
