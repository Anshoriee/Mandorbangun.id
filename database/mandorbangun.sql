-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2025 at 04:21 AM
-- Server version: 8.0.43
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mandorbangun_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$VkMQHB7YvcWzFpNrylNVTOcJqb9SOOmuX40GNiDp/Et7Nq4Q.0RH6', '2025-08-13 18:14:51');

-- --------------------------------------------------------

--
-- Table structure for table `alur_kerja`
--

CREATE TABLE `alur_kerja` (
  `id` int NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `order_num` int NOT NULL DEFAULT '99'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alur_kerja`
--

INSERT INTO `alur_kerja` (`id`, `image_path`, `title`, `order_num`) VALUES
(9, 'assets/images/gallery/1755686649_Alur kerja.jpg', 'Alur Kerja MandorbangunId', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gallery_categories`
--

CREATE TABLE `gallery_categories` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `order_num` int DEFAULT '99'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery_categories`
--

INSERT INTO `gallery_categories` (`id`, `name`, `title`, `order_num`) VALUES
(1, '3D Design', '3D Design', 1),
(2, 'Proyek Jadi', 'Proyek Jadi', 2);

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` int NOT NULL,
  `gallery_item_id` int NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `image_type` enum('main','interior','exterior','detail') DEFAULT 'main',
  `caption` varchar(255) DEFAULT NULL,
  `order_num` int DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery_images`
--

INSERT INTO `gallery_images` (`id`, `gallery_item_id`, `image_path`, `image_type`, `caption`, `order_num`, `created_at`) VALUES
(1, 7, 'assets/images/gallery/1755748252_Enscape_2024-12-10-14-53-25.png', 'main', '', 1, '2025-08-21 03:50:52'),
(3, 10, 'assets/images/gallery/1755748793_01.png', 'main', '', 1, '2025-08-21 03:59:53'),
(5, 12, 'assets/images/gallery/1755749386_Enscape_2024-11-13-11-05-01.png', 'main', '', 1, '2025-08-21 04:09:46'),
(6, 13, 'assets/images/gallery/1755749520_Enscape_2025-02-25-12-17-11.png', 'main', '', 1, '2025-08-21 04:12:00'),
(17, 7, 'assets/images/gallery/1755797732_68a758e42d903.png', 'exterior', 'Carport', 2, '2025-08-21 17:35:32'),
(19, 7, 'assets/images/gallery/1755797846_68a759563aa4b.png', 'exterior', 'Balkon', 4, '2025-08-21 17:37:26'),
(23, 7, 'assets/images/gallery/1756103105_68ac01c1b175c.png', 'exterior', 'Lantai 2', 5, '2025-08-25 06:25:05'),
(24, 7, 'assets/images/gallery/1756103105_68ac01c1b22ed.png', 'exterior', 'Teras', 6, '2025-08-25 06:25:05'),
(25, 10, 'assets/images/gallery/1756103255_68ac02573d01c.png', 'interior', 'Kamar Tidur', 1, '2025-08-25 06:27:35'),
(26, 10, 'assets/images/gallery/1756103255_68ac02573d337.png', 'interior', 'Ruang Tamu', 2, '2025-08-25 06:27:35'),
(27, 10, 'assets/images/gallery/1756103255_68ac02573d6dc.png', 'main', 'Ruang Keluarga', 3, '2025-08-25 06:27:35'),
(28, 11, 'assets/images/gallery/1756103438_68ac030ecb5be.png', 'main', '', 1, '2025-08-25 06:30:38'),
(29, 11, 'assets/images/gallery/1756103438_68ac030ecb904.png', 'exterior', 'Carport', 2, '2025-08-25 06:30:38'),
(30, 11, 'assets/images/gallery/1756103438_68ac030ecbbdd.png', 'exterior', 'Akses Utama', 3, '2025-08-25 06:30:38'),
(31, 11, 'assets/images/gallery/1756103438_68ac030ecbeed.png', 'interior', 'Lantai 1', 4, '2025-08-25 06:30:38'),
(32, 11, 'assets/images/gallery/1756103438_68ac030ecc17c.png', 'interior', 'Lantai 2', 5, '2025-08-25 06:30:38'),
(33, 12, 'assets/images/gallery/1756103690_68ac040a31e83.png', 'interior', 'Kitchen', 1, '2025-08-25 06:34:50'),
(34, 12, 'assets/images/gallery/1756103690_68ac040a321f3.png', 'interior', 'Ruang Tamu', 2, '2025-08-25 06:34:50'),
(35, 12, 'assets/images/gallery/1756103690_68ac040a32566.png', 'interior', 'Ruang Tengah', 3, '2025-08-25 06:34:50'),
(36, 12, 'assets/images/gallery/1756103690_68ac040a328d1.png', 'interior', 'Kamar tidur', 4, '2025-08-25 06:34:50'),
(37, 13, 'assets/images/gallery/1756103769_68ac0459c70e6.png', 'exterior', 'Halaman Rumah', 1, '2025-08-25 06:36:09'),
(38, 13, 'assets/images/gallery/1756103769_68ac0459c7685.png', 'exterior', 'Halaman Samping', 2, '2025-08-25 06:36:09'),
(39, 13, 'assets/images/gallery/1756103769_68ac0459c7a66.png', 'main', '', 3, '2025-08-25 06:36:09'),
(40, 20, 'assets/images/gallery/1756104996_68ac09249243d.png', 'main', '', 1, '2025-08-25 06:56:36'),
(41, 20, 'assets/images/gallery/1756104996_68ac09249276b.png', 'main', '', 2, '2025-08-25 06:56:36'),
(42, 20, 'assets/images/gallery/1756104996_68ac092492aad.png', 'main', '', 3, '2025-08-25 06:56:36'),
(43, 20, 'assets/images/gallery/1756104996_68ac092492ddb.png', 'main', '', 4, '2025-08-25 06:56:36'),
(44, 20, 'assets/images/gallery/1756104996_68ac092493055.png', 'main', '', 5, '2025-08-25 06:56:36'),
(45, 20, 'assets/images/gallery/1756104996_68ac0924932aa.png', 'main', '', 6, '2025-08-25 06:56:36'),
(46, 20, 'assets/images/gallery/1756104996_68ac0924934b2.png', 'main', '', 7, '2025-08-25 06:56:36'),
(47, 20, 'assets/images/gallery/1756104996_68ac0924937f3.png', 'main', '', 8, '2025-08-25 06:56:36'),
(48, 20, 'assets/images/gallery/1756104996_68ac092493a83.png', 'main', '', 9, '2025-08-25 06:56:36'),
(49, 21, 'assets/images/gallery/1756105229_68ac0a0dd317d.png', 'main', '', 1, '2025-08-25 07:00:29'),
(50, 21, 'assets/images/gallery/1756105229_68ac0a0dd345e.png', 'exterior', '', 2, '2025-08-25 07:00:29'),
(51, 21, 'assets/images/gallery/1756105229_68ac0a0dd36e0.png', 'exterior', '', 3, '2025-08-25 07:00:29'),
(52, 21, 'assets/images/gallery/1756105229_68ac0a0dd3907.png', 'interior', '', 4, '2025-08-25 07:00:29'),
(53, 21, 'assets/images/gallery/1756105229_68ac0a0dd3b48.png', 'exterior', '', 5, '2025-08-25 07:00:29'),
(54, 21, 'assets/images/gallery/1756105229_68ac0a0dd3d7f.png', 'exterior', '', 6, '2025-08-25 07:00:29'),
(55, 22, 'assets/images/gallery/1756105531_68ac0b3b81140.png', 'main', '', 1, '2025-08-25 07:05:31'),
(56, 22, 'assets/images/gallery/1756105531_68ac0b3b814bf.png', 'exterior', '', 2, '2025-08-25 07:05:31'),
(57, 22, 'assets/images/gallery/1756105531_68ac0b3b8174c.png', 'detail', '', 3, '2025-08-25 07:05:31'),
(58, 22, 'assets/images/gallery/1756105531_68ac0b3b81b19.jpg', 'detail', '', 4, '2025-08-25 07:05:31'),
(59, 22, 'assets/images/gallery/1756105531_68ac0b3b81e59.jpg', 'detail', '', 5, '2025-08-25 07:05:31'),
(60, 22, 'assets/images/gallery/1756105531_68ac0b3b820ff.png', 'detail', '', 6, '2025-08-25 07:05:31'),
(69, 25, 'assets/images/gallery/1756105984_68ac0d0091523.png', 'main', '', 1, '2025-08-25 07:13:04'),
(70, 25, 'assets/images/gallery/1756105984_68ac0d009184a.png', 'interior', '', 2, '2025-08-25 07:13:04'),
(71, 25, 'assets/images/gallery/1756105984_68ac0d0091b00.png', 'interior', '', 3, '2025-08-25 07:13:04'),
(73, 26, 'assets/images/gallery/1756106466_68ac0ee2b8a2a.jpg', 'main', '', 1, '2025-08-25 07:21:06'),
(74, 26, 'assets/images/gallery/1756106466_68ac0ee2b8dc2.jpg', 'interior', '', 2, '2025-08-25 07:21:06'),
(75, 26, 'assets/images/gallery/1756106466_68ac0ee2b9058.jpg', 'interior', '', 3, '2025-08-25 07:21:06'),
(76, 23, 'assets/images/gallery/1756106487_68ac0ef7ea389.jpg', 'main', '', 1, '2025-08-25 07:21:27'),
(77, 23, 'assets/images/gallery/1756106622_68ac0f7e11a1d.png', 'exterior', '', 1, '2025-08-25 07:23:42'),
(78, 23, 'assets/images/gallery/1756106622_68ac0f7e11d78.png', 'interior', '', 2, '2025-08-25 07:23:42'),
(79, 23, 'assets/images/gallery/1756106622_68ac0f7e1211b.png', 'interior', '', 3, '2025-08-25 07:23:42'),
(80, 23, 'assets/images/gallery/1756106622_68ac0f7e1241f.png', 'interior', '', 4, '2025-08-25 07:23:42'),
(81, 23, 'assets/images/gallery/1756106622_68ac0f7e127ca.png', 'interior', '', 5, '2025-08-25 07:23:42');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_items`
--

CREATE TABLE `gallery_items` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `main_image_path` varchar(255) DEFAULT NULL,
  `category_id` int NOT NULL,
  `order_num` int DEFAULT '99',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery_items`
--

INSERT INTO `gallery_items` (`id`, `title`, `image_path`, `main_image_path`, `category_id`, `order_num`, `created_at`) VALUES
(7, 'Rumah Modern Industrial Tropis', 'assets/images/gallery/1755748252_Enscape_2024-12-10-14-53-25.png', 'assets/images/gallery/1755748252_Enscape_2024-12-10-14-53-25.png', 1, 1, '2025-08-21 03:50:52'),
(10, 'Rumah Mediterania Modern.', 'assets/images/gallery/1755748793_01.png', 'assets/images/gallery/1755748793_01.png', 1, 2, '2025-08-21 03:59:53'),
(11, 'Rumah Modern Kontemporer.', 'assets/images/gallery/1756103438_68ac030ecb5be.png', 'assets/images/gallery/1756103438_68ac030ecb5be.png', 1, 3, '2025-08-21 04:03:43'),
(12, 'Rumah Minimalis Modern', 'assets/images/gallery/1755749386_Enscape_2024-11-13-11-05-01.png', 'assets/images/gallery/1755749386_Enscape_2024-11-13-11-05-01.png', 1, 4, '2025-08-21 04:09:46'),
(13, 'Rumah Modern Minimalis Tropis.', 'assets/images/gallery/1755749520_Enscape_2025-02-25-12-17-11.png', 'assets/images/gallery/1755749520_Enscape_2025-02-25-12-17-11.png', 1, 5, '2025-08-21 04:12:00'),
(20, 'Rumah Modern Tropis Minimalis.', 'assets/images/gallery/1756104996_68ac09249243d.png', 'assets/images/gallery/1756104996_68ac09249243d.png', 1, 7, '2025-08-25 06:56:36'),
(21, 'Rumah Modern Minimalis. ', 'assets/images/gallery/1756105229_68ac0a0dd317d.png', 'assets/images/gallery/1756105229_68ac0a0dd317d.png', 1, 6, '2025-08-25 07:00:29'),
(22, 'Rumah Modern Kontemporer ', 'assets/images/gallery/1756105531_68ac0b3b81140.png', 'assets/images/gallery/1756105531_68ac0b3b81140.png', 2, 8, '2025-08-25 07:05:31'),
(23, 'Rumah Modern Minimalis.', 'assets/images/gallery/1756106487_68ac0ef7ea389.jpg', 'assets/images/gallery/1756106487_68ac0ef7ea389.jpg', 2, 9, '2025-08-25 07:08:54'),
(25, 'Rumah Modern Industrial Tropis.', 'assets/images/gallery/1756105984_68ac0d0091523.png', 'assets/images/gallery/1756105984_68ac0d0091523.png', 2, 11, '2025-08-25 07:13:04'),
(26, 'Rumah Minimalis Modern Tropis.', 'assets/images/gallery/1756106466_68ac0ee2b8a2a.jpg', 'assets/images/gallery/1756106466_68ac0ee2b8a2a.jpg', 2, 10, '2025-08-25 07:21:06');

-- --------------------------------------------------------

--
-- Table structure for table `monthly_visits`
--

CREATE TABLE `monthly_visits` (
  `id` int NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `visit_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monthly_visits`
--

INSERT INTO `monthly_visits` (`id`, `session_id`, `visit_date`) VALUES
(2164, '08mkm7ude2bout0tek0n1kme0a', '2025-08-26'),
(2221, '08mkm7ude2bout0tek0n1kme0a', '2025-08-27'),
(1242, '1jou2335ac31uftf3ovkm1sq3u', '2025-08-26'),
(1987, '1mblfijq4idq16av2brnknbi8r', '2025-08-26'),
(2238, '22cgie3bidqqqq2krldpe1i60f', '2025-08-27'),
(209, '39bjfv2c9c75jio7pa0k3ncvgi', '2025-08-25'),
(295, '4be2qknef1hiluhhm3q2dc294e', '2025-08-25'),
(1450, '5c1h8agpgkfn507p063dlu44cm', '2025-08-26'),
(1476, '6m3v2k3t8unruam9m6rt35q1p5', '2025-08-26'),
(941, '6m71ontp4omh6pn6qlrj1sghc4', '2025-08-26'),
(2553, '6qr4je2ng5vbm1pbuumjdrfkp0', '2025-08-30'),
(1448, '6va1bhu2nnr7td2jh13b5o1b45', '2025-08-26'),
(2024, '727vtbfd3v6e4nqhqhbfjgen8c', '2025-08-26'),
(1237, '9sfeundkj7vc26c2higa9pcjti', '2025-08-26'),
(2, 'ajsmp3bbarh9o0gmbv5vknrvri', '2025-08-25'),
(1075, 'akeuqnonp1heh53lol86p6tv1s', '2025-08-26'),
(1935, 'bffiitbhn0im0d84a2hq62k210', '2025-08-26'),
(1211, 'cpnindk4fi24o9lbs0dades6ul', '2025-08-26'),
(314, 'd2ahfiq9gvoc8uoe7sh5socd63', '2025-08-25'),
(937, 'd2ahfiq9gvoc8uoe7sh5socd63', '2025-08-26'),
(1, 'd87llqopgocit5g1phbp8v0354', '2025-08-25'),
(1207, 'dknenoevitbfp6lgto13uhk5oq', '2025-08-26'),
(1240, 'dul0evk0rckcks4tnojqm1t3co', '2025-08-26'),
(228, 'h97j3mcmhomuo0o0a9eo6411c5', '2025-08-25'),
(210, 'hl6dp52ceblf8eau1h0f7nrtgc', '2025-08-25'),
(215, 'i5eqbnkvb4uv6t8jebsqcrbmi6', '2025-08-25'),
(1453, 'ijhpk9cra8p4ut3vl273nl3ope', '2025-08-26'),
(1463, 'jrgffepdkj8ueo2tehbe8mfvvt', '2025-08-26'),
(2502, 'l6kgq1f2kc9qkeu1i5gvglgn86', '2025-08-30'),
(2481, 'l80hkp2p697v8h6ivujeu7s98k', '2025-08-30'),
(214, 'l97bkldd203dun6askqolrhik8', '2025-08-25'),
(5, 'lpf35oropv1gsa9ldus40uc5o5', '2025-08-25'),
(1948, 'nmpdjghhvcoj9hm65nl60nhs28', '2025-08-26'),
(2558, 'ok0r1v0ukk637r1hknc8bum06o', '2025-08-30'),
(51, 'p2p2t8u0cmerpkrhvih258bii6', '2025-08-25'),
(4, 'qo27ea45fmuf2ra54i5p0c6s4t', '2025-08-25'),
(1457, 'sahk9n8qg6113qgtk7cc6r56el', '2025-08-26'),
(227, 'teu1825tl9sjgb4j92sp5m2e6v', '2025-08-25'),
(3, 'ug9dtdhbjab0ja5q4t7qq92ha6', '2025-08-25'),
(1209, 'vl2toiso1ovs27rbtm9pbmeqah', '2025-08-26');

-- --------------------------------------------------------

--
-- Table structure for table `popup`
--

CREATE TABLE `popup` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `show_delay` int DEFAULT '3000',
  `show_frequency` enum('always','once_per_session','once_per_day') DEFAULT 'always',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `popup`
--

INSERT INTO `popup` (`id`, `title`, `image_path`, `link_url`, `is_active`, `show_delay`, `show_frequency`, `created_at`, `updated_at`) VALUES
(2, 'Promo Spesial', 'assets/images/Promo.jpg', 'https://wa.me/628152318805?text=Halo%20saya%20tertarik%20dengan%20promo%20spesial', 1, 1000, 'always', '2025-08-13 18:14:51', '2025-08-25 22:37:36');

-- --------------------------------------------------------

--
-- Table structure for table `portfolio`
--

CREATE TABLE `portfolio` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `order_num` int DEFAULT '99',
  `service_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `portfolio`
--

INSERT INTO `portfolio` (`id`, `title`, `image_path`, `order_num`, `service_id`, `created_at`) VALUES
(1, 'Rumah Modern Kontemporer', 'assets/images/Img1.png', 1, 1, '2025-08-13 18:14:51'),
(2, 'Rumah Modern Minimalist', 'assets/images/Img2.jpg', 2, 1, '2025-08-13 18:14:51'),
(3, 'Rumah Model Tropical', 'assets/images/Img3.png', 3, 2, '2025-08-13 18:14:51'),
(4, 'Interior Kitchen', 'assets/images/Img4.jpg', 4, 3, '2025-08-13 18:14:51'),
(5, 'Interior Living Room', 'assets/images/Img5.jpg', 5, 3, '2025-08-13 18:14:51'),
(6, 'Area Tangga', 'assets/images/Img6.png', 6, 2, '2025-08-13 18:14:51');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `icon_class` varchar(50) NOT NULL,
  `order_num` int DEFAULT '99'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `description`, `icon_class`, `order_num`) VALUES
