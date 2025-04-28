-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: gymrat_db:3306
-- Generation Time: Apr 27, 2025 at 06:27 PM
-- Server version: 9.3.0
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gymrat`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `to_all` enum('rats','trainers','rats+trainers') NOT NULL DEFAULT 'rats+trainers',
  `source` varchar(100) NOT NULL DEFAULT '',
  `valid_till` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bmi_records`
--

CREATE TABLE `bmi_records` (
  `user` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bmi` float NOT NULL,
  `weight` float NOT NULL,
  `height` float NOT NULL,
  `age` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bmi_records`
--

INSERT INTO `bmi_records` (`user`, `created_at`, `bmi`, `weight`, `height`, `age`) VALUES
(44, '2024-12-10 10:00:00', 28.4, 86.2, 1.74, 23),
(44, '2025-01-15 10:15:00', 27.6, 83.7, 1.74, 23),
(44, '2025-02-20 10:30:00', 26.8, 81.3, 1.74, 23),
(44, '2025-03-25 10:45:00', 25.9, 78.6, 1.74, 23),
(44, '2025-04-20 11:00:00', 25.1, 76.2, 1.74, 23),
(45, '2024-12-15 11:15:00', 26.8, 85, 1.78, 28),
(45, '2025-01-20 11:30:00', 26.1, 82.7, 1.78, 28),
(45, '2025-02-25 11:45:00', 25.4, 80.5, 1.78, 28),
(45, '2025-03-30 12:00:00', 24.9, 78.9, 1.78, 28),
(45, '2025-04-22 12:15:00', 24.1, 76.4, 1.78, 28),
(46, '2024-12-05 09:00:00', 22.9, 65.3, 1.69, 32),
(46, '2025-01-10 09:15:00', 22.5, 64.2, 1.69, 32),
(46, '2025-02-15 09:30:00', 22.3, 63.5, 1.69, 32),
(46, '2025-03-20 09:45:00', 22, 62.7, 1.69, 32),
(46, '2025-04-20 10:00:00', 21.8, 62.1, 1.69, 32),
(47, '2025-01-05 14:00:00', 23.8, 72.5, 1.74, 25),
(47, '2025-02-10 14:15:00', 23.5, 71.6, 1.74, 25),
(47, '2025-03-15 14:30:00', 23.1, 70.4, 1.74, 25),
(47, '2025-04-20 14:45:00', 22.8, 69.5, 1.74, 25),
(48, '2024-11-10 15:00:00', 26.4, 68.4, 1.61, 29),
(48, '2024-12-15 15:15:00', 25.9, 67.1, 1.61, 29),
(48, '2025-01-20 15:30:00', 25.3, 65.5, 1.61, 29),
(48, '2025-02-25 15:45:00', 24.7, 64, 1.61, 29),
(48, '2025-03-30 16:00:00', 24.3, 63, 1.61, 29),
(48, '2025-04-25 16:15:00', 23.9, 61.9, 1.61, 29),
(49, '2024-12-01 10:00:00', 27.2, 78.4, 1.7, 27),
(49, '2025-02-01 10:30:00', 26.3, 75.7, 1.7, 27),
(49, '2025-04-01 11:00:00', 25.1, 72.3, 1.7, 27),
(50, '2024-12-10 13:00:00', 21.3, 56.2, 1.62, 24),
(50, '2025-01-15 13:30:00', 21, 55.4, 1.62, 24),
(50, '2025-02-20 14:00:00', 20.8, 54.9, 1.62, 24),
(50, '2025-03-25 14:30:00', 20.5, 54.1, 1.62, 24),
(50, '2025-04-23 15:00:00', 20.3, 53.6, 1.62, 24),
(51, '2025-02-15 16:00:00', 24.9, 76.8, 1.75, 31),
(51, '2025-03-15 16:30:00', 24.4, 75.2, 1.75, 31),
(51, '2025-04-15 17:00:00', 23.9, 73.7, 1.75, 31),
(52, '2025-01-05 11:00:00', 29.8, 95.3, 1.79, 35),
(52, '2025-02-05 11:30:00', 28.9, 92.4, 1.79, 35),
(52, '2025-03-05 12:00:00', 28.2, 90.1, 1.79, 35),
(52, '2025-04-05 12:30:00', 27.6, 88.3, 1.79, 35),
(53, '2025-03-20 09:00:00', 22.1, 58.5, 1.63, 26),
(53, '2025-04-20 09:30:00', 21.7, 57.4, 1.63, 26),
(54, '2024-12-15 17:00:00', 25.7, 82.3, 1.79, 34),
(54, '2025-02-15 17:30:00', 24.8, 79.4, 1.79, 34),
(54, '2025-04-15 18:00:00', 24.1, 77.2, 1.79, 34);

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int NOT NULL,
  `type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int NOT NULL,
  `user_type` enum('rat','trainer') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'rat',
  `review_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `reviewed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `type`, `description`, `user_id`, `user_type`, `review_message`, `reviewed_at`, `created_at`) VALUES
(16, 'Facility Issues', 'asdasd', 44, 'rat', 'A gym trainer, often referred to as a personal trainer or fitness coach, is an individual who\nspecializes in guiding and instructing clients in their fitness journeys.', '2025-04-30 15:05:23', '2025-04-15 09:35:07'),
(20, 'Membership Issues', 'asda ad sad asd as das das dasd', 44, 'rat', NULL, NULL, '2025-04-15 10:18:18'),
(21, 'Equipment Misuse', 'asd', 44, 'rat', NULL, NULL, '2025-04-15 18:26:07'),
(22, 'Hygiene Concern', '{\"type\":\"CUSTOMER REPORT\",\"customer_id\":44,\"severity\":\"medium\",\"description\":\"He doesnt bring towel all the time.\"}', 1, 'trainer', NULL, NULL, '2025-04-22 06:23:47');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int NOT NULL,
  `fname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'https://stackoverflow.com/questions/247304/what-data-type-to-use-for-hashed-password-field-and-what-length',
  `avatar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `onboarded` tinyint(1) NOT NULL DEFAULT '0',
  `trainer` int DEFAULT NULL,
  `membership_plan` int DEFAULT NULL,
  `membership_plan_activated_at` timestamp NULL DEFAULT NULL,
  `workout` int DEFAULT NULL COMMENT 'add related workout id',
  `meal_plan` int DEFAULT NULL COMMENT 'add related meal plan id',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `fname`, `lname`, `email`, `phone`, `password`, `avatar`, `onboarded`, `trainer`, `membership_plan`, `membership_plan_activated_at`, `workout`, `meal_plan`, `created_at`, `updated_at`) VALUES
(44, 'Theshawa', 'Nimantha', 'mrclocktd@gmail.com', '0766743755', '$2y$10$IGHZG4mmj55XXuoG1U2tzeEELUNWnOub6Ny92ChuLocFD96ftymTO', 'customer-avatars/boy-avatar-1.jpg', 1, 1, 15, '2025-04-15 07:27:46', 5, 3, '2025-04-15 07:27:26', '2025-04-15 07:27:26'),
(45, 'Luka', 'Johnson', 'wadroneth@gmail.com', '+94716060662', '$2y$12$6Ekl5IFW1gZz7vxmsTkKYe4QALJKZAJJ4Q2vpl2XxICbGy0KhUwPS', 'customer-avatars/boy-avatar-2.jpg', 1, 1, 15, '2025-04-15 07:27:46', 1, 3, '2025-04-22 10:26:05', '2025-04-22 10:26:05'),
(46, 'Nadeesha', 'Fernando', 'nadeesha.f@gmail.com', '0761000002', '$2y$10$IGHZG4mmj55XXuoG1U2tzeEELUNWnOub6Ny92ChuLocFD96ftymTO', 'customer-avatars/girl-avatar-1.jpg', 1, 1, 18, '2024-12-01 14:50:55', 1, 2, '2024-12-01 14:50:20', '2024-12-01 14:50:20'),
(47, 'Ravindu', 'Jayasinghe', 'ravindu.j@gmail.com', '0761000003', '$2y$10$IGHZG4mmj55XXuoG1U2tzeEELUNWnOub6Ny92ChuLocFD96ftymTO', 'customer-avatars/boy-avatar-3.jpg', 1, 1, 17, '2025-01-03 07:45:22', 1, NULL, '2025-01-03 07:44:50', '2025-01-03 07:44:50'),
(48, 'Sajani', 'Kariyawasam', 'sajani.k@gmail.com', '0761000004', '$2y$10$IGHZG4mmj55XXuoG1U2tzeEELUNWnOub6Ny92ChuLocFD96ftymTO', 'customer-avatars/girl-avatar-2.jpg', 1, 1, 15, '2025-01-05 10:20:00', NULL, 2, '2024-11-05 10:15:00', '2024-11-05 10:15:00'),
(49, 'Dineth', 'Lakshan', 'dineth.l@gmail.com', '0761000005', '$2y$10$IGHZG4mmj55XXuoG1U2tzeEELUNWnOub6Ny92ChuLocFD96ftymTO', 'customer-avatars/boy-avatar-4.jpg', 1, 1, 17, '2024-11-20 15:10:10', 1, NULL, '2024-11-20 15:09:40', '2024-11-20 15:09:40'),
(50, 'Nimasha', 'Madushani', 'nimasha.m@gmail.com', '0761000006', '$2y$10$IGHZG4mmj55XXuoG1U2tzeEELUNWnOub6Ny92ChuLocFD96ftymTO', 'customer-avatars/girl-avatar-3.jpg', 1, NULL, NULL, '2024-12-01 06:15:00', 1, 2, '2024-12-01 06:14:30', '2024-12-01 06:14:30'),
(51, 'Kalindu', 'Ranasinghe', 'kalindu.r@gmail.com', '0761000007', '$2y$10$IGHZG4mmj55XXuoG1U2tzeEELUNWnOub6Ny92ChuLocFD96ftymTO', 'customer-avatars/boy-avatar-5.jpg', 1, 1, 17, '2025-03-12 09:00:30', NULL, 2, '2025-02-12 09:00:00', '2025-02-12 09:00:00'),
(52, 'Thisara', 'Senanayake', 'thisara.s@gmail.com', '0761000008', '$2y$10$IGHZG4mmj55XXuoG1U2tzeEELUNWnOub6Ny92ChuLocFD96ftymTO', 'customer-avatars/boy-avatar-6.jpg', 1, 1, 18, '2025-01-01 12:10:10', 1, NULL, '2025-01-01 12:09:50', '2025-01-01 12:09:50'),
(53, 'Amaya', 'Disanayaka', 'amaya.d@gmail.com', '0761000009', '$2y$10$IGHZG4mmj55XXuoG1U2tzeEELUNWnOub6Ny92ChuLocFD96ftymTO', 'customer-avatars/girl-avatar-4.jpg', 1, 1, 15, '2025-03-15 17:00:00', 1, 2, '2025-03-15 16:59:30', '2025-03-15 16:59:30'),
(54, 'Yasitha', 'Abeysekera', 'yasitha.a@gmail.com', '0761000010', '$2y$10$IGHZG4mmj55XXuoG1U2tzeEELUNWnOub6Ny92ChuLocFD96ftymTO', 'customer-avatars/boy-avatar-7.jpg', 1, NULL, 17, '2025-04-10 13:35:30', 1, 2, '2024-12-10 13:32:50', '2024-12-10 13:32:50');

-- --------------------------------------------------------

--
-- Table structure for table `customer_email_verification_requests`
--

CREATE TABLE `customer_email_verification_requests` (
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `code` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `creation_attempt` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_initial_data`
--

CREATE TABLE `customer_initial_data` (
  `customer_id` int NOT NULL,
  `gender` varchar(10) NOT NULL,
  `age` int NOT NULL,
  `goal` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `other_goal` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `height` float NOT NULL,
  `weight` float NOT NULL,
  `physical_activity_level` varchar(255) NOT NULL,
  `dietary_preference` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `allergies` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer_initial_data`
--

INSERT INTO `customer_initial_data` (`customer_id`, `gender`, `age`, `goal`, `other_goal`, `height`, `weight`, `physical_activity_level`, `dietary_preference`, `allergies`, `created_at`) VALUES
(44, 'male', 23, 'weight_loss', '', 123, 123, 'beginner', '', '', '2025-04-15 07:30:04'),
(45, 'male', 28, 'muscle_gain', '', 178, 85, 'intermediate', 'high_protein', 'none', '2025-04-22 10:30:00'),
(46, 'female', 32, 'weight_loss', '', 169, 65.3, 'beginner', 'balanced', 'gluten', '2024-12-01 15:00:00'),
(47, 'male', 25, 'muscle_gain', '', 174, 72.5, 'intermediate', 'high_protein', 'none', '2025-01-03 08:00:00'),
(48, 'female', 29, 'weight_loss', '', 161, 68.4, 'beginner', 'low_carb', 'dairy', '2024-11-05 10:30:00'),
(49, 'male', 27, 'weight_loss', '', 170, 78.4, 'intermediate', 'balanced', 'none', '2024-11-20 15:20:00'),
(50, 'female', 24, 'fitness', 'improve_flexibility', 162, 56.2, 'beginner', 'vegetarian', 'nuts', '2024-12-01 06:30:00'),
(51, 'male', 31, 'muscle_gain', '', 175, 76.8, 'advanced', 'high_protein', 'none', '2025-02-12 09:15:00'),
(52, 'male', 35, 'weight_loss', '', 179, 95.3, 'beginner', 'keto', 'shellfish', '2025-01-01 12:20:00'),
(53, 'female', 26, 'fitness', 'improve_endurance', 163, 58.5, 'intermediate', 'balanced', 'none', '2025-03-15 17:10:00'),
(54, 'male', 34, 'muscle_gain', '', 179, 82.3, 'intermediate', 'high_protein', 'none', '2024-12-10 13:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `customer_password_reset_requests`
--

CREATE TABLE `customer_password_reset_requests` (
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `code` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `creation_attempt` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_progress`
--

CREATE TABLE `customer_progress` (
  `id` int NOT NULL,
  `customer_id` int NOT NULL,
  `trainer_id` int NOT NULL,
  `message` text NOT NULL,
  `performance_type` enum('well_done','try_harder') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer_progress`
--

INSERT INTO `customer_progress` (`id`, `customer_id`, `trainer_id`, `message`, `performance_type`, `created_at`) VALUES
(26, 44, 1, 'Your strength gains in bench press are remarkable. Keep pushing!', 'well_done', '2025-02-01 03:00:00'),
(27, 44, 1, 'Need to work on consistency with your meal prep schedule.', 'try_harder', '2025-02-15 10:15:00'),
(28, 44, 1, 'Excellent progress on ab definition. Your core routine is working well!', 'well_done', '2025-03-05 04:50:00'),
(29, 44, 1, 'Focus more on proper breathing during heavy lifts for better results.', 'try_harder', '2025-03-22 07:40:00'),
(30, 44, 1, 'Great consistency with morning cardio sessions. Your endurance has improved!', 'well_done', '2025-04-10 03:45:00'),
(31, 45, 1, 'Excellent muscle development in your shoulders and back!', 'well_done', '2024-12-05 05:50:00'),
(32, 45, 1, 'Need to increase protein intake to support your training intensity.', 'try_harder', '2024-12-20 08:30:00'),
(33, 45, 1, 'Great improvement on deadlift form. You\'re maintaining a neutral spine now.', 'well_done', '2025-01-10 04:00:00'),
(34, 45, 1, 'Focus more on mobility work between strength sessions.', 'try_harder', '2025-01-25 10:45:00'),
(35, 45, 1, 'Impressive progress with your pull-up strength! Added 10kg to weighted pull-ups.', 'well_done', '2025-02-10 05:15:00'),
(36, 45, 1, 'Remember to prioritize recovery days in your training split.', 'try_harder', '2025-03-01 07:30:00'),
(37, 45, 1, 'Your dedication to progressive overload is paying off. Visible muscle gains!', 'well_done', '2025-03-18 06:00:00'),
(38, 45, 1, 'Excellent work maintaining low body fat while building muscle mass.', 'well_done', '2025-04-05 09:15:00'),
(39, 45, 1, 'Keep focusing on mind-muscle connection during isolation exercises.', 'try_harder', '2025-04-22 03:30:00'),
(40, 47, 1, 'Your dedication to your strength program is impressive. Great progress!', 'well_done', '2024-12-10 03:45:00'),
(41, 47, 1, 'Need to improve hydration throughout your workouts.', 'try_harder', '2024-12-28 09:00:00'),
(42, 47, 1, 'Excellent improvement in squat depth while maintaining proper form.', 'well_done', '2025-01-15 05:15:00'),
(43, 47, 1, 'Work on incorporating more unilateral exercises to address imbalances.', 'try_harder', '2025-02-05 10:30:00'),
(44, 47, 1, 'Your chest development is progressing well. Bench press PR achieved!', 'well_done', '2025-02-25 06:00:00'),
(45, 47, 1, 'Remember to track your macros more consistently for optimal results.', 'try_harder', '2025-03-15 08:15:00'),
(46, 47, 1, 'Great job maintaining workout consistency despite busy schedule.', 'well_done', '2025-04-08 04:45:00'),
(47, 47, 1, 'Impressive progress on core strength. Your stability has improved significantly.', 'well_done', '2025-04-24 10:00:00'),
(48, 48, 1, 'Excellent progress with your cardio endurance. 5k time improved by 3 minutes!', 'well_done', '2024-12-12 03:15:00'),
(49, 48, 1, 'Need to focus more on proper cooling down after intense sessions.', 'try_harder', '2025-01-05 07:50:00'),
(50, 48, 1, 'Great job maintaining your nutrition plan through the holidays.', 'well_done', '2025-01-22 04:45:00'),
(51, 48, 1, 'Try to include more variety in your strength training routine.', 'try_harder', '2025-02-10 10:10:00'),
(52, 48, 1, 'Impressive progress with your flexibility goals. Front splits getting closer!', 'well_done', '2025-03-05 04:00:00'),
(53, 48, 1, 'Focus more on consistent sleep schedule to maximize recovery.', 'try_harder', '2025-03-25 08:40:00'),
(54, 48, 1, 'Your dedication to morning workouts is paying off. Energy levels visibly improved!', 'well_done', '2025-04-15 05:30:00'),
(55, 49, 1, 'Great progress with your weight loss goals. Down 3kg this month!', 'well_done', '2024-12-15 05:00:00'),
(56, 49, 1, 'Need to improve consistency with your strength training sessions.', 'try_harder', '2025-01-10 09:45:00'),
(57, 49, 1, 'Excellent job incorporating HIIT into your routine. Metabolism boost evident!', 'well_done', '2025-02-01 04:15:00'),
(58, 49, 1, 'Work on better form during compound movements to prevent injury.', 'try_harder', '2025-02-20 08:55:00'),
(59, 49, 1, 'Your endurance improvements are remarkable. Completed full circuit without breaks!', 'well_done', '2025-03-12 05:40:00'),
(60, 49, 1, 'Remember to prioritize protein intake for muscle preservation during weight loss.', 'try_harder', '2025-04-02 11:00:00'),
(61, 49, 1, 'Great job with consistency on nutrition tracking. Results clearly visible!', 'well_done', '2025-04-22 04:30:00'),
(62, 50, 1, 'Excellent progress with your posture corrective exercises.', 'well_done', '2024-12-18 03:30:00'),
(63, 50, 1, 'Need to challenge yourself more with progressive overload on weights.', 'try_harder', '2025-01-08 08:15:00'),
(64, 50, 1, 'Great improvement in core strength. Plank time doubled!', 'well_done', '2025-01-28 05:00:00'),
(65, 50, 1, 'Focus more on proper breathing techniques during yoga practices.', 'try_harder', '2025-02-15 09:30:00'),
(66, 50, 1, 'Your consistency with mobility work is paying off. Shoulder range improved!', 'well_done', '2025-03-10 05:45:00'),
(67, 50, 1, 'Try to incorporate more cardio variety to prevent plateaus.', 'try_harder', '2025-03-30 09:10:00'),
(68, 50, 1, 'Impressive progress balancing strength and flexibility goals. Well-rounded fitness!', 'well_done', '2025-04-18 04:00:00'),
(69, 51, 1, 'Your dedication to your strength goals is impressive. Deadlift PR achieved!', 'well_done', '2025-02-14 04:45:00'),
(70, 51, 1, 'Need to improve consistency with post-workout nutrition timing.', 'try_harder', '2025-02-28 09:00:00'),
(71, 51, 1, 'Excellent muscle development in your shoulders and back. Results clearly visible!', 'well_done', '2025-03-17 05:30:00'),
(72, 51, 1, 'Remember to incorporate deload weeks for optimal long-term progress.', 'try_harder', '2025-04-05 11:15:00'),
(73, 51, 1, 'Great job maintaining perfect form even with heavier weights.', 'well_done', '2025-04-22 05:00:00'),
(74, 52, 1, 'Impressive progress with your weight loss. Down 5kg in two months!', 'well_done', '2025-01-10 04:00:00'),
(75, 52, 1, 'Need to increase water intake throughout the day.', 'try_harder', '2025-01-25 07:45:00'),
(76, 52, 1, 'Great improvement in cardiovascular endurance. Recovery time decreased significantly!', 'well_done', '2025-02-12 06:15:00'),
(77, 52, 1, 'Try to be more consistent with your morning workout schedule.', 'try_harder', '2025-03-05 10:00:00'),
(78, 52, 1, 'Excellent progress with body composition changes. Losing fat while preserving muscle!', 'well_done', '2025-03-26 04:30:00'),
(79, 52, 1, 'Focus more on quality of movement rather than quantity during circuit training.', 'try_harder', '2025-04-15 08:50:00'),
(80, 53, 1, 'Your dedication to your flexibility program is paying off. Front splits achieved!', 'well_done', '2025-03-18 05:15:00'),
(81, 53, 1, 'Need to challenge yourself more with resistance training.', 'try_harder', '2025-03-30 09:30:00'),
(82, 53, 1, 'Great progress with your endurance. Completed 10k run under target time!', 'well_done', '2025-04-10 06:00:00'),
(83, 53, 1, 'Work on incorporating more variety in your core training routine.', 'try_harder', '2025-04-22 08:45:00'),
(84, 54, 1, 'Excellent muscle development in your arms and chest. Hard work is showing!', 'well_done', '2024-12-20 05:30:00'),
(85, 54, 1, 'Need to improve consistency with leg day attendance.', 'try_harder', '2025-01-15 11:00:00'),
(86, 54, 1, 'Great progress with your military press. Form has improved significantly!', 'well_done', '2025-02-08 04:45:00'),
(87, 54, 1, 'Focus more on stretching to improve overall mobility and prevent injury.', 'try_harder', '2025-03-02 09:15:00'),
(88, 54, 1, 'Impressive commitment to your training program. 100% attendance this month!', 'well_done', '2025-03-25 04:00:00'),
(89, 54, 1, 'Try to incorporate more compound movements for overall strength development.', 'try_harder', '2025-04-15 07:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `equipments`
--

CREATE TABLE `equipments` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `manufacturer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_maintenance` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipments`
--

INSERT INTO `equipments` (`id`, `name`, `type`, `quantity`, `status`, `description`, `manufacturer`, `image`, `purchase_date`, `last_maintenance`, `created_at`, `updated_at`) VALUES
(1, 'Leg Press Machine', 'Strength Equipment', 2, 'available', 'A versatile machine designed to target quadriceps, hamstrings, and glutes effectively.', 'GymPro', '', '2022-03-01 08:00:00', '2023-06-15 08:00:00', '2024-11-26 20:00:21', '2024-11-27 00:30:38'),

(2, 'Squat Rack', 'Strength Equipment', 3, 'not available', 'A rack for performing squats and other compound exercises.', 'IronMax', '', '2021-05-10 08:00:00', '2023-07-01 08:00:00', '2024-11-26 20:00:21', '2024-11-29 04:53:53'),

(4, 'Calf Raise Machine', 'Strength Equipment', 1, 'available', 'Targets and strengthens the calf muscles.', 'PowerFit', NULL, '2020-11-20 08:00:00', '2023-08-01 08:00:00', '2024-11-26 20:00:21', '2024-11-26 20:00:21'),

(5, 'Bench Press', 'Strength Equipment', 5, 'not available', 'A classic equipment for chest and triceps strength training.', 'MuscleTech', NULL, '2023-03-05 08:00:00', '2023-09-01 08:00:00', '2024-11-26 20:00:21', '2024-11-26 20:00:21'),

(6, 'Chest Fly Machine', 'Strength Equipment', 2, 'available', 'Builds chest muscles and improves posture.', 'HealthLine', NULL, '2022-10-12 08:00:00', '2023-06-20 08:00:00', '2024-11-26 20:00:21', '2024-11-26 20:00:21'),

(7, 'Lat Pulldown Machine', 'Strength Equipment', 1, 'available', 'A machine for strengthening the back and biceps.', 'BackFit', 'uploads/default-images/latpull.png', '2021-09-30 08:00:00', '2023-04-15 08:00:00', '2024-11-26 20:00:21', '2024-11-26 20:00:21'),

(8, 'Dumbbells', 'Strength Equipment', 10, 'not available', 'Versatile free weights for full-body strength training.', 'FlexPro', 'uploads/default-images/dumbbells.jpg', '2022-12-25 08:00:00', '2023-05-01 08:00:00', '2024-11-26 20:00:21', '2024-11-26 20:00:21');


-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `video_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'https://www.youtube.com/watch?v=a3ICNMQW7Ok',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `muscle_group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `difficulty_level` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `equipment_needed` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exercises`
--

INSERT INTO `exercises` (`id`, `name`, `description`, `video_link`, `created_at`, `updated_at`, `image`, `muscle_group`, `difficulty_level`, `type`, `equipment_needed`) VALUES
(1, 'Squats', 'Squats are a fundamental lower-body exercise that targets multiple muscle groups, including the quadriceps, hamstrings, glutes, and core. They help improve strength, stability, and overall athletic performance.', 'https://www.youtube.com/embed/xqvCmoLULNY?si=ErFwoXeLoayhajlQ', '2024-11-15 12:02:46', '2025-04-17 04:50:43', 'staff-exercise-images/680085f8b2959.jpg', 'Quadriceps, Hamstrings, Glutes, Core', 'Intermediate', 'Strength', 'Bodyweight or Barbell'),
(2, 'Deadlifts', 'A compound exercise targeting the back, glutes, and hamstrings.', 'https://www.youtube.com/embed/AweC3UaM14o?si=eFXRgNn2Ri00G5hA', '2024-11-15 12:02:46', '2025-04-17 05:21:10', 'staff-exercise-images/68008fc6215f5.jpg', 'Back, Glutes, Hamstrings', 'Advanced', 'Strength', 'Barbell or Dumbbells'),
(3, 'Bench Press', 'Targets the chest, shoulders, and triceps.', 'https://www.youtube.com/embed/gRVjAtPip0Y?si=yqLfcTDioKzWOWxb', '2024-11-15 12:02:46', '2025-04-17 05:21:30', 'staff-exercise-images/68008fda5414f.jpg', 'Chest, Shoulders, Triceps', 'Intermediate', 'Strength', 'Barbell or Dumbbells'),
(4, 'Pull-Ups', 'An upper-body exercise that targets the back and biceps.', 'https://www.youtube.com/embed/Au__15of2k0?si=NMOPftg_s5NcDh1E', '2024-11-15 12:02:46', '2025-04-17 05:22:25', 'staff-exercise-images/6800901163dc5.jpg', 'Back, Biceps', 'Advanced', 'Strength', 'Pull-Up Bar'),
(5, 'Overhead Press', 'Strengthens shoulders, upper chest, and triceps.', 'https://www.youtube.com/embed/KP1sYz2VICk?si=WQeGnaUFVUrNfnpm', '2024-11-15 12:02:46', '2025-04-17 05:43:05', 'staff-exercise-images/680094e8e9a47.png', 'Shoulders, Upper Chest, Triceps', 'Intermediate', 'Strength', 'Barbell or Dumbbells'),
(6, 'Lunges', 'Targets quadriceps, hamstrings, and glutes.', 'https://www.youtube.com/embed/wrwwXE_x-pQ?si=FxQzav0NGQN7PzNS', '2024-11-15 12:02:46', '2025-04-17 05:44:36', 'staff-exercise-images/680095440066b.jpg', 'Quadriceps, Hamstrings, Glutes', 'Beginner', 'Strength', 'Bodyweight or Dumbbells'),
(7, 'Quads', 'Focuses on the quadriceps muscle group.', 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', '2024-11-15 12:02:46', '2024-11-15 12:02:46', '', 'Quadriceps', 'Beginner', 'Strength', 'Bodyweight or Machines'),
(8, 'Dumbbell Rows', 'Strengthens the back and biceps.', 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', '2024-11-15 12:02:46', '2024-11-15 12:02:46', '', 'Back, Biceps', 'Intermediate', 'Strength', 'Dumbbells or Resistance Bands');

-- --------------------------------------------------------

--
-- Table structure for table `mealplans`
--

CREATE TABLE `mealplans` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `duration` int NOT NULL COMMENT 'Duration in days',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mealplans`
--

INSERT INTO `mealplans` (`id`, `name`, `description`, `duration`, `created_at`, `updated_at`) VALUES
(1, 'Weight Loss Plan', 'A balanced meal plan designed for weight loss with calorie deficit', 30, '2025-03-01 18:28:49', '2025-03-03 07:53:07'),
(2, 'Muscle Building', 'High protein meal plan to support muscle growth and recovery', 14, '2025-03-01 18:28:49', '2025-03-01 18:28:49'),
(3, 'Vegetarian Essentials', 'Plant-based complete nutrition plan', 7, '2025-03-01 18:28:49', '2025-03-01 18:28:49');

-- --------------------------------------------------------

--
-- Table structure for table `mealplan_meals`
--

CREATE TABLE `mealplan_meals` (
  `id` int NOT NULL,
  `mealplan_id` int NOT NULL,
  `meal_id` int NOT NULL,
  `day` varchar(10) NOT NULL COMMENT 'Monday, Tuesday, etc.',
  `time` varchar(20) NOT NULL COMMENT 'Breakfast, Lunch, Dinner',
  `amount` int NOT NULL COMMENT 'Amount/portion of the meal',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mealplan_meals`
--

INSERT INTO `mealplan_meals` (`id`, `mealplan_id`, `meal_id`, `day`, `time`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 'Monday', 'Breakfast', 1, '2025-03-01 18:29:01', '2025-03-01 18:29:01'),
(2, 1, 4, 'Monday', 'Lunch', 1, '2025-03-01 18:29:01', '2025-03-01 18:29:01'),
(3, 1, 3, 'Monday', 'Dinner', 1, '2025-03-01 18:29:01', '2025-03-01 18:29:01'),
(4, 1, 2, 'Tuesday', 'Breakfast', 1, '2025-03-01 18:29:01', '2025-03-01 18:29:01'),
(5, 1, 5, 'Tuesday', 'Lunch', 1, '2025-03-01 18:29:01', '2025-03-02 12:50:55'),
(6, 1, 2, 'Saturday', 'Lunch', 1, '2025-03-01 18:29:01', '2025-03-02 12:50:55'),
(7, 2, 4, 'Monday', 'Breakfast', 2, '2025-03-01 18:29:01', '2025-03-01 18:29:01'),
(8, 2, 3, 'Monday', 'Lunch', 1, '2025-03-01 18:29:01', '2025-03-01 18:29:01'),
(9, 2, 3, 'Monday', 'Dinner', 1, '2025-03-01 18:29:01', '2025-03-01 18:29:01'),
(10, 3, 2, 'Monday', 'Breakfast', 1, '2025-03-01 18:29:01', '2025-03-01 18:29:01'),
(11, 3, 5, 'Monday', 'Lunch', 1, '2025-03-01 18:29:01', '2025-03-01 18:29:01'),
(12, 3, 2, 'Monday', 'Dinner', 1, '2025-03-01 18:29:01', '2025-03-01 18:29:01');

-- --------------------------------------------------------

--
-- Table structure for table `mealplan_requests`
--

CREATE TABLE `mealplan_requests` (
  `id` int NOT NULL,
  `trainer_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `reviewed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mealplan_requests`
--

INSERT INTO `mealplan_requests` (`id`, `trainer_id`, `customer_id`, `description`, `created_at`, `updated_at`, `reviewed`) VALUES
(29, 1, 45, 'Goal: Muscle gain\nPriority: Normal\n\nAdd High-Strength Omega 3 Tablets Pack', '2025-04-23 09:16:00', '2025-04-23 10:37:06', 1),
(30, 1, 45, 'Goal: Performance\nPriority: Normal\n\nAiyoo1', '2025-04-23 09:23:13', '2025-04-23 09:24:33', 1),
(31, 1, 44, 'Goal: Performance\nPriority: Normal\n\nRavindu, trainer gen meal plan req ekak awada balanna.', '2025-04-23 10:34:37', '2025-04-23 10:36:56', 1),
(32, 1, 44, 'Goal: Performance\nPriority: Normal\n\nHehe', '2025-04-23 15:08:14', '2025-04-23 15:08:14', 0),
(33, 1, 45, 'Goal: Muscle gain\nPriority: Normal\n\nhhh', '2025-04-23 22:56:48', '2025-04-23 22:56:48', 0);

-- --------------------------------------------------------

--
-- Table structure for table `meals`
--

CREATE TABLE `meals` (
  `id` int NOT NULL,
  `description` text,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `calories` float DEFAULT NULL,
  `proteins` float DEFAULT NULL,
  `fats` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `meals`
--

INSERT INTO `meals` (`id`, `description`, `name`, `image`, `calories`, `proteins`, `fats`, `created_at`, `updated_at`) VALUES
(2, 'Oatmeal topped with fresh fruits and nuts', 'Fruit Oatmeal Bowl', '', 300, 10, 10, '2025-02-26 12:45:26', '2025-04-19 15:33:15'),
(3, 'Salmon fillet with roasted sweet potatoes and asparagus', 'Salmon Delight', '', 500, 35, 20, '2025-02-26 12:45:26', '2025-04-19 15:33:18'),
(4, 'Protein shake with banana, peanut butter, and almond milk', 'Protein Shake', '', 250, 25, 10, '2025-02-26 12:45:26', '2025-04-19 15:33:27'),
(5, 'Avocado toast with a poached egg on whole-grain bread', 'Avocado Toast', '', 350, 15, 18, '2025-02-26 12:45:26', '2025-04-19 15:33:30');

-- --------------------------------------------------------

--
-- Table structure for table `membership_payments`
--

CREATE TABLE `membership_payments` (
  `id` int NOT NULL,
  `customer` int NOT NULL,
  `membership_plan` int NOT NULL,
  `amount` double NOT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `membership_payments`
--

INSERT INTO `membership_payments` (`id`, `customer`, `membership_plan`, `amount`, `completed_at`, `created_at`) VALUES
(19, 44, 15, 15000, '2025-04-15 07:27:46', '2025-04-15 07:27:35'),
(20, 44, 18, 60000, '2025-04-08 22:15:43', '2025-04-19 16:46:10'),
(21, 45, 18, 300000, NULL, '2025-04-22 10:26:30'),
(22, 45, 18, 300000, NULL, '2025-04-22 10:26:42'),
(23, 45, 15, 15000, '2025-01-10 10:20:45', '2025-01-10 10:20:20'),
(24, 45, 15, 15000, '2025-02-10 11:30:10', '2025-02-10 11:29:40'),
(25, 45, 15, 15000, '2025-03-10 12:41:30', '2025-03-10 12:41:00'),
(26, 46, 18, 300000, '2024-12-01 14:50:55', '2024-12-01 14:50:20'),
(27, 47, 17, 30000, '2025-01-03 07:45:22', '2025-01-03 07:44:50'),
(28, 47, 17, 30000, '2025-02-03 07:47:10', '2025-02-03 07:46:45'),
(29, 47, 17, 30000, '2025-03-03 07:48:35', '2025-03-03 07:48:10'),
(30, 48, 14, 6000, '2024-11-05 10:15:25', '2024-11-05 10:15:00'),
(31, 48, 14, 6000, '2024-12-05 10:17:00', '2024-12-05 10:16:30'),
(32, 48, 15, 15000, '2025-01-05 10:20:00', '2025-01-05 10:19:40'),
(33, 48, 15, 15000, '2025-02-05 10:22:10', '2025-02-05 10:21:40'),
(34, 49, 17, 30000, '2024-11-20 15:10:10', '2024-11-20 15:09:40'),
(35, 49, 17, 30000, '2025-01-20 15:11:25', '2025-01-20 15:10:55'),
(36, 49, 17, 30000, '2025-03-20 15:12:40', '2025-03-20 15:12:10'),
(37, 50, 14, 6000, '2024-12-01 06:15:00', '2024-12-01 06:14:30'),
(38, 50, 14, 6000, '2025-01-01 06:15:00', '2025-01-01 06:14:30'),
(39, 50, 14, 6000, '2025-02-01 06:15:00', '2025-02-01 06:14:30'),
(40, 50, 14, 6000, '2025-03-01 06:15:00', '2025-03-01 06:14:30'),
(41, 50, 14, 6000, '2025-04-01 06:15:00', '2025-04-01 06:14:30'),
(42, 51, 15, 15000, '2025-02-12 09:00:10', '2025-02-12 09:00:00'),
(43, 51, 17, 30000, '2025-03-12 09:00:30', '2025-03-12 09:00:10'),
(44, 51, 17, 30000, '2025-04-12 09:00:45', '2025-04-12 09:00:20'),
(45, 52, 18, 300000, '2025-01-01 12:10:10', '2025-01-01 12:09:50'),
(46, 53, 15, 15000, '2025-03-15 17:00:00', '2025-03-15 16:59:30'),
(47, 53, 15, 15000, '2025-04-15 17:00:30', '2025-04-15 17:00:00'),
(48, 54, 17, 30000, '2024-12-10 13:33:10', '2024-12-10 13:32:50'),
(49, 54, 17, 30000, '2025-02-10 13:34:15', '2025-02-10 13:33:50'),
(50, 54, 17, 30000, '2025-04-10 13:35:30', '2025-04-10 13:35:00');

-- --------------------------------------------------------

--
-- Table structure for table `membership_plans`
--

CREATE TABLE `membership_plans` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` int NOT NULL,
  `duration` int NOT NULL COMMENT 'In days',
  `is_locked` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership_plans`
--

INSERT INTO `membership_plans` (`id`, `name`, `description`, `price`, `duration`, `is_locked`, `created_at`, `updated_at`) VALUES
(14, 'Basic', 'Access to gym facilities during standard hours. Ideal for beginners looking to start their fitness journey.', 6000, 30, 0, '2024-11-12 08:26:41', '2024-11-27 11:05:25'),
(15, 'Silver', 'Includes unlimited access to gym facilities, one personal training session, and group fitness classes. Perfect for intermediate fitness enthusiasts.', 15000, 30, 0, '2024-11-12 08:27:12', '2024-11-27 11:05:30'),
(17, 'Gold', 'Comprehensive membership offering 24/7 gym access, weekly personal training, and free entry to premium workshops. Great for dedicated fitness goals.', 30000, 30, 0, '2024-11-27 10:55:58', '2024-11-27 11:05:35'),
(18, 'Annual Elite', 'Full-year membership with unlimited gym access, customized training plans, monthly health assessments, and exclusive discounts on gym merchandise.', 300000, 365, 0, '2024-11-27 10:57:09', '2024-11-27 11:05:41');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `receiver_id` int NOT NULL,
  `receiver_type` enum('rat','trainer') NOT NULL DEFAULT 'rat',
  `source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `valid_till` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `message`, `receiver_id`, `receiver_type`, `source`, `is_read`, `valid_till`, `created_at`) VALUES
(52, 'Welcome to GYMRAT', 'Thank you for registering with us. We hope you have a great experience!', 45, 'rat', 'system', 0, NULL, '2025-04-22 15:56:05'),
(56, 'Workout Plan Request Submitted', 'Your trainer has requested a custom workout plan for you. Our fitness team will create it soon.', 44, 'rat', 'system', 0, NULL, '2025-04-24 01:22:20'),
(57, 'Workout Plan Request Submitted', 'Your trainer has requested a custom workout plan for you. Our fitness team will create it soon.', 44, 'rat', 'system', 0, NULL, '2025-04-24 01:43:20'),
(58, 'Workout Plan Request Submitted', 'Your trainer has requested a custom workout plan for you. Our fitness team will create it soon.', 48, 'rat', 'system', 0, NULL, '2025-04-27 23:46:39');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `contact_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contact_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `workout_session_expiry` int NOT NULL COMMENT 'In hours',
  `max_capacity` int NOT NULL,
  `min_workout_time` int NOT NULL COMMENT 'In hours',
  `gym_banner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gym_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gym_desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `gym_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `show_widgets` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `contact_email`, `contact_phone`, `workout_session_expiry`, `max_capacity`, `min_workout_time`, `gym_banner`, `gym_name`, `gym_desc`, `gym_address`, `show_widgets`) VALUES
(1, 'support@gymrat.com', '1234567890', 24, 100, 1, NULL, 'PRAN FITNESS', 'Top-tier training facility with premium equipment.', '123 Muscle St, Fit City', 1);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'root', 'admin@example.com', '$2y$10$oNFBPqdhK6HK/EICyTB9JugEoGzqcn.Y.s/RZkLi0zaIIyQHpgBWW', 'admin', '2024-11-25 18:20:16', '2024-11-25 18:20:16'),
(1, 'root', 'admin@gmail.com', '$2y$10$zdFIy/uQ4BUhKOHzuuc6s.CD5ZTjhPzXrpH8MvS4v0PE/pSahgGwC', 'admin', '2024-11-25 18:20:16', '2024-11-25 18:20:16'),
(3, 'eq', 'eq@example.com', '$2y$10$oNFBPqdhK6HK/EICyTB9JugEoGzqcn.Y.s/RZkLi0zaIIyQHpgBWW', 'eq', '2024-11-25 18:34:54', '2024-11-25 18:34:54'),
(3, 'eq', 'eq@gmail.com', '$2y$10$zdFIy/uQ4BUhKOHzuuc6s.CD5ZTjhPzXrpH8MvS4v0PE/pSahgGwC', 'eq', '2024-11-25 18:34:54', '2024-11-25 18:34:54'),
(2, 'wnmp', 'wnmp@example.com', '$2y$10$oNFBPqdhK6HK/EICyTB9JugEoGzqcn.Y.s/RZkLi0zaIIyQHpgBWW', 'wnmp', '2024-11-25 18:34:54', '2024-11-25 18:34:54'),
(2, 'wnmp', 'wnmp@gmail.com', '$2y$10$zdFIy/uQ4BUhKOHzuuc6s.CD5ZTjhPzXrpH8MvS4v0PE/pSahgGwC', 'wnmp', '2024-11-25 18:34:54', '2024-11-25 18:34:54');

-- --------------------------------------------------------

--
-- Table structure for table `trainers`
--

CREATE TABLE `trainers` (
  `id` int NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `bio` text NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `trainers`
--

INSERT INTO `trainers` (`id`, `fname`, `lname`, `username`, `password`, `avatar`, `bio`, `phone`) VALUES
(1, 'Cos', 'Fam', 'john', '$2y$12$DqWWm8SbOhtT2.P0NEUkoO6YEou.4fKDxUnLSDX4X8Xqv1tzGBUXW', NULL, 'Default trainer account with expertise in strength training and cardio.', '0716060662');

-- --------------------------------------------------------

--
-- Table structure for table `trainer_ratings`
--

CREATE TABLE `trainer_ratings` (
  `id` int NOT NULL,
  `trainer_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `rating` int NOT NULL,
  `review` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `trainer_ratings`
--

INSERT INTO `trainer_ratings` (`id`, `trainer_id`, `customer_id`, `rating`, `review`, `created_at`) VALUES
(1, 1, 44, 3, 'Excellent trainer! Really helped me achieve my fitness goals.Excellent trainer! Really helped me achieve my fitness goals.Excellent trainer! Really helped me achieve my fitness goals.Excellent trainer! Really helped me achieve my fitness goals.Excellent trainer! Really helped me achieve my fitness goals.Excellent trainer! Really helped me achieve my fitness goals.', '2025-03-16 05:35:56'),
(2, 1, 44, 5, 'The best trainer I have ever had! Very knowledgeable and motivating.', '2025-03-31 05:35:56'),
(3, 1, 44, 5, 'Helped me transform my fitness level completely. I can\'t thank them enough.', '2025-04-10 05:35:56'),
(4, 1, 44, 5, 'Very professional and knowledgeable about proper form and technique.', '2025-03-26 05:35:56'),
(5, 1, 44, 5, 'Amazing trainer! Tailored workouts to my specific needs and goals.', '2025-02-14 05:35:56'),
(6, 1, 44, 5, 'Always punctual and prepared for our sessions. Highly recommended!', '2025-03-01 05:35:56'),
(7, 1, 44, 5, 'I\'ve seen incredible results since working with this trainer.', '2025-02-22 05:35:56'),
(8, 1, 44, 5, 'Extremely knowledgeable about nutrition alongside fitness training.', '2025-03-08 05:35:56'),
(9, 1, 44, 5, 'Changed my perspective on fitness entirely. So grateful!', '2025-03-24 05:35:56'),
(10, 1, 44, 5, 'Outstanding approach to balancing strength and flexibility training.', '2025-04-03 05:35:56'),
(21, 1, 44, 3, 'fghj', '2025-04-23 23:03:39');

-- --------------------------------------------------------

--
-- Table structure for table `workouts`
--

CREATE TABLE `workouts` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `duration` int NOT NULL COMMENT 'In Days',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workouts`
--

INSERT INTO `workouts` (`id`, `name`, `description`, `duration`, `created_at`, `updated_at`) VALUES
(1, 'Strength Training', 'Squats, Deadlifts, Bench Press, Pull-Ups, Overhead Press, Lunges, Quads, Dumbbell Rows', 30, '2024-11-15 06:13:10', '2024-11-15 06:13:10'),
(2, 'Cardio', 'Running, Cycling, Swimming, Rowing, Jump Rope, Stair Climbing, Hiking, Elliptical', 30, '2024-11-15 06:13:10', '2024-11-15 06:13:10'),
(3, 'Flexibility', 'Stretching, Yoga, Pilates, Tai Chi, Foam Rolling, Dynamic Stretching, Static Stretching', 30, '2024-11-15 06:13:10', '2024-11-15 06:13:10'),
(4, 'Default Workout', 'Custom workout for customer #44', 30, '2025-04-22 10:19:51', '2025-04-22 10:19:51'),
(5, 'ABS Focused', 'I think it\'s better to have a custom workout for this client, since he is asking all the time, when I can get abs.', 30, '2025-04-23 02:11:35', '2025-04-23 02:11:35');

-- --------------------------------------------------------

--
-- Table structure for table `workout_exercises`
--

CREATE TABLE `workout_exercises` (
  `id` int NOT NULL,
  `workout_id` int NOT NULL,
  `exercise_id` int NOT NULL,
  `day` int NOT NULL,
  `sets` int NOT NULL,
  `reps` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workout_exercises`
--

INSERT INTO `workout_exercises` (`id`, `workout_id`, `exercise_id`, `day`, `sets`, `reps`) VALUES
(1, 1, 1, 1, 4, 10),
(2, 1, 2, 1, 3, 8),
(3, 1, 3, 2, 4, 12),
(4, 1, 4, 2, 3, 10),
(5, 1, 5, 3, 4, 8),
(6, 1, 6, 3, 3, 12),
(7, 1, 7, 4, 4, 10),
(8, 1, 8, 4, 3, 12),
(9, 5, 6, 7, 4, 15),
(10, 5, 4, 4, 4, 10),
(11, 5, 2, 3, 3, 12);

-- --------------------------------------------------------

--
-- Table structure for table `workout_requests`
--

CREATE TABLE `workout_requests` (
  `id` int NOT NULL,
  `trainer_id` int NOT NULL,
  `description` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `reviewed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `workout_requests`
--

INSERT INTO `workout_requests` (`id`, `trainer_id`, `description`, `created_at`, `updated_at`, `reviewed`) VALUES
(1, 1, 'I want a strength training program to build muscle and improve strength. It should include compound and isolation exercises, proper form guidance, and a weekly schedule with progressive overload. Warm-up and cool-down routines are also needed for safety and recovery.', '2025-04-10 10:00:00', '2025-04-10 10:00:00', 0),
(2, 1, 'I need a cardio workout plan for weight loss with running, cycling, and HIIT. Tips on consistency, progress tracking, and combining cardio with a healthy diet are appreciated. A balanced plan with rest days and low-impact options for sustainability is ideal.', '2025-04-11 12:30:00', '2025-04-11 12:30:00', 0),
(3, 1, 'I want a flexibility and mobility program with stretches, yoga, and drills for range of motion. Exercises for hips, shoulders, and hamstrings are needed. Gradual progression and tips for daily routine integration are essential for long-term benefits.', '2025-04-12 15:45:00', '2025-04-12 15:45:00', 0),
(4, 1, 'I need a beginner-friendly workout routine with simple exercises for cardio, strength, and flexibility. Clear instructions on form, gradual intensity increase, and a weekly schedule with rest days are essential for building a fitness foundation.', '2025-04-13 09:00:00', '2025-04-13 09:00:00', 0),
(5, 1, 'Type: Strength\nDuration: 30 days\n\nMuscle building while fat loss', '2025-04-22 11:35:05', '2025-04-22 11:35:05', 0),
(6, 1, 'Type: Flexibility\nDuration: 30 days\n\nBla Bla', '2025-04-22 11:35:21', '2025-04-22 17:06:58', 1),
(7, 1, 'Type: Cardio\nDuration: 30 days\n\nedddsd', '2025-04-23 19:52:20', '2025-04-23 19:52:20', 0),
(8, 1, '{\"name\":\"Burn fat and build lean muscle\",\"type\":\"hiit\",\"duration\":14,\"priority\":\"normal\",\"description\":\"A balanced program designed to burn fat while building lean muscle. Combines strength training with high-intensity cardio to boost metabolism, improve endurance, and sculpt the body. Ideal for those aiming to lose weight without sacrificing muscle.\",\"exercises\":[{\"id\":1,\"day\":1,\"sets\":4,\"reps\":15},{\"id\":6,\"day\":1,\"sets\":3,\"reps\":10},{\"id\":3,\"day\":2,\"sets\":3,\"reps\":10},{\"id\":7,\"day\":2,\"sets\":3,\"reps\":10},{\"id\":8,\"day\":3,\"sets\":4,\"reps\":10},{\"id\":6,\"day\":3,\"sets\":4,\"reps\":10}],\"customer_id\":44,\"trainer_id\":1}\n\nName: Burn fat and build lean muscle\nType: Hiit\nDuration: 14 days\nPriority: Normal\n\nA balanced program designed to burn fat while building lean muscle. Combines strength training with high-intensity cardio to boost metabolism, improve endurance, and sculpt the body. Ideal for those aiming to lose weight without sacrificing muscle.\n\nRecommended Exercises:\n- Squats: 4 sets of 15 reps (Day 1)\n- Lunges: 3 sets of 10 reps (Day 1)\n- Bench Press: 3 sets of 10 reps (Day 2)\n- Quads: 3 sets of 10 reps (Day 2)\n- Dumbbell Rows: 4 sets of 10 reps (Day 3)\n- Lunges: 4 sets of 10 reps (Day 3)\n', '2025-04-23 20:09:40', '2025-04-23 20:09:40', 0),
(9, 1, '{\"name\":\"Burn fat and build lean muscle\",\"type\":\"hiit\",\"duration\":14,\"priority\":\"normal\",\"description\":\"A balanced program designed to burn fat while building lean muscle. Combines strength training with high-intensity cardio to boost metabolism, improve endurance, and sculpt the body. Ideal for those aiming to lose weight without sacrificing muscle.\",\"exercises\":[{\"id\":1,\"day\":1,\"sets\":4,\"reps\":15},{\"id\":6,\"day\":1,\"sets\":3,\"reps\":10},{\"id\":3,\"day\":2,\"sets\":3,\"reps\":10},{\"id\":7,\"day\":2,\"sets\":3,\"reps\":10},{\"id\":8,\"day\":3,\"sets\":4,\"reps\":10},{\"id\":6,\"day\":3,\"sets\":4,\"reps\":10}],\"customer_id\":44,\"trainer_id\":1}\n\nName: Burn fat and build lean muscle\nType: Hiit\nDuration: 14 days\nPriority: Normal\n\nA balanced program designed to burn fat while building lean muscle. Combines strength training with high-intensity cardio to boost metabolism, improve endurance, and sculpt the body. Ideal for those aiming to lose weight without sacrificing muscle.\n\nRecommended Exercises:\n- Squats: 4 sets of 15 reps (Day 1)\n- Lunges: 3 sets of 10 reps (Day 1)\n- Bench Press: 3 sets of 10 reps (Day 2)\n- Quads: 3 sets of 10 reps (Day 2)\n- Dumbbell Rows: 4 sets of 10 reps (Day 3)\n- Lunges: 4 sets of 10 reps (Day 3)\n', '2025-04-23 20:13:20', '2025-04-23 20:13:20', 0),
(10, 1, '{\"name\":\"Thunder Core 20\",\"type\":\"sport\",\"duration\":14,\"priority\":\"high\",\"description\":\"A fast-paced, 20-minute core-focused workout designed to build rock-solid abs and improve overall stability using just bodyweight movements\\u2014perfect for a quick sweat at home or at the gym.\",\"exercises\":[{\"id\":4,\"d\":1,\"s\":4,\"r\":12},{\"id\":3,\"d\":2,\"s\":4,\"r\":12},{\"id\":6,\"d\":3,\"s\":4,\"r\":10},{\"id\":7,\"d\":2,\"s\":4,\"r\":12}],\"customer_id\":48,\"trainer_id\":1}\n\nName: Thunder Core 20\nType: Sport\nDuration: 14 days\nPriority: High\n\nA fast-paced, 20-minute core-focused workout designed to build rock-solid abs and improve overall stability using just bodyweight movements—perfect for a quick sweat at home or at the gym.\n\nRecommended Exercises: Pull-Ups: 4 sets of 12 reps (Day 1), Bench Press: 4 sets of 12 reps (Day 2), Lunges: 4 sets of 10 reps (Day 3) and 1 more exercises', '2025-04-27 23:46:39', '2025-04-27 23:46:39', 0);

-- --------------------------------------------------------

--
-- Table structure for table `workout_sessions`
--

CREATE TABLE `workout_sessions` (
  `session_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user` int NOT NULL,
  `workout` int NOT NULL,
  `day` int DEFAULT NULL,
  `started_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ended_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workout_sessions`
--

INSERT INTO `workout_sessions` (`session_key`, `user`, `workout`, `day`, `started_at`, `ended_at`) VALUES
('gymrat_wsk_298491667edd73b586896b749100a969627bb42328057c31f950db9de4faee2b', 44, 1, 3, '2025-04-19 13:41:31', '2025-04-19 13:42:22'),
('gymrat_wsk_a504de61c0f5aef61db628b20d615e74c829888d42aed470438a75ee71973865', 44, 1, 2, '2025-04-19 13:23:22', '2025-04-19 17:23:22');

-- --------------------------------------------------------

--
-- Table structure for table `workout_session_keys`
--

CREATE TABLE `workout_session_keys` (
  `session_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `workout_session_keys`
--

INSERT INTO `workout_session_keys` (`session_key`, `created_at`) VALUES
('gymrat_wsk_1bf2a3f5b41ae0ba569f88d9cb86ecdf970fb6399ae63dfb500341d695d1f4d4', '2025-04-19 19:11:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bmi_records`
--
ALTER TABLE `bmi_records`
  ADD PRIMARY KEY (`user`,`created_at`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `customer_workout` (`workout`),
  ADD KEY `fk_customer_trainer` (`trainer`),
  ADD KEY `fk_customer_membership_plan` (`membership_plan`),
  ADD KEY `fk_customer_mealplan` (`meal_plan`);

--
-- Indexes for table `customer_email_verification_requests`
--
ALTER TABLE `customer_email_verification_requests`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `customer_initial_data`
--
ALTER TABLE `customer_initial_data`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `customer_password_reset_requests`
--
ALTER TABLE `customer_password_reset_requests`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `customer_progress`
--
ALTER TABLE `customer_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_progress_customer_id` (`customer_id`),
  ADD KEY `customer_progress_trainer_id` (`trainer_id`);

--
-- Indexes for table `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mealplans`
--
ALTER TABLE `mealplans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mealplan_meals`
--
ALTER TABLE `mealplan_meals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_meal_in_plan` (`mealplan_id`,`day`,`time`,`meal_id`),
  ADD KEY `meal_id` (`meal_id`);

--
-- Indexes for table `mealplan_requests`
--
ALTER TABLE `mealplan_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trainer_id` (`trainer_id`),
  ADD KEY `fk_customer_mpreq` (`customer_id`);

--
-- Indexes for table `meals`
--
ALTER TABLE `meals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `membership_payments`
--
ALTER TABLE `membership_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_subscription_payment_membership_plan` (`membership_plan`),
  ADD KEY `fk_subscription_payment_customer` (`customer`);

--
-- Indexes for table `membership_plans`
--
ALTER TABLE `membership_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `trainers`
--
ALTER TABLE `trainers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `trainer_ratings`
--
ALTER TABLE `trainer_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_trainer_rating_trainer` (`trainer_id`),
  ADD KEY `fk_trainer_rating_customer` (`customer_id`);

--
-- Indexes for table `workouts`
--
ALTER TABLE `workouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workout_exercises`
--
ALTER TABLE `workout_exercises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workout_id` (`workout_id`),
  ADD KEY `exercise_id` (`exercise_id`);

--
-- Indexes for table `workout_requests`
--
ALTER TABLE `workout_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trainer_id` (`trainer_id`);

--
-- Indexes for table `workout_sessions`
--
ALTER TABLE `workout_sessions`
  ADD PRIMARY KEY (`session_key`),
  ADD KEY `WorkoutSession_User` (`user`),
  ADD KEY `WorkoutSession_Workout` (`workout`);

--
-- Indexes for table `workout_session_keys`
--
ALTER TABLE `workout_session_keys`
  ADD PRIMARY KEY (`session_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `customer_progress`
--
ALTER TABLE `customer_progress`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `exercises`
--
ALTER TABLE `exercises`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `mealplans`
--
ALTER TABLE `mealplans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mealplan_meals`
--
ALTER TABLE `mealplan_meals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `mealplan_requests`
--
ALTER TABLE `mealplan_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `meals`
--
ALTER TABLE `meals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `membership_payments`
--
ALTER TABLE `membership_payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `membership_plans`
--
ALTER TABLE `membership_plans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `trainers`
--
ALTER TABLE `trainers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `trainer_ratings`
--
ALTER TABLE `trainer_ratings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `workouts`
--
ALTER TABLE `workouts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `workout_exercises`
--
ALTER TABLE `workout_exercises`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `workout_requests`
--
ALTER TABLE `workout_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bmi_records`
--
ALTER TABLE `bmi_records`
  ADD CONSTRAINT `fk_user_bmi_record` FOREIGN KEY (`user`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customer_workout` FOREIGN KEY (`workout`) REFERENCES `workouts` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_customer_mealplan` FOREIGN KEY (`meal_plan`) REFERENCES `mealplans` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_customer_membership_plan` FOREIGN KEY (`membership_plan`) REFERENCES `membership_plans` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_customer_trainer` FOREIGN KEY (`trainer`) REFERENCES `trainers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `customer_initial_data`
--
ALTER TABLE `customer_initial_data`
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer_progress`
--
ALTER TABLE `customer_progress`
  ADD CONSTRAINT `fk_customer_progress_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_customer_progress_trainer` FOREIGN KEY (`trainer_id`) REFERENCES `trainers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mealplan_meals`
--
ALTER TABLE `mealplan_meals`
  ADD CONSTRAINT `mealplan_meals_ibfk_1` FOREIGN KEY (`mealplan_id`) REFERENCES `mealplans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mealplan_meals_ibfk_2` FOREIGN KEY (`meal_id`) REFERENCES `meals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mealplan_requests`
--
ALTER TABLE `mealplan_requests`
  ADD CONSTRAINT `fk_customer_mpreq` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mealplan_requests_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `trainers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `membership_payments`
--
ALTER TABLE `membership_payments`
  ADD CONSTRAINT `fk_subscription_payment_customer` FOREIGN KEY (`customer`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_subscription_payment_membership_plan` FOREIGN KEY (`membership_plan`) REFERENCES `membership_plans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trainer_ratings`
--
ALTER TABLE `trainer_ratings`
  ADD CONSTRAINT `fk_trainer_rating_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_trainer_rating_trainer` FOREIGN KEY (`trainer_id`) REFERENCES `trainers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `workout_exercises`
--
ALTER TABLE `workout_exercises`
  ADD CONSTRAINT `workout_exercises_ibfk_1` FOREIGN KEY (`workout_id`) REFERENCES `workouts` (`id`),
  ADD CONSTRAINT `workout_exercises_ibfk_2` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`);

--
-- Constraints for table `workout_requests`
--
ALTER TABLE `workout_requests`
  ADD CONSTRAINT `workout_requests_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `trainers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `workout_sessions`
--
ALTER TABLE `workout_sessions`
  ADD CONSTRAINT `WorkoutSession_User` FOREIGN KEY (`user`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `WorkoutSession_Workout` FOREIGN KEY (`workout`) REFERENCES `workouts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
