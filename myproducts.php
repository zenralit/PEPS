<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="addcss.css">
   <link rel="stylesheet" href="productstyle.css">
   <link rel="stylesheet" href="mainpagecss.css">
    <title>Document</title>
</head>
<body>
    <body style="background-color: black;">
<header class="header" style="height: 10%;">
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
       <a href="reg_sup.php">стать поставщиком</a>

   </div>


   <div id="overlay" class="overlay"></div>
   <a href="main_page.php">
   <div class="logo" > <H1 style="color: green" >PEPS@SCUPPCAB</H1></div></a>
  
       <div class="icon-buttons">
            <button class="search" aria-label="Поиск" onclick="callsearc()"> <img src="search.svg" alt="" style="height: 20px; width: 20px;"></button>
            <a href="user_cabinet.php"><button class="cart" aria-label="Корзина"> <img src="shopping-cart.svg" alt="" style="height: 20px; width: 20px;"></button></a>           <FOrm action="C:\xampp\htdocs\my_project\auto_main.html">
            <a href="auto_main.html"> <button class="profile" aria-label="Личный кабинет" type="submit" > <img src="user.svg" alt="" style="height: 20px; width: 20px; padding-right: 30px;"  ></button>
          </FOrm></a>
       </div>
   </header>
   <div class="flex-container">

 <div class="flex-items"><center><h2 style="color: white; font-size: xx-large;" >MY PRODUCTS</h2></center></div>
 <div class="flex-items"> <center><h1 style="margin-left: auto; margin-top: auto;">Добавить новый товар</h1></center></div>
 <div class="flex-items"><center><h2 style="color: white">статистика</h2></center></div>
   </div> 
   <div class="product-catalog" style="margin-top: 6%;">
   <?php require   'show_guitar_for_sup.php'; ?>  </div>
</body>
</html>