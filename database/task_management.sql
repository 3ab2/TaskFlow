-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 18 مارس 2025 الساعة 11:40
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `task_management`
--

-- --------------------------------------------------------

--
-- بنية الجدول `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `content`, `created_at`) VALUES
(11, 5, 'CV BIKHIR', '2025-03-13 14:31:50'),
(23, 4, 'salam', '2025-03-16 15:07:53'),
(24, 17, 'malkom fikom sda3', '2025-03-16 15:25:23'),
(25, 18, 'KHALIWNI NL3EB PES', '2025-03-16 16:00:25'),
(30, 5, 'hhh', '2025-03-16 16:16:57'),
(31, 4, 'hello', '2025-03-17 22:41:33'),
(33, 19, 'fain', '2025-03-17 22:45:53');

-- --------------------------------------------------------

--
-- بنية الجدول `subtasks`
--

CREATE TABLE `subtasks` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `completed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `support_requests`
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
-- إرجاع أو استيراد بيانات الجدول `support_requests`
--

INSERT INTO `support_requests` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(6, 'ABDO', 'elkarchayoub@gmail.com', 'creation de la bse de donnees', 'Essaie ce fichier et dis-moi si tu as besoin d\'améliorations !', '2025-03-14 10:08:44'),
(7, 'ABDO', '3ab2uelkarch2006@gmail.com', 'creation de la bse de donnees', 'Erreur lors de l\'envoi du message.', '2025-03-14 10:34:18'),
(8, 'NAIMA ELKARCH', 'admin2006@gmail.com', 'creation de la bse de donnees', 'bbbbbbbbb', '2025-03-14 10:34:35'),
(9, 'ABDO', '3ab2uelkarch2006@gmail.com', 'creation de la bse de donnees', 'hhhhhhhhhhhh', '2025-03-14 11:17:26'),
(10, 'ABDO', '3ab2uelkarch2006@gmail.com', 'creation de la bse de donnees', 'aaaaaaaaaaa', '2025-03-14 11:19:16'),
(11, 'ADMIN', 'elkarchabdo@gmail.com', 'creation de la bse de donnees', 'gffgfzgfzsf', '2025-03-14 11:22:05'),
(12, 'HAMID', 'elkarchabdo@gmail.com', 'le style', 'mouvais !!', '2025-03-16 15:06:35');

-- --------------------------------------------------------

