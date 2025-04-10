-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 10 avr. 2025 à 10:22
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `task_management`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin_activity`
--

CREATE TABLE `admin_activity` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `details` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin_activity`
--

INSERT INTO `admin_activity` (`id`, `admin_id`, `action`, `details`, `created_at`) VALUES
(24, 26, 'User Creation', 'Created new user: KARIM', '2025-03-30 19:47:40'),
(25, 26, 'User Delete', 'Deleted user WALID', '2025-04-05 18:28:14'),
(26, 26, 'User Delete', 'Deleted user SALAH', '2025-04-05 18:32:17'),
(27, 26, 'User Delete', 'Deleted user KARIM', '2025-04-05 18:33:15'),
(28, 26, 'User Delete', 'Deleted user Abbari', '2025-04-05 18:37:06'),
(29, 26, 'User Creation', 'Created new user: OTMAN', '2025-04-05 19:04:35'),
(30, 26, 'Task Creation', 'Created new task: creation de la base de donnees', '2025-04-05 19:06:15'),
(31, 26, 'Task Delete', 'Deleted task: ccccccc', '2025-04-05 19:14:16'),
(32, 26, 'Task Delete', 'Deleted task: CC', '2025-04-05 19:14:38'),
(33, 26, 'Task Delete', 'Deleted task: MM', '2025-04-05 19:14:54'),
(34, 26, 'Task Delete', 'Deleted task: zz', '2025-04-05 19:15:04'),
(35, 26, 'Task Delete', 'Deleted task: hhh', '2025-04-05 19:15:13'),
(36, 26, 'Task Delete', 'Deleted task: cccc', '2025-04-05 19:15:22'),
(37, 26, 'Task Delete', 'Deleted task: salme', '2025-04-05 19:15:37'),
(38, 26, 'User Delete', 'Suppression de l\'utilisateur OTMAN', '2025-04-05 19:16:04'),
(39, 26, 'Task Creation', 'Created new task: cc', '2025-04-05 19:17:31'),
(40, 26, 'Task Creation', 'Created new task: cc', '2025-04-05 19:18:15'),
(41, 26, 'Task Creation', 'Created new task: vv', '2025-04-05 19:18:38'),
(42, 26, 'Task Creation', 'Created new task: bb', '2025-04-05 19:19:02'),
(45, 26, 'User Delete', 'Suppression de l\'utilisateur Faska', '2025-04-06 17:12:59'),
(46, 26, 'User Delete', 'Suppression de l\'utilisateur Adil El Yessefy', '2025-04-06 17:13:26'),
(47, 26, 'User Creation', 'Created new user: SANAE', '2025-04-06 17:14:43'),
(48, 26, 'User Delete', 'Suppression de l\'utilisateur SANAE', '2025-04-06 17:14:53'),
(49, 26, 'Task Delete', 'Deleted task: FF', '2025-04-06 17:29:58'),
(50, 26, 'Task Delete', 'Deleted task: HH', '2025-04-06 18:52:47'),
(51, 26, 'Task Delete', 'Deleted task: SALUT', '2025-04-07 13:41:01'),
(54, 26, 'Task Delete', 'Deleted task: cc', '2025-04-09 09:36:19');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sent_by` int(11) DEFAULT NULL COMMENT 'ID de l''admin qui a envoyé le message',
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `content`, `created_at`, `sent_by`, `is_read`) VALUES
(115, 29, 'Salam', '2025-03-29 17:09:08', NULL, 1),
(116, 29, 'Cc', '2025-03-29 17:09:19', NULL, 1),
(117, 29, 'Gg', '2025-03-29 17:12:09', NULL, 1),
(129, 29, 'سلام', '2025-04-02 21:18:29', NULL, 1),
(132, 17, 'ccc', '2025-04-03 11:24:53', NULL, 1),
(134, 29, 'Salam', '2025-04-03 11:26:46', NULL, 1),
(135, 29, 'Cv kolchy bikhir', '2025-04-03 11:27:00', NULL, 1),
(138, 29, 'Hani ', '2025-04-03 11:38:03', NULL, 1),
(139, 12, 'HH', '2025-04-03 11:50:36', NULL, 1),
(141, 12, 'ccc', '2025-04-03 11:55:46', NULL, 1),
(147, 17, 'ccC', '2025-04-03 19:52:25', NULL, 1),
(149, 17, 'cc', '2025-04-03 20:42:19', NULL, 1),
(150, 17, 'slm', '2025-04-03 20:44:58', NULL, 1),
(151, 12, 'slm', '2025-04-03 21:03:35', NULL, 1),
(152, 12, 'slm', '2025-04-03 21:04:04', NULL, 1),
(153, 12, 'ss', '2025-04-03 21:05:06', NULL, 1),
(154, 12, 'slm', '2025-04-03 21:05:10', NULL, 1),
(155, 12, 'cc', '2025-04-03 21:05:14', NULL, 1),
(156, 12, 'ss', '2025-04-03 21:05:19', NULL, 1),
(157, 12, 'VVVV', '2025-04-03 21:05:22', NULL, 1),
(158, 12, 'hhh', '2025-04-03 21:05:47', NULL, 1),
(159, 12, 'cccc', '2025-04-03 21:16:18', NULL, 0),
(160, 12, 'VVVV', '2025-04-03 21:16:28', NULL, 0),
(161, 12, 'zz', '2025-04-03 21:17:27', NULL, 0),
(162, 12, 'xx', '2025-04-03 21:17:42', NULL, 0),
(164, 29, 'السلام عليكم ', '2025-04-04 09:48:29', NULL, 0),
(165, 29, 'الشباب بيخير كلشي مزيان', '2025-04-04 09:48:42', NULL, 0),
(166, 29, 'هانيين', '2025-04-04 09:49:03', NULL, 0),
(167, 29, 'هانيين', '2025-04-04 10:10:52', NULL, 0),
(168, 17, 'cc', '2025-04-04 10:59:36', NULL, 0),
(169, 17, 'cv', '2025-04-04 10:59:45', NULL, 0),
(170, 17, 'hi', '2025-04-04 11:03:01', NULL, 0),
(171, 17, 'hi friends', '2025-04-04 11:06:07', NULL, 0),
(172, 17, 'hi', '2025-04-04 11:29:04', NULL, 0),
(173, 17, 'cv', '2025-04-04 11:29:08', NULL, 0),
(174, 34, 'slm drari ra wa7d tache incpomplet', '2025-04-04 11:36:14', NULL, 0),
(176, 35, 'Cc', '2025-04-05 16:32:46', NULL, 0),
(177, 12, 'comment cv', '2025-04-05 18:23:49', NULL, 0),
(178, 17, 'salam', '2025-04-06 17:42:04', NULL, 0),
(179, 17, 'cv', '2025-04-06 17:42:09', NULL, 0),
(180, 17, 'wech', '2025-04-07 13:17:48', NULL, 0),
(182, 29, 'السلام عليكم ', '2025-04-07 14:57:30', NULL, 0),
(183, 29, 'بيخير', '2025-04-07 14:57:44', NULL, 0),
(184, 29, 'كلشي مزيان', '2025-04-07 14:57:55', NULL, 0),
(185, 29, 'واش ادراري', '2025-04-07 15:00:46', NULL, 0),
(186, 38, 'salam', '2025-04-07 18:36:56', NULL, 0),
(197, 38, 'salam', '2025-04-08 13:32:39', NULL, 0),
(204, 29, 'السلام عليكم ', '2025-04-09 10:51:44', NULL, 0),
(205, 29, 'كيف تقومون بتنظيم مهامكم ؟ ', '2025-04-09 10:52:15', NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `message_reads`
--

CREATE TABLE `message_reads` (
  `user_id` int(11) NOT NULL,
  `last_read` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `message_reads`
--

INSERT INTO `message_reads` (`user_id`, `last_read`) VALUES
(12, '2025-04-05 20:23:49'),
(17, '2025-04-09 11:53:13'),
(29, '2025-04-09 12:52:16'),
(34, '2025-04-04 13:36:36'),
(35, '2025-04-05 18:33:55'),
(38, '2025-04-08 15:32:39'),
(39, '2025-04-09 21:36:11');

-- --------------------------------------------------------

--
-- Structure de la table `message_read_logs`
--

CREATE TABLE `message_read_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `read_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `message_read_logs`
--

INSERT INTO `message_read_logs` (`id`, `user_id`, `read_at`) VALUES
(1, 17, '2025-04-03 13:01:34'),
(2, 17, '2025-04-03 13:02:04'),
(3, 17, '2025-04-03 13:02:04'),
(4, 17, '2025-04-03 13:02:17'),
(5, 17, '2025-04-03 13:02:17'),
(6, 17, '2025-04-03 13:10:52'),
(7, 17, '2025-04-03 13:12:34'),
(8, 17, '2025-04-03 13:13:22'),
(9, 17, '2025-04-03 13:22:16'),
(10, 17, '2025-04-03 13:22:17'),
(11, 17, '2025-04-03 13:22:34'),
(12, 17, '2025-04-03 13:24:15'),
(13, 17, '2025-04-03 13:24:53'),
(14, 17, '2025-04-03 13:25:18'),
(15, 29, '2025-04-03 13:25:43'),
(16, 29, '2025-04-03 13:26:46'),
(17, 29, '2025-04-03 13:27:00'),
(18, 29, '2025-04-03 13:27:03'),
(19, 12, '2025-04-03 13:28:01'),
(20, 12, '2025-04-03 13:28:30'),
(21, 17, '2025-04-03 13:31:14'),
(22, 29, '2025-04-03 13:36:04'),
(23, 29, '2025-04-03 13:36:13'),
(24, 12, '2025-04-03 13:36:17'),
(25, 29, '2025-04-03 13:36:25'),
(26, 29, '2025-04-03 13:36:26'),
(27, 29, '2025-04-03 13:36:35'),
(28, 29, '2025-04-03 13:36:39'),
(29, 12, '2025-04-03 13:36:54'),
(30, 12, '2025-04-03 13:37:10'),
(31, 29, '2025-04-03 13:37:14'),
(32, 29, '2025-04-03 13:37:34'),
(33, 29, '2025-04-03 13:37:37'),
(34, 29, '2025-04-03 13:38:03'),
(35, 12, '2025-04-03 13:38:17'),
(36, 29, '2025-04-03 13:38:21'),
(37, 12, '2025-04-03 13:39:46'),
(38, 12, '2025-04-03 13:41:12'),
(39, 12, '2025-04-03 13:44:11'),
(40, 12, '2025-04-03 13:45:08'),
(41, 12, '2025-04-03 13:46:42'),
(42, 12, '2025-04-03 13:46:46'),
(43, 12, '2025-04-03 13:46:48'),
(44, 12, '2025-04-03 13:49:53'),
(45, 12, '2025-04-03 13:50:36'),
(46, 12, '2025-04-03 13:52:43'),
(47, 12, '2025-04-03 13:53:51'),
(48, 12, '2025-04-03 13:55:34'),
(49, 12, '2025-04-03 13:55:46'),
(50, 12, '2025-04-03 13:56:10'),
(51, 17, '2025-04-03 13:56:49'),
(52, 17, '2025-04-03 13:58:10'),
(53, 17, '2025-04-03 13:58:21'),
(54, 17, '2025-04-03 13:58:33'),
(55, 17, '2025-04-03 13:58:36'),
(56, 17, '2025-04-03 13:58:49'),
(57, 17, '2025-04-03 13:58:56'),
(58, 29, '2025-04-03 13:59:03'),
(59, 29, '2025-04-03 13:59:06'),
(60, 17, '2025-04-03 13:59:36'),
(61, 17, '2025-04-03 13:59:48'),
(62, 17, '2025-04-03 14:00:17'),
(63, 17, '2025-04-03 14:00:36'),
(64, 17, '2025-04-03 14:00:55'),
(65, 17, '2025-04-03 14:01:27'),
(66, 29, '2025-04-03 14:01:33'),
(67, 17, '2025-04-03 21:35:45'),
(68, 17, '2025-04-03 21:36:14'),
(69, 17, '2025-04-03 21:36:29'),
(70, 17, '2025-04-03 21:36:33'),
(71, 17, '2025-04-03 21:36:35'),
(72, 17, '2025-04-03 21:36:38'),
(73, 17, '2025-04-03 21:36:40'),
(74, 17, '2025-04-03 21:36:44'),
(75, 17, '2025-04-03 21:45:08'),
(76, 17, '2025-04-03 21:45:29'),
(77, 17, '2025-04-03 21:45:35'),
(78, 17, '2025-04-03 21:46:17'),
(79, 17, '2025-04-03 21:46:20'),
(80, 17, '2025-04-03 21:46:39'),
(81, 17, '2025-04-03 21:47:54'),
(82, 17, '2025-04-03 21:48:20'),
(83, 17, '2025-04-03 21:48:22'),
(84, 17, '2025-04-03 21:49:20'),
(85, 17, '2025-04-03 21:49:20'),
(86, 17, '2025-04-03 21:49:21'),
(87, 17, '2025-04-03 21:49:21'),
(88, 17, '2025-04-03 21:49:22');

-- --------------------------------------------------------

--
-- Structure de la table `message_recipients`
--

CREATE TABLE `message_recipients` (
  `id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_read` tinyint(4) DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `message_recipients`
--

INSERT INTO `message_recipients` (`id`, `message_id`, `user_id`, `is_read`, `read_at`, `created_at`) VALUES
(3, 132, 29, 1, '2025-04-03 13:25:43', '2025-04-03 11:24:53'),
(5, 134, 17, 1, '2025-04-03 13:31:14', '2025-04-03 11:26:46'),
(6, 135, 17, 1, '2025-04-03 13:31:14', '2025-04-03 11:27:00'),
(9, 138, 12, 1, '2025-04-03 13:38:17', '2025-04-03 11:38:03'),
(10, 139, 29, 1, '2025-04-03 13:59:03', '2025-04-03 11:50:36'),
(12, 141, 28, 0, NULL, '2025-04-03 11:55:46');

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','error') NOT NULL DEFAULT 'info',
  `priority` enum('low','normal','high') NOT NULL DEFAULT 'normal',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `message`, `type`, `priority`, `created_at`, `created_by`, `user_id`, `is_read`) VALUES
(5, 'triche', 'ajuster votre comportement !', 'warning', 'high', '2025-03-27 11:19:24', NULL, 17, 1),
(7, 'Cryptage', 'fait attention il y a des  des hackers attaques les site web , veuillez creer un mot de passe fort', 'info', 'low', '2025-03-27 12:33:07', NULL, 12, 1),
(8, 'Cryptage', 'fait attention il y a des  des hackers attaques les site web , veuillez creer un mot de passe fort', 'info', 'low', '2025-03-27 12:33:07', NULL, 17, 1),
(9, 'Cryptage', 'fait attention il y a des  des hackers attaques les site web , veuillez creer un mot de passe fort', 'info', 'low', '2025-03-27 12:33:07', NULL, 26, 1),
(11, 'Cryptage', 'fait attention il y a des  des hackers attaques les site web , veuillez creer un mot de passe fort', 'info', 'low', '2025-03-27 12:33:07', NULL, 28, 0),
(12, 'Filicitaion !', 'l\'equipe de TaskFlow vous filicite sur votre travail continu', 'success', 'low', '2025-03-27 21:30:13', NULL, 17, 1),
(13, 'Filicitaion !', 'l\'equipe de TaskFlow vous filicite sur votre travail continu', 'success', 'low', '2025-03-27 21:30:18', NULL, 17, 1),
(14, 'Erreur', 'vous etes fait des mauvais comportements dans la messagerie', 'error', 'high', '2025-03-27 21:35:00', NULL, 17, 1),
(16, 'L\'esspionage', 'fait attention a l\'esspionage  !', 'warning', 'high', '2025-03-28 21:24:32', NULL, 12, 1),
(17, 'L\'esspionage', 'fait attention a l\'esspionage  !', 'warning', 'high', '2025-03-28 21:24:32', NULL, 17, 1),
(18, 'L\'esspionage', 'fait attention a l\'esspionage  !', 'warning', 'high', '2025-03-28 21:24:32', NULL, 26, 1),
(19, 'test !!', 'seulement pour tester la page', 'info', 'normal', '2025-03-29 00:46:31', NULL, 17, 1),
(20, 'test', 'hhhhhhhhhhhh', 'info', 'normal', '2025-03-29 01:17:35', NULL, 17, 1),
(24, 'hi', 'hhhhhhhhh', 'info', 'normal', '2025-03-29 01:31:58', NULL, 17, 1),
(26, 'Le parole inclusive', 'Éviter s’il vous plaît les paroles incompréhensibles dans la messagerie, et respecter vos camarades', 'info', 'high', '2025-03-29 16:49:48', NULL, 12, 1),
(27, 'Le parole inclusive', 'Éviter s’il vous plaît les paroles incompréhensibles dans la messagerie, et respecter vos camarades', 'info', 'high', '2025-03-29 16:49:48', NULL, 17, 1),
(28, 'Le parole inclusive', 'Éviter s’il vous plaît les paroles incompréhensibles dans la messagerie, et respecter vos camarades', 'info', 'high', '2025-03-29 16:49:48', NULL, 26, 0),
(30, 'Le parole inclusive', 'Éviter s’il vous plaît les paroles incompréhensibles dans la messagerie, et respecter vos camarades', 'info', 'high', '2025-03-29 16:49:48', NULL, 28, 0),
(31, 'Le parole inclusive', 'Éviter s’il vous plaît les paroles incompréhensibles dans la messagerie, et respecter vos camarades', 'info', 'high', '2025-03-29 16:49:48', NULL, 29, 1),
(32, 'H', 'Hh', 'info', 'normal', '2025-03-29 16:52:29', NULL, 17, 1),
(33, 'Hh', 'Hh', 'info', 'normal', '2025-03-29 16:52:42', NULL, 17, 1),
(34, 'Hh', 'Hh', 'info', 'normal', '2025-03-29 16:53:00', NULL, 17, 1),
(35, 'Hhh', 'Hhh', 'info', 'normal', '2025-03-29 16:53:10', NULL, 17, 1),
(37, 'رد البال !!', 'رد بالك من الصفحات لي كيطلبو ليك المعلومات الشخصية و البنكية .', 'info', 'high', '2025-03-30 19:21:03', NULL, 12, 1),
(38, 'رد البال !!', 'رد بالك من الصفحات لي كيطلبو ليك المعلومات الشخصية و البنكية .', 'info', 'high', '2025-03-30 19:21:03', NULL, 17, 1),
(39, 'رد البال !!', 'رد بالك من الصفحات لي كيطلبو ليك المعلومات الشخصية و البنكية .', 'info', 'high', '2025-03-30 19:21:03', NULL, 26, 0),
(41, 'رد البال !!', 'رد بالك من الصفحات لي كيطلبو ليك المعلومات الشخصية و البنكية .', 'info', 'high', '2025-03-30 19:21:03', NULL, 28, 0),
(42, 'رد البال !!', 'رد بالك من الصفحات لي كيطلبو ليك المعلومات الشخصية و البنكية .', 'info', 'high', '2025-03-30 19:21:04', NULL, 29, 1),
(43, 'Attention !', 'fait attention aux cybernet !!', 'warning', 'high', '2025-03-31 22:14:10', NULL, 17, 1),
(50, 'SHUUT !!', 'fait attention a votre compte !', 'warning', 'high', '2025-04-03 20:21:01', NULL, 17, 1),
(51, 'cc', 'ccc', 'info', 'normal', '2025-04-03 20:45:45', NULL, 12, 1),
(52, 'cc', 'vv', 'info', 'normal', '2025-04-03 20:46:00', NULL, 12, 1),
(53, 'vv', 'vv', 'info', 'normal', '2025-04-03 20:46:09', NULL, 12, 1),
(54, 'nn', 'nn', 'info', 'normal', '2025-04-03 20:46:23', NULL, 12, 1),
(55, 'cc', 'cc', 'info', 'normal', '2025-04-04 09:34:55', NULL, 17, 1),
(56, 'hi', 'ffffffffffffff', 'info', 'normal', '2025-04-04 09:35:06', NULL, 17, 1),
(57, 'attention !', 'hhhhhh', 'info', 'normal', '2025-04-04 09:44:49', NULL, 12, 1),
(58, 'aa', 'aa', 'info', 'normal', '2025-04-04 11:20:37', NULL, 17, 1),
(59, 'aaaa', 'aa', 'info', 'normal', '2025-04-04 11:20:49', NULL, 17, 1),
(60, 'Attention !', 'attention a vos comportements !', 'warning', 'normal', '2025-04-04 11:37:12', NULL, 34, 1),
(61, 'Esspionage', 'fait attention a l\'esspionage !!', 'warning', 'normal', '2025-04-05 15:31:29', NULL, 12, 0),
(62, 'Attention ⚠️', 'Attention a votre comportement dans la messagerie !', 'warning', 'normal', '2025-04-05 16:39:01', NULL, 35, 1),
(64, 'ccc', '.', 'info', 'normal', '2025-04-07 13:47:46', NULL, 29, 1),
(65, 'zz', 'zz', 'info', 'normal', '2025-04-07 14:58:20', NULL, 29, 1),
(66, 'z', 'z', 'info', 'normal', '2025-04-07 14:58:33', NULL, 12, 0),
(67, 'z', 'z', 'info', 'normal', '2025-04-07 14:58:53', NULL, 35, 0),
(68, 'cc', 'cc', 'info', 'normal', '2025-04-09 09:01:50', NULL, 17, 1);

-- --------------------------------------------------------

--
-- Structure de la table `notification_recipients`
--

CREATE TABLE `notification_recipients` (
  `id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `plan` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `stripe_subscription_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `subtasks`
--

CREATE TABLE `subtasks` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `completed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `support_requests`
--

