<?php
require "connect_bd_no.php";
$sql = "SELECT AVG(rating) AS avg_rating FROM reviews WHERE product_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    echo "Средний рейтинг: " . round($row['avg_rating'], 1) . "★";
    $stmt->close();
}