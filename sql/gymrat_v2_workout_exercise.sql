-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2024 at 02:50 PM
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `video_link` varchar(500) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exercises`
--

INSERT INTO `exercises` (`id`, `name`, `description`, `video_link`, `created_at`, `updated_at`, `image`) VALUES
(1, 'Squats', 'A compound exercise that targets the lower body, including the quadriceps and glutes.', 'https://example.com/squats', '2024-11-15 06:32:46', '2024-11-15 06:32:46', ''),
(2, 'Deadlifts', 'A compound exercise targeting the back, glutes, and hamstrings.', 'https://example.com/deadlifts', '2024-11-15 06:32:46', '2024-11-15 06:32:46', ''),
(3, 'Bench Press', 'Targets the chest, shoulders, and triceps.', 'https://example.com/bench-press', '2024-11-15 06:32:46', '2024-11-15 06:32:46', ''),
(4, 'Pull-Ups', 'An upper-body exercise that targets the back and biceps.', 'https://example.com/pull-ups', '2024-11-15 06:32:46', '2024-11-15 06:32:46', ''),
(5, 'Overhead Press', 'Strengthens shoulders, upper chest, and triceps.', 'https://example.com/overhead-press', '2024-11-15 06:32:46', '2024-11-15 06:32:46', ''),
(6, 'Lunges', 'Targets quadriceps, hamstrings, and glutes.', 'https://example.com/lunges', '2024-11-15 06:32:46', '2024-11-15 06:32:46', ''),
(7, 'Quads', 'Focuses on the quadriceps muscle group.', 'https://example.com/quads', '2024-11-15 06:32:46', '2024-11-15 06:32:46', ''),
(8, 'Dumbbell Rows', 'Strengthens the back and biceps.', 'https://example.com/dumbbell-rows', '2024-11-15 06:32:46', '2024-11-15 06:32:46', '');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
