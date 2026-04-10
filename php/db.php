<?php
// db.php - подключение к базе данных
$host = 'localhost';
$dbname = 'Udmurtia_t2';  // Имя вашей БД
$username = 'root';      // Ваш пользователь MySQL
$password = '';      // Ваш пароль

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}
?>