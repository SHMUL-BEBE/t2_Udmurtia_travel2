<?php
require_once '../config/connectDb.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fio = trim($_POST['fio'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Простая проверка
    if ($password !== $confirm_password) {
        $error = 'Пароли не совпадают';
    } else {
        // Проверка на существование
        $check = $pdo->prepare("SELECT id_user FROM user WHERE login = ? OR email = ? OR phone = ?");
        $check->execute([$login, $email, $phone]);
        
        if ($check->fetch()) {
            $error = 'Пользователь уже существует';
        } else {
            // Хешируем пароль
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Вставляем пользователя
            $stmt = $pdo->prepare("INSERT INTO user (fio, phone, email, login, password, status) VALUES (?, ?, ?, ?, ?, 'user')");
            
            if ($stmt->execute([$fio, $phone, $email, $login, $hashed_password])) {
                $success = 'Регистрация успешна! <a href="login_process.php">Войти</a>';
            } else {
                $error = 'Ошибка при регистрации';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - Тургид</title>
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
            </ul>
        </nav>
    </header>
    <div class="form-container">
        <h2>Регистрация</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>ФИО</label>
                <input type="text" name="fio" required value="<?= htmlspecialchars($_POST['fio'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Телефон</label>
                <input type="tel" name="phone" placeholder="+7XXXXXXXXXX" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Логин</label>
                <input type="text" name="login" required value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Пароль (мин. 6 символов)</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label>Повторите пароль</label>
                <input type="password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn">Зарегистрироваться</button>
            
            <div class="form-footer">
                Уже есть аккаунт? <a href="login_process.php">Войти</a>
            </div>
        </form>
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