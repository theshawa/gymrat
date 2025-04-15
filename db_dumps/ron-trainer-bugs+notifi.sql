-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql_db:3306
-- Generation Time: Apr 15, 2025 at 11:07 AM
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

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `message`, `to_all`, `source`, `valid_till`, `created_at`) VALUES
(4, 'Happy New Year Rats!', 'Cos Mama wishes you a very happy new year!!', 'rats', '1', '2025-04-16', '2025-04-14 06:11:06'),
(5, 'I&#039;m not Cos Mama', 'Sorry to say that I&#039;m not cos mama', 'rats', '1', '2025-05-14', '2025-04-14 06:26:06'),
(6, 'test', 'adas sad asd as dsa dsa da', 'rats', '1', '2025-04-14', '2025-04-14 15:07:33'),
(7, 'Test', 'asd aasd asd sad as dsad', 'rats', '1', '2025-04-16', '2025-04-15 06:36:23');

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

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int NOT NULL,
  `type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_created_by_trainer` tinyint(1) NOT NULL DEFAULT '0',
  `admin_reply` text COLLATE utf8mb4_general_ci COMMENT 'Admin response to the complaint',
  `status` enum('pending','reviewed') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending' COMMENT 'Status of the complaint review',
  `replied_at` timestamp NULL DEFAULT NULL COMMENT 'When the admin replied to the complaint'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `type`, `description`, `user_id`, `created_at`, `is_created_by_trainer`, `admin_reply`, `status`, `replied_at`) VALUES
(1, 'Equipment Issue', 'The treadmill is not functioning properly.', 101, '2024-11-25 20:25:18', 0, NULL, 'pending', NULL),
(2, 'Cleanliness', 'The locker room needs cleaning.', 102, '2024-11-25 20:25:18', 0, NULL, 'pending', NULL),
(3, 'Schedule Conflict', 'Trainer not available during requested hours.', 103, '2024-11-25 20:25:18', 1, NULL, 'pending', NULL),
(4, 'Billing Error', 'Charged extra for last month\'s subscription.', 104, '2024-11-25 20:25:18', 0, NULL, 'pending', NULL),
(5, 'Feedback', 'Requesting more yoga classes.', 105, '2024-11-25 20:25:18', 1, NULL, 'pending', NULL),
(11, 'Billing Error', 'Not credited my salary to my account', 1, '2025-04-15 09:42:50', 1, 'Okay, I will check', 'reviewed', NULL),
(12, 'Other Issue', 'Shoulder Press Machine is not functioning well.', 1, '2025-04-15 09:57:03', 1, NULL, 'pending', NULL),
(15, 'Attendance Problem', '[Customer ID: 1] [Severity: medium] He is not coming regularly and not infoming me early about his absence.', 1, '2025-04-15 10:17:49', 1, NULL, 'pending', NULL),
(16, 'Inappropriate Behavior', '[Customer ID: 1] [Severity: high] Hello! you are bad!', 1, '2025-04-15 10:59:03', 1, NULL, 'pending', NULL);

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
  `membership_plan` int NOT NULL,
  `membership_plan_activated_at` timestamp NULL DEFAULT NULL,
  `workout` int DEFAULT NULL COMMENT 'add related workout id',
  `meal_plan` int DEFAULT NULL COMMENT 'add related meal plan id',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `fname`, `lname`, `email`, `phone`, `password`, `avatar`, `onboarded`, `membership_plan`, `membership_plan_activated_at`, `workout`, `meal_plan`, `created_at`, `updated_at`) VALUES
