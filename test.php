<?php
require 'connect_bd.php';
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

          
           
echo $address;

?>
