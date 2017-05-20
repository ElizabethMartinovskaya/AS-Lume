-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Дек 15 2016 г., 17:59
-- Версия сервера: 5.5.23
-- Версия PHP: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `lume_db`
--

DELIMITER $$
--
-- Функции
--
CREATE DEFINER=`root`@`localhost` FUNCTION `get_all_stocks_company` (`id_user` INT) RETURNS INT(11) NO SQL
RETURN(SELECT count(`stock`.`id_stock`)
FROM stock
WHERE id_user = `stock`.`id_author`)$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_all_stocks_users` (`id_user` INT) RETURNS INT(11) NO SQL
return (SELECT count(`user_and_stock`.`id_user`) as 'stock_count' 
from user_and_stock
WHERE user_and_stock.id_user = id_user)$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_download_photos_company` (`id_user` INT) RETURNS INT(11) NO SQL
RETURN(SELECT count(`image`.`id_image`)
FROM `image`
WHERE id_user=`image`.`id_author`)$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_do_stocks_company` (`id_user` INT) RETURNS INT(11) NO SQL
RETURN (SELECT count(`stock`.`id_stock`)
FROM  `stock`
WHERE id_user=`stock`.`id_author`
AND `stock`.`date_end`<NOW())$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_do_stock_users` (`id_user` INT) RETURNS INT(11) NO SQL
RETURN (SELECT count(`stock`.`id_stock`)
FROM  `stock`, `user_and_stock`
WHERE id_user=`user_and_stock`.`id_user`
AND `user_and_stock`.`stock_progress`=1
AND `user_and_stock`.`id_stock`=`stock`.`id_stock`)$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_now_stocks_company` (`id_user` INT) RETURNS INT(11) NO SQL
RETURN (SELECT count(`stock`.`id_stock`)
FROM `stock`
WHERE id_user = `stock`.`id_author`
AND `stock`.`date_begin` < NOW()
AND stock.date_end > NOW())$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_now_stocks_users` (`id_user` INT) RETURNS INT(11) NO SQL
RETURN (SELECT count(`stock`.`id_stock`)
FROM `user_and_stock`,`stock`
WHERE `user_and_stock`.`stock_progress`=0
AND `user_and_stock`.`id_user` = id_user
AND `user_and_stock`.`id_stock`=`stock`.`id_stock`
AND `stock`.`date_begin` < NOW()
AND stock.date_end > NOW())$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_participants_stocks` (`id_stock` INT) RETURNS INT(11) NO SQL
RETURN(SELECT count(`user_and_stock`.`id_User_and_Stock`)
FROM `user_and_stock`, `stock`
WHERE id_stock=`user_and_stock`.`id_user`
AND `user_and_stock`.`id_stock`=`stock`.`id_stock`)$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_scan_photos_users` (`id_user` INT) RETURNS INT(11) NO SQL
RETURN(SELECT count(`image`.`id_image`)
FROM `history`,`image`
WHERE id_user=`history`.`id_user`
AND `history`.`id_image`=`image`.`id_image`)$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `admin_users`
--

