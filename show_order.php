<?php


require 'connect_bd_no.php';

if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
}

// Проверка активной сессии
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // SQL для получения списка покупок
    $sql = "SELECT p.pname, pur.quantity, p.guitpic, pur.purchase_date, pur.price_at_purchase
            FROM purchases pur
            JOIN products p ON pur.product_id = p.product_id
            WHERE pur.user_id = ?
            ORDER BY pur.purchase_date DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Проверяем, есть ли покупки
    if ($result->num_rows > 0) {
        echo "<ul class='purchase-list'>"; 
        
        // Выводим покупки
        while ($row = $result->fetch_assoc()) {
            $pname = $row['pname'];
            $quantity = $row['quantity'];
            $guitpic = $row['guitpic'];
            $date = $row['purchase_date'];
            $sum_price = $row['price_at_purchase'];

            echo '<li>';
            echo '<div class="product-item">';
            
            echo '<img src="' . $guitpic . '" alt="Product">';
            
            echo '<div class="product-info">';
            echo '<p class="product-name">' . $pname . '</p>';
            echo '<p class="product-quantity">Количество: ' . $quantity . '</p>';
            echo '<p class="product-quantity">дата: ' . $date . '</p>';
            echo '<p class="product-quantity">сумма: ' . $sum_price . '</p>';
            echo '</div>';
            
            echo '</div>';
            echo '</li>';
        }

        echo "</ul>";
    } else {
        echo "<p>Ваш список покупок пуст.</p>";
    }

    $stmt->close();
} 

$conn->close();

