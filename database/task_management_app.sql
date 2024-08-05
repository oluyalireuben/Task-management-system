-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2024 at 04:35 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `task_management_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(2, 'formal task', '2024-08-05 14:10:41'),
(3, 'unformal task', '2024-08-05 14:10:50'),
(4, 'unknown task', '2024-08-05 14:10:58');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','in_progress','completed') DEFAULT 'pending',
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `category_id`, `title`, `description`, `status`, `due_date`, `created_at`) VALUES
(2, 8, NULL, 'washing', 'I will be washing utensils', 'pending', '2024-08-04', '2024-08-05 13:19:47'),
(3, 10, NULL, 'Playing ps', 'i will play PS', 'pending', '2024-08-05', '2024-08-05 13:52:08'),
(5, 7, 2, 'e', 'I will be cooking Ugali Mayai today night', 'pending', '2024-08-05', '2024-08-05 14:16:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'sharon', 'sharon@gmaill.com', '$2y$10$9o/B51lYV9RbfEIjYJaQrO0q.fW/AJswqs8SYWI6WKMhOk3wVKa26', 'user', '2024-08-05 10:58:40'),
(2, 'iano', 'iano@gmail.com', '$2y$10$CcWNpk6U9nxzQTEllyYKVeLMxILWQpTsbrpQODFZr2IIzN9phyzUu', 'admin', '2024-08-05 11:01:08'),
(3, 'oluyali', 'oluyalireuben1@gmail.com', '$2y$10$GEgFF0Ni1zGFl8nUwS0/D.gnGcHdaD2mOfj3zykr4ujwgx5S.VwUO', 'admin', '2024-08-05 11:36:16'),
(6, 'oluyalir', 'oluyalireuben@gmail.com', '$2y$10$667AW8cM1kyRHtOeT.VP0eReIeoYVZwUuP5F2e/qCgib/bko5Xk0m', 'user', '2024-08-05 11:37:08'),
(7, 'normal', 'normal@gmail.com', '$2y$10$lRYbDgHBD7hd2pVOetyCz.mrC4ZzR9EMrPo.zRs88jFUK9vs9ULH2', 'user', '2024-08-05 12:20:12'),
(8, 'ian', 'ian1@gmail.com', '$2y$10$xkfVBfCqbZoY1MIckdVyzurszLFo8HMjEkHSQc1NL6qR4QVxrYFFe', 'user', '2024-08-05 12:22:24'),
(10, 'milton', 'milton@gmail.com', '$2y$10$DAkaTzO9fPNCaWMskcUjv.0uC/TReRx/rK0WaFb.VIqfXjg3dZLTG', 'user', '2024-08-05 12:30:03'),
(11, 'admin', 'admin@gmail.com', '$2y$10$QzNDRHHSsi2eyfZI0oXDwuSYpy0sEvhln90H97o.K0aWNrDC/sjgK', 'admin', '2024-08-05 13:53:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