CREATE TABLE `support_requests` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `support_requests`
--

INSERT INTO `support_requests` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(6, 'ABDO', 'elkarchayoub@gmail.com', 'creation de la bse de donnees', 'Essaie ce fichier et dis-moi si tu as besoin d\'améliorations !', '2025-03-14 10:08:44'),
(7, 'ABDO', '3ab2uelkarch2006@gmail.com', 'creation de la bse de donnees', 'Erreur lors de l\'envoi du message.', '2025-03-14 10:34:18'),
(8, 'NAIMA ELKARCH', 'admin2006@gmail.com', 'creation de la bse de donnees', 'bbbbbbbbb', '2025-03-14 10:34:35'),
(9, 'ABDO', '3ab2uelkarch2006@gmail.com', 'creation de la bse de donnees', 'hhhhhhhhhhhh', '2025-03-14 11:17:26'),
(10, 'ABDO', '3ab2uelkarch2006@gmail.com', 'creation de la bse de donnees', 'aaaaaaaaaaa', '2025-03-14 11:19:16'),
(11, 'ADMIN', 'elkarchabdo@gmail.com', 'creation de la bse de donnees', 'gffgfzgfzsf', '2025-03-14 11:22:05'),
(12, 'HAMID', 'elkarchabdo@gmail.com', 'le style', 'mouvais !!', '2025-03-16 15:06:35'),
(13, 'ELKARCH', 'elkarchabd@gmail.com', 'Cryptogramme', 'Votre site est mal sécurise !!', '2025-03-29 15:10:19');

