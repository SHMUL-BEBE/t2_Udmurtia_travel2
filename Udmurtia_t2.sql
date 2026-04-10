-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 10 2026 г., 15:05
-- Версия сервера: 10.3.36-MariaDB
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `Udmurtia_t2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `card_route`
--

CREATE TABLE `card_route` (
  `id` int(255) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `Duration` enum('1 день','2 дня','3 дня','более 3 дней') COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` set('Пеший','Автомобильный','Автобусный') COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` set('Этнографический','Исторический','Природный','Гастрономический','Волонтерский','Культурно-познавательный','Экологический') COLLATE utf8mb4_unicode_ci NOT NULL,
  `budget` enum('Эконом','Средний') COLLATE utf8mb4_unicode_ci NOT NULL,
  `child` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `animals` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `rating` decimal(2,1) NOT NULL DEFAULT 0.0,
  `reviews_count` int(11) NOT NULL DEFAULT 0,
  `img_1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `img_2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `img_3` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `card_route`
--

INSERT INTO `card_route` (`id`, `name`, `description`, `Duration`, `type`, `category`, `budget`, `child`, `animals`, `price`, `rating`, `reviews_count`, `img_1`, `img_2`, `img_3`) VALUES
(1, '«В Игру на выходные»', 'Новый автомобильный маршрут, стартующий в Ижевске и уходящий на север республики в Игринский район. Знакомит с историей Сибирского тракта, культурой удмуртского народа и современными арт-объектами.', '2 дня', 'Автомобильный', 'Исторический,Культурно-познавательный', 'Средний', 'Да', 'Да (в машине, на некоторых открытых площадках)', 2499, '0.0', 0, 'route_photo/igra/1.jpg', 'route_photo/igra/2.jpg', 'route_photo/igra/3.jpeg'),
(2, '«Ожерелье Камы» (межрегиональный)', 'Крупный межрегиональный автомаршрут, объединяющий достопримечательности Удмуртии и Башкортостана вдоль живописных берегов Камы. Путешествие охватывает ключевые города и культурные центры двух республик.', 'более 3 дней', 'Автомобильный', 'Исторический,Культурно-познавательный', 'Средний', 'Да', 'Да', 2499, '0.0', 0, 'route_photo/kama/1.jpg', 'route_photo/kama/2.jpg', 'route_photo/kama/3.jpg'),
(3, '«Влюбиться в Удмуртию!»', 'Трехдневный гастрономический тур по главным городам республики. Помимо осмотра достопримечательностей, предлагает мастер-классы по приготовлению национальных блюд: пельменей, перепечей и табаней.', '3 дня', 'Автобусный', 'Гастрономический,Культурно-познавательный', 'Средний', 'Да, с 7 лет', 'Нет', 2499, '0.0', 0, 'route_photo/love/1.jpg', 'route_photo/love/2.jpg', 'route_photo/love/3.jpg'),
(4, 'Древнее городище «Иднакар»', 'Посещение «Удмуртской Трои» — археологического комплекса IX-XIII веков на горе Солдырь. С вершины открывается панорамный вид, а в музее можно увидеть реконструкцию быта древних жителей.', '1 день', 'Пеший', 'Исторический', 'Эконом', 'Да (школьный возраст)', 'Да (на территории)', 2499, '0.0', 0, 'route_photo/idnakar/1.jpg', 'route_photo/idnakar/2.jpg', 'route_photo/idnakar/3.jpg'),
(5, 'Архитектурно-этнографический музей «Лудорвай»', 'Музей удмуртского быта и зодчества под открытым небом в 10 км от Ижевска. Позволяет погрузиться в традиционную деревенскую жизнь и попробовать блюда национальной кухни.', '1 день', 'Автомобильный', 'Этнографический', 'Эконом', 'Да', 'Да (на территории)', 2499, '0.0', 0, 'route_photo/ludorvai/1.jpg', 'route_photo/ludorvai/2.jpg', 'route_photo/ludorvai/3.jpg'),
(6, 'Интерактивная экотропа «Наедине с лесом» (Нечкинский парк)', 'Инклюзивный экологический маршрут в национальном парке «Нечкинский». Оборудован деревянными настилами и информационными стендами с персонажами удмуртской мифологии, доступен для людей с ОВЗ.', '1 день', 'Автомобильный', 'Природный', 'Эконом', 'Да (включая дошкольников)', 'Да', 2499, '0.0', 0, 'route_photo/nechkino/1.jpg', 'route_photo/nechkino/2.jpg', 'route_photo/nechkino/3.jpg'),
(7, 'Экотропа «Книга природы»', 'Пятиметровый пеший маршрут по Кокманскому заказнику в Красногорском районе. Уникальная возможность увидеть редкие растения верховых болот и многовековые лиственницы.', '1 день', 'Пеший', 'Природный', 'Эконом', 'Да (рекомендуется с 6 лет)', 'Нет (заказник)', 2499, '0.0', 0, 'route_photo/ecotropa/1.jpg', 'route_photo/ecotropa/2.jpg', 'route_photo/ecotropa/3.jpg'),
(8, '«Купеческий Сарапул»', 'Путешествие в город на Каме, славящийся своей купеческой архитектурой XIX века в стиле модерн. Экскурсия включает прогулку по набережной и посещение историко-краеведческого музея.', '1 день', 'Пеший', 'Исторический', 'Эконом', 'Да', 'Да', 2499, '0.0', 0, 'route_photo/sarapul/1.jpg', 'route_photo/sarapul/2.jpg', 'route_photo/sarapul/3.jpg'),
(9, 'Музей-усадьба П. И. Чайковского (Воткинск)', 'Мемориальный комплекс, где родился великий композитор. Воссоздает атмосферу дворянского быта XIX века и включает прогулку по старинному саду.', '1 день', 'Пеший', 'Исторический', 'Эконом', 'Да', 'Да (на территории)', 2499, '0.0', 0, 'route_photo/votkinsk/1.jpg', 'route_photo/votkinsk/2.jpg', 'route_photo/votkinsk/3.jpg'),
(10, '«Северная столица» — город Глазов', 'Пешеходный маршрут по самому северному городу Удмуртии с богатой историей. Включает посещение Спасо-Преображенского собора и арт-объекта «Место памяти — место силы».', '1 день', 'Пеший', 'Исторический', 'Эконом', 'Да', 'Да (на улице)', 2499, '0.0', 0, 'route_photo/glazov/1.jpg', 'route_photo/glazov/2.jpg', 'route_photo/glazov/3.jpg'),
(11, '«Помощь в деревне Сеп» — волонтерский маршрут', 'Поездка для помощи и развития уникальной деревни Сеп — известной своим Народным музеем исчезнувших деревень и необычной автобусной остановкой. Волонтеры участвуют в создании и поддержке местного культурного центра.', 'более 3 дней', 'Автомобильный', 'Волонтерский', 'Эконом', 'Да (с 14 лет)', 'Да', 2499, '0.0', 0, 'route_photo/sep/1.jpg', 'route_photo/sep/2.jpg', ''),
(12, '«Посади лес в Нечкинском парке» — волонтерский маршрут', 'Участие в экологических акциях по восстановлению лесов на территории национального парка «Нечкинский». Волонтеры помогают высаживать саженцы сосны на местах лесных пожаров или санитарных вырубок.', '1 день', 'Пеший', 'Волонтерский', 'Эконом', 'Да, с 14 лет (присутствие взрослых обязательно)', 'Нет (территория национального парка)', 2499, '0.0', 0, 'route_photo/forest/1.jpg', 'route_photo/forest/2.jpg', '');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL COMMENT 'код пользователя',
  `fio` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ФИО пользователя',
  `phone` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'телефон',
  `email` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'адрес электронной почты',
  `login` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'логин',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'пароль',
  `status` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user' COMMENT 'статус в системе'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id_user`, `fio`, `phone`, `email`, `login`, `password`, `status`) VALUES
