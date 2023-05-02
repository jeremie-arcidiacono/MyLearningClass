-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 02, 2023 at 01:08 PM
-- Server version: 10.3.38-MariaDB-0ubuntu0.20.04.1
-- PHP Version: 8.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_learning_class`
--
CREATE DATABASE IF NOT EXISTS `my_learning_class` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `my_learning_class`;

-- --------------------------------------------------------

--
-- Table structure for table `CHAPTER`
--

CREATE TABLE `CHAPTER` (
  `idChapter` int(12) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `videoFilename` varchar(30) DEFAULT NULL COMMENT 'The name of the file of the video stored in the server',
  `videoName` int(100) DEFAULT NULL COMMENT 'The original name of the video, before renaming the file to make it unique',
  `videoDuration` int(6) DEFAULT NULL COMMENT 'The duration of the video in seconds',
  `ressourceFilename` varchar(30) DEFAULT NULL COMMENT 'An additional file to download (pdf)',
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `idCourse` int(10) UNSIGNED NOT NULL,
  `idNextChapter` int(12) UNSIGNED DEFAULT NULL COMMENT 'The id of the next chapter. If null, this chapter is the last one of the course'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `CHAPTER_PROGRESS`
--

CREATE TABLE `CHAPTER_PROGRESS` (
  `idUser` int(10) UNSIGNED NOT NULL,
  `idChapter` int(12) UNSIGNED NOT NULL,
  `status` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '1: to do   2: started   3: finished',
  `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `COURSE`
--

