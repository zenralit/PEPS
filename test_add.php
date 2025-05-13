<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить товар</title>
</head>
<body>
    <h1>Добавить новый товар</h1>
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <label for="pname">Название товара:</label><br>
        <input type="text" id="pname" name="pname" required><br><br>

        <label for="category">Категория:</label><br>
        <input type="text" id="category" name="category" required><br><br>

        <label for="description">Описание товара:</label><br>
        <textarea id="description" name="description" rows="4" cols="50" required></textarea><br><br>

        <label for="material">Материал:</label><br>
        <input type="text" id="material" name="material" required><br><br>

        <label for="number_of_frets">Количество ладов:</label><br>
        <input type="number" id="number_of_frets" name="number_of_frets" required><br><br>

        <label for="color">Цвет:</label><br>
        <input type="text" id="color" name="color" required><br><br>

        <label for="string_space">Расстояние между струнами (мм):</label><br>
        <input type="number" id="string_space" name="string_space" step="0.01" required><br><br>

        <label for="quantity_in_stock">Количество на складе:</label><br>
        <input type="number" id="quantity_in_stock" name="quantity_in_stock" value="1000" required><br><br>

        <label for="price">Цена:</label><br>
        <input type="number" id="price" name="price" step="0.01" required><br><br>

        <label for="guitpic">Изображение товара:</label><br>
        <input type="file" id="guitpic" name="guitpic" accept="image/*" required><br><br>

        <input type="submit" value="Добавить товар">
    </form>
</body>
</html>