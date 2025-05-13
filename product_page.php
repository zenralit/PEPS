<html lang="en">
    <head>
    <!DOCTYPE html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="productstyle.css">
    <link rel="stylesheet" href="mainpagecss.css">
    <link rel="shortcut icon" type="image/jpg" href="favicon.png">
    <link rel="stylesheet" href="product_page_css.css">
</head>
<body style="max-width: 100%;">
    <a href="#pagetop"></a>
   <?php  require'header.php'?>



    <main class="products-page">
      
        </section>
        <section class="banner" >
        <h1 style="z-index: 1; margin-top: 10%;" >all products</h1>
       
        </section>

      

       <div class="filters">
        <center>
        <div class="filter-group">

            <label>Поиск</label>
            <input type="text" id="search" placeholder="Название товара">
        </div>
        
        <div class="filter-group">
            <label>Категория</label>
            <select id="category">
                <option value="">Все</option>
                <?php
                 require 'connect_bd_no.php';
                 
                $sql = "SELECT DISTINCT category FROM products WHERE category IS NOT NULL";
                $result = $conn->query(query: $sql);
                while($row = $result->fetch_assoc()) {
                    echo '<option>'.$row['category'].'</option>';
                }
                ?>
            </select>
        </div>

        <div class="filter-group">
            <label>Материал</label>
            <select id="material">
                <option value="">Все</option>
                <?php
                $sql = "SELECT DISTINCT material FROM products WHERE material IS NOT NULL";
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()) {
                    echo '<option>'.$row['material'].'</option>';
                }
                ?>
            </select>
        </div>

        <div class="filter-group">
            <label>Цена</label>
            <input type="number" id="min_price" placeholder="Мин">
            <input type="number" id="max_price" placeholder="Макс">
        </div>
      
    </div>

    <div id="products-container" class="products-container">
        <?php
        $sql = "SELECT product_id, pname, guitpic, price, category, material 
                FROM products";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product-card">';
            echo '<a href="product.php?product_id='.$row['product_id'].'">';
            echo '<img src="'.$row['guitpic'].'" alt="'.$row['pname'].'"></a>';
             echo '<h3>'.$row['pname'].'</h3>';
             echo '<p>Цена: '.$row['price'].' €</p>';
           
            echo '</div>';
        }
        $stmt->close();
        $conn->close();
        ?>

</center>
    </div>


    </main>


        <?php require'footer.php' ?>

    <script src="productscript.js"></script>
    <script src="main_page_scripts.js"></script>
    <script>
setInterval(function() {
    fetch('update_status.php');
}, 60000); 
</script>
</body>
</html>
