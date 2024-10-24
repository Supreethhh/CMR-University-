-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2024 at 12:45 PM
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
-- Database: `task_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `assigned_to` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `rating` int(2) DEFAULT NULL,
  `format` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `status`, `assigned_to`, `created_by`, `file`, `rating`, `format`) VALUES
(1, 'Monitoring of Classes', '1. Attendance Compliance Rate: Achieve 100% compliance with the scheduled timetable for all staff.\r\n', 'pending', 2, 1, '', NULL, 'awdwadawd'),
(125, 'Event Calendar', '1. Calendar Accuracy: Number of all the events correctly listed and scheduled on the calendar.-28 in a semester, 56 events spread over 28 weeks in an academic year.\r\n\r\n2. Student Competitions:\r\na. Number of students participating in competitions compared to the total student population.- 50% of  individual student participation to keep track \r\n\r\nStudent Outreach Programs:\r\na. Program Reach: Number of students reached and engaged through outreach programs.\r\nb. Program Impact: Positive impact as measured by student feedback and program outcomes.\r\nc. Resource Utilization: Effectiveness in utilizing resources allocated for outreach programs.\r\n3. Outcome of the events: 50% students to be attending the event, report to be generated, signature, NAAC', 'pending', 2, 1, NULL, NULL, NULL),
(127, ' LMS', '1. Report Completeness: Number of LMS-related aspects covered in your school. Analysis of LMS usage data, such as logins, course completions, and user engagement. (Subject Allotment and LMS - 15 days after the allotment of the subject)\r\nFor Dean: LMS (responsible for the allotments)- June/ July every semester/ November/ December\r\nDirector: Need to supervise the LMS ', 'pending', 2, 1, NULL, NULL, NULL),
(128, ' Institution Collaboration report', '1. Number of Collaborations: Count of active industrial collaborations.- Min 2 per semester\r\n2. Project Outcomes: Achievements and results from collaborative projects, including research outputs and successful internships.\r\n3. Impact Stories: Documented success stories and case studies from collaborations.', 'pending', 2, 1, NULL, NULL, NULL),
(129, ' Institution Collaboration report', '1. Number of Collaborations: Count of active industrial collaborations.- Min 2 per semester\r\n2. Project Outcomes: Achievements and results from collaborative projects, including research outputs and successful internships.\r\n3. Impact Stories: Documented success stories and case studies from collaborations.', 'pending', 2, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', 'admin123', 'admin'),
(2, 'user1', '123', 'user'),
(3, 'user2', '234', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `created_by` (`created_by`);

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
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
