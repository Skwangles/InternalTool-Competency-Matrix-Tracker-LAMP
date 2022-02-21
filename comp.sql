-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2022 at 10:39 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `comp`
--

-- --------------------------------------------------------

--
-- Table structure for table `competencies`
--

CREATE TABLE `competencies` (
  `CompetencyID` int(10) UNSIGNED NOT NULL,
  `CName` varchar(128) NOT NULL,
  `CDescription` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `competencygroups`
--

CREATE TABLE `competencygroups` (
  `Competencies` int(10) UNSIGNED NOT NULL,
  `Groups` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

-- --------------------------------------------------------

--
-- Table structure for table `competencyroles`
--

CREATE TABLE `competencyroles` (
  `Competencies` int(10) UNSIGNED NOT NULL,
  `Roles` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `GroupID` int(10) UNSIGNED NOT NULL,
  `GName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `individualusercompetencies`
--

CREATE TABLE `individualusercompetencies` (
  `Users` int(10) UNSIGNED NOT NULL,
  `Competencies` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `RoleID` int(10) UNSIGNED NOT NULL,
  `RName` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`RoleID`, `RName`) VALUES
(1, 'Staff'),
(2, 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `usercompetencies`
--

CREATE TABLE `usercompetencies` (
  `Users` int(10) UNSIGNED NOT NULL,
  `Competencies` int(10) UNSIGNED NOT NULL,
  `Rating` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

-- --------------------------------------------------------

--
-- Table structure for table `usergroups`
--

CREATE TABLE `usergroups` (
  `Users` int(10) UNSIGNED NOT NULL,
  `Groups` int(10) UNSIGNED NOT NULL,
  `isManager` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(10) UNSIGNED NOT NULL,
  `UName` varchar(30) DEFAULT NULL,
  `UUsername` varchar(30) NOT NULL,
  `UPassword` varchar(128) DEFAULT NULL,
  `URole` int(1) NOT NULL DEFAULT 1,
  `UAdmin` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
-- Default Admin account is Admin/Admin
--

INSERT INTO `users` (`UserID`, `UName`, `UUsername`, `UPassword`, `URole`, `UAdmin`) VALUES
(1, 'Admin', 'Admin', '$2y$10$C.vqEKqMmQpBG8EI.dxGNep084tolflZkisznFSrQVvdJKXyE0Pzu', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `competencies`
--
ALTER TABLE `competencies`
  ADD PRIMARY KEY (`CompetencyID`),
  ADD UNIQUE KEY `CName` (`CName`);

--
-- Indexes for table `competencygroups`
--
ALTER TABLE `competencygroups`
  ADD PRIMARY KEY (`Competencies`,`Groups`),
  ADD KEY `Constr_CompetencyGroups_Groups_fk` (`Groups`);

--
-- Indexes for table `competencyroles`
--
ALTER TABLE `competencyroles`
  ADD PRIMARY KEY (`Competencies`,`Roles`),
  ADD KEY `Constr_CompetencyRoles_Roles_fk` (`Roles`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`GroupID`),
  ADD UNIQUE KEY `GName` (`GName`);

--
-- Indexes for table `individualusercompetencies`
--
ALTER TABLE `individualusercompetencies`
  ADD PRIMARY KEY (`Users`,`Competencies`),
  ADD KEY `Constr_IndividualUserCompetencies_Competencies_fk` (`Competencies`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`RoleID`);

--
-- Indexes for table `usercompetencies`
--
ALTER TABLE `usercompetencies`
  ADD PRIMARY KEY (`Users`,`Competencies`),
  ADD KEY `Constr_UserCompetencies_Competencies_fk` (`Competencies`);

--
-- Indexes for table `usergroups`
--
ALTER TABLE `usergroups`
  ADD PRIMARY KEY (`Users`,`Groups`),
  ADD KEY `Constr_UserGroups_Groups_fk` (`Groups`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `UUsername` (`UUsername`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `competencies`
--
ALTER TABLE `competencies`
  MODIFY `CompetencyID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `GroupID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `RoleID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `competencygroups`
--
ALTER TABLE `competencygroups`
  ADD CONSTRAINT `Constr_CompetencyGroups_Competencies_fk` FOREIGN KEY (`Competencies`) REFERENCES `competencies` (`CompetencyID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Constr_CompetencyGroups_Groups_fk` FOREIGN KEY (`Groups`) REFERENCES `groups` (`GroupID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `competencyroles`
--
ALTER TABLE `competencyroles`
  ADD CONSTRAINT `Constr_CompetencyRoles_Competencies_fk` FOREIGN KEY (`Competencies`) REFERENCES `competencies` (`CompetencyID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Constr_CompetencyRoles_Roles_fk` FOREIGN KEY (`Roles`) REFERENCES `roles` (`RoleID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `individualusercompetencies`
--
ALTER TABLE `individualusercompetencies`
  ADD CONSTRAINT `Constr_IndividualUserCompetencies_Competencies_fk` FOREIGN KEY (`Competencies`) REFERENCES `competencies` (`CompetencyID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Constr_IndividualUserCompetencies_Users_fk` FOREIGN KEY (`Users`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usercompetencies`
--
ALTER TABLE `usercompetencies`
  ADD CONSTRAINT `Constr_UserCompetencies_Competencies_fk` FOREIGN KEY (`Competencies`) REFERENCES `competencies` (`CompetencyID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Constr_UserCompetencies_Users_fk` FOREIGN KEY (`Users`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usergroups`
--
ALTER TABLE `usergroups`
  ADD CONSTRAINT `Constr_UserGroups_Groups_fk` FOREIGN KEY (`Groups`) REFERENCES `groups` (`GroupID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Constr_UserGroups_Users_fk` FOREIGN KEY (`Users`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