-- --------------------------------------------------------

--
-- Structure de la table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('to_do','in_progress','completed') DEFAULT 'to_do',
  `priority` enum('low','medium','high') DEFAULT 'medium',
  `due_date` date DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `assigned_by` int(11) DEFAULT NULL COMMENT 'ID de l''admin qui a assigné la tâche'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `user_id`, `status`, `priority`, `due_date`, `deadline`, `created_at`, `updated_at`, `assigned_by`) VALUES
(52, 'Sss', '', 29, 'in_progress', 'medium', NULL, '2025-04-27 00:00:00', '2025-03-29 16:48:24', '2025-04-09 08:03:16', NULL),
(58, 'Titre', 'Ddd', 35, 'completed', 'medium', NULL, '2025-04-05 00:00:00', '2025-04-05 16:31:55', '2025-04-05 16:32:12', NULL),
(68, 'Hhh', '', 29, 'to_do', 'low', NULL, '2025-04-07 00:00:00', '2025-04-07 14:56:17', '2025-04-09 07:47:04', NULL),
(69, 'Dd', '', 29, 'completed', 'low', NULL, '2025-04-07 00:00:00', '2025-04-07 14:56:49', '2025-04-09 08:12:55', NULL),
(70, 'hhhh', '', 38, 'completed', 'low', NULL, '2025-04-01 00:00:00', '2025-04-07 18:27:30', '2025-04-08 13:32:18', NULL),
(71, 'cc', 'cc', 38, 'completed', 'medium', NULL, '2025-04-27 00:00:00', '2025-04-08 13:32:10', '2025-04-08 13:32:16', NULL),
(72, 'cc', '', 17, 'completed', 'low', NULL, '2025-04-27 00:00:00', '2025-04-09 09:36:45', '2025-04-09 09:49:03', NULL),
(73, 'Rédiger un rapport hebdomadaire', 'Préparer un résumé des activités réalisées durant la semaine.', 39, 'to_do', 'low', NULL, '2025-04-01 00:00:00', '2025-04-09 10:40:51', '2025-04-09 18:22:47', NULL),
(74, 'Organiser une réunion d’équipe', 'Planifier une réunion pour faire le point sur l’avancement des projets.', 39, 'in_progress', 'medium', NULL, '2025-04-20 00:00:00', '2025-04-09 10:41:33', '2025-04-09 18:22:29', NULL),
(75, 'Répondre aux emails urgents', 'Traiter les messages importants reçus dans la boîte mail.', 39, 'completed', 'high', NULL, '2025-04-09 00:00:00', '2025-04-09 10:42:10', '2025-04-09 18:23:06', NULL),
(76, 'Mettre à jour le site web', 'Apporter les modifications nécessaires sur le contenu du site.', 39, 'in_progress', 'low', NULL, '2025-04-01 00:00:00', '2025-04-09 10:43:02', '2025-04-09 18:23:05', NULL),
(77, 'Effectuer une sauvegarde des données', 'Sauvegarder les fichiers importants pour éviter toute perte.', 39, 'completed', 'medium', NULL, '2025-04-20 00:00:00', '2025-04-09 10:43:36', '2025-04-09 18:22:51', NULL),
(78, 'Réviser un document', 'Relire et corriger un texte avant sa publication ou son envoi.', 39, 'to_do', 'high', NULL, '2025-04-27 00:00:00', '2025-04-09 10:44:15', '2025-04-09 18:22:36', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `status` enum('available','busy','away') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profile_picture` varchar(255) DEFAULT 'default.png',
  `is_admin` tinyint(1) DEFAULT 0,
  `admin_level` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `remember_token` varchar(64) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `plan` enum('free','premium') DEFAULT 'free'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`, `profile_picture`, `is_admin`, `admin_level`, `active`, `last_login`, `remember_token`, `token_expiry`, `plan`) VALUES
