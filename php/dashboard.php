<?php
session_start();
require_once '../config/connectDb.php';

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header('Location: login_process.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Получаем данные пользователя
$stmt = $pdo->prepare("SELECT * FROM user WHERE id_user = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="../css/style1.css">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<header>
        <div class="title">УДМУРТ<a class="pink">иЯ</a>&nbsp;с&nbsp;</div>
        <img class="logo" src="../img/logo1.svg" alt="логотип">
        <nav>
            <ul>
                <li><a href="#">Маршруты</a></li>
                <li><a href="#">Экскурсии</a></li>
                <li><a href="#">Гиды</a></li>
                <li><a href="#">Мы в соцсетях</a></li>
                <li><a href="#">Экскурсии на карте</a></li>
                <li><a href="../index.php">Выйти</a></li>
            </ul>
        </nav>
    </header>



<div class="dashboard-container">
    <!-- Боковое меню -->
    <aside class="dashboard-sidebar">
        <h3>Личный кабинет</h3>
        <ul class="sidebar-menu">
            <li class="active"><a href="#">Профиль</a></li>
            <li><a href="add_location.php">Добавить локацию</a></li>
        </ul>
    </aside>

    <!-- Основной контент -->
    <main class="dashboard-content">
        <div class="profile-card">
            <!-- Ошибки и успехи -->
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <!-- Шапка профиля -->
            <div class="profile-header">
                <div class="profile-avatar">
                    <?= mb_substr($user['fio'], 0, 1) ?>
                </div>
                <div class="profile-title">
                    <h2><?= htmlspecialchars($user['fio']) ?></h2>
                    <p><?= htmlspecialchars($user['email']) ?></p>
                </div>
            </div>

            <!-- Информация о пользователе -->
            <div class="info-section">
                <h3>Информация</h3>
                <div class="info-row">
                    <div class="info-label">ФИО:</div>
                    <div class="info-value"><?= htmlspecialchars($user['fio']) ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Логин:</div>
                    <div class="info-value"><?= htmlspecialchars($user['login']) ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value"><?= htmlspecialchars($user['email']) ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Телефон:</div>
                    <div class="info-value"><?= htmlspecialchars($user['phone']) ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Статус:</div>
                    <div class="info-value"><?= htmlspecialchars($user['status']) ?></div>
                </div>
            </div>
            </div>
            </div>

            <footer>
        <div class="title1">УДМУРТ<a class="pink">иЯ</a>&nbsp;с&nbsp;</div>
        <img class="logo" src="../img/logo1.svg" alt="логотип">
        <nav>
            <ul>
                <li><a href="#">Маршруты</a></li>
                <li><a href="#">Экскурсии</a></li>
                <li><a href="#">Гиды</a></li>
                <li><a href="#">Мы в соцсетях</a></li>
                <li><a href="#">Экскурсии на карте</a></li>
            </ul>
        </nav>
        <p class="protect">©2026 Все права защищены</p>
    </footer>
            

</body>
</html>