<?php
session_start();
require_once '../config/connectDb.php';

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Получаем данные пользователя
$stmt = $pdo->prepare("SELECT * FROM user WHERE id_user = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Обработчик отправки формы (имитация)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publish'])) {
    // Имитация успешной публикации
    $success = 'Локация успешно добавлена!';
    // Здесь позже будет реальное сохранение в БД
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить локацию - Удмуртия</title>
    <link rel="stylesheet" href="../css/style2.css">
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
    <aside class="dashboard-sidebar">
        <h3>Личный кабинет</h3>
        <a href='dashboard.php'>Профиль</a>
        <a href='add_location.php'>Добавить локацию</a>
    </aside>

    <!-- Основной контент -->
    <main class="dashboard-content">
        <div class="form-card">
            <h2>Добавить локацию</h2>
            <p class="subtitle">Заполните информацию о новом месте</p>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" id="locationForm">
                <!-- Раздел: Что посмотреть -->
                <div class="form-section">
                    <h3>Что посмотреть</h3>
                    <div class="form-group">
                        <label>Название места <span class="required">*</span></label>
                        <input type="text" name="place_name" placeholder="Например: Ижевский пруд" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Категория <span class="required">*</span></label>
                            <select name="category" required>
                                <option value="">Выберите категорию</option>
                                <option value="architecture">Памятники архитектуры</option>
                                <option value="nature">Природные достопримечательности</option>
                                <option value="museums">Музеи</option>
                                <option value="parks">Парки и скверы</option>
                                <option value="religious">Храмы и соборы</option>
                                <option value="other">Другое</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Город / Населенный пункт</label>
                            <input type="text" name="city" placeholder="Например: Ижевск">
                        </div>
                    </div>
                </div>
                
                <!-- Раздел: Адрес и контакты -->
                <div class="form-section">
                    <h3>Адрес и контакты</h3>
                    <div class="form-group">
                        <label>Адрес</label>
                        <input type="text" name="address" placeholder="Улица, дом">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Телефон</label>
                            <input type="tel" name="phone" placeholder="+7 (999) 123-45-67">
                        </div>
                        
                        <div class="form-group">
                            <label>Режим работы</label>
                            <input type="text" name="work_hours" placeholder="Например: Пн-Вс 09:00-20:00">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Сайт</label>
                        <input type="url" name="website" placeholder="https://example.com">
                    </div>
                </div>
                
                
                <!-- Раздел: Изображения -->
                <div class="form-section">
                    <h3>Изображения</h3>
                    <div class="image-upload" onclick="document.getElementById('imageInput').click()">
                        <div class="upload-icon"></div>
                        <p>Нажмите для загрузки изображений</p>
                        <p style="font-size: 12px;">Поддерживаются JPG, PNG, WEBP</p>
                        <input type="file" id="imageInput" accept="image/*" multiple style="display: none;">
                    </div>
                    <div class="image-preview" id="imagePreview"></div>
                </div>

                <div id="map-container">
                <h2 class="map-title">Выберите место на карте</h2>
                <div id="map"></div>
            </div>
        </div>
        <!-- Блок для отображения выбранных координат -->
<div id="coordinates-display" style="margin-top: 15px; padding: 12px 15px; background: #f8f9fa; border-radius: 10px; font-family: Regular; font-size: 14px; border-left: 3px solid var(--pink);">
    <strong>📍 Выбранные координаты:</strong><br>
    Широта: 56.850000<br>
    Долгота: 53.200000
</div>

</div>
                
                <!-- Кнопки формы -->
                <div class="form-actions">
                    <button type="submit" name="publish" class="btn-publish">Опубликовать</button>
                    <button type="reset" class="btn-cancel">Очистить форму</button>
                </div>
            </form>
            </main>
            


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

<!-- Яндекс Карты (без API ключа) -->
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
<script>
    // Инициализация карты
ymaps.ready(init);

function init() {
    // Создаем карту с центром в Ижевске
    var myMap = new ymaps.Map("map", {
        center: [56.85, 53.20],
        zoom: 12,
        controls: ['zoomControl', 'fullscreenControl']
    });
    
    // Переменная для хранения текущей метки
    var currentPlacemark = null;
    
    // Добавляем метку
    var placemark = new ymaps.Placemark([56.85, 53.20], {
        hintContent: 'Ижевск',
        balloonContent: '<b>Добро пожаловать в Удмуртию!</b><br>Выберите местоположение'
    });
    
    myMap.geoObjects.add(placemark);
    currentPlacemark = placemark;
    
    // Показываем начальные координаты
    updateCoordinatesDisplay(56.85, 53.20);
    
    // По клику на карту можно перемещать метку
    myMap.events.add('click', function(e) {
        var coords = e.get('coords');
        var lat = coords[0].toFixed(6);
        var lon = coords[1].toFixed(6);
        
        // Удаляем старые метки
        myMap.geoObjects.removeAll();
        
        // Добавляем новую метку
        var newPlacemark = new ymaps.Placemark(coords, {
            hintContent: 'Выбранное место',
            balloonContent: '<b>Выбранное местоположение</b><br>' +
                           'Широта: ' + lat + '<br>' +
                           'Долгота: ' + lon
        });
        
        myMap.geoObjects.add(newPlacemark);
        currentPlacemark = newPlacemark;
        
        // Обновляем отображение координат
        updateCoordinatesDisplay(lat, lon);
        
        // Сохраняем координаты в скрытые поля формы
        setCoordinatesInForm(lat, lon);
    });
}

// Функция для отображения координат на странице
function updateCoordinatesDisplay(lat, lon) {
    // Ищем или создаем блок для отображения координат
    var coordsDisplay = document.getElementById('coordinates-display');
    
    if (!coordsDisplay) {
        // Создаем блок для отображения координат
        coordsDisplay = document.createElement('div');
        coordsDisplay.id = 'coordinates-display';
        coordsDisplay.style.cssText = `
            margin-top: 15px;
            padding: 12px 15px;
            background: #f8f9fa;
            border-radius: 10px;
            font-family: Regular;
            font-size: 14px;
            border-left: 3px solid var(--pink);
        `;
        
        // Вставляем после карты
        var mapContainer = document.getElementById('map-container');
        if (mapContainer) {
            mapContainer.appendChild(coordsDisplay);
        }
    }
    
    coordsDisplay.innerHTML = `
        <strong>Выбранные координаты:</strong><br>
        Широта: ${lat}<br>
        Долгота: ${lon}
    `;
}

// Функция для сохранения координат в скрытые поля формы
function setCoordinatesInForm(lat, lon) {
    // Проверяем, есть ли уже скрытые поля
    var latInput = document.getElementById('latitude');
    var lonInput = document.getElementById('longitude');
    
    if (!latInput) {
        // Создаем скрытые поля, если их нет
        latInput = document.createElement('input');
        latInput.type = 'hidden';
        latInput.id = 'latitude';
        latInput.name = 'latitude';
        
        lonInput = document.createElement('input');
        lonInput.type = 'hidden';
        lonInput.id = 'longitude';
        lonInput.name = 'longitude';
        
        // Добавляем поля в форму
        var form = document.getElementById('locationForm');
        if (form) {
            form.appendChild(latInput);
            form.appendChild(lonInput);
        }
    }
    
    // Устанавливаем значения
    latInput.value = lat;
    lonInput.value = lon;
}

// Функция для получения текущих координат (можно вызвать при отправке формы)
function getCurrentCoordinates() {
    var lat = document.getElementById('latitude')?.value || '56.85';
    var lon = document.getElementById('longitude')?.value || '53.20';
    return { lat: lat, lon: lon };
}
</script>

<script>
    // Предпросмотр изображений
    const imageInput = document.getElementById('imageInput');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            
            const files = Array.from(e.target.files);
            
            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(event) {
                        const div = document.createElement('div');
                        div.className = 'preview-item';
                        div.innerHTML = `
                            <img src="${event.target.result}" alt="Preview">
                            <div class="remove" onclick="this.parentElement.remove()">×</div>
                        `;
                        preview.appendChild(div);
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        });
    }
    
    // Очистка предпросмотра при сбросе формы
    const resetBtn = document.querySelector('input[type="reset"]');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            const preview = document.getElementById('imagePreview');
            if (preview) preview.innerHTML = '';
            const imageInput2 = document.getElementById('imageInput');
            if (imageInput2) imageInput2.value = '';
        });
    }
</script>

</body>
</html>