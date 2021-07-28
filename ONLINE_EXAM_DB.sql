-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jul 28, 2021 at 12:22 PM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ONLINE_EXAM_DB`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_category`
--

CREATE TABLE `tb_category` (
                               `cat_id` int(11) NOT NULL,
                               `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
                               `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                               `teacher_id` int(11) NOT NULL,
                               `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                               `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_category`
--

INSERT INTO `tb_category` (`cat_id`, `name`, `description`, `teacher_id`, `created_at`, `updated_at`) VALUES
(1, 'Networking', 'TCP/IP network training', 2, '2021-02-21 05:06:21', '2021-02-21 05:06:23'),
(3, 'Windows Administration', 'Windows server administrator training', 5, '2021-02-21 05:06:21', '2021-02-21 05:06:23'),
(4, 'WWW General', 'Training for World Wide Web scheme such as HTTP, HTML, CGI', 5, '2021-02-21 05:08:28', '2021-02-21 05:08:28'),
(5, 'Oracle 10g Database (DBA)', 'Administrator of Oracle 10g Database training', 2, '2021-02-21 05:08:28', '2021-02-21 05:08:28'),
(6, 'MySQL Database', 'MySQL Database administrator training', 2, '2021-02-21 05:08:28', '2021-02-21 05:08:28'),
(7, 'UML', 'Unified Modeling Language training', 5, '2021-02-21 05:08:28', '2021-02-21 05:08:28'),
(8, 'IBM Homepage builder', 'Web contents authoring tool training', 2, '2021-02-21 05:08:28', '2021-02-21 05:08:28'),
(9, 'IoT', 'Internet of things', 2, '2021-04-06 10:56:19', '2021-04-06 10:56:19');

-- --------------------------------------------------------

--
-- Table structure for table `tb_questions`
--

CREATE TABLE `tb_questions` (
                                `q_id` int(11) NOT NULL,
                                `q_title` text COLLATE utf8_unicode_ci NOT NULL,
                                `q_desc` text COLLATE utf8_unicode_ci,
                                `q_difficulty` int(3) NOT NULL,
                                `q_answer_type` int(1) NOT NULL,
                                `q_sel1` text COLLATE utf8_unicode_ci NOT NULL,
                                `q_sel2` text COLLATE utf8_unicode_ci,
                                `q_sel3` text COLLATE utf8_unicode_ci,
                                `q_sel4` text COLLATE utf8_unicode_ci,
                                `q_sel5` text COLLATE utf8_unicode_ci,
                                `q_fig1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                                `q_fig2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                                `q_fig3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                                `q_fig4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                                `q_fig5` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                                `q_answer` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
                                `q_explain` text COLLATE utf8_unicode_ci,
                                `q_use_count` int(11) DEFAULT NULL,
                                `q_status` tinyint(4) NOT NULL,
                                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                `sub_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_questions`
--

INSERT INTO `tb_questions` (`q_id`, `q_title`, `q_desc`, `q_difficulty`, `q_answer_type`, `q_sel1`, `q_sel2`, `q_sel3`, `q_sel4`, `q_sel5`, `q_fig1`, `q_fig2`, `q_fig3`, `q_fig4`, `q_fig5`, `q_answer`, `q_explain`, `q_use_count`, `q_status`, `created_at`, `updated_at`, `sub_id`) VALUES
(1, 'What is the SQL*Plus command that deletes a line ?', 'ທົດສອບ', 1, 1, 'DEL', 'DROP', 'CLEAR', 'CHANGE', '', '', '', '', '', '', '1', 'ABCD', NULL, 0, '2021-02-21 09:15:55', '2021-02-21 09:15:55', 1),
(2, 'What is the purpose of SELECT statement ?', NULL, 2, 2, 'Creating table data', 'Performing conditional search of table data', 'Changing table data', 'Sorting the searched data', 'Calculating with the searched data', NULL, NULL, NULL, NULL, NULL, '2,5', 'ABD', NULL, 1, '2021-02-21 09:15:55', '2021-02-21 09:15:55', 1),
(3, 'Please guess the result of the next SELECT statement. SELECT 100*(5+100)/2+1000/50 ?', 'Show desc here', 2, 1, '550', '125', '31', '260', '5270', '', '', '', '', '', '5', 'CBA', NULL, 0, '2021-02-21 09:15:55', '2021-02-21 09:15:55', 1),
(4, 'Group functions work across many rows to produce one result per group ?', '', 2, 1, 'True', 'False', '', '', '', '', '', '', '', '', '1', '', NULL, 1, '2021-03-06 15:24:09', '2021-03-06 15:24:09', 1),
(5, 'Group functions include nulls in calculations ?', '', 3, 1, 'True', 'False', '', '', '', '', '', '', '', '', '2', 'Group functions ignore null values. If you want to include null\r\nvalues, use the NVL function.', NULL, 1, '2021-03-06 15:25:42', '2021-03-06 15:25:42', 1),
(6, 'The WHERE clause restricts rows before inclusion in a group calculation ?', '', 1, 1, 'True', 'False', '', '', '', '', '', '', '', '', '1', '', NULL, 1, '2021-03-06 16:23:40', '2021-03-06 16:23:40', 1),
(7, 'How many join types in join condition ?', '', 4, 1, '2', '3', '4', '5', '', '', '', '', '', '', '4', 'INNER JOIN, LEFT JOIN, RIGHT JOIN, FULL JOIN, EQUIJOIN.', NULL, 1, '2021-03-07 07:00:20', '2021-03-07 07:00:20', 2),
(8, 'Which are the join types in join condition ?', '', 1, 1, 'Cross join', 'Natural join', 'Join with USING clause', 'All of the mentioned', '', '', '', '', '', '', '4', 'INNER JOIN, LEFT JOIN, RIGHT JOIN, FULL JOIN, EQUIJOIN are the types of joins.', NULL, 1, '2021-03-07 07:01:51', '2021-03-07 07:01:51', 2),
(9, 'Which join refers to join records from the write table that have no matching key in the left table are include in the result set ?', '', 3, 1, 'Left outer join', 'Right outer join', 'Full outer join', 'None of the above', '', '', '', '', '', '', '2', 'Right outer join refers to join records from the write table that have no matching key in the left table are include in the result set', NULL, 1, '2021-03-07 07:54:22', '2021-03-07 07:54:22', 2),
(11, 'Test qt', '', 1, 1, 'AAa', 'CC', '', '', '', '', '', '', '', '', '1', '', NULL, 0, '2021-03-09 12:04:35', '2021-03-09 12:04:35', 4),
(12, 'How many types of arduinos do we have?', 'Test desc', 1, 1, '5', '6', '7', '8', '', '', '', '', '', '', '4', 'There are 4 Arduino boards and 4 Arduino shields that fit on top of Arduino compatible boards to provide additional capability like connecting to the internet, motor controller, LCD screen controlling etc.,.', NULL, 1, '2021-04-06 11:00:55', '2021-04-06 11:00:55', 6),
(13, 'What is the microcontroller used in Arduino UNO?', '', 3, 1, 'ATmega328p', 'ATmega2560', 'ATmega32114', 'AT91SAM3x8E', '', '', '', '', '', '', '1', 'ATmega328p is a microcontroller which is 32KB of flash ROM and 8-bit microcontroller.', NULL, 1, '2021-04-06 11:07:08', '2021-04-06 11:07:08', 6),
(14, 'What is the default bootloader of the Arduino UNO?', '', 4, 1, 'Optiboot bootloader', 'AIR-boot', 'Bare box', 'GAG', '', '', '', '', '', '', '1', 'The optiboot bootloader will take 512 bytes, leaving 32256 bytes for application code. Due to its small size larger up-loadable sketch size is achieved.', NULL, 1, '2021-04-06 12:36:56', '2021-04-06 12:36:56', 6);

-- --------------------------------------------------------

--
-- Table structure for table `tb_score`
--

CREATE TABLE `tb_score` (
                            `sc_choice` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
                            `sc_answer` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
                            `sc_judge` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
                            `test_id` int(11) NOT NULL,
                            `q_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_score`
--

INSERT INTO `tb_score` (`sc_choice`, `sc_answer`, `sc_judge`, `test_id`, `q_id`) VALUES
('1', '1', 'Good', 1, 1),
('2,5', '2,5', 'Good', 1, 2),
('4', '5', 'N.G', 1, 3),
('1', '1', 'Good', 1, 4),
('2', '2', 'Good', 1, 5),
('1', '4', 'N.G', 2, 7),
('4', '4', 'Good', 2, 8),
('2', '2', 'Good', 2, 9),
('4', '4', 'Good', 3, 7),
('4', '4', 'Good', 3, 8),
('4', '2', 'N.G', 3, 9),
('4', '4', 'Good', 4, 7),
('4', '4', 'Good', 4, 8),
('1', '2', 'N.G', 4, 9),
('4', '4', 'Good', 5, 7),
('4', '4', 'Good', 5, 8),
('2', '2', 'Good', 5, 9),
('1', '1', 'Good', 6, 1),
('2', '2,5', 'N.G', 6, 2),
('3', '5', 'N.G', 6, 3),
('1', '1', 'Good', 6, 4),
('1', '4', 'N.G', 7, 7),
('1', '4', 'N.G', 7, 8),
('1', '2', 'N.G', 7, 9),
('1', '1', 'Good', 8, 1),
('2,5', '2,5', 'Good', 8, 2),
('1', '1', 'Good', 8, 4),
('2', '2', 'Good', 8, 5),
('1', '1', 'Good', 8, 6),
('2,5', '2,5', 'Good', 9, 2),
('1', '1', 'Good', 9, 4),
('2', '2', 'Good', 9, 5),
('1', '1', 'Good', 9, 6),
('2', '4', 'N.G', 10, 7),
('1', '4', 'N.G', 10, 8),
('1', '2', 'N.G', 10, 9),
('1,2', '2,5', 'N.G', 11, 2),
('1', '1', 'Good', 11, 4),
('2', '2', 'Good', 11, 5),
('1', '1', 'Good', 11, 6),
('4', '4', 'Good', 14, 12),
('3', '4', 'N.G', 15, 12),
('1', '1', 'Good', 15, 13),
('2,5', '2,5', 'Good', 16, 2),
('1', '1', 'Good', 16, 4),
('2', '2', 'Good', 16, 5),
('1', '1', 'Good', 16, 6),
('2', '4', 'N.G', 17, 7),
('3', '4', 'N.G', 17, 8),
('3', '2', 'N.G', 17, 9),
('3', '4', 'N.G', 18, 12),
('1', '1', 'Good', 18, 13),
('3', '1', 'N.G', 18, 14);

-- --------------------------------------------------------

--
-- Table structure for table `tb_subjects`
--

CREATE TABLE `tb_subjects` (
                               `sub_id` int(11) NOT NULL,
                               `title` text COLLATE utf8_unicode_ci NOT NULL,
                               `description` text COLLATE utf8_unicode_ci,
                               `level` int(3) NOT NULL,
                               `give_minute` int(5) NOT NULL,
                               `pass_percent` int(11) NOT NULL,
                               `use_count` int(2) NOT NULL,
                               `status` tinyint(4) NOT NULL,
                               `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                               `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                               `cat_id` int(11) NOT NULL,
                               `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_subjects`
--

INSERT INTO `tb_subjects` (`sub_id`, `title`, `description`, `level`, `give_minute`, `pass_percent`, `use_count`, `status`, `created_at`, `updated_at`, `cat_id`, `teacher_id`) VALUES
(1, 'Basic of Oracle SQL', 'Test the fundamental knowledge of SQL', 1, 5, 100, 2, 1, '2021-02-21 05:15:17', '2021-02-21 05:15:19', 5, 2),
(2, 'Table Join with SQL', 'Test the knowledge of JOIN option of SQL', 3, 20, 60, 2, 1, '2021-02-21 05:15:17', '2021-02-21 05:15:19', 5, 2),
(4, 'Test 2', 'Okay', 4, 20, 40, 1, 0, '2021-03-09 11:42:13', '2021-03-09 11:42:13', 1, 2),
(5, 'Window server', 'Window server system management (Test)', 3, 1, 65, 2, 1, '2021-04-06 07:36:00', '2021-04-06 07:36:00', 3, 5),
(6, 'Basic Arduino', 'Arduino Uno board', 2, 10, 75, 1, 0, '2021-04-06 10:59:10', '2021-04-06 10:59:10', 9, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tb_test_result`
--

CREATE TABLE `tb_test_result` (
                                  `test_id` int(11) NOT NULL,
                                  `test_date` date NOT NULL,
                                  `test_minute` int(5) NOT NULL,
                                  `test_count_question` int(5) DEFAULT NULL,
                                  `all_question` int(5) DEFAULT NULL,
                                  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                  `user_id` int(11) NOT NULL,
                                  `sub_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_test_result`
--

INSERT INTO `tb_test_result` (`test_id`, `test_date`, `test_minute`, `test_count_question`, `all_question`, `created_at`, `user_id`, `sub_id`) VALUES
(1, '2021-03-07', 25, 5, 6, '2021-03-07 05:19:32', 6, 1),
(2, '2021-03-07', 11, 3, 3, '2021-03-07 11:17:02', 6, 2),
(3, '2021-03-08', 34, 3, 3, '2021-03-08 07:29:40', 6, 2),
(4, '2021-03-08', 15, 3, 3, '2021-03-08 07:58:39', 6, 2),
(5, '2021-03-08', 12, 3, 3, '2021-03-08 08:54:05', 6, 2),
(6, '2021-03-08', 10, 4, 6, '2021-03-08 09:00:45', 6, 1),
(7, '2021-03-08', 9, 3, 3, '2021-03-08 09:08:31', 6, 2),
(8, '2021-03-09', 20, 5, 5, '2021-03-09 11:02:03', 6, 1),
(9, '2021-03-13', 55, 4, 4, '2021-03-13 01:17:49', 6, 1),
(10, '2021-03-29', 9, 3, 3, '2021-03-29 12:06:18', 1, 2),
(11, '2021-03-29', 12, 4, 4, '2021-03-29 12:16:32', 6, 1),
(12, '2021-04-06', 60, 0, 0, '2021-04-06 07:47:21', 6, 5),
(13, '2021-04-06', 60, 0, 0, '2021-04-06 07:54:49', 6, 5),
(14, '2021-04-06', 11, 1, 1, '2021-04-06 11:03:31', 1, 6),
(15, '2021-04-06', 17, 2, 2, '2021-04-06 11:08:01', 1, 6),
(16, '2021-04-06', 97, 4, 4, '2021-04-06 12:28:35', 9, 1),
(17, '2021-04-06', 9, 3, 3, '2021-04-06 12:29:54', 9, 2),
(18, '2021-04-06', 16, 3, 3, '2021-04-06 12:43:17', 9, 6);

-- --------------------------------------------------------

--
-- Table structure for table `tb_users`
--

CREATE TABLE `tb_users` (
                            `user_id` int(11) NOT NULL,
                            `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
                            `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
                            `fullname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
                            `role` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Student',
                            `status` tinyint(1) NOT NULL,
                            `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                            `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_users`
--

INSERT INTO `tb_users` (`user_id`, `email`, `password`, `fullname`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'student@swg9.la', 'f2627417566b0b7effab602fa9f5447c', 'Student', 'student', 1, '2021-02-21 05:17:45', '2021-02-21 05:17:46'),
(2, 'teacher@swg9.la', '6b7f29a4feeb2bcf8d9de882d2030a5e', 'Teacher SWG9', 'teacher', 1, '2021-02-21 05:17:45', '2021-03-29 12:09:43'),
(3, 'admin@swg9.la', '31ad2f10870bb5e4c517357bf9f95e58', 'Administrator', 'admin', 1, '2021-02-21 05:17:45', '2021-02-21 05:17:46'),
(5, 'khout@swg9.la', '5df1a9e25112574ab7adac04f2f46bc8', 'KHOUTNALIN', 'teacher', 1, '2021-03-02 15:04:22', '2021-03-29 12:08:14'),
(6, 'khamphai@swg9.la', 'b345b94e859379fc760ea7f11518cb08', 'KPhai SWG9', 'student', 1, '2021-03-06 04:18:50', '2021-03-06 04:18:50'),
(7, 'tadam@swg9.la', '6b7f29a4feeb2bcf8d9de882d2030a5e', 'Tadam SWG9', 'admin', 1, '2021-03-09 07:12:20', '2021-03-29 12:11:34'),
(9, 'khamphet@swg9.la', '5df1a9e25112574ab7adac04f2f46bc8', 'Khamphet', 'student', 1, '2021-04-06 12:25:18', '2021-04-06 12:25:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_category`
--
ALTER TABLE `tb_category`
    ADD PRIMARY KEY (`cat_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `tb_questions`
--
ALTER TABLE `tb_questions`
    ADD PRIMARY KEY (`q_id`),
  ADD KEY `sub_id` (`sub_id`);

--
-- Indexes for table `tb_score`
--
ALTER TABLE `tb_score`
    ADD KEY `test_id` (`test_id`),
  ADD KEY `q_id` (`q_id`);

--
-- Indexes for table `tb_subjects`
--
ALTER TABLE `tb_subjects`
    ADD PRIMARY KEY (`sub_id`),
  ADD KEY `cat_id` (`cat_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `tb_test_result`
--
ALTER TABLE `tb_test_result`
    ADD PRIMARY KEY (`test_id`),
  ADD KEY `tb_test_result_ibfk_1` (`user_id`),
  ADD KEY `tb_test_result_ibfk_2` (`sub_id`);

--
-- Indexes for table `tb_users`
--
ALTER TABLE `tb_users`
    ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_category`
--
ALTER TABLE `tb_category`
    MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tb_questions`
--
ALTER TABLE `tb_questions`
    MODIFY `q_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tb_subjects`
--
ALTER TABLE `tb_subjects`
    MODIFY `sub_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tb_test_result`
--
ALTER TABLE `tb_test_result`
    MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tb_users`
--
ALTER TABLE `tb_users`
    MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_category`
--
ALTER TABLE `tb_category`
    ADD CONSTRAINT `tb_category_tb_users_user_id_fk` FOREIGN KEY (`teacher_id`) REFERENCES `tb_users` (`user_id`);

--
-- Constraints for table `tb_questions`
--
ALTER TABLE `tb_questions`
    ADD CONSTRAINT `tb_questions_ibfk_1` FOREIGN KEY (`sub_id`) REFERENCES `tb_subjects` (`sub_id`);

--
-- Constraints for table `tb_score`
--
ALTER TABLE `tb_score`
    ADD CONSTRAINT `tb_score_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tb_test_result` (`test_id`),
  ADD CONSTRAINT `tb_score_ibfk_2` FOREIGN KEY (`q_id`) REFERENCES `tb_questions` (`q_id`);

--
-- Constraints for table `tb_subjects`
--
ALTER TABLE `tb_subjects`
    ADD CONSTRAINT `tb_subjects_ibfk_1` FOREIGN KEY (`cat_id`) REFERENCES `tb_category` (`cat_id`),
  ADD CONSTRAINT `tb_subjects_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `tb_users` (`user_id`);

--
-- Constraints for table `tb_test_result`
--
ALTER TABLE `tb_test_result`
    ADD CONSTRAINT `tb_test_result_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tb_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_test_result_ibfk_2` FOREIGN KEY (`sub_id`) REFERENCES `tb_subjects` (`sub_id`) ON DELETE CASCADE ON UPDATE CASCADE;
