<?php


require 'connect_bd_no.php';


    

    $sql = "SELECT product_id, pname, guitpic
            FROM products
            WHERE category like 'guitar'";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();


        
       
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['product_id'];
            $pname = $row['pname'];
            $guitpic = $row['guitpic'];


           echo' <div class="product-card">';
   echo' <a href="product.php?product_id='; echo $product_id; echo '">';
    echo ' <img src="'; echo $guitpic; echo'" alt="AZ"></a>';
     echo '<h3>'; echo $pname ; echo'</h3>';
   echo'</div>';

     

    }

    $stmt->close();


$conn->close();

