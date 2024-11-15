-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2024 at 06:33 PM
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
(21, 'test', 'asd', 132, 1234, 0, '2024-11-15 12:48:13', '2024-11-15 12:48:13'),
(29, 'Theshawa', '132', 123, 123, 0, '2024-11-15 12:53:13', '2024-11-15 12:54:49');

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
-- Indexes for table `membership_plans`
--
ALTER TABLE `membership_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `membership_plans`
--
ALTER TABLE `membership_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- --------------------------------------------------------

--
-- Table structure for table `workouts`
--

CREATE TABLE `workouts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `description` varchar(500) NOT NULL,
    `duration` int(11) NOT NULL COMMENT 'In Days',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --
-- -- AUTO_INCREMENT for table `workouts`
-- --
-- ALTER TABLE workouts MODIFY id INT(11) NOT NULL AUTO_INCREMENT;

--
-- Dumping data for table `workouts`
--
INSERT INTO workouts (name, description, duration) VALUES
   ('Strength Training', 'Squats, Deadlifts, Bench Press, Pull-Ups, Overhead Press, Lunges, Quads, Dumbbell Rows', 30),
   ('Cardio', 'Running, Cycling, Swimming, Rowing, Jump Rope, Stair Climbing, Hiking, Elliptical', 30),
   ('Flexibility', 'Stretching, Yoga, Pilates, Tai Chi, Foam Rolling, Dynamic Stretching, Static Stretching', 30);


-- --------------------------------------------------------

--
-- Table structure for table `exercise`
--

CREATE TABLE `exercises` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(100) NOT NULL,
        `description` varchar(500) NOT NULL,
        `video_link` varchar(500) NOT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --
-- -- AUTO_INCREMENT for table `exercises`
-- --
-- ALTER TABLE exercises MODIFY id INT(11) NOT NULL AUTO_INCREMENT;

--
-- Dumping data for table `exercises`
--

INSERT INTO exercises (name, description, video_link) VALUES
        ('Squats', 'A compound exercise that targets the lower body, including the quadriceps and glutes.', 'https://example.com/squats'),
        ('Deadlifts', 'A compound exercise targeting the back, glutes, and hamstrings.', 'https://example.com/deadlifts'),
        ('Bench Press', 'Targets the chest, shoulders, and triceps.', 'https://example.com/bench-press'),
        ('Pull-Ups', 'An upper-body exercise that targets the back and biceps.', 'https://example.com/pull-ups'),
        ('Overhead Press', 'Strengthens shoulders, upper chest, and triceps.', 'https://example.com/overhead-press'),
        ('Lunges', 'Targets quadriceps, hamstrings, and glutes.', 'https://example.com/lunges'),
        ('Quads', 'Focuses on the quadriceps muscle group.', 'https://example.com/quads'),
        ('Dumbbell Rows', 'Strengthens the back and biceps.', 'https://example.com/dumbbell-rows');

-- --------------------------------------------------------

--
-- Table structure for table `workout_exercises`
--

CREATE TABLE workout_exercises (
        id INT(11) NOT NULL AUTO_INCREMENT,
        workout_id INT(11) NOT NULL,
        exercise_id INT(11) NOT NULL,
        day INT(11) NOT NULL,
        sets INT(11) NOT NULL,
        reps INT(11) NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (workout_id) REFERENCES workouts(id),
        FOREIGN KEY (exercise_id) REFERENCES exercises(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workout_exercises`
--

INSERT INTO workout_exercises (workout_id, exercise_id, day, sets, reps) VALUES
        (1, 1, 1, 4, 10), -- Squats
        (1, 2, 1, 3, 8),  -- Deadlifts
        (1, 3, 2, 4, 12), -- Bench Press
        (1, 4, 2, 3, 10), -- Pull-Ups
        (1, 5, 3, 4, 8),  -- Overhead Press
        (1, 6, 3, 3, 12), -- Lunges
        (1, 7, 4, 4, 10), -- Quads
        (1, 8, 4, 3, 12); -- Dumbbell Rows