(12, 'SIMO', 'simohammed@gmail.com', '$2y$10$Pve63xA8C562VzmpX/T1V.SmSEyxMMUttM3QOE8tLy6nWgvrD0OVW', 'user', 'busy', '2025-03-14 08:41:41', '2025-04-03 21:08:05', 'profile_12.jpg', 0, 0, 1, NULL, NULL, NULL, 'free'),
(17, 'HJAWZI', 'zouhirhjawzi@gmail.com', '$2y$10$8hRR45NLvOj7dY7MJra38OwfOvuc2KnbwOeBt8lrCvX2F55WMgE.C', 'user', 'busy', '2025-03-16 15:24:21', '2025-04-09 09:37:35', 'default.png', 0, 0, 1, NULL, NULL, NULL, 'free'),
(26, 'ADMIN', 'admintaskflow@gmail.com', '$2y$10$ECfn4K1DOwB8lkr.4NdwQOztYLM9AcbvX3D2JPVPGzClIjkPITLHy', 'admin', 'available', '2025-03-25 22:44:32', '2025-03-26 22:42:16', 'default.png', 0, 0, 1, NULL, NULL, NULL, 'free'),
(28, 'ZIYAD', 'ziyaderraji@gmail.com', '$2y$10$NboWKWJGA9kcfGBVplQWGe9THEPupUVS/yzgdUR7jOUkKeMKqL/zu', 'user', 'available', '2025-03-27 09:05:18', '2025-03-27 09:05:18', 'default.png', 0, 0, 1, NULL, NULL, NULL, 'free'),
(29, 'KAWTAR', 'kawtarhiya@gmail.com', '$2y$10$Vb042Sv2tV0Mgt99idqWD.K7GslU7vF28Ja7QxEDHC01XBOdJPmoy', 'user', 'busy', '2025-03-29 16:47:43', '2025-04-09 07:50:37', 'default.png', 0, 0, 1, NULL, NULL, NULL, 'free'),
(34, 'zyad', 'ziad.anjare789@gmail.com', '$2y$10$iVEXVgzKqWx.25yrLeBGjeHyhztV4WvbxIfyrzclxuZt5JWuXcAM6', 'user', 'available', '2025-04-04 11:35:25', '2025-04-04 11:35:25', 'default.png', 0, 0, 1, NULL, NULL, NULL, 'free'),
(35, 'Reda slimani', 'redaslimani@gmail.com', '$2y$10$rFTHMl7IoTdFismGqOgxPe1XnNtqgDQtqaxIliT7MJ0bJ5.4e3LOy', 'user', 'available', '2025-04-05 16:31:05', '2025-04-05 16:34:31', 'default.png', 0, 0, 1, NULL, NULL, NULL, 'free'),
(38, 'SALIM', 'salim@gmail.com', '$2y$10$tJ7/1x633Yb4d.sPdDA68uk0SbOSBzEIVjEzJ/bKCHIw7wFVml8la', 'user', 'busy', '2025-04-07 13:27:35', '2025-04-07 18:28:33', 'default.png', 0, 0, 1, NULL, NULL, NULL, 'free'),
(39, 'Utilisateur', 'utilisateur@gmail.com', '$2y$10$sqXpZV1kMydBjKR1nQd57.b9e5/Qp/lNZp0dQxzCT1SsXk6IebjX.', 'user', 'busy', '2025-04-09 10:39:10', '2025-04-09 12:40:24', 'default.png', 0, 0, 1, NULL, NULL, NULL, 'free');