CREATE TABLE `admin_users` (
  `id_Admin_users` int(11) NOT NULL,
  `ausers_name` varchar(255) NOT NULL COMMENT 'Имя',
  `ausers_login` varchar(255) NOT NULL COMMENT 'Логин',
  `ausers_password` varchar(255) CHARACTER SET utf8mb4 NOT NULL COMMENT 'Пароль',
  `last_visit` datetime NOT NULL COMMENT 'Последнее посещение'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `admin_users`
--

INSERT INTO `admin_users` (`id_Admin_users`, `ausers_name`, `ausers_login`, `ausers_password`, `last_visit`) VALUES
(1, 'Lisa', 'adminLume', '7852', '2016-12-13 20:03:15'),
(2, 'Yan', 'admin', 'admin', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `avatars`
--

CREATE TABLE `avatars` (
  `id_Avatars` int(11) NOT NULL,
  `url_avatar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `avatars`
--

INSERT INTO `avatars` (`id_Avatars`, `url_avatar`) VALUES
(1, 'avatars/mts.png'),
(2, 'avatars/no-photo.png');

-- --------------------------------------------------------

--
-- Структура таблицы `brand`
--

CREATE TABLE `brand` (
  `id_brand` int(11) UNSIGNED NOT NULL,
  `brand_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `brand`
--

INSERT INTO `brand` (`id_brand`, `brand_name`) VALUES
(1, 'Apple'),
(2, 'Samsung'),
(3, 'LG');

-- --------------------------------------------------------

--
-- Структура таблицы `category_image`
--

CREATE TABLE `category_image` (
  `id_category_image` int(11) UNSIGNED NOT NULL,
  `category_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `category_image`
--

INSERT INTO `category_image` (`id_category_image`, `category_image`) VALUES
(1, 'Люди'),
(2, 'Здания'),
(3, 'Города'),
(4, 'Природа'),
(5, 'Эмблемы и логотипы'),
(6, 'Геральдика'),
(7, 'Награды'),
(8, 'Мероприятия'),
(9, 'Животные');

-- --------------------------------------------------------

--
-- Структура таблицы `city`
--

CREATE TABLE `city` (
  `id_city` int(11) UNSIGNED NOT NULL,
  `city_name` varchar(255) NOT NULL,
  `id_country` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `city`
--

INSERT INTO `city` (`id_city`, `city_name`, `id_country`) VALUES
(1, 'Минск', 1),
(2, 'Брест', 1),
(3, 'София', 2),
(5, 'Могилев', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `country`
--

CREATE TABLE `country` (
  `id_country` int(11) UNSIGNED NOT NULL,
  `country_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `country`
--

INSERT INTO `country` (`id_country`, `country_name`) VALUES
(1, 'Беларусь'),
(2, 'Болгария');

-- --------------------------------------------------------

--
-- Структура таблицы `data`
--

CREATE TABLE `data` (
  `id_Data` int(11) UNSIGNED NOT NULL,
  `Source_id_source` int(11) UNSIGNED NOT NULL,
  `Type_id_Type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `data`
--

INSERT INTO `data` (`id_Data`, `Source_id_source`, `Type_id_Type`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `device_info`
--

CREATE TABLE `device_info` (
  `id_device_info` int(11) UNSIGNED NOT NULL,
  `id_os` int(11) UNSIGNED NOT NULL DEFAULT '1',
  `id_model` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Информация об устройстве.';

--
-- Дамп данных таблицы `device_info`
--

INSERT INTO `device_info` (`id_device_info`, `id_os`, `id_model`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `event`
--

CREATE TABLE `event` (
  `id_event` int(11) UNSIGNED NOT NULL,
  `id_data` int(11) UNSIGNED NOT NULL COMMENT 'Событие.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Событие, привязываемое к изображению.';

--
-- Дамп данных таблицы `event`
--

INSERT INTO `event` (`id_event`, `id_data`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `history`
--

CREATE TABLE `history` (
  `id_History` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_image` int(10) UNSIGNED NOT NULL,
  `history_N` double DEFAULT NULL,
  `history_E` double DEFAULT NULL,
  `Date_scaning` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `history`
--

INSERT INTO `history` (`id_History`, `id_user`, `id_image`, `history_N`, `history_E`, `Date_scaning`) VALUES
(1, 1, 1, NULL, NULL, '2016-11-08'),
(2, 1, 2, NULL, NULL, '2016-11-19');

-- --------------------------------------------------------

--
-- Структура таблицы `image`
--

CREATE TABLE `image` (
  `id_image` int(11) UNSIGNED NOT NULL,
  `id_properties` int(11) UNSIGNED NOT NULL COMMENT 'Свойства изображения.',
  `id_descriptor` int(11) UNSIGNED DEFAULT NULL COMMENT 'Дескриптор изображения...',
  `id_author` int(11) UNSIGNED NOT NULL COMMENT 'Автор изображения.',
  `publication_date` datetime NOT NULL COMMENT 'Дата загрузки.',
  `id_category_image` int(11) UNSIGNED DEFAULT NULL,
  `id_event` int(11) UNSIGNED DEFAULT NULL,
  `url_image` varchar(255) NOT NULL,
  `description_image` varchar(1000) NOT NULL,
  `image_N` double NOT NULL,
  `image_E` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Изображение, которое является основой для привязки событий.';

--
-- Дамп данных таблицы `image`
--

INSERT INTO `image` (`id_image`, `id_properties`, `id_descriptor`, `id_author`, `publication_date`, `id_category_image`, `id_event`, `url_image`, `description_image`, `image_N`, `image_E`) VALUES
(1, 1, NULL, 2, '2016-11-08 18:00:00', NULL, 1, 'images/1.jpg', 'В нескольких сотнях метров от офиса, на обочине третьего транспортного кольца уже второй месяц меня радует по-настоящему зимний билборд МТС. Горы, снег, сноуборд – теперь все это напоминает об уже закончившемся новогоднем отдыхе, вызывая ностальгию и тоску по веселым дням новогоднего безделья. Рекламируется же специальное предложение для абонентов МТС на период зимних каникул: скидка 50% на исходящие SMS и вызовы со 2-ой минуты во Франции, Швейцарии, Италии, Австрии и Финляндии. Предложение, актуальное для тех, кому посчастливится провести зимний отдых на горнолыжных курортах Европы.', 6543253, 2345678),
(2, 2, NULL, 2, '2016-11-13 00:00:00', NULL, 2, 'images/2.jpg', 'Всем новым абонентам мтс, с тарифом smart, до 4 месяцев бесплатного интернета ', 765432, 582901);

-- --------------------------------------------------------

--
-- Структура таблицы `location`
--

CREATE TABLE `location` (
  `id_location` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `model`
--

CREATE TABLE `model` (
  `id_model` int(11) UNSIGNED NOT NULL,
  `name_model` varchar(255) NOT NULL,
  `id_brand` int(11) UNSIGNED NOT NULL,
  `id_type_of_device` int(11) UNSIGNED NOT NULL,
  `screen_size` double NOT NULL,
  `width_model_screen` int(11) NOT NULL,
  `height_model_screen` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `model`
--

INSERT INTO `model` (`id_model`, `name_model`, `id_brand`, `id_type_of_device`, `screen_size`, `width_model_screen`, `height_model_screen`) VALUES
(1, 'IPhone 7 plus ', 1, 1, 5.5, 1080, 1920),
(2, 'Galaxy Note 7', 2, 1, 5.5, 1080, 1920),
(3, 'G3', 3, 1, 5.5, 1080, 1920),
(4, 'Galaxy S7', 2, 1, 5.5, 1080, 1920);

-- --------------------------------------------------------

--
-- Структура таблицы `os`
--

CREATE TABLE `os` (
  `id_os` int(11) UNSIGNED NOT NULL,
  `os_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `os`
--

INSERT INTO `os` (`id_os`, `os_name`) VALUES
(1, 'Android'),
(2, 'iOS');

-- --------------------------------------------------------

--
-- Структура таблицы `prize`
--

CREATE TABLE `prize` (
  `id_prize` int(11) UNSIGNED NOT NULL,
  `prize_description` varchar(255) NOT NULL,
  `id_type_prize` int(11) UNSIGNED NOT NULL,
  `prize_data` varchar(255) DEFAULT NULL COMMENT 'Ссылка на изображение, штрих-код, qr-код, реферальная ссылка.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Приз за выполнение условий акции.';

--
-- Дамп данных таблицы `prize`
--

INSERT INTO `prize` (`id_prize`, `prize_description`, `id_type_prize`, `prize_data`) VALUES
(1, '1 рубль на счет телефона', 1, NULL),
(2, 'Неделя бесплатного тонинга ', 2, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `properties_images`
--

CREATE TABLE `properties_images` (
  `id_propertie` int(11) UNSIGNED NOT NULL,
  `width_image` int(11) NOT NULL,
  `height_image` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `properties_images`
--

INSERT INTO `properties_images` (`id_propertie`, `width_image`, `height_image`) VALUES
(1, 500, 375),
(2, 1079, 864);

-- --------------------------------------------------------

--
-- Структура таблицы `source`
--

CREATE TABLE `source` (
  `id_source` int(11) UNSIGNED NOT NULL,
  `source` varchar(255) DEFAULT NULL COMMENT 'Например, youtube, mail.ru, server, lume.by.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Источник данных для события. Например, youtube (для видео), обычный сервер. В зависимости от источника происходит загрузка и обработка материала для события.';

--
-- Дамп данных таблицы `source`
--

INSERT INTO `source` (`id_source`, `source`) VALUES
(1, 'youtube.com'),
(2, 'rutube.ru');

-- --------------------------------------------------------

--
-- Структура таблицы `stock`
--

CREATE TABLE `stock` (
  `id_stock` int(11) UNSIGNED NOT NULL,
  `stock_name` varchar(255) NOT NULL COMMENT 'Название акции.',
  `id_author` int(11) UNSIGNED NOT NULL,
  `description_stock` varchar(255) NOT NULL COMMENT 'Краткое описание акции.',
  `date_begin` datetime NOT NULL COMMENT 'Дата и время старта акции.',
  `date_end` datetime DEFAULT NULL COMMENT 'Дата и время окончания акции.',
  `id_stock_type` int(11) UNSIGNED NOT NULL COMMENT 'Тип акции.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Акция, в которой учавствуют пользователи.';

--
-- Дамп данных таблицы `stock`
--

INSERT INTO `stock` (`id_stock`, `stock_name`, `id_author`, `description_stock`, `date_begin`, `date_end`, `id_stock_type`) VALUES
(1, 'Начальный вклад', 2, 'Отсканируй фотографию и получи 1 рубль на телефон', '2016-11-09 00:00:00', '2016-11-23 00:00:00', 1),
(2, 'Бесплатный тонинг', 2, 'Отсканируй фотографии и получи дл 3 месяцев бесплатного интернета и до 500 минут в роуминге   ', '2016-11-09 00:00:00', '2016-11-28 00:00:00', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `stock_and_image`
--

CREATE TABLE `stock_and_image` (
  `id_stock_and_image` int(11) UNSIGNED NOT NULL,
  `id_image` int(11) UNSIGNED NOT NULL,
  `id_stock` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='События типа "акция".';

--
-- Дамп данных таблицы `stock_and_image`
--

INSERT INTO `stock_and_image` (`id_stock_and_image`, `id_image`, `id_stock`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `stock_prize`
--

CREATE TABLE `stock_prize` (
  `id_stock_prize` int(11) NOT NULL,
  `Stock_id_stock` int(11) UNSIGNED NOT NULL,
  `id_prize` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `stock_prize`
--

INSERT INTO `stock_prize` (`id_stock_prize`, `Stock_id_stock`, `id_prize`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `stock_progress`
--

CREATE TABLE `stock_progress` (
  `id_stock_progress` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `id_stock_and_Image` int(11) UNSIGNED NOT NULL,
  `is_scanned` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Успешно отсканированное изображение будет помечено переведено в значение TRUE(1).'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Прогресс участия пользователь в акции. Например, есть акция, в которой необходимо отсканировать 5 изображений. Благодаря данной таблице можно определеить уже отсканированные изображения. Когда акция завершается (либо сама компания устанавливает срок), все записи связанные с конкретной акцией удаляются';

--
-- Дамп данных таблицы `stock_progress`
--

INSERT INTO `stock_progress` (`id_stock_progress`, `id_user`, `id_stock_and_Image`, `is_scanned`) VALUES
(1, 1, 1, 0),
(2, 1, 2, 1),
(3, 1, 3, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `stock_type`
--

CREATE TABLE `stock_type` (
  `id_stock_type` int(11) UNSIGNED NOT NULL,
  `stock_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Тип акции. Продумать варианты...';

--
-- Дамп данных таблицы `stock_type`
--

INSERT INTO `stock_type` (`id_stock_type`, `stock_type`) VALUES
(1, 'Отсканировать n фотографий');

-- --------------------------------------------------------

--
-- Структура таблицы `type_of_data`
--

CREATE TABLE `type_of_data` (
  `id_Type` int(11) NOT NULL,
  `type_data` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `type_of_data`
--

INSERT INTO `type_of_data` (`id_Type`, `type_data`) VALUES
(1, 'video'),
(2, 'animation');

-- --------------------------------------------------------

--
-- Структура таблицы `type_of_device`
--

CREATE TABLE `type_of_device` (
  `id_type_of_device` int(11) UNSIGNED NOT NULL,
  `device_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `type_of_device`
--

INSERT INTO `type_of_device` (`id_type_of_device`, `device_type`) VALUES
(1, 'smartphone');

-- --------------------------------------------------------

--
-- Структура таблицы `type_of_prize`
--

CREATE TABLE `type_of_prize` (
  `id_type` int(11) UNSIGNED NOT NULL,
  `prize_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `type_of_prize`
--

INSERT INTO `type_of_prize` (`id_type`, `prize_type`) VALUES
(1, 'Деньги на телефон '),
(2, 'Бесплатная услуга');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id_user` int(11) UNSIGNED NOT NULL,
  `users_name` varchar(255) NOT NULL COMMENT 'Имя.',
  `users_surname` varchar(255) DEFAULT NULL COMMENT 'Фамилия.',
  `users_login` varchar(255) NOT NULL COMMENT 'Логин.',
  `users_password` varchar(255) NOT NULL COMMENT 'Хеш-значения пароля.',
  `users_email` varchar(255) NOT NULL COMMENT 'E-mail адрес.',
  `type_user` enum('user','company') NOT NULL COMMENT 'Тип пользователя',
  `id_device_info` int(11) UNSIGNED DEFAULT NULL COMMENT 'Информация об используемом устройстве.',
  `id_city` int(11) UNSIGNED NOT NULL COMMENT 'Текущее месторасположение.',
  `phone_number` varchar(45) DEFAULT NULL COMMENT 'Номер телефона.',
  `id_avatars` int(11) NOT NULL DEFAULT '2',
  `users_N` double DEFAULT NULL,
  `users_E` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id_user`, `users_name`, `users_surname`, `users_login`, `users_password`, `users_email`, `type_user`, `id_device_info`, `id_city`, `phone_number`, `id_avatars`, `users_N`, `users_E`) VALUES
(1, 'Ян', 'Германович', 'yan_germanovich', '1234', 'yan.germanovich@mail.ru', 'user', 1, 1, '+375336885282', 2, NULL, NULL),
(2, 'Мтс', NULL, 'mts', '1111', 'mts@mail.ru', 'company', NULL, 2, NULL, 1, NULL, NULL),
(3, 'Елизавета', 'Мартиновская', 'log', 'pass', 'ga@gmail.com', 'user', 1, 1, '+375297582091', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user_and_stock`
--

CREATE TABLE `user_and_stock` (
  `id_User_and_Stock` int(10) UNSIGNED NOT NULL,
  `id_stock` int(10) UNSIGNED NOT NULL COMMENT 'Акция',
  `id_user` int(10) UNSIGNED NOT NULL COMMENT 'В зависимости от типа участник или создатель акции.',
  `stock_progress` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Участники и создатели акций. Таблица отражает участников и создателей акциий.';

--
-- Дамп данных таблицы `user_and_stock`
--

INSERT INTO `user_and_stock` (`id_User_and_Stock`, `id_stock`, `id_user`, `stock_progress`) VALUES
(1, 1, 1, 0),
(3, 2, 3, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id_Admin_users`);

--
-- Индексы таблицы `avatars`
--
ALTER TABLE `avatars`
  ADD PRIMARY KEY (`id_Avatars`);

--
-- Индексы таблицы `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id_brand`);

--
-- Индексы таблицы `category_image`
--
ALTER TABLE `category_image`
  ADD PRIMARY KEY (`id_category_image`);

--
-- Индексы таблицы `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id_city`),
  ADD KEY `City_country_idx` (`id_country`);

--
-- Индексы таблицы `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id_country`);

--
-- Индексы таблицы `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`id_Data`),
  ADD KEY `fk_Data_Source1_idx` (`Source_id_source`),
  ADD KEY `fk_Data_Type1_idx` (`Type_id_Type`);

--
-- Индексы таблицы `device_info`
--
ALTER TABLE `device_info`
  ADD PRIMARY KEY (`id_device_info`),
  ADD KEY `fk_Device_info_OS1_idx` (`id_os`),
  ADD KEY `fk_Device_info_Model1_idx` (`id_model`);

--
-- Индексы таблицы `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id_event`),
  ADD KEY `Event_fk0_idx` (`id_data`);

--
-- Индексы таблицы `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id_History`),
  ADD KEY `fk_History_History1_idx` (`id_user`),
  ADD KEY `fk_History_Image1_idx` (`id_image`);

--
-- Индексы таблицы `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id_image`),
  ADD UNIQUE KEY `id_image_UNIQUE` (`id_image`),
  ADD KEY `Image_fk0` (`id_author`),
  ADD KEY `Image_fk1_idx` (`id_category_image`),
  ADD KEY `Image_fk2_idx` (`id_event`),
  ADD KEY `id_properties` (`id_properties`);

--
-- Индексы таблицы `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id_location`);

--
-- Индексы таблицы `model`
--
ALTER TABLE `model`
  ADD PRIMARY KEY (`id_model`),
  ADD KEY `fk_Model_Brand1_idx` (`id_brand`),
  ADD KEY `fk_Model_Type_of_device1_idx` (`id_type_of_device`);

--
-- Индексы таблицы `os`
--
ALTER TABLE `os`
  ADD PRIMARY KEY (`id_os`);

--
-- Индексы таблицы `prize`
--
ALTER TABLE `prize`
  ADD PRIMARY KEY (`id_prize`),
  ADD KEY `fk_Prize_type1_idx` (`id_type_prize`);

--
-- Индексы таблицы `properties_images`
--
ALTER TABLE `properties_images`
  ADD PRIMARY KEY (`id_propertie`);

--
-- Индексы таблицы `source`
--
ALTER TABLE `source`
  ADD PRIMARY KEY (`id_source`);

--
-- Индексы таблицы `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id_stock`),
  ADD KEY `fk_Stock_Stock_type1_idx` (`id_stock_type`),
  ADD KEY `id_author` (`id_author`);

--
-- Индексы таблицы `stock_and_image`
--
ALTER TABLE `stock_and_image`
  ADD PRIMARY KEY (`id_stock_and_image`),
  ADD UNIQUE KEY `id_stock_and_image_UNIQUE` (`id_stock_and_image`),
  ADD KEY `Stock_and_Image_fk0_idx` (`id_image`),
  ADD KEY `Stock_and_Image_fk1_idx` (`id_stock`);

--
-- Индексы таблицы `stock_prize`
--
ALTER TABLE `stock_prize`
  ADD PRIMARY KEY (`id_stock_prize`),
  ADD KEY `fk_Stock_prize_Stock1_idx` (`Stock_id_stock`),
  ADD KEY `fk_Stock_prize_Prize1_idx` (`id_prize`);

--
-- Индексы таблицы `stock_progress`
--
ALTER TABLE `stock_progress`
  ADD PRIMARY KEY (`id_stock_progress`),
  ADD KEY `fk_Stock_progress_User1_idx` (`id_user`),
  ADD KEY `fk_Stock_progress_User_and_Stock1_idx` (`id_stock_and_Image`);

--
-- Индексы таблицы `stock_type`
--
ALTER TABLE `stock_type`
  ADD PRIMARY KEY (`id_stock_type`);

--
-- Индексы таблицы `type_of_data`
--
ALTER TABLE `type_of_data`
  ADD PRIMARY KEY (`id_Type`);

--
-- Индексы таблицы `type_of_device`
--
ALTER TABLE `type_of_device`
  ADD PRIMARY KEY (`id_type_of_device`);

--
-- Индексы таблицы `type_of_prize`
--
ALTER TABLE `type_of_prize`
  ADD PRIMARY KEY (`id_type`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `login` (`users_login`),
  ADD UNIQUE KEY `email` (`users_email`),
  ADD KEY `fk_User_Device_info1_idx` (`id_device_info`),
  ADD KEY `User_fk1_idx` (`id_city`),
  ADD KEY `id_avatars` (`id_avatars`);

--
-- Индексы таблицы `user_and_stock`
--
ALTER TABLE `user_and_stock`
  ADD PRIMARY KEY (`id_User_and_Stock`),
  ADD KEY `fk_User_and_Stock_Stock1_idx` (`id_stock`),
  ADD KEY `fk_User_and_Stock_User1_idx` (`id_user`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id_Admin_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `avatars`
--
ALTER TABLE `avatars`
  MODIFY `id_Avatars` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `brand`
--
ALTER TABLE `brand`
  MODIFY `id_brand` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `category_image`
--
ALTER TABLE `category_image`
  MODIFY `id_category_image` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `city`
--
ALTER TABLE `city`
  MODIFY `id_city` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `country`
--
ALTER TABLE `country`
  MODIFY `id_country` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `data`
--
ALTER TABLE `data`
  MODIFY `id_Data` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `device_info`
--
ALTER TABLE `device_info`
  MODIFY `id_device_info` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `event`
--
ALTER TABLE `event`
  MODIFY `id_event` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `history`
--
ALTER TABLE `history`
  MODIFY `id_History` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `image`
--
ALTER TABLE `image`
  MODIFY `id_image` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `model`
--
ALTER TABLE `model`
  MODIFY `id_model` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `os`
--
ALTER TABLE `os`
  MODIFY `id_os` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `prize`
--
ALTER TABLE `prize`
  MODIFY `id_prize` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `properties_images`
--
ALTER TABLE `properties_images`
  MODIFY `id_propertie` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `source`
--
ALTER TABLE `source`
  MODIFY `id_source` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `stock`
--
ALTER TABLE `stock`
  MODIFY `id_stock` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `stock_and_image`
--
ALTER TABLE `stock_and_image`
  MODIFY `id_stock_and_image` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `stock_prize`
--
ALTER TABLE `stock_prize`
  MODIFY `id_stock_prize` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `stock_progress`
--
ALTER TABLE `stock_progress`
  MODIFY `id_stock_progress` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `stock_type`
--
ALTER TABLE `stock_type`
  MODIFY `id_stock_type` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `type_of_data`
--
ALTER TABLE `type_of_data`
  MODIFY `id_Type` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `type_of_device`
--
ALTER TABLE `type_of_device`
  MODIFY `id_type_of_device` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `type_of_prize`
--
ALTER TABLE `type_of_prize`
  MODIFY `id_type` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `user_and_stock`
--
ALTER TABLE `user_and_stock`
  MODIFY `id_User_and_Stock` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `City_country` FOREIGN KEY (`id_country`) REFERENCES `country` (`id_country`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `data`
--
ALTER TABLE `data`
  ADD CONSTRAINT `fk_Data_Source1` FOREIGN KEY (`Source_id_source`) REFERENCES `source` (`id_source`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Data_Type1` FOREIGN KEY (`Type_id_Type`) REFERENCES `type_of_data` (`id_Type`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `device_info`
--
ALTER TABLE `device_info`
  ADD CONSTRAINT `fk_Device_info_Model1` FOREIGN KEY (`id_model`) REFERENCES `model` (`id_model`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Device_info_OS1` FOREIGN KEY (`id_os`) REFERENCES `os` (`id_os`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `Event_fk0` FOREIGN KEY (`id_data`) REFERENCES `data` (`id_Data`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `fk_History_History1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_History_Image1` FOREIGN KEY (`id_image`) REFERENCES `image` (`id_image`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `Image_fk0` FOREIGN KEY (`id_author`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `Image_fk1` FOREIGN KEY (`id_category_image`) REFERENCES `category_image` (`id_category_image`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Image_fk2` FOREIGN KEY (`id_event`) REFERENCES `event` (`id_event`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `image_ibfk_1` FOREIGN KEY (`id_properties`) REFERENCES `properties_images` (`id_propertie`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `model`
--
ALTER TABLE `model`
  ADD CONSTRAINT `fk_Model_Brand1` FOREIGN KEY (`id_brand`) REFERENCES `brand` (`id_brand`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Model_Type_of_device1` FOREIGN KEY (`id_type_of_device`) REFERENCES `type_of_device` (`id_type_of_device`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `prize`
--
ALTER TABLE `prize`
  ADD CONSTRAINT `prize_ibfk_1` FOREIGN KEY (`id_type_prize`) REFERENCES `type_of_prize` (`id_type`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `fk_Stock_Stock_type1` FOREIGN KEY (`id_stock_type`) REFERENCES `stock_type` (`id_stock_type`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`id_author`) REFERENCES `users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `stock_and_image`
--
ALTER TABLE `stock_and_image`
  ADD CONSTRAINT `Stock_and_Image_fk0` FOREIGN KEY (`id_image`) REFERENCES `image` (`id_image`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Stock_and_Image_fk1` FOREIGN KEY (`id_stock`) REFERENCES `stock` (`id_stock`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `stock_prize`
--
ALTER TABLE `stock_prize`
  ADD CONSTRAINT `fk_Stock_prize_Stock1` FOREIGN KEY (`Stock_id_stock`) REFERENCES `stock` (`id_stock`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `stock_prize_ibfk_1` FOREIGN KEY (`id_prize`) REFERENCES `prize` (`id_prize`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `stock_progress`
--
ALTER TABLE `stock_progress`
  ADD CONSTRAINT `fk_Stock_progress_User1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Stock_progress_User_and_Stock1` FOREIGN KEY (`id_stock_and_Image`) REFERENCES `stock_and_image` (`id_stock_and_image`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_User_Device_info1` FOREIGN KEY (`id_device_info`) REFERENCES `device_info` (`id_device_info`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`id_avatars`) REFERENCES `avatars` (`id_Avatars`),
  ADD CONSTRAINT `User_fk1` FOREIGN KEY (`id_city`) REFERENCES `city` (`id_city`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `user_and_stock`
--
ALTER TABLE `user_and_stock`
  ADD CONSTRAINT `fk_User_and_Stock_Stock1` FOREIGN KEY (`id_stock`) REFERENCES `stock` (`id_stock`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_User_and_Stock_User1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