CREATE TABLE `COURSE` (
  `idCourse` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `imgFilename` varchar(30) NOT NULL,
  `visibility` enum('1','2','3') NOT NULL COMMENT '1: draft   2: public   3: private',
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `codeCourseCategory` int(4) UNSIGNED NOT NULL,
  `idUser` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `COURSE_BOOKMARK`
--

CREATE TABLE `COURSE_BOOKMARK` (
  `idUser` int(10) UNSIGNED NOT NULL,
  `idCourse` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `COURSE_CATEGORY`
--

CREATE TABLE `COURSE_CATEGORY` (
  `codeCourseCategory` int(4) UNSIGNED NOT NULL,
  `label` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `COURSE_CATEGORY`
--

INSERT INTO `COURSE_CATEGORY` (`codeCourseCategory`, `label`) VALUES
(1, 'Front-end'),
(2, 'Back-end'),
(3, 'Mobile');

-- --------------------------------------------------------

--
-- Table structure for table `COURSE_ENROLLMENT`
--

CREATE TABLE `COURSE_ENROLLMENT` (
  `idUser` int(10) UNSIGNED NOT NULL,
  `idCouse` int(10) UNSIGNED NOT NULL,
  `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `PERMISSION`
--

CREATE TABLE `PERMISSION` (
  `codePermission` int(4) UNSIGNED NOT NULL,
  `action` enum('create','read','read_own','read_any','update','update_own','update_any','delete','delete_any','delete_own') NOT NULL,
  `ressoure` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `PERMISSION`
--

INSERT INTO `PERMISSION` (`codePermission`, `action`, `ressoure`) VALUES
(1, 'delete_own', 'enrollment'),
(2, 'create', 'course'),
(3, 'update_own', 'course'),
(4, 'delete_own', 'course'),
(5, 'delete_any', 'course'),
(6, 'create', 'user'),
(7, 'delete_any', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `ROLE`
--

CREATE TABLE `ROLE` (
  `codeRole` int(2) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `label` varchar(40) NOT NULL COMMENT 'A more humain-friendly name'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ROLE`
--

INSERT INTO `ROLE` (`codeRole`, `name`, `label`) VALUES
(1, 'user', 'Utilisateur'),
(2, 'teacher', 'Enseignant'),
(3, 'admin', 'Administrateur');

-- --------------------------------------------------------

--
-- Table structure for table `ROLE_HAS_PERMISSION`
--

CREATE TABLE `ROLE_HAS_PERMISSION` (
  `codeRole` int(2) UNSIGNED NOT NULL,
  `codePermission` int(4) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ROLE_HAS_PERMISSION`
--

INSERT INTO `ROLE_HAS_PERMISSION` (`codeRole`, `codePermission`) VALUES
(1, 1),
(2, 2),
(2, 3),
(2, 4),
(3, 2),
(3, 3),
(3, 5),
(3, 6),
(3, 7);

-- --------------------------------------------------------

--
-- Table structure for table `SESSION`
--

CREATE TABLE `SESSION` (
  `idSession` varchar(64) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `SESSION_DATA`
--

CREATE TABLE `SESSION_DATA` (
  `idSessionData` int(15) UNSIGNED NOT NULL,
  `dataKey` varchar(30) NOT NULL,
  `dataValue` mediumtext DEFAULT NULL COMMENT 'May have serialized content',
  `isFlash` tinyint(1) NOT NULL DEFAULT 0 COMMENT ' If true, the data is temporary. It will be deleted after the first read ',
  `idSession` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `USER`
--

CREATE TABLE `USER` (
  `idUser` int(10) UNSIGNED NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL,
  `codeRole` int(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `CHAPTER`
--
ALTER TABLE `CHAPTER`
  ADD PRIMARY KEY (`idChapter`),
  ADD KEY `idCourse` (`idCourse`),
  ADD KEY `idNextChapter` (`idNextChapter`);

--
-- Indexes for table `CHAPTER_PROGRESS`
--
ALTER TABLE `CHAPTER_PROGRESS`
  ADD PRIMARY KEY (`idUser`,`idChapter`),
  ADD KEY `idChapter` (`idChapter`);

--
-- Indexes for table `COURSE`
--
ALTER TABLE `COURSE`
  ADD PRIMARY KEY (`idCourse`),
  ADD KEY `codeCourseCategory` (`codeCourseCategory`),
  ADD KEY `idUser` (`idUser`);

--
-- Indexes for table `COURSE_BOOKMARK`
--
ALTER TABLE `COURSE_BOOKMARK`
  ADD PRIMARY KEY (`idUser`,`idCourse`),
  ADD KEY `idCourse` (`idCourse`);

--
-- Indexes for table `COURSE_CATEGORY`
--
ALTER TABLE `COURSE_CATEGORY`
  ADD PRIMARY KEY (`codeCourseCategory`);

--
-- Indexes for table `COURSE_ENROLLMENT`
--
ALTER TABLE `COURSE_ENROLLMENT`
  ADD PRIMARY KEY (`idUser`,`idCouse`),
  ADD KEY `idCouse` (`idCouse`);

--
-- Indexes for table `PERMISSION`
--
ALTER TABLE `PERMISSION`
  ADD PRIMARY KEY (`codePermission`);

--
-- Indexes for table `ROLE`
--
ALTER TABLE `ROLE`
  ADD PRIMARY KEY (`codeRole`);

--
-- Indexes for table `ROLE_HAS_PERMISSION`
--
ALTER TABLE `ROLE_HAS_PERMISSION`
  ADD PRIMARY KEY (`codeRole`,`codePermission`),
  ADD KEY `codePermission` (`codePermission`);

--
-- Indexes for table `SESSION`
--
ALTER TABLE `SESSION`
  ADD PRIMARY KEY (`idSession`);

--
-- Indexes for table `SESSION_DATA`
--
ALTER TABLE `SESSION_DATA`
  ADD PRIMARY KEY (`idSessionData`),
  ADD KEY `idSession` (`idSession`);

--
-- Indexes for table `USER`
--
ALTER TABLE `USER`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `codeRole` (`codeRole`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `CHAPTER`
--
ALTER TABLE `CHAPTER`
  MODIFY `idChapter` int(12) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `COURSE`
--
ALTER TABLE `COURSE`
  MODIFY `idCourse` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PERMISSION`
--
ALTER TABLE `PERMISSION`
  MODIFY `codePermission` int(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `SESSION_DATA`
--
ALTER TABLE `SESSION_DATA`
  MODIFY `idSessionData` int(15) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `USER`
--
ALTER TABLE `USER`
  MODIFY `idUser` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `CHAPTER`
--
ALTER TABLE `CHAPTER`
  ADD CONSTRAINT `CHAPTER_ibfk_1` FOREIGN KEY (`idCourse`) REFERENCES `COURSE` (`idCourse`),
  ADD CONSTRAINT `CHAPTER_ibfk_2` FOREIGN KEY (`idNextChapter`) REFERENCES `CHAPTER` (`idChapter`);

--
-- Constraints for table `CHAPTER_PROGRESS`
--
ALTER TABLE `CHAPTER_PROGRESS`
  ADD CONSTRAINT `CHAPTER_PROGRESS_ibfk_1` FOREIGN KEY (`idChapter`) REFERENCES `CHAPTER` (`idChapter`),
  ADD CONSTRAINT `CHAPTER_PROGRESS_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `USER` (`idUser`);

--
-- Constraints for table `COURSE`
--
ALTER TABLE `COURSE`
  ADD CONSTRAINT `COURSE_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `USER` (`idUser`),
  ADD CONSTRAINT `COURSE_ibfk_2` FOREIGN KEY (`codeCourseCategory`) REFERENCES `COURSE_CATEGORY` (`codeCourseCategory`);

--
-- Constraints for table `COURSE_BOOKMARK`
--
ALTER TABLE `COURSE_BOOKMARK`
  ADD CONSTRAINT `COURSE_BOOKMARK_ibfk_1` FOREIGN KEY (`idCourse`) REFERENCES `COURSE` (`idCourse`),
  ADD CONSTRAINT `COURSE_BOOKMARK_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `USER` (`idUser`);

--
-- Constraints for table `COURSE_ENROLLMENT`
--
ALTER TABLE `COURSE_ENROLLMENT`
  ADD CONSTRAINT `COURSE_ENROLLMENT_ibfk_1` FOREIGN KEY (`idCouse`) REFERENCES `COURSE` (`idCourse`),
  ADD CONSTRAINT `COURSE_ENROLLMENT_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `USER` (`idUser`);

--
-- Constraints for table `ROLE_HAS_PERMISSION`
--
ALTER TABLE `ROLE_HAS_PERMISSION`
  ADD CONSTRAINT `ROLE_HAS_PERMISSION_ibfk_1` FOREIGN KEY (`codePermission`) REFERENCES `PERMISSION` (`codePermission`),
  ADD CONSTRAINT `ROLE_HAS_PERMISSION_ibfk_2` FOREIGN KEY (`codeRole`) REFERENCES `ROLE` (`codeRole`);

--
-- Constraints for table `SESSION_DATA`
--
ALTER TABLE `SESSION_DATA`
  ADD CONSTRAINT `SESSION_DATA_ibfk_1` FOREIGN KEY (`idSession`) REFERENCES `SESSION` (`idSession`);

--
-- Constraints for table `USER`
--
ALTER TABLE `USER`
  ADD CONSTRAINT `USER_ibfk_1` FOREIGN KEY (`codeRole`) REFERENCES `ROLE` (`codeRole`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
