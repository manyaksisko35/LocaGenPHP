-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 30 May 2026, 20:25:48
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `locagen`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `target_lang` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `file_name`, `target_lang`, `created_at`) VALUES
(1, 1, 'translated_English.json', 'Turkish', '2026-05-21 19:59:48'),
(2, 2, 'translated_English.json', 'Turkish', '2026-05-21 20:04:31'),
(3, 2, 'translated_English.json', 'Turkish', '2026-05-21 20:07:50'),
(4, 2, 'translated_English.json', 'German', '2026-05-21 20:08:46'),
(5, 2, 'translated_English.json', 'Turkish', '2026-05-21 20:38:26'),
(6, 2, 'translated_English.json', 'Turkish', '2026-05-21 20:41:05'),
(7, 2, 'translated_English.json', 'Turkish', '2026-05-26 15:26:25'),
(8, 2, 'translated_English.json', 'Turkish', '2026-05-26 15:42:47'),
(9, 2, 'translated_English.json', 'Turkish', '2026-05-26 15:46:29'),
(10, 2, 'translated_Norwegian.json', 'Turkish', '2026-05-27 19:20:11'),
(11, 2, 'translated_1779909611.json', 'English', '2026-05-27 23:10:03'),
(12, 2, 'translated_German.json', 'Turkish', '2026-05-29 12:36:12'),
(13, 2, 'translated_Norwegian.json', 'Turkish', '2026-05-29 20:42:28');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin'),
(2, 'dev_user', 'e10adc3949ba59abbe56e057f20f883e', 'user');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
