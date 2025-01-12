-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 01, 2023 at 01:51 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elmsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `EmailId` varchar(120) NOT NULL,
  `Image` varchar(255) NOT NULL DEFAULT '/assets/images/NBY_IT_SOLUTION_LOGO_SYMBLE-removebg-preview.png',
  `updationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `UserName`, `Password`, `EmailId`, `Image`, `updationDate`) VALUES
(1, 'admin', 'admin123', 'admin@admin.com', '/assets/images/NBY_IT_SOLUTION_LOGO_SYMBLE-removebg-preview.png', '2023-08-31 14:49:11');

--
-- Dumping data for table `admin`
--


-- --------------------------------------------------------

--
-- Table structure for table `tbldepartments`
--

CREATE TABLE `tbldepartments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `DepartmentName` varchar(150) DEFAULT NULL,
  `DepartmentShortName` varchar(100) DEFAULT NULL,
  `DepartmentCode` varchar(50) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbldepartments`
--

INSERT INTO `tbldepartments` (`id`, `DepartmentName`, `DepartmentShortName`, `DepartmentCode`, `CreationDate`) VALUES
(1, 'Human Resource', 'HR', 'HR01', '2023-08-31 14:50:20'),
(2, 'Information Technology', 'IT', 'IT01', '2023-08-31 14:50:56'),
(3, 'Accounts', 'Accounts', 'ACCNT01', '2023-08-31 14:51:26'),
(4, 'ADMIN', 'Admin', 'ADMN01', '2023-09-01 11:35:50');

-- --------------------------------------------------------

--
-- Table structure for table `tblemployees`
--

CREATE TABLE `tblemployees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `EmpId` varchar(100) NOT NULL,
  `FirstName` varchar(150) DEFAULT NULL,
  `LastName` varchar(150) DEFAULT NULL,
  `EmailId` varchar(200) DEFAULT NULL,
  `Password` varchar(180) DEFAULT NULL,
  `Gender` varchar(100) DEFAULT NULL,
  `Dob` varchar(100) DEFAULT NULL,
  `Department` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `City` varchar(200) DEFAULT NULL,
  `Country` varchar(150) DEFAULT NULL,
  `Phonenumber` char(11) DEFAULT NULL,
  `Username` varchar(100) NOT NULL,
  `Status` int(1) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblemployees`
--

INSERT INTO `tblemployees` (`id`, `EmpId`, `FirstName`, `LastName`, `EmailId`, `Password`, `Gender`, `Dob`, `Department`, `Address`, `City`, `Country`, `Phonenumber`, `Username`, `Status`, `RegDate`) VALUES
(1, '10805121', 'Rakesh', 'Singh', 'rksingh@test.com', 'f925916e2754e5e03f75dd58a5733251', 'Male', '3 August, 1995', 'Information Technology', 'A 123 XYZ Apartment ', 'New Delhi', 'India', '12121212', 'rakesh123', 1, '2023-08-31 14:56:23'),
(2, '10235612', 'Shivani', 'Yadav', 'shivani123@gmail.com', 'f925916e2754e5e03f75dd58a5733251', 'Female', '2 January, 1997', 'Accounts', 'Hno 123 ABC Colony', 'New Delhi', 'India', '7485963210', 'shivani123', 1, '2023-08-31 15:02:47'),
(5, '7856214', 'Raj', 'Verma', 'rajverma123@gmail.com', 'f925916e2754e5e03f75dd58a5733251', 'Male', '3 January, 1995', 'Accounts', 'H no 1', 'Ghaziabad ', 'India', '23232323', 'rajverma123', 1, '2023-09-01 11:38:23');

-- --------------------------------------------------------

--
-- Table structure for table `tblleaves`
--

CREATE TABLE `tblleaves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `LeaveType` varchar(110) DEFAULT NULL,
  `ToDate` varchar(120) DEFAULT NULL,
  `FromDate` varchar(120) DEFAULT NULL,
  `Description` mediumtext DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp(),
  `AdminRemark` mediumtext DEFAULT NULL,
  `AdminRemarkDate` varchar(120) DEFAULT NULL,
  `Status` int(1) DEFAULT NULL,
  `IsRead` int(1) DEFAULT NULL,
  `empid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblleaves`
--

INSERT INTO `tblleaves` (`id`, `LeaveType`, `ToDate`, `FromDate`, `Description`, `PostingDate`, `AdminRemark`, `AdminRemarkDate`, `Status`, `IsRead`, `empid`) VALUES
(11, 'Casual Leaves', '17/09/2023', '10/09/2023', 'I need leave to visit my home town. ', '2023-08-31 15:06:21', 'Approved', '2023-08-31 20:39:40 ', 1, 1, 1),
(12, 'Casual Leaves', '15/09/2023', '09/09/2023', 'Need casual leaves for some personal work.', '2023-09-01 11:42:40', 'Leave approved', '2023-09-01 17:13:20 ', 1, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tblleavetype`
--

CREATE TABLE `tblleavetype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `LeaveType` varchar(200) DEFAULT NULL,
  `Description` mediumtext DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblleavetype`
--

INSERT INTO `tblleavetype` (`id`, `LeaveType`, `Description`, `CreationDate`) VALUES
(1, 'Casual Leaves', 'Casual Leaves', '2023-08-31 14:52:22'),
(2, 'Earned Leaves', 'Earned Leaves', '2023-08-31 14:52:49'),
(3, 'Sick Leaves', 'Sick Leaves', '2023-08-31 14:53:15'),
(4, 'RH (Restricted Leaves)', 'Restricted Leaves', '2023-09-01 11:37:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbldepartments`
--
ALTER TABLE `tbldepartments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblemployees`
--
ALTER TABLE `tblemployees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblleaves`
--
ALTER TABLE `tblleaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `UserEmail` (`empid`);

--
-- Indexes for table `tblleavetype`
--
ALTER TABLE `tblleavetype`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbldepartments`
--
ALTER TABLE `tbldepartments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblemployees`
--
ALTER TABLE `tblemployees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblleaves`
--
ALTER TABLE `tblleaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblleavetype`
--
ALTER TABLE `tblleavetype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
