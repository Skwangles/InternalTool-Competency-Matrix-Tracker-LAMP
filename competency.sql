-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2021 at 10:16 AM
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
-- Database: `competency`
--

-- --------------------------------------------------------

--
-- Table structure for table `competencies`
--

CREATE TABLE `competencies` (
  `CompetencyID` int(10) UNSIGNED NOT NULL,
  `CName` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `competencies`
--

INSERT INTO `competencies` (`CompetencyID`, `CName`) VALUES
(20, 'Banter'),
(25, 'Call Outs'),
(14, 'First Aid'),
(17, 'Loyalty To Lord Barry'),
(21, 'Professionalism'),
(23, 'RepairShopR'),
(22, 'Sales'),
(19, 'Syncro Managment Software');

-- --------------------------------------------------------

--
-- Table structure for table `competencygroups`
--

CREATE TABLE `competencygroups` (
  `Competencies` int(10) UNSIGNED NOT NULL,
  `Groups` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

--
-- Dumping data for table `competencygroups`
--

INSERT INTO `competencygroups` (`Competencies`, `Groups`) VALUES
(14, 1),
(19, 8),
(20, 2),
(20, 4),
(21, 5),
(21, 8),
(22, 2),
(22, 5),
(23, 2),
(23, 5);

-- --------------------------------------------------------

--
-- Table structure for table `competencyroles`
--

CREATE TABLE `competencyroles` (
  `Competencies` int(10) UNSIGNED NOT NULL,
  `Roles` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

--
-- Dumping data for table `competencyroles`
--

INSERT INTO `competencyroles` (`Competencies`, `Roles`) VALUES
(17, 3),
(21, 2),
(21, 3),
(22, 1);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `GroupID` int(10) UNSIGNED NOT NULL,
  `GName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`GroupID`, `GName`) VALUES
(1, 'Health & Safety'),
(2, 'Lab/Ham'),
(5, 'Lab/TGA'),
(8, 'Managed Services'),
(4, 'Security & Wiring');

-- --------------------------------------------------------

--
-- Table structure for table `individualusercompetencies`
--

CREATE TABLE `individualusercompetencies` (
  `Users` int(10) UNSIGNED NOT NULL,
  `Competencies` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

--
-- Dumping data for table `individualusercompetencies`
--

INSERT INTO `individualusercompetencies` (`Users`, `Competencies`) VALUES
(15, 19),
(16, 19),
(16, 25);

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
(2, 'Manager'),
(3, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `usercompetencies`
--

CREATE TABLE `usercompetencies` (
  `Users` int(10) UNSIGNED NOT NULL,
  `Competencies` int(10) UNSIGNED NOT NULL,
  `Rating` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

--
-- Dumping data for table `usercompetencies`
--

INSERT INTO `usercompetencies` (`Users`, `Competencies`, `Rating`) VALUES
(13, 14, 2),
(13, 17, 0),
(13, 20, 3),
(13, 21, 3),
(13, 22, 3),
(13, 23, 3),
(15, 14, 3),
(15, 17, 0),
(15, 19, 3),
(15, 21, 3),
(16, 14, 1),
(16, 19, 3),
(16, 21, 3),
(16, 25, 0),
(17, 14, 3),
(17, 17, 0),
(17, 20, 2),
(17, 21, 0),
(18, 14, 0),
(18, 20, 0),
(18, 22, 0),
(18, 23, 0),
(19, 14, 0),
(19, 20, 0),
(19, 22, 0),
(19, 23, 0);

-- --------------------------------------------------------

--
-- Table structure for table `usergroups`
--

CREATE TABLE `usergroups` (
  `Users` int(10) UNSIGNED NOT NULL,
  `Groups` int(10) UNSIGNED NOT NULL,
  `isManager` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ascii;

--
-- Dumping data for table `usergroups`
--

INSERT INTO `usergroups` (`Users`, `Groups`, `isManager`) VALUES
(13, 1, 0),
(13, 2, 1),
(15, 1, 1),
(16, 1, 0),
(16, 8, 1),
(17, 1, 1),
(17, 4, 1),
(18, 1, 0),
(18, 2, 0),
(19, 1, 0),
(19, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(10) UNSIGNED NOT NULL,
  `UName` varchar(30) DEFAULT NULL,
  `UUsername` varchar(30) NOT NULL,
  `UPassword` varchar(128) DEFAULT NULL,
  `URole` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `UName`, `UUsername`, `UPassword`, `URole`) VALUES
(13, 'Steben', 'Stephen', '$2y$10$QIMGsX27dxM952qFpRf4L.cr64PRdiyAM0S/cG3RTzNqpxg29ZooC', 3),
(15, 'Barry M', 'Barry', '$2y$10$BmKbVgXE6O0iKcJ5P1DJ7uqpLUxqDKnRMUthkG7ArAi5bbdnqadRy', 3),
(16, 'Nav', 'Navdeep', '$2y$10$knaz8H/RIEyLvDViHdoo3O.1T2YUHLDK4/XS02opzRxGFnT7EEHmC', 2),
(17, 'Danie', 'Danie', '$2y$10$FmTyPOffgUF9JfxQLEdPl.7clz7RxgCrOw8Vd4OQ1nCXbrOMEyH7q', 3),
(18, 'Alexander S', 'Xander', '$2y$10$zQZGbcKUss5YYzqkVjcCM.jy/kMqp92NdrzJo3PVblfa9kUGOfJUK', 1),
(19, 'Alex C', 'Alex', '$2y$10$lf4GkGc7MFxVhVyrIKy28uxx9sUzHTlR2wDkck4ucx5R7oGcwEVDe', 1);

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
  MODIFY `CompetencyID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `GroupID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `RoleID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
