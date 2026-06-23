-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 02 oct. 2025 à 14:11
-- Version du serveur : 5.7.23-23
-- Version de PHP : 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sast_academy`
--

-- --------------------------------------------------------

--
-- Structure de la table `news`
--

CREATE TABLE `news` (
  `id` int(10) UNSIGNED NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `publisher_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `news`
--

INSERT INTO `news` (`id`, `thumbnail`, `image`, `active`, `publisher_id`, `created_at`, `updated_at`, `type`) VALUES
(1, 'news/349701.jpg', 'news/349701.jpg', 1, NULL, '2019-10-17 10:36:04', '2019-10-17 15:36:04', 'news'),
(2, 'news/322265.jpg', 'news/322265.jpg', 1, NULL, '2017-03-11 16:00:20', '2019-01-10 22:00:20', 'news'),
(3, 'news/503693.jpg', 'news/503693.jpg', 1, NULL, '2017-03-11 16:00:49', '2019-01-10 22:00:49', 'news'),
(4, 'news/13610.jpg', 'news/13610.jpg', 1, NULL, '2017-03-31 15:01:27', '2019-01-10 22:01:27', 'news'),
(5, 'news/641571.jpg', 'news/641571.jpg', 1, NULL, '2017-03-11 09:34:13', '2017-03-11 21:00:00', 'news'),
(6, 'news/198608.jpg', 'news/198608.jpg', 1, NULL, '2017-03-11 09:34:13', '2017-03-11 21:00:00', 'news'),
(7, 'news/252037.jpg', 'news/252037.jpg', 1, NULL, '2017-03-31 08:34:13', '2017-03-31 21:00:00', 'news'),
(9, 'news/211942.jpeg', 'news/211942.jpeg', 1, NULL, '2017-04-15 08:34:13', '2017-04-15 21:00:00', 'news'),
(10, 'news/673845.jpeg', 'news/673845.jpeg', 1, NULL, '2019-10-18 14:50:25', '2019-10-18 19:50:25', 'news'),
(12, 'news/583228.jpg', 'news/583228.jpg', 1, NULL, '2017-05-04 08:34:13', '2017-05-04 21:00:00', 'news'),
(15, 'news/706216.jpg', 'news/706216.jpg', 1, NULL, '2019-01-17 11:58:46', '2019-01-17 17:58:46', 'Choose type'),
(16, 'news/642511.jpg', 'news/642511.jpg', 1, NULL, '2017-06-03 21:00:00', '2017-06-03 21:00:00', ''),
(17, 'news/469300.jpeg', 'news/469300.jpeg', 1, NULL, '2017-07-03 21:00:00', '2017-07-03 21:00:00', ''),
(18, 'news/401319.jpg', 'news/401319.jpg', 1, NULL, '2017-07-03 21:00:00', '2017-07-03 21:00:00', ''),
(19, 'news/415651.jpeg', 'news/415651.jpeg', 1, NULL, '2017-07-08 21:00:00', '2017-07-08 21:00:00', ''),
(20, 'news/193718.jpeg', 'news/193718.jpeg', 1, NULL, '2017-08-02 21:00:00', '2017-08-02 21:00:00', ''),
(21, 'news/758255.jpeg', 'news/758255.jpeg', 1, NULL, '2017-08-02 21:00:00', '2017-08-02 21:00:00', ''),
(22, 'news/700039.png', 'news/700039.png', 1, NULL, '2017-08-20 21:00:00', '2017-08-20 21:00:00', ''),
(23, 'news/919483.jpeg', 'news/919483.jpeg', 1, NULL, '2017-08-27 21:00:00', '2017-08-27 21:00:00', ''),
(24, 'news/856590.jpg', 'news/856590.jpg', 1, NULL, '2017-08-29 21:00:00', '2017-08-29 21:00:00', ''),
(25, 'news/710125.png', 'news/710125.png', 1, NULL, '2017-09-13 21:00:00', '2017-09-13 21:00:00', ''),
(26, 'news/885472.jpg', 'news/885472.jpg', 1, NULL, '2017-09-13 21:00:00', '2017-09-13 21:00:00', ''),
(27, 'news/861658.png', 'news/861658.png', 1, NULL, '2017-09-19 21:00:00', '2017-09-19 21:00:00', ''),
(28, 'news/370205.jpeg', 'news/370205.jpeg', 1, NULL, '2017-09-21 21:00:00', '2017-09-21 21:00:00', ''),
(29, 'news/727892.png', 'news/727892.png', 1, NULL, '2017-09-21 21:00:00', '2017-09-21 21:00:00', ''),
(30, 'news/414136.jpg', 'news/414136.jpg', 1, NULL, '2017-11-06 21:00:00', '2017-11-06 21:00:00', ''),
(31, 'news/576353.jpg', 'news/576353.jpg', 1, NULL, '2017-11-20 21:00:00', '2017-11-20 21:00:00', ''),
(32, 'news/889560.jpg', 'news/889560.jpg', 1, NULL, '2017-12-13 21:00:00', '2017-12-13 21:00:00', ''),
(33, 'news/272394.jpg', 'news/272394.jpg', 1, NULL, '2017-12-13 21:00:00', '2017-12-13 21:00:00', ''),
(34, 'news/384879.jpg', 'news/384879.jpg', 1, NULL, '2017-12-23 21:00:00', '2017-12-23 21:00:00', ''),
(35, 'news/28529.jpg', 'news/28529.jpg', 1, NULL, '2017-12-23 21:00:00', '2017-12-23 21:00:00', ''),
(36, 'news/209999.jpg', 'news/209999.jpg', 1, NULL, '2017-12-19 21:00:00', '2017-12-19 21:00:00', ''),
(37, 'news/415298.jpg', 'news/415298.jpg', 1, NULL, '2018-01-04 21:00:00', '2018-01-04 21:00:00', ''),
(38, 'news/553125.jpg', 'news/553125.jpg', 1, NULL, '2018-01-24 21:00:00', '2018-01-24 21:00:00', ''),
(39, 'news/665229.png', 'news/665229.png', 1, NULL, '2018-02-11 21:00:00', '2018-02-11 21:00:00', ''),
(40, 'news/254049.png', 'news/254049.png', 1, NULL, '2018-03-01 21:00:00', '2018-03-01 21:00:00', ''),
(41, 'news/955419.jpg', 'news/955419.jpg', 1, NULL, '2018-03-26 21:00:00', '2018-03-26 21:00:00', ''),
(42, 'news/217429.png', 'news/217429.png', 1, NULL, '2018-03-29 21:00:00', '2018-03-29 21:00:00', ''),
(43, 'news/23205.png', 'news/23205.png', 1, NULL, '2018-04-01 21:00:00', '2018-04-01 21:00:00', ''),
(44, 'IMG_0578.jpg', 'IMG_0577.jpg', 1, NULL, '2018-09-03 16:18:22', '2018-09-03 16:18:22', ''),
(71, 'thumbnails/TEST.jpg', 'thumbnails/TEST.jpg', 1, NULL, '2019-01-16 15:38:00', '2019-01-16 21:38:00', 'article'),
(73, 'thumbnails/37736703_1746509682132004_1000722522359988224_o.jpg', 'thumbnails/37736703_1746509682132004_1000722522359988224_o.jpg', 1, NULL, '2019-01-17 21:10:17', '2019-01-17 21:10:17', 'article'),
(74, 'thumbnails/tamazo9.jpg', 'thumbnails/tamazo9.jpg', 1, NULL, '2019-02-01 14:44:40', '2019-02-01 20:44:40', 'article'),
(88, 'gcss1_47692909_1965263600447552_7769241416974138687_n.jpg', 'gcss1_47692909_1965263600447552_7769241416974138687_n.jpg', 1, NULL, '2019-02-01 22:30:05', '2019-02-01 22:30:05', 'article'),
(110, 'FINAL%20MA3LOUMETT.jpg', 'FINAL%20MA3LOUMETT.jpg', 1, NULL, '2019-02-13 16:55:28', '2019-02-13 16:55:28', 'article'),
(111, 'after%20workout.jpg', 'after%20workout.jpg', 1, NULL, '2019-02-15 16:35:05', '2019-02-15 16:35:05', 'article'),
(112, 'gcss1_50661519_370427953739177_5385766758402168867_n.jpg', 'gcss1_50661519_370427953739177_5385766758402168867_n.jpg', 1, NULL, '2019-03-19 15:59:08', '2019-03-19 15:59:08', 'article'),
(114, 'news/WhatsApp%20Image%202019-05-29%20at%2012.09.31.jpeg', 'news/WhatsApp%20Image%202019-05-29%20at%2012.09.31.jpeg', 1, NULL, '2019-05-29 16:16:09', '2019-05-29 16:16:09', 'article'),
(115, 'WhatsApp%20Image%202019-05-30%20at%2015.03.26.jpeg', 'WhatsApp%20Image%202019-05-30%20at%2015.03.26.jpeg', 1, NULL, '2019-05-30 14:09:39', '2019-05-30 19:09:39', 'article'),
(116, '61504219_2482625685299815_995159762322587648_n.jpg', '61504219_2482625685299815_995159762322587648_n.jpg', 1, NULL, '2019-06-20 21:27:19', '2019-06-20 21:27:19', 'news'),
(117, 'news/shoulders.jpg', 'news/shoulders.jpg', 1, 23, '2021-09-08 11:03:59', '2021-09-01 19:53:21', 'article'),
(118, 'TENS(1).png', 'TENS(1).png', 1, 23, '2021-10-19 08:29:28', '2021-10-19 13:29:28', 'article');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `publisher_id` (`publisher_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`publisher_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
