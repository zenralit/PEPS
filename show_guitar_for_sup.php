<?php
require 'connect_bd.php';
$user_id = $_SESSION['user_id'];
$sql = "SELECT supplier_id FROM suppliers Where user_id = $user_id";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $_SESSION['supp_id'] = $row['supplier_id'];
            $supp_id= $_SESSION['supp_id'];
    

    $sql = "SELECT product_id, pname, guitpic
            FROM products 
            WHERE supp_id = '$supp_id' "
           ;
            
    
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
 echo $_SESSION['supp_id'];
    $stmt->close();


$conn->close();