(1, 'Craig', 'Bricknell', 'ron@gmail.com', '119', '$2y$10$FjUeSnitJt4GslR2H9IZVu6D4EmW//tSxD0lkDvP30se3UN0ipDFa', 'customer-avatars/6746fda47d8c2.jpg', 1, 14, '2024-11-26 04:40:06', NULL, 1, '2024-11-26 09:09:46', '2024-11-26 09:09:46'),
(2, 'Craig', 'Boss', 'craig@gmail.com', '119', '$2y$10$zLuimhijl2g7jyPLbO6rB.BghjmFeyUAAfdqopHhsD7RDqOLkCYNS', 'customer-avatars/67fa1eaa3e36c.png', 1, 14, '2024-11-26 04:43:16', NULL, 2, '2024-11-26 09:13:04', '2024-11-26 09:13:04'),
(3, 'Rona', 'New', 'ronanew@gmail.com', '001223', '$2y$10$lxeC2LN4GFaXnEhsiW47AetY83yQAH2afBvquuDZaEncUB1HMt.4a', NULL, 1, 14, '2024-11-26 05:02:27', NULL, 3, '2024-11-26 09:31:35', '2024-11-26 09:31:35'),
(4, 'Emily', 'Carter', 'emily.carter@example.com', '0771234567', '$2y$10$oNFBPqdhK6HK/EICyTB9JugEoGzqcn.Y.s/RZkLi0zaIIyQHpgBWW', NULL, 1, 14, '2025-04-11 20:15:18', 1, 3, '2024-11-27 10:44:59', '2024-11-27 10:44:59'),
(5, 'Liam', 'Johnson', 'liam.johnson@example.co.uk', '0771234568', '$2y$10$v0Hl20SW7r.tQNutvfKAhep6WktBBZqtNjH74nZXwGnzaalwMIRU.', 'customer-avatars/674590fdb9828.jpg', 1, 18, '2024-11-27 11:08:44', 3, 1, '2024-11-27 11:08:30', '2024-11-27 11:08:30'),
(6, 'Theshawa', 'Nimantha', 'mrclocktd@gmail.com', '0766743755', '$2y$10$FVx1kGvBRN0e7HgMkQEyDuosucLqiubWLWrDUn1N1.qi3gVgxtle6', 'customer-avatars/67459044d47b9.jpg', 1, 14, '2025-04-12 07:06:54', NULL, 2, '2025-04-12 07:06:41', '2025-04-12 07:06:41');

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
(1, 'male', 24, 'other', 'hasd asd asd sad sa dasasd ', 123, 123, 'beginner', 'gluten_free', '123adsads', '2025-04-12 07:37:03');

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
(1, 1, 1, 'You need to focus more on your diet.', 'try_harder', '2024-11-14 10:00:00'),
(2, 1, 1, 'Great job on your flexibility exercises.', 'well_done', '2024-11-15 10:00:00'),
(3, 1, 1, 'You missed your workout today.', 'try_harder', '2024-11-16 10:00:00'),
(4, 1, 1, 'Your stamina is improving.', 'well_done', '2024-11-17 10:00:00'),
(5, 1, 1, 'Try to maintain a consistent workout schedule.', 'try_harder', '2024-11-18 10:00:00'),
(6, 1, 1, 'Excellent performance in today\'s session.', 'well_done', '2024-11-19 10:00:00'),
(7, 1, 1, 'You need to work on your balance.', 'try_harder', '2024-11-20 10:00:00'),
(8, 1, 1, 'Great improvement in your strength training.', 'well_done', '2024-11-21 10:00:00'),
(9, 1, 1, 'You skipped your warm-up exercises.', 'try_harder', '2024-11-22 10:00:00'),
(10, 2, 1, 'Good progress with your weight lifting routine.', 'well_done', '2024-11-14 11:00:00'),
(11, 2, 1, 'Need to increase your water intake.', 'try_harder', '2024-11-16 11:00:00'),
(12, 2, 1, 'Excellent form on your squats today.', 'well_done', '2024-11-18 11:00:00'),
(13, 3, 1, 'Great progress with your cardio endurance.', 'well_done', '2024-11-15 09:00:00'),
(14, 3, 1, 'You need to work on your stretching routine.', 'try_harder', '2024-11-17 09:00:00'),
(15, 3, 1, 'Impressive improvement in your push-ups.', 'well_done', '2024-11-19 09:00:00'),
(16, 2, 1, 'Your V shape is now getting sharpened. Keep going champ!', 'well_done', '2025-04-13 14:13:18'),
(17, 6, 1, 'Ubawa hadanna bah matto!', 'try_harder', '2025-04-13 14:14:13'),
(18, 6, 1, 'Dhamya ekata enna kollo!', 'well_done', '2025-04-13 14:14:27'),
(19, 6, 1, 'Maru bn!', 'well_done', '2025-04-13 14:29:26'),
(20, 1, 1, 'ANe shoi!', 'well_done', '2025-04-15 06:54:52');

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

