<?php
   session_start();

    $host = 'localhost'; 
    $db = 'users'; 
    $user = 'root'; 
    $pass = ''; 
    
    $conn = new mysqli($host, $user, $pass, $db);
    
