-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 14, 2024 at 09:14 AM
-- Server version: 8.0.18
-- PHP Version: 7.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `m217578`
--

-- --------------------------------------------------------

--
-- Table structure for table `experimentaldata`
--

CREATE TABLE `experimentaldata` (
  `DataID` int(64) NOT NULL,
  `LinkedExperimentID` int(64) NOT NULL,
  `Observations` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `RecordedAt` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Table structure for table `experiments`
--

CREATE TABLE `experiments` (
  `ExperimentID` int(64) NOT NULL,
  `Name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL,
  `Description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Table structure for table `experimentsscientists`
--

CREATE TABLE `experimentsscientists` (
  `LinkedExperimentID` int(64) NOT NULL,
  `LinkedScientistID` int(64) NOT NULL,
  `Role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `ID` int(11) NOT NULL,
  `Image_data` longblob,
  `Mime_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `Image_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instruments`
--

CREATE TABLE `instruments` (
  `InstrumentID` int(64) NOT NULL,
  `Name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL,
  `Type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL,
  `Location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scientists`
--

CREATE TABLE `scientists` (
  `ScientistID` int(64) NOT NULL,
  `FirstName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL,
  `LastName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL,
  `Specialization` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `experimentaldata`
--
ALTER TABLE `experimentaldata`
  ADD PRIMARY KEY (`DataID`),
  ADD KEY `LinkedExperimentID` (`LinkedExperimentID`);

--
-- Indexes for table `experiments`
--
ALTER TABLE `experiments`
  ADD PRIMARY KEY (`ExperimentID`);

--
-- Indexes for table `experimentsscientists`
--
ALTER TABLE `experimentsscientists`
  ADD KEY `LinkedExperimentID` (`LinkedExperimentID`),
  ADD KEY `LinkedScientistID` (`LinkedScientistID`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `instruments`
--
ALTER TABLE `instruments`
  ADD PRIMARY KEY (`InstrumentID`);

--
-- Indexes for table `scientists`
--
ALTER TABLE `scientists`
  ADD PRIMARY KEY (`ScientistID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `experimentaldata`
--
ALTER TABLE `experimentaldata`
  MODIFY `DataID` int(64) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `experiments`
--
ALTER TABLE `experiments`
  MODIFY `ExperimentID` int(64) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `instruments`
--
ALTER TABLE `instruments`
  MODIFY `InstrumentID` int(64) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `scientists`
--
ALTER TABLE `scientists`
  MODIFY `ScientistID` int(64) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `experimentaldata`
--
ALTER TABLE `experimentaldata`
  ADD CONSTRAINT `experimentaldata_ibfk_1` FOREIGN KEY (`LinkedExperimentID`) REFERENCES `experiments` (`ExperimentID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `experimentsscientists`
--
ALTER TABLE `experimentsscientists`
  ADD CONSTRAINT `experimentsscientists_ibfk_1` FOREIGN KEY (`LinkedExperimentID`) REFERENCES `experiments` (`ExperimentID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `experimentsscientists_ibfk_2` FOREIGN KEY (`LinkedScientistID`) REFERENCES `scientists` (`ScientistID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
