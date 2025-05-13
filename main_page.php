<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>main page</title>
    <link rel="stylesheet" href="mainpagecss.css">
    <link rel="shortcut icon" type="image/jpg" href="favicon.png">
    
</head>

<body>

  <?php
     
      require 'header.php';
    ?>
<div class="background">
         
<div id="background-slider" class="background-slider" ></div>


<div class="thumbnail-container">
    <div class="thumbnails" id="thumbnails">
        
    </div>
</div>

</div>

<div class="container" style="margin-bottom:0px ;" >
    
    <div class="section">
        <h2><a href="product_page.php">PRODUCTS</a></h2>
        <ul class="products-list" >
            <li><a href="product_page.php">Electroguitar</a></li>
            <li><a href="product_page.php">Acoustic Guitars</a></li>
            <li><a href="product_page.php">bass</a></li>
        </ul>
    </div>

    
    <div class="section">
        <h2><a href="#new-products" onclick="callprod()">NEW PRODUCTS </a></h2>
        <ul class="products-list" >
        <li><a href=""  onclick="callprod()">coming soon</a></li>
        </ul>
    </div>

    
    <div class="section">
        <h2><a href="#feature-products" onclick="callprod()">FEATURE PRODUCTS</a></h2>
        <div class="feature-placeholder"></div>
    </div>
</div>


<!-- футер -->
<?php require'footer.php'; ?>


<script src="main_page_scripts.js"></script>
<script>
setInterval(function() {
    fetch('update_status.php');
}, 60000); 
</script>

</body>
</html>
