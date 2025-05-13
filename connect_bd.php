<?php
   session_start();

    $host = 'localhost'; 
    $db = 'users'; 
    $user = 'root'; 
    $pass = 'zeent'; 
    
    $conn = new mysqli($host, $user, $pass, $db);
    
