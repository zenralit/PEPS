<?php


require 'connect_bd_no.php';

if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
}


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
  
    $sql = "SELECT c.product_id, p.pname, p.price, c.quantity, p.guitpic
            FROM cart_items c
            JOIN products p ON c.product_id = p.product_id
            WHERE c.user_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows > 0) {
        echo "<ul class='purchase-list'>";
        

        while ($row = $result->fetch_assoc()) {
            $product_id = $row['product_id'];
            $pname = $row['pname'];
            $price = $row['price'];
            $quantity = $row['quantity'];
            $total_price = $price * $quantity;
            $guitpic = $row['guitpic'];

            echo '<li>';
            echo '<div class="product-item">';
            echo '<div class="product-info">';
            echo '<p class="product-name">' . $pname . '</p>';
            echo '<p class="product-price">€' . $total_price . '</p>';
            echo '</div>';
            echo '<img src="' . $guitpic . '" alt="Product Image">';

           
            echo "
                <form action='order.php' method='POST'>
                    <input type='hidden' name='product_id' value='$product_id'>
                    <input type='hidden' name='quantity' value='$quantity'>
                    <input type='hidden' name='total_price' value='$total_price'>";
                   echo ' <div class="buybutt">';
                    echo "<button type='submit' class='btn-23'>
                        <span class='text'>Buy</span>
                        <span aria-hidden='true' class='marquee'>$quantity</span>
                    </button>
                </form>
            ";
                
            echo '</div>';
            echo '</li>';
        }

        echo "</ul>";
    } else {
        echo "Ваша корзина пуста.";
    }

    $stmt->close();
} else {
    echo "Пожалуйста, войдите в систему, чтобы просмотреть корзину.";
}

$conn->close();
