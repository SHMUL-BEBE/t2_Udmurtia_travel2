<?php
// Подключение к БД - ИСПРАВЛЕННЫЙ ПУТЬ
require_once __DIR__ . '/php/db.php';

// Подключаем PHPMailer
require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/SMTP.php';
require_once 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Обработка подписки
$subscribe_message = '';
$subscribe_type = '';
$show_modal = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subscribe'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("SELECT id FROM subscribers WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $subscribe_message = "Вы уже подписаны на рассылку!";
            $subscribe_type = 'error';
        } else {
            $stmt = $pdo->prepare("INSERT INTO subscribers (email, status, subscribed_at) VALUES (?, 1, NOW())");
            if ($stmt->execute([$email])) {
                if (sendWelcomeEmail($email)) {
                    $show_modal = true;
                } else {
                    $subscribe_message = "Вы подписаны, но не удалось отправить письмо. Попробуйте позже.";
                    $subscribe_type = 'error';
                }
            } else {
                $subscribe_message = "Ошибка при подписке. Попробуйте позже.";
                $subscribe_type = 'error';
            }
        }
    } else {
        $subscribe_message = "Введите корректный email адрес";
        $subscribe_type = 'error';
    }
}

// Функция отправки письма через Gmail
function sendWelcomeEmail($userEmail) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'domhappyend@gmail.com'; 
        $mail->Password   = 'lbkt mwmq rbst cepw'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';
        
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        $mail->setFrom('domhappyend@gmail.com', 'УдмуртиЯ с t2');
        $mail->addAddress($userEmail);
        
        $mail->isHTML(true);
        $mail->Subject = '=?UTF-8?B?' . base64_encode('Вы подписались на рассылку УдмуртиЯ с t2') . '?=';
        
        $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
        </head>
        <body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
            <h2 style="color: #c72a2a;">Спасибо за подписку! 🏔️</h2>
            <p>Вы успешно подписались на рассылку <strong>«УдмуртиЯ с t2»</strong>.</p>
            <p>В ближайшее время мы добавим <strong>новые маршруты</strong> по Удмуртии и интересные места для путешествий.</p>
            <div style="background: #f5f5f5; padding: 15px; border-radius: 10px; margin: 20px 0;">
                <p style="margin: 0;">✨ Оставайтесь на связи — всё самое интересное ещё впереди!</p>
            </div>
            <hr style="margin: 30px 0;">
            <p style="font-size: 12px; color: #888;">
                Вы получили это письмо, потому что подписались на рассылку на нашем сайте<br>
                Если вы не подписывались — просто проигнорируйте это письмо.
            </p>
        </body>
        </html>
        ';
        
        $mail->AltBody = "Спасибо за подписку!\n\nВы успешно подписались на рассылку «УдмуртиЯ с t2».\n\nВ ближайшее время мы добавим новые маршруты по Удмуртии.\n\nОставайтесь на связи!";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail error: " . $mail->ErrorInfo);
        return false;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>t2_izhevsk</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <style>
        /* Дополнительные стили для слайдера */
        .modal-slider-container {
            position: relative;
            width: 100%;
            height: 350px;
            overflow: hidden;
            background: #f0f0f0;
            border-radius: 20px 20px 0 0;
        }

        .modal-slider {
            display: flex;
            width: 100%;
            height: 100%;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .modal-slider::-webkit-scrollbar {
            display: none;
        }

        .modal-slide {
            flex: 0 0 100%;
            scroll-snap-align: start;
            width: 100%;
            height: 100%;
            position: relative;
        }

        .modal-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 24px;
            font-weight: bold;
            transition: all 0.3s;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-slider-btn:hover {
            background: rgba(0, 0, 0, 0.8);
            transform: translateY(-50%) scale(1.1);
        }

        .modal-slider-prev {
            left: 15px;
        }

        .modal-slider-next {
            right: 15px;
        }

        .modal-slider-dots {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .modal-slider-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            transition: all 0.3s;
        }

        .modal-slider-dot.active {
            background: #c72a2a;
            width: 24px;
            border-radius: 4px;
        }

        .modal-slider-dot:hover {
            background: white;
            transform: scale(1.2);
        }

        .modal-info {
            padding: 25px;
        }

        .modal-info h2 {
            font-size: 26px;
            margin-bottom: 10px;
            color: #333;
            font-weight: 700;
        }

        .modal-rating {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .modal-rating .star {
            color: #ff9800;
            font-size: 18px;
        }

        .modal-rating .star.filled {
            color: #ff9800;
        }

        .modal-rating .star.half {
            color: #ff9800;
        }

        .modal-rating .reviews {
            color: #666;
            font-size: 14px;
            margin-left: 8px;
        }

        .modal-description {
            font-size: 15px;
            line-height: 1.6;
            color: #555;
            margin-bottom: 20px;
        }

        .modal-details {
            background: #f8f8f8;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.8;
        }

        .modal-details-row {
            display: flex;
            margin-bottom: 8px;
        }

        .modal-details-label {
            font-weight: 600;
            color: #c72a2a;
            width: 100px;
            flex-shrink: 0;
        }

        .modal-details-value {
            color: #555;
        }

        .modal-price {
            font-size: 22px;
            font-weight: bold;
            color: #00BFFF;
            margin-bottom: 20px;
            text-align: right;
        }

        .modal-price small {
            font-size: 14px;
            font-weight: normal;
            color: #888;
        }

        .btn-book-start {
            width: 100%;
            padding: 15px;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 10px;
        }

        .btn-book-start:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(199, 42, 42, 0.3);
        }

        .close-btn {
            width: 100%;
            padding: 12px;
            background: #f0f0f0;
            color: #333;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
        }

        .close-btn:hover {
            background: #e0e0e0;
        }
    </style>
</head>

<body>
    <header>
        <div class="title">УДМУРТ<a class="pink">иЯ</a>&nbsp;с&nbsp;</div>
        <img class="logo" src="img/logo1.svg" alt="логотип">
        <nav>
            <ul>
                <li><a href="#preimuchestvo">Преимущества</a></li>
                <li><a href="#destinations">Маршруты</a></li>
                <li><a href="#gids">Гиды</a></li>
                <li><a href="#social">Мы в соцсетях</a></li>
                <li><a href="#map">Экскурсии на карте</a></li>
                <li><a href="php/register_process.php">Войти</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="block1">
            <img class="pattern" src="img/back-patt.png" alt="паттерн">
            <div class="block1_1">
                <p class="name1"><strong>Туристические маршруты</strong></p>
                <p class="text1">Путешествуй по Удмуртии с удобством</p>

                <div class="filter">
                    <form action="#" method="GET" class="filter-form">
                        <div class="filter-group">
                            <label>Дата начала поездки</label>
                            <input type="date" name="start_date" class="filter-input">
                        </div>

                        <div class="filter-group">
                            <label>Дата конца поездки</label>
                            <input type="date" name="end_date" class="filter-input">
                        </div>

                        <div class="filter-group">
                            <label>Компания</label>
                            <select name="company" class="filter-select">
                                <option value="">Любая</option>
                                <option value="alone">Один</option>
                                <option value="family_with_children">С семьей</option>
                                <option value="friends">С друзьями</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Транспорт</label>
                            <select name="transport" class="filter-select">
                                <option value="">Любой</option>
                                <option value="own_car">Есть свой автомобиль</option>
                                <option value="no_car">Нет своего автомобиля</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Бюджет</label>
                            <select name="budget" class="filter-select">
                                <option value="">Любой</option>
                                <option value="lawful">Экономный</option>
                                <option value="medium">Средний</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Интересы</label>
                            <select name="interests" class="filter-select">
                                <option value="">Любые</option>
                                <option value="history_culture">История и культура</option>
                                <option value="nature">Природа</option>
                                <option value="active">Активный отдых</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Дополнительно</label>
                            <select name="additional" class="filter-select">
                                <option value="">Не важно</option>
                                <option value="animals">Животные</option>
                                <option value="without_animals">Без животных</option>
                            </select>
                        </div>

                        <button class="btn1" type="submit">Найти</button>
                        <button type="button" class="btn1" onclick="resetFilters()" style="background: #999;">Сбросить</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="block2" id='preimuchestvo'>
            <p class="name2">Наши преимущества</p>
            <div class="block2_1">
                <div class="prime">
                    <p class="name3">Питание</p>
                    <p class="text3">Вкусные обеды и ужины в самых культовых ресторанах города</p>
                </div>
                <div class="prime">
                    <p class="name3">Трансфер</p>
                    <p class="text3">Удобный и комфортный транспорт</p>
                </div>
                <div class="prime">
                    <p class="name3">Интересная программа</p>
                    <p class="text3">Вам гарантирован насыщенный и продуктивный день</p>
                </div>
                <div class="prime">
                    <p class="name3">Профессиональные гиды</p>
                    <p class="text3">Увлекательные истории и полное погружение в культуру края</p>
                </div>
            </div>
        </div>
        
        <section class="block3" id="destinations">
            <h2 class="section-title">Наши экспедиции</h2>
            <div class="destinations-container">
                <?php include 'php/card.php'; ?>
            </div>
        </section>
        
        <!-- Модалка 1: Описание тура -->
        <div id="tourModal" class="modal-overlay">
            <div class="modal-content">
                <!-- Содержимое будет динамически вставлено через JavaScript -->
            </div>
        </div>

        <!-- Модалка 2: Форма бронирования -->
        <div id="bookingModal" class="modal-overlay">
            <div class="booking-modal-content">
                <span class="close-x" onclick="closeModal('bookingModal')">&times;</span>
                <h2>Бронирование поездки</h2>
                
                <div class="header-row">
                    <button type="button" class="btn-add" id="add-tourist">+ Добавить туриста</button>
                </div>

                <div class="booking-container">
                    <div id="tourists-list"></div>

                    <div class="payment-info">
                        <h3>Оплата</h3>
                        <p>Тур: <span id="selected-tour-name">«Название»</span></p>
                        <p>Туристов: <span id="tourists-count">0</span></p>
                        <hr>
                        <div class="total-section">
                            <span>Итого:</span>
                            <strong id="total-price">0</strong> ₽
                        </div>
                        <button class="btn-pay" onclick="processPayment()">Оплатить онлайн</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="gids" id='gids'>
            <p class="name4">Наши гиды</p>
            <div class="block_gid">
                <div class="gid1">
                    <img src="img/marina.png" alt="марина">
                    <p class="gid_name">Марина</p>
                    <p class="gid_prof">Историк-краевед</p>
                </div>
                <div class="gid1">
                    <img src="img/nastya.png" alt="настя">
                    <p class="gid_name">Анастасия</p>
                    <p class="gid_prof">Гид-проводник</p>
                </div>
                <div class="gid1">
                    <img src="img/kirill.png" alt="кирилл">
                    <p class="gid_name">Кирилл</p>
                    <p class="gid_prof">Гастрогид</p>
                </div>
                <div class="gid1">
                    <img src="img/oleg.png" alt="олег">
                    <p class="gid_name">Олег</p>
                    <p class="gid_prof">Волонтер</p>
                </div>
            </div>
        </div>
        
        <div class="separator"></div>

        <div class="reklama" id='social'>
            <div class="reklama1">
                <p class="rek_title">Следите за персональными акциями и скидками!</p>
                <p class="rek_text">Подпишитесь на нашу рассылку</p>
                
                <?php if ($subscribe_message && !$show_modal): ?>
                    <div class="subscribe-message <?php echo $subscribe_type; ?>">
                        <?php echo htmlspecialchars($subscribe_message); ?>
                    </div>
                <?php endif; ?>
                
                <div class="forma_email">
                    <form method="POST" action="">
                        <input type="email" name="email" placeholder="Электронная почта" required>
                        <button class="button" type="submit" name="subscribe">Подписаться</button>
                    </form>
                </div>
                
                <p class="small_text">Нажимая «Подписаться», вы даете согласие на получение рекламных и информационных сообщений в соответствии с условиями политики обработки персональных данных</p>
                <img class="rek1" src="img/izh_v_rekl.png" alt="фон">
            </div>
            <div class="reklama1">
                <p class="rek_title">Планируйте путешествие вместе с нами</p>
                <p class="rek_text">Мы в соцсетях</p>
                <div class="rek_ssilki">
                    <div class="rek_block">
                        <img src="img/qr-code.png" alt="куаркод">
                    </div>
                    <div class="rek_block">
                        <img src="img/tg.png" alt="тг">
                    </div>
                    <div class="rek_block">
                        <img src="img/vk.png" alt="вк">
                    </div>
                </div>
                <img class="rek2" src="img/izh_v_rekl2.png" alt="фон">
            </div>
        </div>

        <div class="separator"></div>

        <p class="name2">Путешествуй по Удмуртии вместе с нами!</p>
        <div class="block4">
            <video controls preload="metadata">
                <source src="img/udm.mp4" type="video/mp4">
                Ваш браузер не поддерживает тег video.
            </video>
        </div>

        <div class="shop">
            <p class="name4">Экипировка</p>
            <div class="block_shop">
                <div class="shop1">
                    <p class="tovar">Термос t2</p>
                    <img src="img/termos.png" alt="термос">
                    <p class="price">899₽</p>
                    <button class="button_shop">Купить</button>
                </div>
                <div class="shop1">
                    <p class="tovar">Рюкзак t2</p>
                    <img src="img/rukzak.png" alt="термос">
                    <p class="price">1899₽</p>
                    <button class="button_shop">Купить</button>
                </div>
                <div class="shop1">
                    <p class="tovar">Шапка t2</p>
                    <img src="img/shapka.png" alt="термос">
                    <p class="price">1299₽</p>
                    <button class="button_shop">Купить</button>
                </div>
                <div class="shop1">
                    <p class="tovar">Мешок t2</p>
                    <img src="img/meshok.png" alt="термос">
                    <p class="price">1499₽</p>
                    <button class="button_shop">Купить</button>
                </div>
                <div class="shop1">
                    <p class="tovar">Сумка t2</p>
                    <img src="img/sumka.png" alt="термос">
                    <p class="price">2499₽</p>
                    <button class="button_shop">Купить</button>
                </div>
                <div class="shop1">
                    <p class="tovar">Свитшот t2</p>
                    <img src="img/svitshot.png" alt="термос">
                    <p class="price">3499₽</p>
                    <button class="button_shop">Купить</button>
                </div>
            </div>
        </div>

        <div id="map-container" id='map'>
            <h2 class="map-title">Маршруты на карте</h2>
            <div id="map"></div>
        </div>
    </main>
    
    <footer>
        <div class="title1">УДМУРТ<a class="pink">иЯ</a>&nbsp;с&nbsp;</div>
        <img class="logo" src="img/logo1.svg" alt="логотип">
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

    <!-- Модальное окно подписки -->
    <div id="subscribeModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeSubscribeModal()">&times;</span>
            <div class="modal-icon">✉️</div>
            <div class="modal-title">Спасибо за подписку!</div>
            <div class="modal-text">
                Письмо отправлено на вашу почту.<br>
                Оставайтесь на связи!
            </div>
            <button class="modal-btn" onclick="closeSubscribeModal()">Закрыть</button>
        </div>
    </div>

    <script src="https://api-maps.yandex.ru/2.1/?apikey=YOUR_YANDEX_MAPS_API_KEY&lang=ru_RU"></script>
    <script>
        // ========== ГЛОБАЛЬНЫЕ ПЕРЕМЕННЫЕ ==========
        window.baseTourPrice = 0;
        window.currentTourName = '';

        // Функции для модального окна подписки
        function showSubscribeModal() {
            document.getElementById('subscribeModal').style.display = 'flex';
        }

        function closeSubscribeModal() {
            document.getElementById('subscribeModal').style.display = 'none';
        }

        <?php if ($show_modal): ?>
        showSubscribeModal();
        <?php endif; ?>

        // Функции для карты
        function hideBalloon() {
            var balloon = document.getElementById('customBalloon');
            if (balloon) balloon.style.display = 'none';
        }

        var globalMap = null;

        ymaps.ready(function () {
            globalMap = new ymaps.Map("map", {
                center: [56.85, 53.2],
                zoom: 8,
                controls: ["zoomControl", "fullscreenControl"]
            });

            var locations = [
                { name: "Ижевск", coords: [56.8528, 53.2119], cardId: "card-1" },
                { name: "Игра", coords: [57.5564, 53.0691], cardId: "card-2" },
                { name: "Воткинск", coords: [57.0546, 54.0078], cardId: "card-3" },
                { name: "Сарапул", coords: [56.4666, 53.8001], cardId: "card-4" },
                { name: "Иднакар", coords: [58.1553, 52.6323], cardId: "card-5" },
                { name: "Лудорвай", coords: [56.8226, 53.3683], cardId: "card-6" },
                { name: "Нечкинский парк", coords: [56.6858, 53.7869], cardId: "card-7" },
                { name: "Книга природы", coords: [57.5730, 52.3423], cardId: "card-8" },
                { name: "Глазов", coords: [58.1356, 52.6514], cardId: "card-9" },
                { name: "Ува", coords: [56.9873, 52.1309], cardId: "card-10" },
                { name: "Байгурезь", coords: [57.651844, 53.758439], cardId: "card-13" },
                { name: "Кездурский водопад", coords: [57.836129, 53.679913], cardId: "card-14" }
            ];

            locations.forEach(function (loc) {
                var placemark = new ymaps.Placemark(loc.coords, {
                    hintContent: loc.name
                }, {
                    preset: 'islands#redIcon',
                    iconColor: '#c72a2a'
                });

                placemark.events.add('click', function () {
                    var card = document.getElementById(loc.cardId);
                    if (card) {
                        openTourDetails(loc.cardId);
                    }
                });

                globalMap.geoObjects.add(placemark);
            });
        });

        // ========== ОСНОВНЫЕ ФУНКЦИИ ДЛЯ МОДАЛЬНЫХ ОКОН ==========

        // 1. ОТКРЫТИЕ ОПИСАНИЯ ТУРА С СЛАЙДЕРОМ
        function openTourDetails(cardId) {
            const card = document.getElementById(cardId);
            if (!card) return;

            const title = card.getAttribute('data-name') || 'Тур';
            const description = card.getAttribute('data-desc') || '';
            const price = card.getAttribute('data-price') || '0 ₽';
            const rating = parseFloat(card.getAttribute('data-rating')) || 0;
            const reviews = card.getAttribute('data-reviews') || '0';
            const duration = card.getAttribute('data-duration') || 'Не указано';
            const categoryName = card.getAttribute('data-category-name') || 'Не указано';
            const typeName = card.getAttribute('data-type-name') || 'Не указано';
            const budgetName = card.getAttribute('data-budget-name') || 'Не указано';
            
            let images = [];
            const imagesJson = card.getAttribute('data-images');
            if (imagesJson) {
                try {
                    images = JSON.parse(imagesJson);
                } catch(e) {
                    const img = card.querySelector('img');
                    if (img) images.push(img.src);
                }
            }
            
            if (images.length === 0) {
                const img = card.querySelector('img');
                if (img) images.push(img.src);
            }
            
            window.baseTourPrice = parseInt(price.replace(/[^\d]/g, '')) || 0;
            window.currentTourName = title;
            
            function getStarsHTML(rating) {
                let stars = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= rating) {
                        stars += '<span class="star filled">★</span>';
                    } else if (i - 0.5 <= rating) {
                        stars += '<span class="star half">½</span>';
                    } else {
                        stars += '<span class="star">☆</span>';
                    }
                }
                return stars;
            }
            
            let sliderHTML = '';
            if (images.length > 0) {
                sliderHTML = `
                    <div class="modal-slider-container">
                        <div class="modal-slider" id="modalSlider">
                            ${images.map(img => `
                                <div class="modal-slide">
                                    <img src="${img}" alt="${title}" onerror="this.src='img/default-route.jpg'">
                                </div>
                            `).join('')}
                        </div>
                        ${images.length > 1 ? `
                            <button class="modal-slider-btn modal-slider-prev" onclick="modalSlidePrev()">‹</button>
                            <button class="modal-slider-btn modal-slider-next" onclick="modalSlideNext()">›</button>
                            <div class="modal-slider-dots" id="modalSliderDots">
                                ${images.map((_, i) => `<div class="modal-slider-dot ${i === 0 ? 'active' : ''}" onclick="modalGoToSlide(${i})"></div>`).join('')}
                            </div>
                        ` : ''}
                    </div>
                `;
            }
            
            const modalHtml = `
                ${sliderHTML}
                <div class="modal-info">
                    <h2>${title}</h2>
                    <div class="modal-rating">
                        ${getStarsHTML(rating)}
                        <span class="reviews">(${reviews} отзывов)</span>
                    </div>
                    <p class="modal-description">${description}</p>
                    <div class="modal-details">
                        <div class="modal-details-row">
                            <span class="modal-details-label">Длительность:</span>
                            <span class="modal-details-value">${duration}</span>
                        </div>
                        <div class="modal-details-row">
                            <span class="modal-details-label">Категория:</span>
                            <span class="modal-details-value">${categoryName}</span>
                        </div>
                        <div class="modal-details-row">
                            <span class="modal-details-label">Тип:</span>
                            <span class="modal-details-value">${typeName}</span>
                        </div>
                        <div class="modal-details-row">
                            <span class="modal-details-label">Бюджет:</span>
                            <span class="modal-details-value">${budgetName}</span>
                        </div>
                    </div>
                    <div class="modal-price">
                        ${price} <small>за человека</small>
                    </div>
                    <button class="btn-book-start" id="bookTourBtn">Забронировать тур</button>
                    <button class="close-btn" onclick="closeModal('tourModal')">Закрыть</button>
                </div>
            `;
            
            const modalContent = document.querySelector('#tourModal .modal-content');
            if (modalContent) {
                modalContent.innerHTML = modalHtml;
                
                const bookBtn = document.getElementById('bookTourBtn');
                if (bookBtn) {
                    bookBtn.onclick = function() {
                        openBookingForm();
                    };
                }
            }
            
            document.getElementById('tourModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            setTimeout(function() {
                initModalSlider();
            }, 100);
        }

        // Функции для слайдера
        let modalCurrentSlide = 0;
        let modalTotalSlides = 0;

        function initModalSlider() {
            const slider = document.getElementById('modalSlider');
            if (!slider) return;
            
            modalTotalSlides = slider.children.length;
            if (modalTotalSlides <= 1) return;
            
            slider.addEventListener('scroll', function() {
                const slideWidth = slider.clientWidth;
                const newIndex = Math.round(slider.scrollLeft / slideWidth);
                if (newIndex !== modalCurrentSlide && !isNaN(newIndex)) {
                    modalCurrentSlide = newIndex;
                    updateModalDots();
                }
            });
        }

        function updateModalDots() {
            const dots = document.querySelectorAll('#modalSliderDots .modal-slider-dot');
            dots.forEach((dot, i) => {
                if (i === modalCurrentSlide) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }

        function modalSlidePrev() {
            if (modalCurrentSlide > 0) {
                modalGoToSlide(modalCurrentSlide - 1);
            }
        }

        function modalSlideNext() {
            if (modalCurrentSlide < modalTotalSlides - 1) {
                modalGoToSlide(modalCurrentSlide + 1);
            }
        }

        function modalGoToSlide(index) {
            const slider = document.getElementById('modalSlider');
            if (!slider) return;
            
            modalCurrentSlide = index;
            const slideWidth = slider.clientWidth;
            slider.scrollTo({ left: index * slideWidth, behavior: 'smooth' });
            updateModalDots();
        }

        // 2. ПЕРЕХОД К БРОНИРОВАНИЮ
        function openBookingForm() {
            const tourModal = document.getElementById('tourModal');
            if (tourModal) {
                tourModal.style.display = 'none';
            }
            
            let tourName = window.currentTourName;
            if (!tourName) {
                const titleElement = document.querySelector('#tourModal .modal-info h2');
                if (titleElement) {
                    tourName = titleElement.innerText;
                } else {
                    tourName = 'Тур';
                }
            }
            
            const list = document.getElementById('tourists-list');
            if (list) list.innerHTML = '';
            
            const nameSpan = document.getElementById('selected-tour-name');
            if (nameSpan) nameSpan.innerText = tourName;
            
            addTourist();
            
            const bookingModal = document.getElementById('bookingModal');
            if (bookingModal) {
                bookingModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        // 3. ЗАКРЫТИЕ МОДАЛОК
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }

        // 4. ДОБАВЛЕНИЕ ТУРИСТА
        function addTourist() {
            const list = document.getElementById('tourists-list');
            if (!list) return;
            
            const touristCount = list.children.length + 1;
            
            const touristCard = document.createElement('div');
            touristCard.className = 'tourist-form';
            touristCard.innerHTML = `
                <div class="tourist-header">
                    <span>Турист ${touristCount}</span>
                    <span class="delete-tourist" onclick="removeTourist(this)">❌</span>
                </div>
                <div class="inputs-row">
                    <input type="text" placeholder="Фамилия" required>
                    <input type="text" placeholder="Имя" required>
                    <input type="text" placeholder="Отчество">
                    <input type="tel" placeholder="Телефон" required>
                    <input type="email" placeholder="Почта" required>
                </div>
                <div class="extra-services">
                    <p>Дополнительные услуги:</p>
                    <label><input type="checkbox" class="service-check" data-price="499" onchange="updateTotal()"> Страховка (+499 ₽)</label><br>
                    <label><input type="checkbox" class="service-check" data-price="299" onchange="updateTotal()"> Питание (+299 ₽)</label><br>
                    <label><input type="checkbox" class="service-check" data-price="599" onchange="updateTotal()"> Снаряжение (+599 ₽)</label>
                </div>
            `;
            
            list.appendChild(touristCard);
            updateTotal();
        }

        // 5. УДАЛЕНИЕ ТУРИСТА
        function removeTourist(btn) {
            const cards = document.querySelectorAll('.tourist-form');
            if (cards.length > 1) {
                btn.closest('.tourist-form').remove();
                reindexTourists();
                updateTotal();
            } else {
                alert("Должен быть хотя бы один турист");
            }
        }

        // 6. ПЕРЕИНДЕКСАЦИЯ
        function reindexTourists() {
            const cards = document.querySelectorAll('.tourist-form');
            cards.forEach((card, index) => {
                const span = card.querySelector('.tourist-header span:first-child');
                if (span) span.innerText = `Турист ${index + 1}`;
            });
        }

        // 7. ОБНОВЛЕНИЕ ИТОГА
        function updateTotal() {
            const cards = document.querySelectorAll('.tourist-form');
            const priceElement = document.getElementById('total-price');
            const countElement = document.getElementById('tourists-count');
            
            if (!priceElement || !countElement) return;

            let total = 0;
            
            cards.forEach(card => {
                total += window.baseTourPrice;
                const checks = card.querySelectorAll('.service-check:checked');
                checks.forEach(check => {
                    total += parseInt(check.getAttribute('data-price')) || 0;
                });
            });

            priceElement.innerText = total.toLocaleString();
            countElement.innerText = cards.length;
        }

        // 8. ОПЛАТА
        function processPayment() {
            const touristsCount = document.querySelectorAll('.tourist-form').length;
            const totalPrice = document.getElementById('total-price').innerText;
            const tourName = document.getElementById('selected-tour-name').innerText;
            
            let allFilled = true;
            let missingFields = [];
            
            document.querySelectorAll('.tourist-form').forEach((form, index) => {
                const lastName = form.querySelector('input[placeholder="Фамилия"]').value;
                const firstName = form.querySelector('input[placeholder="Имя"]').value;
                const phone = form.querySelector('input[placeholder="Телефон"]').value;
                const email = form.querySelector('input[placeholder="Почта"]').value;
                
                if (!lastName) missingFields.push(`Турист ${index + 1}: Фамилия`);
                if (!firstName) missingFields.push(`Турист ${index + 1}: Имя`);
                if (!phone) missingFields.push(`Турист ${index + 1}: Телефон`);
                if (!email) missingFields.push(`Турист ${index + 1}: Почта`);
            });
            
            if (missingFields.length > 0) {
                alert(`Заполните следующие поля:\n${missingFields.join('\n')}`);
                return;
            }
            
            alert(`✅ Бронирование оформлено!\n\nТур: ${tourName}\nТуристов: ${touristsCount}\nСумма: ${totalPrice} ₽\n\nСпасибо за покупку!`);
            closeModal('bookingModal');
        }

        // 9. ФИЛЬТРАЦИЯ
        function resetFilters() {
            document.querySelectorAll('.filter-select').forEach(select => select.value = '');
            const startDate = document.querySelector('input[name="start_date"]');
            const endDate = document.querySelector('input[name="end_date"]');
            if (startDate) startDate.value = '';
            if (endDate) endDate.value = '';
            filterCards();
        }

        function filterCards() {
            const company = document.querySelector('select[name="company"]')?.value || '';
            const transport = document.querySelector('select[name="transport"]')?.value || '';
            const budget = document.querySelector('select[name="budget"]')?.value || '';
            const interests = document.querySelector('select[name="interests"]')?.value || '';
            const additional = document.querySelector('select[name="additional"]')?.value || '';

            const cards = document.querySelectorAll('.floating-card');
            let visibleCount = 0;

            cards.forEach(card => {
                let show = true;

                if (budget && budget !== 'everywhere') {
                    const cardBudget = card.getAttribute('data-budget');
                    if (cardBudget !== budget) show = false;
                }

                if (show && transport) {
                    const cardType = card.getAttribute('data-type');
                    if (transport === 'own_car' && cardType !== 'car') show = false;
                    if (transport === 'no_car' && (cardType !== 'bus' && cardType !== 'pedestrian')) show = false;
                }

                if (show && interests) {
                    const cardCategory = card.getAttribute('data-category');
                    if (cardCategory !== interests) show = false;
                }

                if (show && additional) {
                    const cardAnimals = card.getAttribute('data-animals');
                    if (cardAnimals !== additional) show = false;
                }

                if (show && company) {
                    const cardChild = card.getAttribute('data-child');
                    if (company === 'family_with_children' && cardChild !== 'yes') show = false;
                }

                if (show) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            let noResultsMsg = document.querySelector('.no-results');
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.className = 'no-results';
                document.querySelector('.destinations-container').appendChild(noResultsMsg);
            }

            if (visibleCount === 0) {
                noResultsMsg.style.display = 'block';
                noResultsMsg.innerHTML = '😔 По вашему запросу ничего не найдено. Попробуйте изменить параметры фильтра.';
            } else {
                noResultsMsg.style.display = 'none';
            }
        }

        // 10. ИНИЦИАЛИЗАЦИЯ
        document.addEventListener('DOMContentLoaded', function() {
            const addBtn = document.getElementById('add-tourist');
            if (addBtn) {
                addBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    addTourist();
                });
            }
            
            const filterForm = document.querySelector('.filter-form');
            if (filterForm) {
                filterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    filterCards();
                });
            }
            
            document.querySelectorAll('.filter-select').forEach(select => {
                select.addEventListener('change', function() {
                    filterCards();
                });
            });
            
            document.querySelectorAll('.modal-overlay').forEach(overlay => {
                overlay.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }
                });
            });
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.modal-overlay').forEach(modal => {
                        if (modal.style.display === 'flex') {
                            modal.style.display = 'none';
                            document.body.style.overflow = 'auto';
                        }
                    });
                }
            });
        });

        // Делаем функции глобальными
        window.openTourDetails = openTourDetails;
        window.openBookingForm = openBookingForm;
        window.closeModal = closeModal;
        window.addTourist = addTourist;
        window.removeTourist = removeTourist;
        window.updateTotal = updateTotal;
        window.processPayment = processPayment;
        window.resetFilters = resetFilters;
        window.filterCards = filterCards;
        window.hideBalloon = hideBalloon;
        window.modalSlidePrev = modalSlidePrev;
        window.modalSlideNext = modalSlideNext;
        window.modalGoToSlide = modalGoToSlide;
        window.showSubscribeModal = showSubscribeModal;
        window.closeSubscribeModal = closeSubscribeModal;
    </script>
</body>

</html>