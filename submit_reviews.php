<?php

require "connect_bd.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];  


    $sql_check_purchase = "SELECT COUNT(*) AS purchase_count FROM purchases WHERE user_id = ? AND product_id = ?";
    $stmt_purchase = $conn->prepare($sql_check_purchase);
    $stmt_purchase->bind_param("ii", $user_id, $product_id);
    $stmt_purchase->execute();
    $result_purchase = $stmt_purchase->get_result();
    $purchase_data = $result_purchase->fetch_assoc();
    
    if ($purchase_data['purchase_count'] == 0) {
        echo "сначала купите, потом оценивайте)";
        exit();
    }


    $sql_check_review = "SELECT COUNT(*) AS review_count FROM reviews WHERE user_id = ? AND product_id = ?";
    $stmt_review = $conn->prepare($sql_check_review);
    $stmt_review->bind_param("ii", $user_id, $product_id);
    $stmt_review->execute();
    $result_review = $stmt_review->get_result();
    $review_data = $result_review->fetch_assoc();

    if ($review_data['review_count'] > 0) {
        echo "а зачем 2 отзыва то?";
        exit();
    }


    $sql_insert_review = "INSERT INTO reviews (user_id, product_id, rating, reviews_text) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert_review);
    $stmt_insert->bind_param("iiis", $user_id, $product_id, $rating, $review_text);

    if ($stmt_insert->execute()) {
        header("Location: product.php?product_id=$product_id");
        exit();
    } else {
        echo "Ошибка при добавлении отзыва. Попробуйте снова.";
    }

    $stmt_purchase->close();
    $stmt_review->close();
    $stmt_insert->close();
}
?>