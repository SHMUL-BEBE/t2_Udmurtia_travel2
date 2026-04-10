<?php
session_start();

// Параметры подключения к БД
$host = 'localhost';
$dbname = 'Udmurtia_t2';  // Укажите правильное имя вашей БД!
$username = 'root';      // Ваш пользователь MySQL (обычно root)
$password = '';          // Ваш пароль MySQL (обычно пустая строка для OpenServer/XAMPP)

try {
    // Важно! Указываем базу данных в DSN
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>