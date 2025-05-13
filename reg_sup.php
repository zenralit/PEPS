<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registration</title>
    <link rel="stylesheet" href="auto.css.css">
</head>
<body>
  
<form class="form" style="
   width: 400px;
  margin-top: 70px; 
  margin-left: auto;
  margin-right: auto;
  box-shadow: 0px 0px 150px rgba(245, 245, 245, 0.808);"
  action="php_for_reg_sup.php" method="post" >
   
    <p class="title">Reg </p>
    <center>
    <p class="message">создайте аккаунт поставщика для выкладывания своих товаров</p></center>
        <div class="flex">
        <label>
            <input required="" placeholder="" type="text" class="input" name="comname" id="comname">
            <span>Company name</span>
        </label>

        <label style="margin-right: 10px;">
            <input required="" placeholder="" type="text" class="input" name="contname" id="V">
            <span>contact name</span>
        </label>
    </div>  
            
    <label style="margin-right:19px ;">
        <input required="" placeholder= "" type="numbers" class="input" name="contphone" id="contphone">
        <span>contact phone </span>
    </label>  
    <label style="margin-right:19px ;">
        <input required="" placeholder= "" type="email" class="input" name="email" id="email">
        <span>email</span>
    </label>  
        

    <button class="submit" name="register" id="register" type="submit">создать</button>
    <!-- <p class="signup-link">Уже есть аккаунт? <a href="auto_main.html">Войти</a> </p> -->
    
</form>
<center>
    <input type="checkbox" class="theme-checkbox" 
    style=" width: 60px;
    margin-top: 30px;
    margin-left: auto;
    margin-right: auto;
    box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.808);"
    onchange="changeTheme(this.checked)">
   
   </center>
   
   <script src="auto_switch_theme.js"></script>
</body>
</html>