(1, 'Иванов Иван Иванович', '+79001234567', 'ivanov@example.com', 'ivanovii', 'qwerty123', 'user'),
(2, 'Петров Петр Петрович', '+79007654321', 'petrov@example.com', 'petrop', 'asdfg456', 'user'),
(3, 'Сидорова Мария Николаевна', '+79009876543', 'sidorova@example.com', 'sidorovamn', 'zxcvb789', 'user'),
(4, 'Смирнов Алексей Михайлович', '+79001112233', 'smirnov@example.com', 'admin', 'gruzovik2024', 'admin'),
(5, 'Козлова Екатерина Александровна', '+79004445566', 'kozlova@example.com', 'kozlovaea', 'lkjhg456', 'user'),
(6, 'Кузнецов Александр Владимирович', '+79007778899', 'kuznecov@example.com', 'kuznecovav', 'mnbol789', 'user'),
(7, 'Орлова Юлия Денисовна', '+79001237890', 'orlova@example.com', 'orlovajd', 'cvbnm123', 'user'),
(8, 'Миронова Виктория Анатольевна', '+79004561230', 'mironova@example.com', 'mironovava', 'asdfgh456', 'user'),
(9, 'Гришин Максим Евгеньевич', '+79007894560', 'grishin@example.com', 'grishime', 'qwert789', 'user'),
(10, 'Лебедева Дарья Константиновна', '+79001230987', 'lebedeva@example.com', 'lebedevadk', 'yuiop123', 'user'),
(11, 'Фролов Павел Сергеевич', '+79007651234', 'frolov@example.com', 'frolovps', 'hgfds456', 'user'),
(12, 'Цветкова Полина Витальевна', '+79009874561', 'cvetkova@example.com', 'cvetkovapv', 'zaqwsx789', 'user'),
(13, 'Некрасов Роман Леонидович', '+79001115566', 'nekrason@example.com', 'nekrasornl', 'qazwsx123', 'user'),
(14, 'Чернышова Анастасия Павловна', '+79004448899', 'cherhnysheva@example.com', 'cherhnyshevap', 'edcrfv456', 'user'),
(15, 'Демидов Евгений Юрьевич', '+79007771234', 'demidov@example.com', 'demidoje', 'ujmkiol789', 'user'),
(16, '', '', '', '', '', 'user'),
(17, 'Фадеева МИлолика андреевна', '799999999999', 'milolikafad@mail.ru', 'tgyhbu', '$2y$10$lHsx9uWlAo84XJUf7MZrIOdhfV.YVp1sDExXHSEei/0zFiowl1Fme', 'user');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `card_route`
--
ALTER TABLE `card_route`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `card_route`
--
ALTER TABLE `card_route`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT COMMENT 'код пользователя', AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
