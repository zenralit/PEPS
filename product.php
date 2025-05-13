<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="guitarcss.css">
    <title>product</title>
</head>
<body>
<?php require 'showguitinfo.php'; ?>

<?php require'header.php' ?>
<div class="container">



    <div class="top-section">
        <h1 class="product-title"><?php  echo $pname; ?></h1>
        <img src="<?php  echo $gp; ?>" alt="Top Guitar Image" class="top-image">
    </div>
    </div>
            
        
<img src="<?php  echo $gp; ?>" alt="Guitar Side Image" class="guitar-image">
    <div class="content">
        
            <div class="description">
                <p>
                    <?php  echo $des; ?>
                </p>
            </div>
           <?php
           sound($category); 
           ?>

            <div class="specs">
                <h2>SPECS</h2>
                <hr>
                <ul>
                    <li>Number of frets -<?php  echo $nof; ?></li>
                    <li>String space -<?php  echo $Sp; ?></li>
                    <li>Color -<?php  echo $color; ?></li>
                    <li>fretboard -<?php  echo $material; ?> </li>
                    <li><a href="guitarjs.html" style="color: white;">try it!</a></li>
                </ul>

                <br>
        <?php require "avg_rating.php"  ?>
                <div class="price-section">
                <div class="price-box">
                    <p><?php  echo $price; ?> <span>&#8364;</span></p>



                            <form method="POST" action="add_to_cart.php">
                         <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                         <button class="number-minus" type="button" onclick="this.nextElementSibling.stepDown(); this.nextElementSibling.onchange();">-</button>
                         <input type="number" name="quantity" value="1" min="1">
                         <button class="number-plus" type="button" onclick="this.previousElementSibling.stepUp(); this.previousElementSibling.onchange();">+</button>
                                 <button class="cart-button" type="submit">Добавить в корзину</button>
                            </form>

       </div>

    
        </div>


<!-- отзывы -->
     <form action="submit_reviews.php" method="post">
<div class="rating">
    
  <input value="5" name="rating" id="star5" type="radio">
  <label for="star5"></label>
  <input value="4" name="rating" id="star4" type="radio">
  <label for="star4"></label>
  <input value="3" name="rating" id="star3" type="radio">
  <label for="star3"></label>
  <input value="2" name="rating" id="star2" type="radio">
  <label for="star2"></label>
  <input value="1" name="rating" id="star1" type="radio">
  <label for="star1" style="bottom: 10%;"></label>
</div>
<br>

<input placeholder="оставьте комментарий" class="input" name="review_text" id="review_text" type="text">

<br>
<input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

<!-- <input type="submit" value="оставьте отзыв:" style="margin-bottom: 40px;"> -->
</form>
 

<?php require "show_reviews.php" ?>



<script src="main_page_scripts.js"></script>
<script>
setInterval(function() {
    fetch('update_status.php');
}, 60000); 
</script>

<?php
require "connect_bd_no.php";
require "show_user_info.php";
if ($status == "поставщик" || $status == "главный администратор") 
{
    echo "<a class=\"editbtn\" href=\"edit_product.php?product_id=$product_id>Редактировать</a>";
}
?>
<!-- <a href="edit_product.php?product_id=<?= $product_id ?>" style="
margin-left: 80%; 
    margin-top: 8%;
  display: inline-block;
  padding: 6px 12px;
  background-color: #4CAF50;
  color: white;
  text-decoration: none;
  border-radius: 5px;

">Редактировать</a> 
</body>
</html> 

