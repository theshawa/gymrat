-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql_db:3306
-- Generation Time: Apr 14, 2025 at 06:32 AM
-- Server version: 9.2.0
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
(5, 'I&#039;m not Cos Mama', 'Sorry to say that I&#039;m not cos mama', 'rats', '1', '2025-05-14', '2025-04-14 06:26:06');

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
  `is_created_by_trainer` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `type`, `description`, `user_id`, `created_at`, `is_created_by_trainer`) VALUES
(1, 'Equipment Issue', 'The treadmill is not functioning properly.', 101, '2024-11-25 20:25:18', 0),
(2, 'Cleanliness', 'The locker room needs cleaning.', 102, '2024-11-25 20:25:18', 0),
(3, 'Schedule Conflict', 'Trainer not available during requested hours.', 103, '2024-11-25 20:25:18', 1),
(4, 'Billing Error', 'Charged extra for last month\'s subscription.', 104, '2024-11-25 20:25:18', 0),
(5, 'Feedback', 'Requesting more yoga classes.', 105, '2024-11-25 20:25:18', 1),
(9, 'Facility Issues', 'he hee', 1, '2025-04-14 02:00:32', 1);

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

INSERT INTO `customers` (`id`, `fname`, `lname`, `email`, `phone`, `password`, `avatar`, `onboarded`, `trainer`, `membership_plan`, `membership_plan_activated_at`, `workout`, `meal_plan`, `created_at`, `updated_at`) VALUES
(21, 'Craig', 'Bricknell', 'ron@gmail.com', '119', '$2y$10$FjUeSnitJt4GslR2H9IZVu6D4EmW//tSxD0lkDvP30se3UN0ipDFa', 'customer-avatars/67459044d47b9.jpeg', 1, NULL, 14, '2024-11-26 04:40:06', NULL, NULL, '2024-11-26 09:09:46', '2024-11-26 09:09:46'),
(22, 'Craig', 'Boss', 'craig@gmail.com', '119', '$2y$10$zLuimhijl2g7jyPLbO6rB.BghjmFeyUAAfdqopHhsD7RDqOLkCYNS', 'customer-avatars/67459114de138.jpeg', 1, NULL, 14, '2024-11-26 04:43:16', NULL, NULL, '2024-11-26 09:13:04', '2024-11-26 09:13:04'),
(23, 'Rona', 'New', 'ronanew@gmail.com', '001223', '$2y$10$lxeC2LN4GFaXnEhsiW47AetY83yQAH2afBvquuDZaEncUB1HMt.4a', NULL, 1, NULL, 14, '2024-11-26 05:02:27', NULL, NULL, '2024-11-26 09:31:35', '2024-11-26 09:31:35'),
(25, 'Emily', 'Carter', 'emily.carter@example.com', '0771234567', '$2y$10$oNFBPqdhK6HK/EICyTB9JugEoGzqcn.Y.s/RZkLi0zaIIyQHpgBWW', NULL, 1, NULL, 14, '2025-04-11 20:15:18', 1, NULL, '2024-11-27 10:44:59', '2024-11-27 10:44:59'),
(26, 'Liam', 'Johnson', 'liam.johnson@example.co.uk', '0771234568', '$2y$10$v0Hl20SW7r.tQNutvfKAhep6WktBBZqtNjH74nZXwGnzaalwMIRU.', 'customer-avatars/6746fda47d8c2.jpg', 1, NULL, 18, '2024-11-27 11:08:44', 3, NULL, '2024-11-27 11:08:30', '2024-11-27 11:08:30'),
(38, 'Theshawa', 'Nimantha', 'mrclocktd@gmail.com', '0766743755', '$2y$10$479CEeEBC4DJGEJIuAijIOql11UUTeopP/uyNTjxrjVaL7TYA1126', 'customer-avatars/67fb98ce0e22d.jpg', 1, 1, 14, '2025-04-13 10:58:49', NULL, NULL, '2025-04-13 10:58:43', '2025-04-13 10:58:43');

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
(38, 'male', 24, 'weight_loss', '', 123, 122, 'beginner', 'paleo', '', '2025-04-13 10:59:01');

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
(1, 'Cos', 'Mama', 'john', '$2y$12$DqWWm8SbOhtT2.P0NEUkoO6YEou.4fKDxUnLSDX4X8Xqv1tzGBUXW', NULL, 'Default trainer account with expertise in strength training and cardio.', 4.8, 483);

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
(3, 'Flexibility', 'Stretching, Yoga, Pilates, Tai Chi, Foam Rolling, Dynamic Stretching, Static Stretching', 30, '2024-11-15 06:13:10', '2024-11-15 06:13:10');

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
(2, 38, 1, '2025-04-13 11:06:48', '2025-04-13 11:06:54'),
(3, 38, 1, '2025-04-13 11:06:56', '2025-04-13 11:19:45'),
(4, 38, 1, '2025-04-13 11:36:58', '2025-04-13 11:37:01'),
(5, 38, 1, '2025-04-13 11:44:11', '2025-04-13 11:44:37'),
(6, 38, 1, '2025-04-13 11:45:05', '2025-04-13 11:46:36'),
(7, 38, 1, '2025-04-13 11:46:38', '2025-04-13 11:47:25'),
(8, 38, 1, '2025-04-14 01:49:40', '2025-04-14 01:49:52'),
(9, 38, 1, '2025-04-14 04:47:54', '2025-04-14 04:47:58'),
(10, 38, 1, '2025-04-14 05:05:34', '2025-04-14 05:06:07'),
(11, 38, 1, '2025-04-14 05:06:11', '2025-04-14 05:06:25'),
(12, 38, 1, '2025-04-14 05:06:28', '2025-04-14 05:06:52'),
(13, 38, 1, '2025-04-14 05:06:52', '2025-04-14 05:07:53'),
(14, 38, 1, '2025-04-14 05:07:53', '2025-04-14 05:09:44');

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
  ADD KEY `fk_customer_trainer` (`trainer`);

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
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `trainers`
--
ALTER TABLE `trainers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `workouts`
--
ALTER TABLE `workouts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `workout_exercises`
--
ALTER TABLE `workout_exercises`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `workout_sessions`
--
ALTER TABLE `workout_sessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  ADD CONSTRAINT `fk_customer_trainer` FOREIGN KEY (`trainer`) REFERENCES `trainers` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `customer_initial_data`
--
ALTER TABLE `customer_initial_data`
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
