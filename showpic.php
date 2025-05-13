 <?php
session_start(); 

if (!isset($_SESSION['user_id'])) {
    echo "Вы не авторизованы.";
    exit();
}

require 'connect_bd_no.php';

if ($conn->connect_error) {
    die("Ошибка соединения: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id']; // Получение id пользователя из сессии

$sql = "SELECT profile_pic FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $profile_pic = $row['profile_pic'];

    if ($profile_pic) {
      
    } else {
        $profile_pic = 'uploads\base.png';
    }
} else {
    echo "Ошибка: не удалось найти пользователя.";
}

$stmt->close();
$conn->close();
?>
