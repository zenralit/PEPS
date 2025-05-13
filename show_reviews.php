<?php
require 'connect_bd_no.php';
$product_id = $_GET['product_id']; 

$sql = "SELECT reviews.rating, reviews.reviews_text, reviews.reviews_date, users.firstname 
        FROM reviews 
        JOIN users ON reviews.user_id = users.user_id 
        WHERE reviews.product_id = ? ORDER BY reviews.reviews_date DESC";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<div class='review'>";
        echo "<p><strong>" . htmlspecialchars($row['firstname']) . "</strong> оставил(а) отзыв " . $row['reviews_date'] . "</p>";
        echo "<p>Рейтинг: " . str_repeat("★", $row['rating']) . "</p>";
        echo "<p>" . nl2br(htmlspecialchars($row['reviews_text'])) . "</p>";
        echo "</div>";
        echo "<hr>";
    }

    $stmt->close();
}
?>