<?php
require 'connect_bd_no.php';


$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$material = $_GET['material'] ?? '';
$min_price = !empty($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$max_price = !empty($_GET['max_price']) ? (float)$_GET['max_price'] : PHP_FLOAT_MAX;
if ($min_price > $max_price) {
    $temp = $max_price;
    $max_price = $min_price;
    $min_price = $temp;
}


$sql = "SELECT product_id, pname, guitpic, price, category, material 
        FROM products
        WHERE 1=1";

$conditions = [];
$types = '';
$params = [];

if (!empty($search)) {
    $conditions[] = " pname LIKE ? ";
    $params[] = "%$search%";
    $types .= 's';
}

if (!empty($category)) {
    $conditions[] = " category = ? ";
    $params[] = $category;
    $types .= 's';
}

if (!empty($material)) {
    $conditions[] = " material = ? ";
    $params[] = $material;
    $types .= 's';
}

if ($min_price > 0) {
    $conditions[] = " price >= ? ";
    $params[] = $min_price;
    $types .= 'd';
}

if ($max_price < PHP_FLOAT_MAX) {
    $conditions[] = " price <= ? ";
    $params[] = $max_price;
    $types .= 'd';
}

if (!empty($conditions)) {
    $sql .= " AND " . implode(" AND ", $conditions);
}

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo '<div class="product-card">';
    echo '<a href="product.php?product_id='.$row['product_id'].'">';
    echo '<img src="'.$row['guitpic'].'" alt="'.$row['pname'].'"></a>';
    // echo '<h3>'.$row['pname'].'</h3>';
    // echo '<p>Цена: '.$row['price'].' ₽</p>';
    // echo '<p>Категория: '.$row['category'].'</p>';
    // echo '<p>Материал: '.$row['material'].'</p>';
    echo '</div>';
}

$stmt->close();
$conn->close();
?>