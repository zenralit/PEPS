<?php
require 'connect_bd.php';

if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
}

if (isset($_SESSION['user_id'])) {
    require 'show_cart.php';
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];  // ID товара
    $quantity = $_POST['quantity'];      // Количество товара
    $total_price = $_POST['total_price']; // Общая стоимость
    $street = $_POST['street'];
    $house = $_POST['house'];
    $apartment = $_POST['apartment'];

    // Обновляем адрес пользователя в таблице users
    $update_address_sql = "UPDATE users SET street = ?, house = ?, apart = ? WHERE user_id = ?";
    $stmt_address = $conn->prepare($update_address_sql);
    $stmt_address->bind_param("sssi", $street, $house, $apart, $user_id);
    $stmt_address->execute();
    $stmt_address->close();

    // Вставляем заказ в таблицу purchases
    $insert_purchase_sql = "INSERT INTO purchases (user_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)";
    $stmt_purchase = $conn->prepare($insert_purchase_sql);
    $stmt_purchase->bind_param("iiid", $user_id, $product_id, $quantity, $total_price);
    $stmt_purchase->execute();
    $stmt_purchase->close();

    echo "Заказ успешно добавлен!";
} else {
    echo "Пожалуйста, войдите в систему для оформления заказа.";
}

$conn->close();
?>