INSERT INTO `equipments` (`id`, `name`, `type`, `description`, `manufacturer`, `image`, `purchase_date`, `last_maintenance`, `created_at`, `updated_at`) VALUES
(1, 'Leg Press Machine', 'Strength Equipment', 'A versatile machine designed to target quadriceps, hamstrings, and glutes effectively.', 'GymPro', '', '2022-03-01 08:00:00', '2023-06-15 08:00:00', '2024-11-26 20:00:21', '2024-11-27 00:30:38'),
(2, 'Squat Rack', 'Strength Equipment', 'A rack for performing squats and other compound exercises.', 'IronMax', '', '2021-05-10 08:00:00', '2023-07-01 08:00:00', '2024-11-26 20:00:21', '2024-11-29 04:53:53'),
(4, 'Calf Raise Machine', 'Strength Equipment', 'Targets and strengthens the calf muscles.', 'PowerFit', NULL, '2020-11-20 08:00:00', '2023-08-01 08:00:00', '2024-11-26 20:00:21', '2024-11-26 20:00:21'),
(5, 'Bench Press', 'Strength Equipment', 'A classic equipment for chest and triceps strength training.', 'MuscleTech', NULL, '2023-03-05 08:00:00', '2023-09-01 08:00:00', '2024-11-26 20:00:21', '2024-11-26 20:00:21'),
(6, 'Chest Fly Machine', 'Strength Equipment', 'Builds chest muscles and improves posture.', 'HealthLine', NULL, '2022-10-12 08:00:00', '2023-06-20 08:00:00', '2024-11-26 20:00:21', '2024-11-26 20:00:21'),
(7, 'Lat Pulldown Machine', 'Strength Equipment', 'A machine for strengthening the back and biceps.', 'BackFit', 'uploads/default-images/latpull.png', '2021-09-30 08:00:00', '2023-04-15 08:00:00', '2024-11-26 20:00:21', '2024-11-26 20:00:21'),
(8, 'Dumbbells', 'Strength Equipment', 'Versatile free weights for full-body strength training.', 'FlexPro', 'uploads/default-images/dumbbells.jpg', '2022-12-25 08:00:00', '2023-05-01 08:00:00', '2024-11-26 20:00:21', '2024-11-26 20:00:21');

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
(1, 'Squats', 'Squats are a fundamental lower-body exercise that targets multiple muscle groups, including the quadriceps, hamstrings, glutes, and core. They help improve strength, stability, and overall athletic performance.', 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', '2024-11-15 12:02:46', '2024-11-15 12:02:46', '', 'Quadriceps, Hamstrings, Glutes, Core', 'Intermediate', 'Strength', 'Bodyweight or Barbell'),
(2, 'Deadlifts', 'A compound exercise targeting the back, glutes, and hamstrings.', 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', '2024-11-15 12:02:46', '2024-11-15 12:02:46', '', 'Back, Glutes, Hamstrings', 'Advanced', 'Strength', 'Barbell or Dumbbells'),
(3, 'Bench Press', 'Targets the chest, shoulders, and triceps.', 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', '2024-11-15 12:02:46', '2024-11-15 12:02:46', '', 'Chest, Shoulders, Triceps', 'Intermediate', 'Strength', 'Barbell or Dumbbells'),
(4, 'Pull-Ups', 'An upper-body exercise that targets the back and biceps.', 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', '2024-11-15 12:02:46', '2024-11-15 12:02:46', '', 'Back, Biceps', 'Advanced', 'Strength', 'Pull-Up Bar'),
(5, 'Overhead Press', 'Strengthens shoulders, upper chest, and triceps.', 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', '2024-11-15 12:02:46', '2024-11-15 12:02:46', '', 'Shoulders, Upper Chest, Triceps', 'Intermediate', 'Strength', 'Barbell or Dumbbells'),
(6, 'Lunges', 'Targets quadriceps, hamstrings, and glutes.', 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', '2024-11-15 12:02:46', '2024-11-15 12:02:46', '', 'Quadriceps, Hamstrings, Glutes', 'Beginner', 'Strength', 'Bodyweight or Dumbbells'),
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
(1, 'Weight Loss Plan', 'A balanced meal plan designed for weight loss with calorie deficit. Includes lean proteins, healthy fats, and controlled carbs to promote fat loss while maintaining energy levels.', 30, '2025-03-01 18:28:49', '2025-04-13 13:28:43'),
(2, 'Muscle Building', 'High protein meal plan to support muscle growth and recovery. Features increased portion sizes and protein content to fuel muscle development and support intense training sessions.', 14, '2025-03-01 18:28:49', '2025-04-13 13:28:43'),
(3, 'Vegetarian Essentials', 'Plant-based complete nutrition plan with balanced macronutrients. Focuses on nutrient-dense vegetarian options that provide all essential vitamins and minerals without animal products.', 7, '2025-03-01 18:28:49', '2025-04-13 13:28:43');

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
(1, 1, 5, 'Monday', 'Breakfast', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(2, 1, 4, 'Monday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(3, 1, 3, 'Monday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(4, 1, 2, 'Tuesday', 'Breakfast', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(5, 1, 5, 'Tuesday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(6, 1, 4, 'Tuesday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(7, 1, 4, 'Wednesday', 'Breakfast', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(8, 1, 3, 'Wednesday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(9, 1, 2, 'Wednesday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(10, 1, 5, 'Thursday', 'Breakfast', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(11, 1, 2, 'Thursday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(12, 1, 3, 'Thursday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(13, 1, 2, 'Friday', 'Breakfast', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(14, 1, 4, 'Friday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(15, 1, 5, 'Friday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(16, 1, 4, 'Saturday', 'Breakfast', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(17, 1, 2, 'Saturday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(18, 1, 3, 'Saturday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(19, 1, 5, 'Sunday', 'Breakfast', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(20, 1, 3, 'Sunday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(21, 1, 4, 'Sunday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(22, 2, 4, 'Monday', 'Breakfast', 2, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(23, 2, 3, 'Monday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(24, 2, 3, 'Monday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(25, 2, 2, 'Tuesday', 'Breakfast', 2, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(26, 2, 3, 'Tuesday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(27, 2, 5, 'Tuesday', 'Dinner', 2, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(28, 2, 4, 'Wednesday', 'Breakfast', 2, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(29, 2, 3, 'Wednesday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(30, 2, 3, 'Wednesday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(31, 2, 2, 'Thursday', 'Breakfast', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(32, 2, 3, 'Thursday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(33, 2, 4, 'Thursday', 'Dinner', 2, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(34, 2, 4, 'Friday', 'Breakfast', 2, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(35, 2, 5, 'Friday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(36, 2, 3, 'Friday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(37, 2, 2, 'Saturday', 'Breakfast', 2, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(38, 2, 3, 'Saturday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(39, 2, 4, 'Saturday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(40, 2, 4, 'Sunday', 'Breakfast', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(41, 2, 3, 'Sunday', 'Lunch', 2, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(42, 2, 2, 'Sunday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(43, 3, 2, 'Monday', 'Breakfast', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(44, 3, 5, 'Monday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(45, 3, 2, 'Monday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(46, 3, 5, 'Tuesday', 'Breakfast', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(47, 3, 4, 'Tuesday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(48, 3, 5, 'Tuesday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(49, 3, 2, 'Wednesday', 'Breakfast', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(50, 3, 2, 'Wednesday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(51, 3, 4, 'Wednesday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(52, 3, 4, 'Thursday', 'Breakfast', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(53, 3, 5, 'Thursday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(54, 3, 2, 'Thursday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(55, 3, 5, 'Friday', 'Breakfast', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(56, 3, 2, 'Friday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(57, 3, 4, 'Friday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(58, 3, 2, 'Saturday', 'Breakfast', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(59, 3, 4, 'Saturday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(60, 3, 5, 'Saturday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(61, 3, 4, 'Sunday', 'Breakfast', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(62, 3, 5, 'Sunday', 'Lunch', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43'),
(63, 3, 2, 'Sunday', 'Dinner', 1, '2025-04-13 13:28:43', '2025-04-13 13:28:43');

-- --------------------------------------------------------

--
-- Table structure for table `meals`
--

CREATE TABLE `meals` (
  `id` int NOT NULL,
  `description` text,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `calories` int DEFAULT NULL,
  `proteins` int DEFAULT NULL,
  `fats` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `meals`
--

INSERT INTO `meals` (`id`, `description`, `name`, `image`, `calories`, `proteins`, `fats`, `created_at`, `updated_at`) VALUES
(2, 'Oatmeal topped with fresh fruits and nuts', 'Fruit Oatmeal Bowl', '', 300, 10, 10, '2025-02-26 12:45:26', '2025-04-12 19:59:50'),
(3, 'Salmon fillet with roasted sweet potatoes and asparagus', 'Salmon Delight', '', 500, 35, 20, '2025-02-26 12:45:26', '2025-02-26 12:45:26'),
(4, 'Protein shake with banana, peanut butter, and almond milk', 'Protein Shake', '', 250, 25, 10, '2025-02-26 12:45:26', '2025-02-26 12:45:26'),
(5, 'Avocado toast with a poached egg on whole-grain bread', 'Avocado Toast', '', 350, 15, 18, '2025-02-26 12:45:26', '2025-02-26 12:45:26');

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
  `avatar` blob,
  `bio` text NOT NULL,
  `rating` float DEFAULT '0',
  `review_count` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `trainers`
--

INSERT INTO `trainers` (`id`, `fname`, `lname`, `username`, `password`, `avatar`, `bio`, `rating`, `review_count`) VALUES
(1, 'Cos', 'Nanda', 'john', '$2y$12$DqWWm8SbOhtT2.P0NEUkoO6YEou.4fKDxUnLSDX4X8Xqv1tzGBUXW', NULL, 'Default trainer account with expertise in strength training and cardio.', 4, 20);

-- --------------------------------------------------------

--
-- Table structure for table `trainer_ratings`
--

CREATE TABLE `trainer_ratings` (
  `id` int NOT NULL,
  `trainer_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `rating` int NOT NULL,
  `review` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `trainer_ratings`
--

INSERT INTO `trainer_ratings` (`id`, `trainer_id`, `customer_id`, `rating`, `review`, `created_at`) VALUES
(1, 1, 1, 5, 'Excellent trainer! Really helped me achieve my fitness goals.Excellent trainer! Really helped me achieve my fitness goals.Excellent trainer! Really helped me achieve my fitness goals.Excellent trainer! Really helped me achieve my fitness goals.Excellent trainer! Really helped me achieve my fitness goals.Excellent trainer! Really helped me achieve my fitness goals.', '2025-03-16 05:35:56'),
(2, 1, 4, 5, 'The best trainer I have ever had! Very knowledgeable and motivating.', '2025-03-31 05:35:56'),
(3, 1, 6, 5, 'Helped me transform my fitness level completely. I can\'t thank them enough.', '2025-04-10 05:35:56'),
(4, 1, 3, 5, 'Very professional and knowledgeable about proper form and technique.', '2025-03-26 05:35:56'),
(5, 1, 11, 5, 'Amazing trainer! Tailored workouts to my specific needs and goals.', '2025-02-14 05:35:56'),
(6, 1, 14, 5, 'Always punctual and prepared for our sessions. Highly recommended!', '2025-03-01 05:35:56'),
(7, 1, 16, 5, 'I\'ve seen incredible results since working with this trainer.', '2025-02-22 05:35:56'),
(8, 1, 18, 5, 'Extremely knowledgeable about nutrition alongside fitness training.', '2025-03-08 05:35:56'),
(9, 1, 19, 5, 'Changed my perspective on fitness entirely. So grateful!', '2025-03-24 05:35:56'),
(10, 1, 21, 5, 'Outstanding approach to balancing strength and flexibility training.', '2025-04-03 05:35:56'),
(11, 1, 2, 4, 'Good knowledge and motivating sessions. Could improve on scheduling flexibility.', '2025-03-21 05:35:56'),
(12, 1, 5, 4, 'Great sessions and helpful advice on nutrition. Sometimes runs a bit over time.', '2025-04-05 05:35:56'),
(13, 1, 8, 4, 'Very effective workouts but occasionally late to sessions.', '2025-03-13 05:35:56'),
(14, 1, 10, 4, 'Really knows their stuff! Just wish the gym had better equipment.', '2025-03-19 05:35:56'),
(15, 1, 13, 4, 'Excellent training program, though sometimes communication could be better.', '2025-03-06 05:35:56'),
(16, 1, 7, 3, 'Decent trainer but seems distracted at times during our sessions.', '2025-03-11 05:35:56'),
(17, 1, 15, 3, 'Knowledgeable but sometimes doesn\'t listen to my concerns about certain exercises.', '2025-02-26 05:35:56'),
(18, 1, 9, 2, 'Often reschedules at the last minute. When we do train, it\'s good though.', '2025-02-19 05:35:56'),
(19, 1, 17, 1, 'Frequently late to sessions and doesn\'t seem prepared with a workout plan.', '2025-03-03 05:35:56'),
(20, 1, 20, 1, 'Not attentive to form corrections which led to minor injury. Disappointed.', '2025-03-28 05:35:56');

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
(4, 'Default Workout', 'Custom workout for customer #2', 30, '2025-04-13 10:38:45', '2025-04-13 10:38:45'),
(5, 'Strength Training', 'Custom version of Strength Training for customer #1', 30, '2025-04-13 10:39:49', '2025-04-13 10:39:49'),
(6, 'Default Workout', 'Custom workout for customer #6', 30, '2025-04-13 14:28:59', '2025-04-13 14:28:59'),
(7, 'Default Workout', 'Custom version of Default Workout for customer #6', 30, '2025-04-13 14:29:09', '2025-04-13 14:29:09'),
(8, 'Strength Training', 'Custom version of Strength Training for customer #1', 30, '2025-04-15 06:52:30', '2025-04-15 06:52:30');

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
(8, 1, 8, 4, 3, 12);

-- --------------------------------------------------------

--
-- Table structure for table `workout_sessions`
--

CREATE TABLE `workout_sessions` (
  `id` int NOT NULL,
  `user` int NOT NULL,
  `workout` int NOT NULL,
  `started_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ended_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workout_sessions`
--

INSERT INTO `workout_sessions` (`id`, `user`, `workout`, `started_at`, `ended_at`) VALUES
(1, 6, 1, '2025-04-12 08:19:06', '2025-04-12 08:19:14'),
(2, 4, 1, '2025-04-13 14:03:32', '2025-04-13 14:05:11');

--
-- Indexes for dumped tables
--

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
  ADD KEY `customer_workout` (`workout`);

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
-- Indexes for table `meals`
--
ALTER TABLE `meals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `membership_plans`
--
ALTER TABLE `membership_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

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
  ADD KEY `trainer_ratings_trainer_id` (`trainer_id`),
  ADD KEY `trainer_ratings_customer_id` (`customer_id`);

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
-- Indexes for table `workout_sessions`
--
ALTER TABLE `workout_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `WorkoutSession_User` (`user`),
  ADD KEY `WorkoutSession_Workout` (`workout`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=266;

--
-- AUTO_INCREMENT for table `customer_progress`
--
ALTER TABLE `customer_progress`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `meals`
--
ALTER TABLE `meals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `membership_plans`
--
ALTER TABLE `membership_plans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `trainers`
--
ALTER TABLE `trainers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `trainer_ratings`
--
ALTER TABLE `trainer_ratings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `workouts`
--
ALTER TABLE `workouts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `workout_exercises`
--
ALTER TABLE `workout_exercises`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `workout_sessions`
--
ALTER TABLE `workout_sessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  ADD CONSTRAINT `customer_workout` FOREIGN KEY (`workout`) REFERENCES `workouts` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `customer_initial_data`
--
ALTER TABLE `customer_initial_data`
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `mealplan_meals`
--
ALTER TABLE `mealplan_meals`
  ADD CONSTRAINT `mealplan_meals_ibfk_1` FOREIGN KEY (`mealplan_id`) REFERENCES `mealplans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mealplan_meals_ibfk_2` FOREIGN KEY (`meal_id`) REFERENCES `meals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workout_exercises`
--
ALTER TABLE `workout_exercises`
  ADD CONSTRAINT `workout_exercises_ibfk_1` FOREIGN KEY (`workout_id`) REFERENCES `workouts` (`id`),
  ADD CONSTRAINT `workout_exercises_ibfk_2` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`);

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
