-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 07, 2024 at 05:21 AM
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
-- Database: `pettycashencoder`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmins`
--

CREATE TABLE `tbladmins` (
  `Admin_id` int(11) NOT NULL,
  `Admin_name` varchar(255) NOT NULL,
  `Admin_nickname` varchar(50) NOT NULL,
  `Admin_email` varchar(100) NOT NULL,
  `Admin_number` varchar(50) NOT NULL,
  `Admin_password` varchar(100) NOT NULL,
  `Department_id` int(11) NOT NULL,
  `Userlevel_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbladmins`
--

INSERT INTO `tbladmins` (`Admin_id`, `Admin_name`, `Admin_nickname`, `Admin_email`, `Admin_number`, `Admin_password`, `Department_id`, `Userlevel_id`) VALUES
(1, 'Maria Victoria Villamor', 'Vicky', 'vicky.pbbi@gmail.com', '09480276900', '123', 1, 1),
(2, 'Miratone Sarmiento', 'Mye', 'miratonesarmiento@gmail.com', '09178728961', '123', 2, 1),
(3, 'Benedicto Aquino', 'Benny', 'pauldoctolero0716@gmail.com', '09194712718', '123', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblbank`
--

CREATE TABLE `tblbank` (
  `Bank_id` int(11) NOT NULL,
  `Bank` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblbank`
--

INSERT INTO `tblbank` (`Bank_id`, `Bank`) VALUES
(1, 'BPI'),
(2, 'BDO');

-- --------------------------------------------------------

--
-- Table structure for table `tbldepartment`
--

CREATE TABLE `tbldepartment` (
  `Department_id` int(11) NOT NULL,
  `Department` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbldepartment`
--

INSERT INTO `tbldepartment` (`Department_id`, `Department`) VALUES
(1, 'Accounting'),
(2, 'Finance Manager'),
(3, 'Liason Officer');

-- --------------------------------------------------------

--
-- Table structure for table `tblpettycash`
--

CREATE TABLE `tblpettycash` (
  `Pettycash_id` int(11) NOT NULL,
  `pcDV_number` int(255) NOT NULL,
  `pc_Date` date NOT NULL,
  `pc_Name` varchar(255) NOT NULL,
  `pc_Amount` varchar(255) NOT NULL,
  `pc_ActualExpenses` varchar(255) NOT NULL,
  `pc_Returnables` varchar(255) NOT NULL,
  `Bank_id` int(11) NOT NULL,
  `pc_Description` varchar(255) NOT NULL,
  `pc_Project` varchar(255) NOT NULL,
  `pc_Lastmodified` datetime DEFAULT NULL,
  `Admin_id` int(11) NOT NULL,
  `Status_id` int(11) NOT NULL,
  `Remarks_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblremarks`
--

CREATE TABLE `tblremarks` (
  `Remarks_id` int(11) NOT NULL,
  `Remarks` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblremarks`
--

INSERT INTO `tblremarks` (`Remarks_id`, `Remarks`) VALUES
(1, 'Liquidated'),
(2, 'Deposited'),
(3, 'Released');

-- --------------------------------------------------------

--
-- Table structure for table `tblstatus`
--

CREATE TABLE `tblstatus` (
  `Status_id` int(11) NOT NULL,
  `PettyStatus` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstatus`
--

INSERT INTO `tblstatus` (`Status_id`, `PettyStatus`) VALUES
(1, 'Pending'),
(2, 'For Cheque Disbursement'),
(3, 'Done'),
(4, 'Canceled');

-- --------------------------------------------------------

--
-- Table structure for table `tbluserlevel`
--

CREATE TABLE `tbluserlevel` (
  `Userlevel_id` int(11) NOT NULL,
  `Userlevel` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluserlevel`
--

INSERT INTO `tbluserlevel` (`Userlevel_id`, `Userlevel`) VALUES
(1, 'Admin'),
(2, 'Employee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmins`
--
ALTER TABLE `tbladmins`
  ADD PRIMARY KEY (`Admin_id`),
  ADD KEY `constraintuserlevel` (`Userlevel_id`),
  ADD KEY `constraintdepartment` (`Department_id`);

--
-- Indexes for table `tblbank`
--
ALTER TABLE `tblbank`
  ADD PRIMARY KEY (`Bank_id`);

--
-- Indexes for table `tbldepartment`
--
ALTER TABLE `tbldepartment`
  ADD PRIMARY KEY (`Department_id`);

--
-- Indexes for table `tblpettycash`
--
ALTER TABLE `tblpettycash`
  ADD PRIMARY KEY (`Pettycash_id`),
  ADD KEY `constraintadmin` (`Admin_id`),
  ADD KEY `constraintstatus` (`Status_id`),
  ADD KEY `constraintbank` (`Bank_id`);

--
-- Indexes for table `tblremarks`
--
ALTER TABLE `tblremarks`
  ADD PRIMARY KEY (`Remarks_id`);

--
-- Indexes for table `tblstatus`
--
ALTER TABLE `tblstatus`
  ADD PRIMARY KEY (`Status_id`);

--
-- Indexes for table `tbluserlevel`
--
ALTER TABLE `tbluserlevel`
  ADD PRIMARY KEY (`Userlevel_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmins`
--
ALTER TABLE `tbladmins`
  MODIFY `Admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblbank`
--
ALTER TABLE `tblbank`
  MODIFY `Bank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbldepartment`
--
ALTER TABLE `tbldepartment`
  MODIFY `Department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblpettycash`
--
ALTER TABLE `tblpettycash`
  MODIFY `Pettycash_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblremarks`
--
ALTER TABLE `tblremarks`
  MODIFY `Remarks_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblstatus`
--
ALTER TABLE `tblstatus`
  MODIFY `Status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbluserlevel`
--
ALTER TABLE `tbluserlevel`
  MODIFY `Userlevel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbladmins`
--
ALTER TABLE `tbladmins`
  ADD CONSTRAINT `constraintdepartment` FOREIGN KEY (`Department_id`) REFERENCES `tbldepartment` (`Department_id`),
  ADD CONSTRAINT `constraintuserlevel` FOREIGN KEY (`Userlevel_id`) REFERENCES `tbluserlevel` (`Userlevel_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
