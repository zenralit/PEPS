<?php
session_start();
require 'connect_bd.php';
$_SESSION['id'] = $user_id;
if (isset($_SESSION['user_id'])) {
    echo "Сессия работает, user_id: " . $_SESSION['user_id'];
} else {
    echo "Сессия не содержит user_id.";
}