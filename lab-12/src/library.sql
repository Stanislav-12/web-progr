-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: MySQL-8.4
-- Generation Time: Oct 29, 2026 at 05:06 PM
-- Server version: 8.4.8
-- PHP Version: 8.5.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `year`, `description`) VALUES
(1, 'Зоряний шлях', 'Петро Вигаданий', 2015, 'Пригоди екіпажу космічного корабля.'),
(2, 'Сад над річкою', 'Олена Придумана', 2008, 'Історія родини, що живе біля річки.'),
(3, 'Казка <про> & \"янн\"', 'Іван Вигаданий', 2020, 'Книга для перевірки спецсимволів.'),
(4, 'Тіні минулого', 'Анатолій Шевченко', 2018, 'Захопливий детективний роман про розслідування давнього злочину.'),
(5, 'Вітер з півночі', 'Марія Коваленко', 2021, 'Романтична історія про кохання на тлі суворих північних пейзажів.'),
(6, 'Код безсмертя', 'Дмитро Бондаренко', 2023, 'Науково-фантастичний трилер про штучний інтелект та таємні дослідження.'),
(7, 'Світло далеких зірок', 'Оксана Бойко', 2019, 'Філософський роман про пошук сенсу життя та космічні мандрівки.'),
(8, 'Стежка у тумані', 'Сергій Кравченко', 2012, 'Містичний детектив про таємничі події в карпатському селі.'),
(9, 'Хроніки втраченого міста', 'Віктор Ткаченко', 2017, 'Пригодницький роман про пошуки стародавньої цивілізації.'),
(10, 'Таємниця старої садиби', 'Наталія Мороз', 2015, 'Готична історія з елементами жахів та сімейних таємниць.'),
(11, 'Океан тиші', 'Андрій Лисенко', 2020, 'Психологічний трилер про виживання на безлюдному острові.'),
(12, 'Останній альхімік', 'Юрій Мельник', 2014, 'Історичний роман про магію, науку та середньовічні інтриги.'),
(13, 'Голос вітру', 'Ірина Савченко', 2022, 'Поетична проза про взаємини людини та природи.'),
(14, 'Лабіринт снів', 'Олександр Петренко', 2021, 'Фантастична повість про подорожі крізь свідомість.'),
(15, 'Вересневий світанок', 'Тетяна Василенко', 2016, 'Драматична історія кількох поколінь однієї родини.'),
(16, 'Привид у мережі', 'Павло Олійник', 2024, 'Кіберпанк-детектив про хакерські війни та цифровий контроль.'),
(17, 'Сонце над степом', 'Ганна Руденко', 2011, 'Епічний роман про життя українського села на початку XX століття.'),
(18, 'Дзеркало часів', 'Ярослав Коваль', 2019, 'Пригодницьке фентезі про часові часові петлі та паралельні світи.'),
(19, 'Забуті мелодії', 'Євгенія Марченко', 2013, 'Історія про музиканта, який шукає втрачене натхнення.'),
(20, 'Ключ від таємної кімнати', 'Олег Поліщук', 2022, 'Підлітковий пригодницький детектив про шкільні таємниці.'),
(21, 'Академія тіней', 'Світлана Дорошенко', 2020, 'Фентезі про навчання у магічній школі та боротьбу з темними силами.'),
(22, 'Місто без сну', 'Вадим Семенов', 2023, 'Нуарний детектив у мегаполісі майбутнього.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `login` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `fullname`, `email`, `password`) VALUES
(1, 'Stanislav', 'Фотєєв Станіслав', '1@gmail.com', '$2y$12$fw1RMIQOswTHZiYpqpaphufLY/g1ARnmnu.sSP3GY4mwASxFBs2cG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