(1, 'Pembangunan Baru', 'Membangun rumah, ruko, dan gedung dari nol dengan material berkualitas dan manajemen proyek yang efisien', 'fa-solid fa-building', 1),
(2, 'Renovasi & Perbaikan', 'Memperbarui dan meningkatkan kualitas bangunan Anda, mulai dari renovasi kecil hingga perombakan total.', 'fa-solid fa-house-chimney-window', 2),
(3, 'Desain Arsitektur', 'Menyediakan jasa desain arsitektur dan interior yang fungsional, estetis, dan sesuai dengan gaya hidup Anda.', 'fa-solid fa-compass-drafting', 3);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('contact_address', 'Ruko Bukit Emerald Jaya, Blok C No. 50, Meteseh, Kec. Tembalang, Kota Semarang, Jawa Tengah 50271'),
('contact_email', 'mandorbangun.id23@gmail.com'),
('contact_phone', '08152318805');

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` int NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`id`, `session_id`, `last_activity`) VALUES
(2558, 'ok0r1v0ukk637r1hknc8bum06o', '2025-08-30 21:25:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `alur_kerja`
--
ALTER TABLE `alur_kerja`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery_categories`
--
ALTER TABLE `gallery_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gallery_item_id` (`gallery_item_id`),
  ADD KEY `idx_order` (`order_num`);

--
-- Indexes for table `gallery_items`
--
ALTER TABLE `gallery_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `monthly_visits`
--
ALTER TABLE `monthly_visits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_date_unique` (`session_id`,`visit_date`);

--
-- Indexes for table `popup`
--
ALTER TABLE `popup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `portfolio`
--
ALTER TABLE `portfolio`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_id` (`session_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `alur_kerja`
--
ALTER TABLE `alur_kerja`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `gallery_categories`
--
ALTER TABLE `gallery_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `gallery_items`
--
ALTER TABLE `gallery_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `monthly_visits`
--
ALTER TABLE `monthly_visits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2609;

--
-- AUTO_INCREMENT for table `popup`
--
ALTER TABLE `popup`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `portfolio`
--
ALTER TABLE `portfolio`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2609;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD CONSTRAINT `fk_gallery_images_item` FOREIGN KEY (`gallery_item_id`) REFERENCES `gallery_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
