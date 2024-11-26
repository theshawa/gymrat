-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2024 at 08:18 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_created_by_trainer` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `type`, `description`, `user_id`, `created_at`, `is_created_by_trainer`) VALUES
(1, 'Equipment Issue', 'The treadmill is not functioning properly.', 101, '2024-11-25 14:55:18', 0),
(2, 'Cleanliness', 'The locker room needs cleaning.', 102, '2024-11-25 14:55:18', 0),
(3, 'Schedule Conflict', 'Trainer not available during requested hours.', 103, '2024-11-25 14:55:18', 1),
(4, 'Billing Error', 'Charged extra for last month\'s subscription.', 104, '2024-11-25 14:55:18', 0),
(5, 'Feedback', 'Requesting more yoga classes.', 105, '2024-11-25 14:55:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(60) NOT NULL COMMENT 'https://stackoverflow.com/questions/247304/what-data-type-to-use-for-hashed-password-field-and-what-length',
  `avatar` varchar(100) DEFAULT NULL,
  `onboarded` tinyint(1) NOT NULL DEFAULT 0,
  `membership_plan` int(11) NOT NULL,
  `membership_plan_activated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `fname`, `lname`, `email`, `phone`, `password`, `avatar`, `onboarded`, `membership_plan`, `membership_plan_activated_at`, `created_at`, `updated_at`) VALUES
(21, 'Ravindu', 'Peeris', 'johndoe@gmail.com', '0123456789', '$2y$10$hiuokpspKkfC6UNV5e8AieiA6v6Aa2pqudV8rkA3/dgofS3eWbSNG', 'customer-avatars/674590fdb9828.jpg', 1, 15, '2024-11-26 04:43:11', '2024-11-26 09:12:41', '2024-11-26 09:12:41'),
(22, 'Test', 'test', 'admin@gmail.com', '0123456789', '$2y$10$TPHXI3MJxUEKtcJP8Oa/jOIEe7OK.4eqVqsQDTmCu8rD9kIcV7HjG', NULL, 1, 14, '2024-11-26 04:44:33', '2024-11-26 09:14:18', '2024-11-26 09:14:18');

-- --------------------------------------------------------

--
-- Table structure for table `customer_email_verification_requests`
--

