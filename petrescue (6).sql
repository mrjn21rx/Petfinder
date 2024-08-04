-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2024 at 05:06 PM
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
-- Database: `petrescue`
--

-- --------------------------------------------------------

--
-- Table structure for table `adoptdog`
--

CREATE TABLE `adoptdog` (
  `id` int(11) NOT NULL,
  `dog_name` varchar(100) NOT NULL,
  `your_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dog_preference` enum('Male','Female','No preference') NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adoptdog`
--

INSERT INTO `adoptdog` (`id`, `dog_name`, `your_name`, `email`, `dog_preference`, `submission_date`) VALUES
(1, 'lll', 'mike', 'kk@gmail.com', 'Female', '2024-07-16 12:15:57'),
(2, 'Lucy', 'Kasun', 'kasun@gmail.com', 'Male', '2024-07-16 12:30:51'),
(3, 'Rocky', 'Deshan', 'deshan@gmail.com', 'Male', '2024-07-20 09:23:04'),
(4, 'Max', 'Himesh', 'himesh@gmail.com', 'Male', '2024-07-21 12:01:17'),
(5, 'Rocky', 'Gayan', 'gayan@gmail.com', 'Male', '2024-07-22 15:05:01');

-- --------------------------------------------------------

--
-- Table structure for table `dogprofile`
--

CREATE TABLE `dogprofile` (
  `id` int(11) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `dog_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `gender` enum('male','female') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dogprofile`
--

INSERT INTO `dogprofile` (`id`, `photo`, `age`, `dog_name`, `description`, `video`, `gender`, `created_at`) VALUES
(1, 'buddy.jpg', 5, 'Buddy', 'Brown color', '561f25ba2c53b44c2e7e303deb243c72.mp4', 'male', '2024-07-19 12:16:18'),
(2, 'ella.jpg', 3, 'Ella', 'Cute adorable', '561f25ba2c53b44c2e7e303deb243c72.mp4', 'female', '2024-07-19 12:21:30'),
(3, 'lucy.jpg', 6, 'Lucy', 'While and brown color', '561f25ba2c53b44c2e7e303deb243c72.mp4', 'male', '2024-07-19 12:25:57'),
(4, 'Rocky.jpg', 3, 'Rocky', 'Small puppy', '', 'male', '2024-07-19 12:34:19'),
(5, 'maxdog.jpg', 5, 'Max', 'Friendly', '', 'male', '2024-07-19 12:35:45'),
(6, 'max.jpg', 3, 'Max Puppy', 'Samll puppy', '', 'male', '2024-07-19 14:37:02'),
(7, 'snovy.jpg', 2, 'Snovy', 'White color puppy.', '', 'male', '2024-07-21 12:08:29');

-- --------------------------------------------------------

--
-- Table structure for table `emergencyreport`
--

CREATE TABLE `emergencyreport` (
  `id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `photos` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Status'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergencyreport`
--

INSERT INTO `emergencyreport` (`id`, `description`, `location`, `photos`, `status`) VALUES
(1, 'road injury', 'Colombo', 'a:1:{i:0;s:20:\"uploads/loginDog.jpg\";}', 'rescued'),
(2, 'Seriously injured ', 'Kadawatha', 'a:1:{i:0;s:21:\"uploads/carinjury.jpg\";}', 'Status'),
(3, 'Dislocated leg', 'Maharagama', 'a:1:{i:0;s:21:\"uploads/legbroken.png\";}', 'Status'),
(4, 'Accident', 'colombo', 'a:1:{i:0;s:19:\"uploads/Dogbite.jpg\";}', 'Status');

-- --------------------------------------------------------

--
-- Table structure for table `lostandfound`
--

CREATE TABLE `lostandfound` (
  `id` int(11) NOT NULL,
  `dog_name` varchar(100) NOT NULL,
  `dog_age` varchar(20) DEFAULT NULL,
  `dog_description` text DEFAULT NULL,
  `last_seen_location` varchar(255) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lostandfound`
--

INSERT INTO `lostandfound` (`id`, `dog_name`, `dog_age`, `dog_description`, `last_seen_location`, `datetime`, `photo_path`, `created_at`) VALUES
(2, 'Max', '3 years', 'Brown color', 'Colombo', '0000-00-00 00:00:00', 'uploads/loginDog.jpg', '2024-07-14 09:46:47'),
(3, 'NiIchol', '5 years', 'White color ', 'Negombo', '0000-00-00 00:00:00', 'uploads/maxdog.jpg', '2024-07-14 09:57:50'),
(4, 'Rocky', '1 year', 'Brown and white color', 'Dehiwala', '2024-07-13 00:00:00', 'uploads/Rocky.jpg', '2024-07-14 10:08:06'),
(5, 'Brown', '2', 'Brown color', 'Kalaniya', '2024-07-21 00:00:00', 'uploads/Browny.jpg', '2024-07-22 14:14:06'),
(6, 'Snow', '2', 'White color small dog', 'galle', '2024-07-21 00:00:00', 'uploads/snovy.jpg', '2024-07-22 14:24:03'),
(7, 'shagi', '4', 'Brown and white color dog', 'Gampaha', '2024-07-20 00:00:00', 'uploads/loginDog.jpg', '2024-07-22 14:41:39');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `interest` text DEFAULT NULL,
  `user_type` varchar(50) NOT NULL,
  `preferences` text DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`id`, `full_name`, `email`, `username`, `password`, `phone_number`, `location`, `bio`, `interest`, `user_type`, `preferences`, `gender`) VALUES
(1, 'Imesha', 'imesha@gmail.com', 'Imesha', '$2y$10$uTpsGf2CaqJRmo24yThnm.IgrbqrdmwR9YZ8Uu14Q8QRr.rBXXnqO', '8863255', 'colombo', '', 'Adopting', 'Volunteer', 'Email', NULL),
(2, 'Kasun', 'kasun@gmail.com', 'Kasun', '$2y$10$X6Y94NPpwHFObjeQy2XjiuE2Q1rS2wRGuMcOybkpVTxNNFDoyLj7G', '555555555', 'Galle', '', '', 'Volunteer', 'Email', NULL),
(4, 'dilshan', 'dilshan@gmail.com', 'Dilshan', '$2y$10$cjABWweriHl5frUv5fYo4uL9EwvHqU3VaL6ObdewncfiRdkO5gEj6', '55555', 'Negombo', '', '', 'General user', '', NULL),
(8, 'Sara', 'sara@gmail.com', 'Sara', '$2y$10$mWNzGw/vYmYB5r2kNf74memrGkDRlYGN1xnMm5lFZOX4ZLZOQL2LG', '072-353958', NULL, '', NULL, 'Vet clinic', 'Email', 'Female'),
(11, 'Pramodya', 'pramo@gmail.com', 'Pramodya', '$2y$10$A43E2yjFOBjoWqYCTqys8.6Q2qGdLJxQnJYaPPbhJwpYzF//6hCBy', '075-3696523', NULL, '', NULL, 'Vet clinic', 'Email', 'Female');

-- --------------------------------------------------------

--
-- Table structure for table `reportstray`
--

CREATE TABLE `reportstray` (
  `id` int(11) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `photos` text DEFAULT NULL,
  `behaviour` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Status'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reportstray`
--

INSERT INTO `reportstray` (`id`, `description`, `location`, `photos`, `behaviour`, `created_at`, `status`) VALUES
(45, 'Injured dog', 'Colombo', 'a:1:{i:0;s:19:\"uploads/injured.jpg\";}', 'Friendly', '2024-07-14 10:14:43', 'rescued'),
(46, 'leg injured dog', 'Dehiwala', 'a:1:{i:0;s:21:\"uploads/legInjury.jpg\";}', 'Friendly', '2024-07-14 10:31:12', 'rescue in progress'),
(47, 'Dogbite', 'Kalaniya', 'a:1:{i:0;s:19:\"uploads/Dogbite.jpg\";}', 'Friendly', '2024-07-14 10:45:11', 'rescue in progress');

-- --------------------------------------------------------

--
-- Table structure for table `shelterfoster`
--

CREATE TABLE `shelterfoster` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `geolocation` varchar(255) DEFAULT NULL,
  `capacity` int(11) NOT NULL,
  `availability` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shelterfoster`
--

INSERT INTO `shelterfoster` (`id`, `name`, `type`, `contact_person`, `phone_number`, `email`, `address`, `geolocation`, `capacity`, `availability`) VALUES
(5, 'Dog shelter', 'Animal Shelter', 'Nimal', '075-56932154', 'nimal@gmail.com', 'colombo', 'colombo', 50, '12'),
(6, 'Shelter', '', 'Nimali', '011-2596325', 'nimali@gmail.com', 'galle', '', 32, '10'),
(7, 'Shelter pet', 'Animal Shelter', 'Sunil', '076-5469325', 'sunil@gmail.com', 'Dehiwala', '', 42, '20'),
(8, 'Foster home', 'Foster Home', 'Danush', '072-2563987', 'danush@gmail.com', 'Ampara', '', 56, '15'),
(17, 'Dog shelter ', 'Animal Shelter', 'Nimal', ' 075-56932154', 'nimal@gmail.com', 'colombo', '', 22, '14'),
(18, 'Paw shelter', 'Foster Home', 'Samadhi', '072-663594', 'paw@gmail.com', 'Colombo-7', '', 25, '12');

-- --------------------------------------------------------

--
-- Table structure for table `volunteer`
--

CREATE TABLE `volunteer` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `experience` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer`
--

INSERT INTO `volunteer` (`id`, `name`, `age`, `location`, `experience`, `created_at`) VALUES
(1, 'Savi', 20, 'Colombo', 'Not any', '2024-07-15 09:58:29'),
(2, 'Amashi', 19, 'Kurunagale', 'Assistant worker in a vet clinic', '2024-07-15 10:09:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adoptdog`
--
ALTER TABLE `adoptdog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dogprofile`
--
ALTER TABLE `dogprofile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emergencyreport`
--
ALTER TABLE `emergencyreport`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lostandfound`
--
ALTER TABLE `lostandfound`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `reportstray`
--
ALTER TABLE `reportstray`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shelterfoster`
--
ALTER TABLE `shelterfoster`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `volunteer`
--
ALTER TABLE `volunteer`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adoptdog`
--
ALTER TABLE `adoptdog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dogprofile`
--
ALTER TABLE `dogprofile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `emergencyreport`
--
ALTER TABLE `emergencyreport`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lostandfound`
--
ALTER TABLE `lostandfound`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reportstray`
--
ALTER TABLE `reportstray`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `shelterfoster`
--
ALTER TABLE `shelterfoster`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `volunteer`
--
ALTER TABLE `volunteer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
