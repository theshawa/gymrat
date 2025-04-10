-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 12, 2025 at 08:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
  `is_created_by_trainer` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `type`, `description`, `user_id`, `created_at`, `is_created_by_trainer`) VALUES
(1, 'Equipment Issue', 'The treadmill is not functioning properly.', 101, '2024-11-25 20:25:18', 0),
(2, 'Cleanliness', 'The locker room needs cleaning.', 102, '2024-11-25 20:25:18', 0),
(3, 'Schedule Conflict', 'Trainer not available during requested hours.', 103, '2024-11-25 20:25:18', 1),
(4, 'Billing Error', 'Charged extra for last month\'s subscription.', 104, '2024-11-25 20:25:18', 0),
(5, 'Feedback', 'Requesting more yoga classes.', 105, '2024-11-25 20:25:18', 1);

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
  `workout` int(11) DEFAULT NULL COMMENT 'add related workout id',
  `meal_plan` int(11) DEFAULT NULL COMMENT 'add related meal plan id',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `fname`, `lname`, `email`, `phone`, `password`, `avatar`, `onboarded`, `membership_plan`, `membership_plan_activated_at`, `workout`, `meal_plan`, `created_at`, `updated_at`) VALUES
(21, 'Craig', 'Bricknell', 'ron@gmail.com', '119', '$2y$10$FjUeSnitJt4GslR2H9IZVu6D4EmW//tSxD0lkDvP30se3UN0ipDFa', 'customer-avatars/67459044d47b9.jpeg', 1, 14, '2024-11-26 04:40:06', NULL, NULL, '2024-11-26 09:09:46', '2024-11-26 09:09:46'),
(22, 'Craig', 'Boss', 'craig@gmail.com', '119', '$2y$10$zLuimhijl2g7jyPLbO6rB.BghjmFeyUAAfdqopHhsD7RDqOLkCYNS', 'customer-avatars/67459114de138.jpeg', 1, 14, '2024-11-26 04:43:16', NULL, NULL, '2024-11-26 09:13:04', '2024-11-26 09:13:04'),
(23, 'Rona', 'New', 'ronanew@gmail.com', '001223', '$2y$10$lxeC2LN4GFaXnEhsiW47AetY83yQAH2afBvquuDZaEncUB1HMt.4a', NULL, 1, 14, '2024-11-26 05:02:27', NULL, NULL, '2024-11-26 09:31:35', '2024-11-26 09:31:35'),
(25, 'Emily', 'Carter', 'emily.carter@example.com', '0771234567', '$2y$10$oNFBPqdhK6HK/EICyTB9JugEoGzqcn.Y.s/RZkLi0zaIIyQHpgBWW', NULL, 1, 17, '2024-11-27 11:06:31', 1, NULL, '2024-11-27 10:44:59', '2024-11-27 10:44:59'),
(26, 'Liam', 'Johnson', 'liam.johnson@example.co.uk', '0771234568', '$2y$10$v0Hl20SW7r.tQNutvfKAhep6WktBBZqtNjH74nZXwGnzaalwMIRU.', 'customer-avatars/6746fda47d8c2.jpg', 1, 18, '2024-11-27 11:08:44', 3, NULL, '2024-11-27 11:08:30', '2024-11-27 11:08:30'),
(28, 'Theshawa', 'Nimantha', 'mrclocktd@gmail.com', '0766743755', '$2y$10$.z7fm3Zh4KdQffbAwlhMhO346QekcWxvE4Zo.8LZgTudOX7iyYIGa', NULL, 1, 14, '2025-01-11 23:49:09', NULL, NULL, '2025-01-12 04:16:50', '2025-01-12 07:07:44');

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
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `video_link` varchar(255) DEFAULT 'https://www.youtube.com/watch?v=a3ICNMQW7Ok',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) NOT NULL DEFAULT '',
  `muscle_group` varchar(255) NOT NULL,
  `difficulty_level` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `equipment_needed` varchar(255) NOT NULL
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
(14, 'Basic', 'Access to gym facilities during standard hours. Ideal for beginners looking to start their fitness journey.', 6000, 30, 0, '2024-11-12 08:26:41', '2024-11-27 11:05:25'),
(15, 'Silver', 'Includes unlimited access to gym facilities, one personal training session, and group fitness classes. Perfect for intermediate fitness enthusiasts.', 15000, 30, 0, '2024-11-12 08:27:12', '2024-11-27 11:05:30'),
(17, 'Gold', 'Comprehensive membership offering 24/7 gym access, weekly personal training, and free entry to premium workshops. Great for dedicated fitness goals.', 30000, 30, 0, '2024-11-27 10:55:58', '2024-11-27 11:05:35'),
(18, 'Annual Elite', 'Full-year membership with unlimited gym access, customized training plans, monthly health assessments, and exclusive discounts on gym merchandise.', 300000, 365, 0, '2024-11-27 10:57:09', '2024-11-27 11:05:41');

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
(1, 'root', 'admin@example.com', '$2y$10$oNFBPqdhK6HK/EICyTB9JugEoGzqcn.Y.s/RZkLi0zaIIyQHpgBWW', 'admin', '2024-11-25 18:20:16', '2024-11-25 18:20:16'),
(1, 'root', 'admin@gmail.com', '$2y$10$zdFIy/uQ4BUhKOHzuuc6s.CD5ZTjhPzXrpH8MvS4v0PE/pSahgGwC', 'admin', '2024-11-25 18:20:16', '2024-11-25 18:20:16'),
(3, 'eq', 'eq@example.com', '$2y$10$oNFBPqdhK6HK/EICyTB9JugEoGzqcn.Y.s/RZkLi0zaIIyQHpgBWW', 'eq', '2024-11-25 18:34:54', '2024-11-25 18:34:54'),
(3, 'eq', 'eq@gmail.com', '$2y$10$zdFIy/uQ4BUhKOHzuuc6s.CD5ZTjhPzXrpH8MvS4v0PE/pSahgGwC', 'eq', '2024-11-25 18:34:54', '2024-11-25 18:34:54'),
(2, 'wnmp', 'wnmp@example.com', '$2y$10$oNFBPqdhK6HK/EICyTB9JugEoGzqcn.Y.s/RZkLi0zaIIyQHpgBWW', 'wnmp', '2024-11-25 18:34:54', '2024-11-25 18:34:54'),
(2, 'wnmp', 'wnmp@gmail.com', '$2y$10$zdFIy/uQ4BUhKOHzuuc6s.CD5ZTjhPzXrpH8MvS4v0PE/pSahgGwC', 'wnmp', '2024-11-25 18:34:54', '2024-11-25 18:34:54');

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

-- --------------------------------------------------------

--
-- Table structure for table `workout_sessions`
--

CREATE TABLE `workout_sessions` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `workout` int(11) NOT NULL,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ended_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

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
  ADD UNIQUE KEY `email` (`email`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `exercises`
--
ALTER TABLE `exercises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `membership_plans`
--
ALTER TABLE `membership_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
-- AUTO_INCREMENT for table `workout_sessions`
--
ALTER TABLE `workout_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customer_workout` FOREIGN KEY (`workout`) REFERENCES `workouts` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

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
