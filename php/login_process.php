
<?php
require_once '../config/connectDb.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_input = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($login_input) || empty($password)) {
        $error = 'Введите логин/email/телефон и пароль';
    } else {
        // Ищем пользователя по логину, email или телефону
        $stmt = $pdo->prepare("SELECT * FROM user WHERE login = ? OR email = ? OR phone = ?");
        $stmt->execute([$login_input, $login_input, $login_input]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['user_fio'] = $user['fio'];
            $_SESSION['user_login'] = $user['login'];
            $_SESSION['user_status'] = $user['status'];
            
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Неверный логин/email/телефон или пароль';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация - Тургид</title>
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
        <h2>Авторизация</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Логин / Email / Телефон</label>
                <input type="text" name="login" required value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">Войти</button>
            
            <div class="form-footer">
                Нет аккаунта? <a href="register_process.php">Зарегистрироваться</a>
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