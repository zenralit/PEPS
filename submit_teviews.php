<?php
session_start();
 require "connect_bd_no.php";
 if ($_SERVER["REQUEST_METHOD"]== "POST") {
    $rating= $_post['rating'];
    $review_text= $_post['review_text'];
    $product_id = $_post['product_id'];
    $user_id= $_SESSION['user_id'];

    $sql= "insert into reviews (user_id, product_id, rating, review_text  values (?,?,?,?)";

    if($stmt = $conn ->prepare($sql)){
        $stmt ->bind_param("iiis", $user_id, $product_id, $rating, $review_text);
            if($stmt->execute()){
               echo " заебись";
            } else {echo "при отправке возникла ошибка";
            
        }
        $stmt->close();
    }
 }