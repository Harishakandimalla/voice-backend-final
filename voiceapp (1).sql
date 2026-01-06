-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2026 at 05:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `voiceapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `ai_suggestions`
--

CREATE TABLE `ai_suggestions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `suggestion` text NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ai_suggestions`
--

INSERT INTO `ai_suggestions` (`id`, `user_id`, `suggestion`, `is_active`, `created_at`) VALUES
(1, 1, 'Your tasks are well organized. Keep it up!', 0, '2025-12-28 14:00:28'),
(2, 1, 'Great job! Your tasks are well organized.', 1, '2025-12-28 14:05:05');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(20) NOT NULL COMMENT 'activity | reminder | ai',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `is_read`, `created_at`) VALUES
(2, 11, 'Task Completed', 'Task \"hi\" marked as done', 'TASK_COMPLETED', 0, '2026-01-02 07:57:13'),
(3, 11, 'Task Completed', 'Task \"hii\" marked as done', 'TASK_COMPLETED', 0, '2026-01-02 07:57:57'),
(4, 11, 'Task Completed', 'Task \"hello\" marked as done', 'TASK_COMPLETED', 0, '2026-01-02 07:58:09'),
(5, 11, 'Task Completed', 'Task \"hi\" marked as done', 'TASK_COMPLETED', 0, '2026-01-02 07:58:13'),
(6, 11, 'Task Completed', 'Task \"hi\" marked as done', 'TASK_COMPLETED', 0, '2026-01-04 11:12:53'),
(7, 11, 'Task Completed', 'Task \"create a task and  5:00 p.m.\" marked as done', 'TASK_COMPLETED', 0, '2026-01-04 11:15:31'),
(8, 11, 'Task Completed', 'Task \"New Task\" marked as done', 'TASK_COMPLETED', 0, '2026-01-05 03:42:25'),
(9, 11, 'Task Completed', 'Task \"Buy Milk\" marked as done', 'TASK_COMPLETED', 0, '2026-01-05 03:42:33'),
(10, 11, 'Task Completed', 'Task \"Task 523\" marked as done', 'TASK_COMPLETED', 0, '2026-01-05 03:42:35'),
(11, 11, 'Task Completed', 'Task \"Task\" marked as done', 'TASK_COMPLETED', 0, '2026-01-05 03:42:37'),
(12, 11, 'Task Completed', 'Task \"Task\" marked as done', 'TASK_COMPLETED', 0, '2026-01-05 03:42:39'),
(13, 11, 'Task Completed', 'Task \"team\" marked as done', 'TASK_COMPLETED', 0, '2026-01-05 03:42:44');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `task_date` date NOT NULL,
  `task_time` time DEFAULT NULL,
  `status` enum('PENDING','COMPLETED') DEFAULT 'PENDING',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(50) NOT NULL DEFAULT 'General',
  `priority` varchar(20) NOT NULL DEFAULT 'MEDIUM'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `task_date`, `task_time`, `status`, `created_at`, `category`, `priority`) VALUES
(1, 1, 'Team meeting', 'Discuss project status', '2025-12-31', '17:00:00', 'COMPLETED', '2025-12-31 06:26:35', 'General', 'MEDIUM'),
(16, 11, 'dj', 'hello', '2026-01-05', '09:12:49', 'PENDING', '2026-01-05 03:42:58', 'Work', 'MEDIUM');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(200) NOT NULL COMMENT 'PRIMARY KEY',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'FULL NAME',
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'user mail',
  `password_hash` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hashed password',
  `verified` tinyint(1) NOT NULL COMMENT '0 = not verified',
  `verification_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'OTP',
  `verification_expiry` datetime DEFAULT NULL COMMENT 'OTP expiry',
  `reset_token` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Forgot-password token',
  `reset_expiry` datetime DEFAULT NULL COMMENT 'Reset expiry',
  `display_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Profile display name',
  `occupation` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Profile occupation',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Created time',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Auto update',
  `reset_otp` varchar(10) DEFAULT NULL,
  `reset_otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `verified`, `verification_code`, `verification_expiry`, `reset_token`, `reset_expiry`, `display_name`, `occupation`, `created_at`, `updated_at`, `reset_otp`, `reset_otp_expiry`) VALUES