--
-- بنية الجدول `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('to_do','in_progress','completed') DEFAULT 'to_do',
  `priority` enum('low','medium','high') DEFAULT 'medium',
  `deadline` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `user_id`, `status`, `priority`, `deadline`, `created_at`, `updated_at`) VALUES
(1, 'Creation de la base donnees', '', 4, 'completed', 'medium', '2025-03-10 00:00:00', '2025-03-12 00:22:11', '2025-03-18 09:50:08'),
(5, 'Developpement du code', 'pour adel elyessfi', 4, 'completed', 'high', '2025-03-30 00:00:00', '2025-03-12 12:07:53', '2025-03-18 09:51:06'),
(13, 'DD', '', 5, 'in_progress', 'medium', '2025-03-23 00:00:00', '2025-03-13 14:32:47', '2025-03-16 15:43:37'),
(14, 'aa', '', 4, 'completed', 'low', '2025-03-29 00:00:00', '2025-03-13 22:58:15', '2025-03-18 09:20:21'),
(15, 'ss', '', 4, 'completed', 'low', '2025-03-08 00:00:00', '2025-03-13 22:58:28', '2025-03-18 09:50:09'),
(16, 'hh', '', 4, 'completed', 'high', '2025-03-10 00:00:00', '2025-03-13 22:58:44', '2025-03-18 09:51:22'),
(18, 'BB', '', 4, 'completed', 'medium', '2025-04-06 00:00:00', '2025-03-14 08:20:29', '2025-03-18 09:19:33'),
(19, 'GG', '', 4, 'completed', 'low', '2025-03-16 00:00:00', '2025-03-14 08:20:46', '2025-03-18 09:19:36'),
(21, 'LAHSSEN', '', 4, 'completed', 'high', '2025-03-30 00:00:00', '2025-03-14 09:45:07', '2025-03-18 09:19:47'),
(22, 'AA', '', 5, 'completed', 'high', '2025-03-30 00:00:00', '2025-03-16 15:42:54', '2025-03-16 15:43:31'),
(23, 'MM', '', 5, 'to_do', 'low', '2025-03-10 00:00:00', '2025-03-16 15:43:21', '2025-03-16 15:43:21'),
(24, 'XX', '', 4, 'completed', 'medium', '2025-03-23 00:00:00', '2025-03-16 15:52:07', '2025-03-18 09:42:55'),
(25, 'PES ', '', 18, 'in_progress', 'high', '2025-03-29 00:00:00', '2025-03-16 15:59:55', '2025-03-16 15:59:55'),
(26, 'sss', '', 4, 'completed', 'medium', '2025-03-29 00:00:00', '2025-03-16 21:50:55', '2025-03-18 09:51:28'),
(27, 'xzx', '', 4, 'completed', 'medium', '2025-03-30 00:00:00', '2025-03-16 21:58:36', '2025-03-17 11:34:24'),
(28, 'xzx', '', 4, 'completed', 'medium', '2025-03-30 00:00:00', '2025-03-16 21:58:36', '2025-03-18 09:42:02'),
(29, 'qq', '', 4, 'completed', 'medium', '2025-03-23 00:00:00', '2025-03-17 10:12:38', '2025-03-18 09:51:23'),
(30, 'dd', '', 4, 'completed', 'medium', '2025-03-30 00:00:00', '2025-03-17 10:12:59', '2025-03-18 09:41:50'),
(31, 'qq', '', 4, 'completed', 'medium', '2025-03-30 00:00:00', '2025-03-17 13:25:10', '2025-03-18 09:42:04'),
(32, 'dd', '', 19, 'in_progress', 'medium', '2025-03-30 00:00:00', '2025-03-17 22:46:42', '2025-03-17 22:46:42'),
(33, 'bb', '', 19, 'in_progress', 'low', '2025-03-30 00:00:00', '2025-03-17 22:46:56', '2025-03-17 22:46:56');

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `status` enum('available','busy','away') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(4, 'ABDOU', '3ab2uelkarch2006@gmail.com', '$2y$10$ECSZ23zmgrieEyCDYKNG7OLkg6J7spOcOxti2lPD5JcppVHaIb6IO', 'user', 'busy', '2025-03-12 00:21:26', '2025-03-18 10:12:57'),
(5, 'AYOUB', 'elkarchabdo@gmail.com', '$2y$10$yGQAVVxMSba2hJkl37b6H.PIqO5q0Vo3NcrW7fjnk9EFlfCZZdy7m', 'user', 'busy', '2025-03-12 13:12:57', '2025-03-16 16:11:22'),
(8, 'REDA', 'reda@gmail.com', '$2y$10$l5h15HEs5XkrPMA6p9WgL.Becb1tlLrB2eqs/VNKTcl4HUhnp5.12', 'user', 'busy', '2025-03-13 09:33:40', '2025-03-13 09:34:29'),
(11, 'WALID', 'walidhadine@gmail.com', '$2y$10$sbLJBSI/RhvoKlAvg7KumeEH8LP1wc2WGaqxsh0m585qrqTQ/QwJ6', 'user', 'available', '2025-03-14 08:39:13', '2025-03-14 08:39:13'),
(12, 'SIMO', 'simohammed@gmail.com', '$2y$10$Pve63xA8C562VzmpX/T1V.SmSEyxMMUttM3QOE8tLy6nWgvrD0OVW', 'user', 'busy', '2025-03-14 08:41:41', '2025-03-14 08:42:19'),
(13, 'admin', 'admin@gmail.com', 'ADMIN2025', 'user', 'available', '2025-03-16 14:53:09', '2025-03-17 22:51:05'),
(17, 'HJAWZY', 'zouhirhjawzi@gmail.com', '$2y$10$8hRR45NLvOj7dY7MJra38OwfOvuc2KnbwOeBt8lrCvX2F55WMgE.C', 'user', 'available', '2025-03-16 15:24:21', '2025-03-16 15:24:21'),
(18, 'ACHRAF', 'achrafboukhiar@gmail.com', '$2y$10$3OXv9N1bCGUKxDv/7plpZ.lMx8mBA0U0.84/TxOhCvpZj4iDKbME.', 'user', 'busy', '2025-03-16 15:40:14', '2025-03-16 16:00:10'),
(19, 'ADEL', 'adil@gmail.com', '$2y$10$zQy6.xnepHEoeFKmoVIkzubilkMqgrQR.U0rSLNKj/u/xgE9DoOcq', 'user', 'available', '2025-03-17 22:42:33', '2025-03-17 22:44:47');

-- --------------------------------------------------------

--
-- بنية الجدول `user_preferences`
--

CREATE TABLE `user_preferences` (
  `user_id` int(11) NOT NULL,
  `theme` enum('light','dark') DEFAULT 'light',
  `notifications_enabled` tinyint(1) DEFAULT 1,
  `language` enum('fr','en','ar') DEFAULT 'fr',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_user_preferences_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Create admin_activity table
--
CREATE TABLE IF NOT EXISTS `admin_activity` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `admin_id` INT NOT NULL,
    `action` VARCHAR(50) NOT NULL,
    `details` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `subtasks`
--
ALTER TABLE `subtasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `support_requests`
--
ALTER TABLE `support_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tasks_user` (`user_id`),
  ADD KEY `idx_tasks_status_tracking` (`status`),
  ADD KEY `idx_tasks_deadline_tracking` (`deadline`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `subtasks`
--
ALTER TABLE `subtasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_requests`
--
ALTER TABLE `support_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `subtasks`
--
ALTER TABLE `subtasks`
  ADD CONSTRAINT `subtasks_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `user_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
