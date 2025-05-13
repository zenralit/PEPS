<?php
require 'connect_bd.php';

if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];


    if (isset($_POST['product_id'], $_POST['quantity'], $_POST['total_price'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $price_at_purchase = $_POST['total_price'];
        $purchase_date = date('Y-m-d H:i:s');

       
        $sql = "INSERT INTO purchases (user_id, product_id, quantity, price_at_purchase, purchase_date)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiids", $user_id, $product_id, $quantity, $price_at_purchase, $purchase_date);

        if ($stmt->execute()) {
            echo "Покупка успешно добавлена!";
            header ('location: user_cabinet.php');
            
            
            if (isset($_POST['street'], $_POST['house'], $_POST['apartment'])) {
                $street = $_POST['street'];
                $house = $_POST['house'];
                $apartment = $_POST['apartment'];

                $update_sql = "UPDATE users SET street = ?, house = ?, apartment = ? WHERE user_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("sssi", $street, $house, $apartment, $user_id);

                if ($update_stmt->execute()) {
                    echo " Информация о пользователе успешно обновлена!";
                } else {
                    echo "Ошибка обновления информации о пользователе: " . $update_stmt->error;
                }
                $update_stmt->close();
            }

            
            $delete_sql = "DELETE FROM cart_items WHERE user_id = ? and product_id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("ii", $user_id, $product_id);
            $delete_stmt->execute();
            $delete_stmt->close();
        } else {
            echo "Ошибка при добавлении покупки: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Не хватает данных для завершения покупки.";
    }
} else {
    echo "Пожалуйста, войдите в систему.";
}

$conn->close();
exit();
