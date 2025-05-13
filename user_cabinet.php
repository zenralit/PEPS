<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mycabinet</title>
     <link rel="shortcut icon" type="image/jpg" href="favicon.png">
    <link rel="stylesheet" href="personalstyle.css">
   
    <link rel="stylesheet" href="mainpagecss.css">
</head>
<script>
   
  </script>
<body>


   <!--  -->
 
    <div class="account-page">


    <div><a href="php_for_reg_admin.php" style="margin-top: 5em; margin-left:75%; font-size: 18px;
    color: #e1e1e1;
    font-family: inherit;
    font-weight: 400;
    cursor: pointer;
    position: relative;
    border: none;
    background: none;
    text-transform: uppercase;
    transition-timing-function: cubic-bezier(0.25, 0.8, 0.25, 1);
    transition-duration: 400ms;
    transition-property: color;" method="post"> <?php  
           
        ?>
            подать заявку на админа 
        </a>
        <a href="destroy_sess.php" style="margin-top: 5em; margin-left:85%; font-size: 18px;
    color: #e1e1e1;
    font-family: inherit;
    font-weight: 800;
    cursor: pointer;
    position: relative;
    border: none;
    background: none;
    text-transform: uppercase;
    transition-timing-function: cubic-bezier(0.25, 0.8, 0.25, 1);
    transition-duration: 400ms;
    transition-property: color;" method="post"> <?php  
           
        ?>
            logout 
        </a>
        <div class="profile-header">
           
   
    <?php require 'showpic.php';
    require 'header.php' ;
    require 'show_user_info.php'; ?>
    
        <?php if (!empty($profile_pic)): ?>
    <img src="<?php echo $profile_pic; ?>" alt="Фото профиля" class="userpic" style="width:200px; height:200px; object-fit:cover;">

    
<?php endif; ?>

        </form>
            <div class="user-info" style="margin-left: 30px;">
            <div class="maininfo">
                <h2 class="username">
                <?php
                echo $firstname, " ", $lastname;
                 ?>
                </h2> <p class="email"> email:
                <?php echo $email?></p> </div>
                        <div class="adressinfo">
                <!-- <p class="address"> город: <?php 
                //  echo $address;
                ?></p> -->
                <p class="address"> телефон: <?php 
              echo $phone;
                ?></p></div> 
                
               <?php 
                wait($status);   ?>

            </div>
            



             <button id="editButton">Редактировать информацию</button>


<div id="editForm">
    <form id="userForm" method="POST" enctype="multipart/form-data" action="update_user.php">
        <label for="firstname">Имя:</label>
        <input type="text" id="firstname" name="firstname" required>

        <label for="lastname">Фамилия:</label>
        <input type="text" id="lastname" name="lastname" required>

        <label for="address">город:</label>
        <input type="text" id="address" name="address">

        <label for="phone">Телефон:</label>
        <input type="text" id="phone" name="phone">

        <label for="profile_pic">Фото профиля:</label>
        <input type="file" id="profile_pic" name="profile_pic" accept="image/*">

        <button type="submit" class="button">Сохранить изменения</button>
    </form>
 </div>
</div>

<input type="checkbox" id="checkboxInput">
  <label for="checkboxInput" class="toggleSwitch"></label>

  <div class="forhid" id="forhid" style="display: none;">
    <div class="purchases">
      <h3>Мои покупки</h3>
      <?php require 'show_order.php'; ?>
    </div>
  </div>
        <div class="purchases" id="cart">
    <h3>Моя корзина</h3>
    
    <?php require 'show_cart.php'; ?>
 
            
            
        </div>
        <?php 
        if ($status == "поставщик" || $status == "главный администратор") 
{
    echo "<a class=\"editbtn\" href=\"edit_product.php?product_id=$product_id>Редактировать</a>";
}
?>
  
</div>
    </div>
    <script src="main_page_scripts.js"></script>
    <script>
        setInterval(function() {
    fetch('update_status.php');
        }, 60000);


        document.getElementById('editButton').addEventListener('click', function() {
        var form = document.getElementById('editForm');
        if (form.style.display === 'none') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    });
     document.addEventListener("DOMContentLoaded", function () {
      const checkbox = document.getElementById("checkboxInput");
      const purchasesList = document.getElementById("forhid");
      const cartList = document.getElementById("cart");

      function toggleDisplay() {
        if (checkbox.checked) {
          purchasesList.style.display = "block";
          cartList.style.display = "none";
        } else {
          purchasesList.style.display = "none";
          cartList.style.display = "block";
        }
      }

      toggleDisplay();

      checkbox.addEventListener("change", toggleDisplay);
    });
    </script>
</body>
</html>