(11, 'harish', 'harishkandimalla92@gmail.com', '$2y$10$FpiGXhXolLEnDRlJGm1da..Rey.eM2QFtHxGbgAZcMqbRMlUL/Lwa', 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-17 07:32:56', '2025-12-17 07:32:56', NULL, NULL),
(13, 'Harish', 'harishkandimalla922@gmail.com', '$2y$10$Q7YmomRzOzEnQcEGOC1kbO6gjSlnEOx61qrSYwMbMs2sERAtsN9/6', 0, '340431', '2025-12-18 05:40:26', NULL, NULL, NULL, NULL, '2025-12-18 04:30:26', '2025-12-18 04:30:26', NULL, NULL),
(38, 'harish', 'yagneshbattu4177.sse@saveetha.com', '$2y$10$Lb7NmckpQVMsx/pMtsb3EucLJ8qsTbDXO1uua3/vD3L6KzAL7.RhS', 0, '907408', '2026-01-05 06:14:02', NULL, NULL, NULL, NULL, '2026-01-05 04:59:03', '2026-01-05 04:59:03', NULL, NULL),
(39, 'harish', 'test@gmail.com', '$2y$10$GtjVTDJMuBSGw7vg69myyuLIUw5PGOv7y/OqUE.aWYSqAp4YiB38.', 0, '795712', '2026-01-05 06:14:36', NULL, NULL, NULL, NULL, '2026-01-05 04:59:36', '2026-01-05 04:59:36', NULL, NULL),
(40, 'harish', 'test@12345gmail.com', '$2y$10$8qfT7VJ0iMB/LG6rgaNqDekri7JN/Wwe2E8cvWtehJxxcCQ2qg3di', 0, '550814', '2026-01-05 06:14:50', NULL, NULL, NULL, NULL, '2026-01-05 04:59:50', '2026-01-05 04:59:50', NULL, NULL),
(41, 'harish', 'test1234@gmail.com', '$2y$10$pr7/8RtsDRE4SEIQ7ZWBN.d2BUfwydeKR6Qz.3vSmXNFHpUfxJLoy', 0, '145608', '2026-01-05 07:56:53', NULL, NULL, NULL, NULL, '2026-01-05 06:41:53', '2026-01-05 06:41:53', NULL, NULL),
(42, 'harish', 'testuu12@gmail.com', '$2y$10$sx7UTinSuZ06NkK76i7BLeDQImdLG91y9q6a3CTBD/VRpEe6XGM9u', 0, '125560', '2026-01-05 07:57:30', NULL, NULL, NULL, NULL, '2026-01-05 06:42:31', '2026-01-05 06:42:31', NULL, NULL),
(43, 'yagnesh', 'testuu1234@gmail.com', '$2y$10$i.UMct/iVNFA1Fu1uxcOQO2bbZ6hUo.02rA0U8wyrbIqDV1YWuMRu', 0, '284258', '2026-01-05 08:10:19', NULL, NULL, NULL, NULL, '2026-01-05 06:55:20', '2026-01-05 06:55:20', NULL, NULL),
(45, 'harish', 'harishk4173.sse@saveetha.com', '$2y$10$nM8G/WV7/uEZCM37IJoniOJdYsBFayexYiJe7wZV.mxjWzH/sXm6y', 0, '173700', '2026-01-05 09:25:22', NULL, NULL, NULL, NULL, '2026-01-05 08:10:22', '2026-01-05 08:10:22', NULL, NULL),
(47, 'harish', 'testuu1234y@gmail.com', '$2y$10$JjBWadikhtEnBCiZ2R0enezbu5baX/xkN/vDbXASxo6RG2zrvP3t2', 0, '230609', '2026-01-05 09:36:00', NULL, NULL, NULL, NULL, '2026-01-05 08:21:00', '2026-01-05 08:21:00', NULL, NULL),
(48, 'harish', 'testlay@gmail.com', '$2y$10$ncEa9Y7bPvvEcbMFOF3qdeQ1R5He.jEWeVnSdvkpMzYqX05OACsEC', 0, '523065', '2026-01-05 09:48:38', NULL, NULL, NULL, NULL, '2026-01-05 08:33:38', '2026-01-05 08:33:38', NULL, NULL),
(49, 'harish', 'testlay123@gmail.com', '$2y$10$EBQhkgM2Qb4G.qRsxu1kE.S9l4I0N57otlo7yLomHgIInnycFY9IC', 0, '318017', '2026-01-05 09:56:03', NULL, NULL, NULL, NULL, '2026-01-05 08:41:03', '2026-01-05 08:41:03', NULL, NULL),
(50, 'tejaswi', 'greeshmamalineni@gmail.com', '$2y$10$G/JstYjj75BmMfjcR1PngeTvQMHIHiqrLlpPzmfAUw7jkwTMzi2uu', 0, '260611', '2026-01-05 09:57:47', NULL, NULL, NULL, NULL, '2026-01-05 08:42:47', '2026-01-05 08:42:47', NULL, NULL),
(53, 'hari', 'layna4115@gmail.com', '$2y$10$LqmEP.c2RVdVj8DzM2NhV.JInIk7CmkqP/fe/KiWdMoGw7vB3J5JO', 0, '482295', '2026-01-05 10:39:02', NULL, NULL, NULL, NULL, '2026-01-05 09:24:03', '2026-01-05 09:24:03', NULL, NULL),
(54, 'Harish', 'harishkandimalla40@gmail.com', '$2y$10$qEAsCt.rOfWpLNoc0k7jU./IBfw5xii0fL7eJob0fWGTCE9oZfwg.', 0, '421036', '2026-01-05 15:10:11', NULL, NULL, NULL, NULL, '2026-01-05 13:55:11', '2026-01-05 13:55:11', NULL, NULL),
(57, 'Harish', 'enumula0119@gmail.com', '$2y$10$I.9RnEDte5kUE0RtnF7g9esQBtwrJxJWVl0V.iUfOEJMKsQt3vKE.', 0, '486798', '2026-01-05 15:32:44', NULL, NULL, NULL, NULL, '2026-01-05 14:17:44', '2026-01-05 14:17:44', NULL, NULL),
(58, 'Harish', 'testyu123@gmail.com', '$2y$10$Jvu5ITDDEtv49CWvN/0X3Ori4S2rkfDvy4e4P89DwN9mOzWGh9pa6', 0, '357292', '2026-01-05 15:35:05', NULL, NULL, NULL, NULL, '2026-01-05 14:20:05', '2026-01-05 14:20:05', NULL, NULL),
(59, 'Harish', 'testyu1234@gmail.com', '$2y$10$gC2X2/UiMmKCEB82aoX/TO.kds73eAzUzdUTG8Lfi4Qi5ih.ABzNS', 0, '645440', '2026-01-05 15:38:19', NULL, NULL, NULL, NULL, '2026-01-05 14:23:19', '2026-01-05 14:23:19', NULL, NULL),
(60, 'harish', 'testyyyuu@gmail.com', '$2y$10$4kErzsZMjgHHYVRYgcoKW.nnhtVUvOA1ARxKhU3YX.6iR0C.eD/6i', 0, '376723', '2026-01-06 04:59:56', NULL, NULL, NULL, NULL, '2026-01-06 03:44:56', '2026-01-06 03:44:56', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `user_id` int(11) NOT NULL,
  `notification_sound` varchar(50) DEFAULT 'Default',
  `weekly_day` varchar(20) DEFAULT 'Friday',
  `weekly_time` time DEFAULT '17:00:00',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_settings`
--

INSERT INTO `user_settings` (`user_id`, `notification_sound`, `weekly_day`, `weekly_time`, `created_at`) VALUES
(1, 'Chime', 'Monday', '09:00:00', '2025-12-28 13:08:23');

-- --------------------------------------------------------

--
-- Table structure for table `voice_logs`
--

CREATE TABLE `voice_logs` (
  `id` int(11) NOT NULL,
  `voice_text` text NOT NULL,
  `parsed_title` varchar(255) DEFAULT NULL,
  `parsed_date` date DEFAULT NULL,
  `parsed_time` time DEFAULT NULL,
  `created_task_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `weekly_summary`
--

CREATE TABLE `weekly_summary` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `week_start` date NOT NULL,
  `week_end` date NOT NULL,
  `total_tasks` int(11) DEFAULT 0,
  `completed_tasks` int(11) DEFAULT 0,
  `pending_tasks` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `weekly_summary`
--

INSERT INTO `weekly_summary` (`id`, `user_id`, `week_start`, `week_end`, `total_tasks`, `completed_tasks`, `pending_tasks`, `created_at`) VALUES
(1, 1, '2025-12-22', '2025-12-28', 0, NULL, NULL, '2025-12-28 13:22:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ai_suggestions`
--
ALTER TABLE `ai_suggestions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notifications_user` (`user_id`),
  ADD KEY `idx_notifications_user_type` (`user_id`,`type`),
  ADD KEY `idx_notifications_user_type_read_date` (`user_id`,`type`,`is_read`,`created_at`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tasks_user_id` (`user_id`),
  ADD KEY `idx_tasks_user_status` (`user_id`,`status`),
  ADD KEY `idx_tasks_user_date` (`user_id`,`task_date`),
  ADD KEY `idx_tasks_date_time` (`task_date`,`task_time`),
  ADD KEY `idx_tasks_user_status_created` (`user_id`,`status`,`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `voice_logs`
--
ALTER TABLE `voice_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weekly_summary`
--
ALTER TABLE `weekly_summary`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ai_suggestions`
--
ALTER TABLE `ai_suggestions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT COMMENT 'PRIMARY KEY', AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `voice_logs`
--
ALTER TABLE `voice_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weekly_summary`
--
ALTER TABLE `weekly_summary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
