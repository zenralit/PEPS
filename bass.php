
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>guitar</title>
    <link rel="stylesheet" href="mainpagecss.css">
    <link rel="stylesheet" href="productstyle.css">
</head>
<body>
    
   
<header class="header">
    <!-- Боковое меню -->
   <button class="menu-btn menu-button " onclick="toggleMenu()">
       <span></span>
       <span></span>
       <span></span>
   </button>
   
<div id="side-menu" class="side-menu">
   <a href="main_page.php">main page</a>
   <a href="product_page.html">Products</a>
  
   <a href="#">Information</a>
   <a href="#">FAQ</a>

</div>


<div id="overlay" class="overlay"></div>
<a href="main_page.php">
   <div class="logo"> <img src="logo.png" alt="" style="width: 150px; height: 100px;"></div>
</a>
   <div class="icon-buttons">
        <button class="search" aria-label="Поиск" onclick="callsearc()"> <img src="search.svg" alt="" style="height: 20px; width: 20px;"></button>
       <a href="user_cabinet.php"><button class="cart" aria-label="Корзина"> <img src="shopping-cart.svg" alt="" style="height: 20px; width: 20px;"></button></a>
       <FOrm action="C:\xampp\htdocs\my_project\auto_main.html">
        <button class="profile" aria-label="Личный кабинет" onclick="callprofile()" type="submit" > <img src="user.svg" alt="" style="height: 20px; width: 20px; padding-right: 30px;"  ></button>
      </FOrm>
   </div>
</header>



<div style="height: 90px; background-color: block;">

</div>

   
   


</div>


<div class="product-catalog">
   
   <?php require   'show_bass.php'; 
   

   ?>

 </div>
 <footer class="footer">
  <div><a href="#tt">ТТ</a></div>
  <div><a href="#insta">ИНСТА</a></div>
  <div><a href="#insta">телега</a></div>
  <div><a href="#insta">вк</a></div>
</div>
       
<script src="main_page_scripts.js">
  
</script>

</html>