<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить товар</title>
    <link rel="stylesheet" href="addcss.css">
    <link rel="stylesheet" href="mainpagecss.css">
</head>
<body style="margin-top: 8%;">
    <?php require 'header.php' ?>
    
    <div class="flex-container">
        <div class="flex-items"><center><h2>MY PRODUCTS</h2></center></div>
        <div class="flex-items"><center><h1>Добавить новый товар</h1></center></div>
        <div class="flex-items"><center><h2>статистика</h2></center></div>
    </div>
    
    <form class="parent" action="add_product.php" method="POST" enctype="multipart/form-data">
        <div class="div1">
            <label for="pname">Название товара:</label>
            <input type="text" id="pname" name="pname" required>
        </div>
        
        <div class="div2">
            <label for="category">Категория:</label>
            <input type="text" id="category" name="category" required>
        </div>
        
        <div class="div3">
            <label for="description">Описание товара:</label>
            <textarea id="description" name="description" rows="4" required></textarea>
        </div>
        
        <div class="div4">
            <label for="material">Материал:</label>
            <input type="text" id="material" name="material" required>
        </div>
        
        <div class="div5">
            <label for="number_of_frets">Количество ладов:</label>
            <input type="number" id="number_of_frets" name="number_of_frets" required>
        </div>
        
        <div class="div6">
            <label for="color">Цвет:</label>
            <input type="text" id="color" name="color" required>
        </div>
        
        <div class="div7">
            <label for="string_space">Расстояние между струнами (мм):</label>
            <input type="number" id="string_space" name="string_space" step="0.01" required>
        </div>
        
        <div class="div8">
            <label for="quantity_in_stock">Количество на складе:</label>
            <input type="number" id="quantity_in_stock" name="quantity_in_stock" value="1000" required>
        </div>
        
        <div class="div9">
            <label for="price">Цена:</label>
            <input type="number" id="price" name="price" step="0.01" required>
        </div>
        
        <div class="div10">
            <label for="guitpic">Изображение товара:</label>
            <input type="file" id="guitpic" name="guitpic" accept="image/*" required>
        </div>
        
        <input type="submit" value="Добавить товар">
    </form>
    <script src="main_page_scripts.js"></script>
</body>
</html>