CREATE TABLE `customer_email_verification_requests` (
  `email` varchar(100) NOT NULL,
  `code` varchar(6) NOT NULL,
  `creation_attempt` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_password_reset_requests`
--

CREATE TABLE `customer_password_reset_requests` (
  `email` varchar(100) NOT NULL,
  `code` varchar(6) NOT NULL,
  `creation_attempt` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_password_reset_requests`
--

INSERT INTO `customer_password_reset_requests` (`email`, `code`, `creation_attempt`, `created_at`) VALUES
('johndoe@gmail.com', '133965', 1, '2024-11-26 18:06:35');

-- --------------------------------------------------------

--
-- Table structure for table `equipments`
--

CREATE TABLE `equipments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` varchar(500) NOT NULL,
  `manufacturer` varchar(255) NOT NULL,
  `image` varchar(500) DEFAULT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_maintenance` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipments`
--

INSERT INTO `equipments` (`id`, `name`, `type`, `description`, `manufacturer`, `image`, `purchase_date`, `last_maintenance`, `created_at`, `updated_at`) VALUES
(1, 'Leg Press Machine', 'Strength Equipment', 'A versatile machine designed to target quadriceps, hamstrings, and glutes effectively.', 'GymPro', '', '2022-03-01 02:30:00', '2023-06-15 02:30:00', '2024-11-26 14:30:21', '2024-11-26 19:00:38'),
(2, 'Squat Rack', 'Strength Equipment', 'A rack for performing squats and other compound exercises.', 'IronMax', NULL, '2021-05-10 02:30:00', '2023-07-01 02:30:00', '2024-11-26 14:30:21', '2024-11-26 14:30:21'),
(4, 'Calf Raise Machine', 'Strength Equipment', 'Targets and strengthens the calf muscles.', 'PowerFit', NULL, '2020-11-20 02:30:00', '2023-08-01 02:30:00', '2024-11-26 14:30:21', '2024-11-26 14:30:21'),
(5, 'Bench Press', 'Strength Equipment', 'A classic equipment for chest and triceps strength training.', 'MuscleTech', NULL, '2023-03-05 02:30:00', '2023-09-01 02:30:00', '2024-11-26 14:30:21', '2024-11-26 14:30:21'),
(6, 'Chest Fly Machine', 'Strength Equipment', 'Builds chest muscles and improves posture.', 'HealthLine', NULL, '2022-10-12 02:30:00', '2023-06-20 02:30:00', '2024-11-26 14:30:21', '2024-11-26 14:30:21'),
(7, 'Lat Pulldown Machine', 'Strength Equipment', 'A machine for strengthening the back and biceps.', 'BackFit', 'uploads/default-images/latpull.png', '2021-09-30 02:30:00', '2023-04-15 02:30:00', '2024-11-26 14:30:21', '2024-11-26 14:30:21'),
(8, 'Dumbbells', 'Strength Equipment', 'Versatile free weights for full-body strength training.', 'FlexPro', 'uploads/default-images/dumbbells.jpg', '2022-12-25 02:30:00', '2023-05-01 02:30:00', '2024-11-26 14:30:21', '2024-11-26 14:30:21');

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `video_link` varchar(255) DEFAULT 'https://www.youtube.com/watch?v=a3ICNMQW7Ok',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exercises`
--

INSERT INTO `exercises` (`id`, `name`, `description`, `video_link`, `created_at`, `updated_at`, `image`) VALUES
(1, 'Squats', 'Squats are a fundamental lower-body exercise that targets multiple muscle groups, including the quadriceps, hamstrings, glutes, and core. They help improve strength, stability, and overall athletic performance.', 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', '2024-11-15 06:32:46', '2024-11-15 06:32:46', ''),
(2, 'Deadlifts', 'A compound exercise targeting the back, glutes, and hamstrings.', 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', '2024-11-15 06:32:46', '2024-11-15 06:32:46', ''),
(3, 'Bench Press', 'Targets the chest, shoulders, and triceps.', 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', '2024-11-15 06:32:46', '2024-11-15 06:32:46', ''),
(4, 'Pull-Ups', 'An upper-body exercise that targets the back and biceps.', 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', '2024-11-15 06:32:46', '2024-11-15 06:32:46', ''),
(5, 'Overhead Press', 'Strengthens shoulders, upper chest, and triceps.', 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', '2024-11-15 06:32:46', '2024-11-15 06:32:46', ''),
(6, 'Lunges', 'Targets quadriceps, hamstrings, and glutes.', 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', '2024-11-15 06:32:46', '2024-11-15 06:32:46', ''),
(7, 'Quads', 'Focuses on the quadriceps muscle group.', 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', '2024-11-15 06:32:46', '2024-11-15 06:32:46', ''),
(8, 'Dumbbell Rows', 'Strengthens the back and biceps.', 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', '2024-11-15 06:32:46', '2024-11-15 06:32:46', '');

-- --------------------------------------------------------

--
-- Table structure for table `membership_plans`
--

CREATE TABLE `membership_plans` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `price` int(11) NOT NULL,
  `duration` int(11) NOT NULL COMMENT 'In days',
  `is_locked` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership_plans`
--

INSERT INTO `membership_plans` (`id`, `name`, `description`, `price`, `duration`, `is_locked`, `created_at`, `updated_at`) VALUES
(14, 'Test', 'sdf', 234, 238, 1, '2024-11-12 08:26:41', '2024-11-12 08:31:47'),
(15, 'Test 2', 'adsa', 123, 123, 0, '2024-11-12 08:27:12', '2024-11-12 08:32:29');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'root', 'admin@gmail.com', '$2y$10$zdFIy/uQ4BUhKOHzuuc6s.CD5ZTjhPzXrpH8MvS4v0PE/pSahgGwC', 'admin', '2024-11-25 12:50:16', '2024-11-25 12:50:16'),
(2, 'wnmp', 'wnmp@gmail.com', '$2y$10$zdFIy/uQ4BUhKOHzuuc6s.CD5ZTjhPzXrpH8MvS4v0PE/pSahgGwC', 'wnmp', '2024-11-25 13:04:54', '2024-11-25 13:04:54'),
(3, 'eq', 'eq@gmail.com', '$2y$10$zdFIy/uQ4BUhKOHzuuc6s.CD5ZTjhPzXrpH8MvS4v0PE/pSahgGwC', 'eq', '2024-11-25 13:04:54', '2024-11-25 13:04:54');

-- --------------------------------------------------------

--
-- Table structure for table `workouts`
--

CREATE TABLE `workouts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `duration` int(11) NOT NULL COMMENT 'In Days',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
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
  `id` int(11) NOT NULL,
  `workout_id` int(11) NOT NULL,
  `exercise_id` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `sets` int(11) NOT NULL,
  `reps` int(11) NOT NULL
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `customer_email_verification_requests`
--
ALTER TABLE `customer_email_verification_requests`
  ADD PRIMARY KEY (`email`);

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
-- Indexes for table `membership_plans`
--
ALTER TABLE `membership_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `exercises`
--
ALTER TABLE `exercises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `membership_plans`
--
ALTER TABLE `membership_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `workouts`
--
ALTER TABLE `workouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `workout_exercises`
--
ALTER TABLE `workout_exercises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `workout_exercises`
--
ALTER TABLE `workout_exercises`
  ADD CONSTRAINT `workout_exercises_ibfk_1` FOREIGN KEY (`workout_id`) REFERENCES `workouts` (`id`),
  ADD CONSTRAINT `workout_exercises_ibfk_2` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
