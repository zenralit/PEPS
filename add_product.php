<?php
session_start();
require "connect_bd_no.php";

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы
    $pname = $_POST['pname'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $material = $_POST['material'];
    $number_of_frets = (int)$_POST['number_of_frets'];
    $color = $_POST['color'];
    $string_space = (float)$_POST['string_space'];
    $quantity_in_stock = (int)$_POST['quantity_in_stock'];
    $price = (float)$_POST['price'];
    $supp_id = (int)$_SESSION['user_id']; 

    
    if (isset($_FILES['guitpic']) && $_FILES['guitpic']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "upguitpic/";
        
        
        if (!is_dir($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                die("Не удалось создать папку для загрузки.");
            }
        }

      
        $file_extension = strtolower(pathinfo($_FILES['guitpic']['name'], PATHINFO_EXTENSION));
        $file_name = uniqid('img-',true) . '.'.$file_extension;

        $target_file = $target_dir . $file_name;

  
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_extension, $allowed_types)) {
            die("Недопустимый тип файла. Разрешены: JPG, JPEG, PNG, GIF.");
        }

        
        $max_size = 10 * 1024 * 1024;
        if ($_FILES['guitpic']['size'] > $max_size) {
            die("Файл слишком большой. Максимальный размер: 5 МБ.");
        }

       
        if (move_uploaded_file($_FILES['guitpic']['tmp_name'], $target_file)) {
          
            $stmt = $conn->prepare("INSERT INTO products 
                (pname, category, description, material, number_of_frets, color, string_space, quantity_in_stock, price, guitpic, supp_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

           
            $stmt->bind_param(
                "ssssisdisss", 
                $pname, 
                $category, 
                $description, 
                $material, 
                $number_of_frets, 
                $color, 
                $string_space, 
                $quantity_in_stock, 
                $price, 
                $target_file, 
                $supp_id
            );

            
            if ($stmt->execute()) {
                header("location: main_page.php");
              exit();
            } else {
                echo "Ошибка запроса: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Ошибка при перемещении файла.";
        }
    } else {
        echo "Ошибка загрузки файла. Код ошибки: " . $_FILES['guitpic']['error'];
    }
}

$conn->close();
?>