-- --------------------------------------------------------

--
-- Structure de la table `user_preferences`
--

CREATE TABLE `user_preferences` (
  `user_id` int(11) NOT NULL,
  `theme` enum('light','dark') DEFAULT 'light',
  `notifications_enabled` tinyint(1) DEFAULT 1,
  `language` enum('fr','en','ar') DEFAULT 'fr',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_preferences`
--

INSERT INTO `user_preferences` (`user_id`, `theme`, `notifications_enabled`, `language`, `updated_at`) VALUES
(26, 'dark', 1, 'fr', '2025-03-26 13:29:51');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin_activity`
--
ALTER TABLE `admin_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `sent_by` (`sent_by`);

--
-- Index pour la table `message_reads`
--
ALTER TABLE `message_reads`
  ADD PRIMARY KEY (`user_id`);

--
-- Index pour la table `message_read_logs`
--
ALTER TABLE `message_read_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `message_recipients`
--
ALTER TABLE `message_recipients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_id` (`message_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `fk_user_id_unique` (`user_id`);

--
-- Index pour la table `notification_recipients`
--
ALTER TABLE `notification_recipients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_notification_user` (`notification_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `subtasks`
--
ALTER TABLE `subtasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`);

--
-- Index pour la table `support_requests`
--
ALTER TABLE `support_requests`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tasks_user` (`user_id`),
  ADD KEY `idx_tasks_status_tracking` (`status`),
  ADD KEY `idx_tasks_deadline_tracking` (`deadline`),
  ADD KEY `assigned_by` (`assigned_by`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin_activity`
--
ALTER TABLE `admin_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=228;

--
-- AUTO_INCREMENT pour la table `message_read_logs`
--
ALTER TABLE `message_read_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT pour la table `message_recipients`
--
ALTER TABLE `message_recipients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT pour la table `notification_recipients`
--
ALTER TABLE `notification_recipients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `subtasks`
--
ALTER TABLE `subtasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `support_requests`
--
ALTER TABLE `support_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `admin_activity`
--
ALTER TABLE `admin_activity`
  ADD CONSTRAINT `admin_activity_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sent_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `message_reads`
--
ALTER TABLE `message_reads`
  ADD CONSTRAINT `message_reads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `message_read_logs`
--
ALTER TABLE `message_read_logs`
  ADD CONSTRAINT `message_read_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `message_recipients`
--
ALTER TABLE `message_recipients`
  ADD CONSTRAINT `message_recipients_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_recipients_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_user_id_unique` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notification_recipients`
--
ALTER TABLE `notification_recipients`
  ADD CONSTRAINT `notification_recipients_ibfk_1` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notification_recipients_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `subtasks`
--
ALTER TABLE `subtasks`
  ADD CONSTRAINT `subtasks_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `fk_user_preferences_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
