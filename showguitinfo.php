<?php
require 'connect_bd_no.php';

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}


if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

   
    $sql = "SELECT pname, price, material, Number_of_frets, color, String_space, description, guitpic, category, product_id
            FROM products 
            WHERE product_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);  
    $stmt->execute();
    $result = $stmt->get_result();

    // Проверяем, найден ли товар
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $product_id = $row['product_id'];
        $category = $row['category'];
        $pname = $row['pname'];
        $price = $row['price'];
        $material = $row['material'];
        $nof = $row['Number_of_frets'];
        $color = $row['color'];
        $Sp = $row['String_space'];
        $des = $row['description'];
        $gp = $row['guitpic'];

       
    } else {
        echo "Товар не найден.";
    }

    $stmt->close();
} else {
    echo "Не передан идентификатор товара.";
}

function sound($category){
    if($category == 'aguitar'){       
      echo  '<audio controls src="sound\acus.ogg"  style="margin: 10px; "></audio>'.
        '<audio controls src="sound\acuspent.ogg" style="margin: 10px;"></audio>';
    } elseif($category == 'guitar'){
        echo '<audio controls src="sound\slts.ogg"  style="margin: 10px;"></audio>';
        echo '<audio controls src="sound\acel.ogg"  style="margin: 10px;"></audio>';
    }elseif($category== 'ukulele'){
        echo '<audio controls src="sound\ukulele.ogg"  style="margin: 10px;"></audio>';
    }elseif($category== 'bass'){
        echo '<audio controls src="sound\bass.ogg" style="margin: 10px;"></audio>';
    }
}



