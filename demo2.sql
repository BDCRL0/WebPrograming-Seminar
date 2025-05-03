-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2025 at 03:19 AM
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
-- Database: `demo2`
--

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_messages`
--

CREATE TABLE `chatbot_messages` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chatbot_messages`
--

INSERT INTO `chatbot_messages` (`id`, `username`, `message`, `created_at`) VALUES
(2, 'Jane', 'I would like to make a reservation.', '2025-04-29 22:43:46'),
(3, 'mikhael2', 'mikhael', '2025-04-29 22:52:26'),
(4, 'guest', 'mikhael', '2025-04-29 22:58:18');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recipe_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `recipe_name`, `created_at`) VALUES
(1, 4, 'Biryani', '2025-05-03 00:31:47'),
(2, 4, 'Pizza', '2025-05-03 00:31:55');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'mikhael ', 'mikha.nabil13@gmail.com', 'as', '2025-05-02 22:21:21'),
(2, 'mikhael ', 'mikha.nabil13@gmail.com', 'as', '2025-05-02 22:23:02'),
(3, 'mikhael ', 'mikha.nabil13@gmail.com', 'as', '2025-05-02 22:24:22');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_images`
--

CREATE TABLE `recipe_images` (
  `id` int(11) NOT NULL,
  `recipe_name` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `tags` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipe_images`
--

INSERT INTO `recipe_images` (`id`, `recipe_name`, `image_path`, `tags`) VALUES
(1, 'Spaghetti Bolognese', 'images/spaghetti.jpg', 'pasta, tomato, beef'),
(2, 'Margherita Pizza', 'images/pizza.jpg', 'pizza, cheese, tomato'),
(3, 'Caesar Salad', 'images/salad.jpg', 'salad, lettuce, chicken'),
(4, 'sss', '../uploads/6815677b11e0d_9.png', 'pasta');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`) VALUES
(1, 'mikhael', '$2y$10$5DvJrZxKWfp64OOEmHMDqujIup6bH38Yf.Tu0QCjKVqysOWrk8UiW', 'mikhael', 'rezk'),
(3, 'mikha', '$2y$10$hmhB0z.ZY2YcPeaJ67U8Xex5v9aErGMvgx7XAP9WLqH3pnDv7igfq', 'mikhael', 'rezk'),
(4, 'mikhael2', '$2y$10$Je8gLwAirQRjsoEgxk6MUertYU1RvOl3Fm1s9Vls4JS3ZdSgDg9B.', 'mikhael', 'rezk'),
(5, 'new', '$2y$10$e.j/EEC0vZxL9w4FtYFsVuVYxD1GwTvncHdOSmKXG47kLaN9cicVW', 'mikhael', 'rezk');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chatbot_messages`
--
ALTER TABLE `chatbot_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipe_images`
--
ALTER TABLE `recipe_images`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `recipe_images` ADD FULLTEXT KEY `tags` (`tags`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chatbot_messages`
--
ALTER TABLE `chatbot_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `recipe_images`
--
ALTER TABLE `recipe_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
