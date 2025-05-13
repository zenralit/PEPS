<?php
require "connect_bd.php";
require "show_user_info.php";
require "showguitinfo.php";

if ($status == "пользователь" || $status == "ожидайте рассмотрения" ) {
    header("Location: product.php?product_id=$product_id");
    exit();
}

$query = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die('Товар не найден');
} 


if (isset($_POST['delete_product'])) {
   
    if (!empty($product['guitpic']) && file_exists($product['guitpic'])) {
        unlink($product['guitpic']);
    }
    
   
    $delete_query = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $product_id);
    
    if ($stmt->execute()) {
        header("Location: product_page.php"); 
        exit;
    } else {
        $error = "Ошибка при удалении товара: " . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pname = $conn->real_escape_string($_POST['pname']);
    $category = $conn->real_escape_string($_POST['category']);
    $description = $conn->real_escape_string($_POST['description']);
    $material = $conn->real_escape_string($_POST['material']);
    $number_of_frets = intval($_POST['number_of_frets']);
    $color = $conn->real_escape_string($_POST['color']);
    $string_space = floatval($_POST['string_space']);
    $quantity_in_stock = intval($_POST['quantity_in_stock']);
    $price = floatval($_POST['price']);

    $guitpic = $product['guitpic']; 
    
    if (isset($_FILES['guitpic']) && $_FILES['guitpic']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'upguitpic/'; 
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
     
        $file_extension = pathinfo($_FILES['guitpic']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('product_') . '.' . $file_extension;
        $target_path = $upload_dir . $filename;
        
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($_FILES['guitpic']['type'], $allowed_types)) {
            if (move_uploaded_file($_FILES['guitpic']['tmp_name'], $target_path)) {
                if ($product['guitpic'] && file_exists($product['guitpic'])) {
                    unlink($product['guitpic']);
                }
                $guitpic = $target_path;
            } else {
                $error = "Ошибка при загрузке изображения";
            }
        } else {
            $error = "Недопустимый тип файла. Разрешены только JPG, PNG, GIF и WebP";
        }
    }

    if (!isset($error)) {
        $update_query = "UPDATE products SET 
                        pname = ?, 
                        category = ?, 
                        description = ?, 
                        material = ?, 
                        number_of_frets = ?, 
                        color = ?, 
                        string_space = ?, 
                        quantity_in_stock = ?, 
                        price = ?,
                        voice = ?,
                        guitpic = ?
                        WHERE product_id = ?";
        
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssisdiissi", 
            $pname, 
            $category, 
            $description, 
            $material, 
            $number_of_frets, 
            $color, 
            $string_space, 
            $quantity_in_stock, 
            $price,
            $voice,
            $guitpic,
            $product_id
        );
    
    if ($stmt->execute()) {
        header("Location: product.php?product_id=$product_id");
        exit();
    } else {
        $error = "Ошибка при обновлении товара: " . $conn->error;
    }
}}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование товара</title>
    <link rel="stylesheet" href="edit_product_css.css">
</head>
<body >
    <div class="edit-form">
        <h1>Редактирование товара: <?php echo htmlspecialchars($product['pname']); ?></h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="pname">Название:</label>
                <input type="text" id="pname" name="pname" value="<?php echo htmlspecialchars($product['pname']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="category">Категория:</label>
                <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($product['category']); ?>">
            </div>
            
            <div class="form-group">
                <label for="description">Описание:</label>
                <textarea id="description" name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="material">Материал:</label>
                <input type="text" id="material" name="material" value="<?php echo htmlspecialchars($product['material']); ?>">
            </div>
            
            <div class="form-group">
                <label for="number_of_frets">Количество ладов:</label>
                <input type="number" id="number_of_frets" name="number_of_frets" value="<?php echo htmlspecialchars($product['number_of_frets']); ?>">
            </div>
            
            <div class="form-group">
                <label for="color">Цвет:</label>
                <input type="text" id="color" name="color" value="<?php echo htmlspecialchars($product['color']); ?>">
            </div>
            
            <div class="form-group">
                <label for="string_space">Расстояние между струнами:</label>
                <input type="text" id="string_space" name="string_space" value="<?php echo htmlspecialchars($product['string_space']); ?>">
            </div>
            
            <div class="form-group">
                <label for="quantity_in_stock">Количество на складе:</label>
                <input type="number" id="quantity_in_stock" name="quantity_in_stock" value="<?php echo htmlspecialchars($product['quantity_in_stock']); ?>">
            </div>
            
            <div class="form-group">
                <label for="price">Цена:</label>
                <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="guitpic">Изображение товара:</label>
                     <input type="file" id="guitpic" name="guitpic">
                        <?php if ($product['guitpic']): ?>
                  <div>Текущее изображение: <img src="<?php echo htmlspecialchars($product['guitpic']); ?>" height="100"></div>
                        <?php endif; ?>
            </div>
            
            <div class="button-group">
                <div>
                    <button type="submit" class="submit-btn">Сохранить изменения</button>
                </div>
                <div>
                    <button type="button" class="delete-btn" id="deleteBtn">Удалить товар</button>
                </div>
            </div>
            
            <div class="confirm-delete" id="confirmDelete">
                <p>Вы уверены, что хотите удалить этот товар? Это действие нельзя отменить.</p>
                <div>
                    <button type="submit" name="delete_product" class="delete-btn">Да, удалить</button>
                    <button type="button" id="cancelDelete" class="submit-btn" style="margin-left:10px;">Отмена</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('deleteBtn').addEventListener('click', function() {
            document.getElementById('confirmDelete').style.display = 'block';
        });
        
        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('confirmDelete').style.display = 'none';
        });
    </script>
</body>
</html>